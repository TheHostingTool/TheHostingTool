<?php
/* Copyright © 2014 TheHostingTool
 *
 * This file is part of TheHostingTool.
 *
 * TheHostingTool is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TheHostingTool is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TheHostingTool.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace TheHostingTool\Utils;

class Crypto {

    public static function getAsciiPassword($length) {
        $ascii = "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";
        return self::getPassword(str_split($ascii), $length);
    }

    public static function getAlphaNumericPassword($length) {
        $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        return self::getPassword(str_split($alphanum), $length);
    }

    // Create a random password composed of $length chars from a $characterSet array.
    // Returns false on error (bad args, or more importantly if the call to getBytes fails)
    // Thanks to Defuse Security for this and the other password related methods
    public static function getPassword($characterSet, $length) {
        if($length < 1 || !is_array($characterSet)) {
            return false;
        }

        $charSetLen = count($characterSet);
        if($charSetLen == 0) {
            return false;
        }

        $ints = self::getInts($length * 2);
        if($ints === false) {
            return false;
        }
        $mask = self::getMinimalBitMask($charSetLen - 1);

        $password = "";

        // To generate the password, we repeatedly try random integers and use the ones within the range
        // 0 to $charSetLen - 1 to select an index into the character set. This is the only known way to
        // make a truly unbiased random selection from a set using random binary data.

        // A poorly implemented or malicious RNG could cause an infinite loop, leading to a denial of service.
        // We need to guarantee termination, so $iterLimit holds the number of further iterations we will allow.
        // It is extremely unlikely (about 2^-64) that more than $length*64 random ints are needed.

        // If length is close to PHP_INT_MAX we don't want to overflow.
        $iterLimit = max($length, $length * 64);
        $randIdx = 0;

        while(strlen($password) < $length) {
            // If we've used all of our random ints let's grab some more
            if($randIdx >= count($ints)) {
                $ints = self::getInts(2 * ($length - strlen($password)));
                if($ints === false) {
                    return false;
                }
                $randIdx = 0;
            }

            // This is wasteful, but RNGs are fast and doing otherwise adds complexity and bias.
            $c = $ints[$randIdx++] & $mask;

            // Only use the random number if it is in range, otherwise try another (next iteration).
            if($c < $charSetLen) {
                $password .= self::safeArrayIndex($characterSet, $c);
                // FIXME: check the return value
            }

            // Guarantee termination
            if(--$iterLimit <= 0) {
                return false;
            }
        }

        return $password;
    }


    // Returns an array of $numInts random integers between 0 and PHP_INT_MAX
    // false on error
    public static function getInts($length) {
        $ints = array();
        if($length <= 0) {
            return $ints;
        }
        $bytes = self::getBytes($length * PHP_INT_SIZE);
        if($bytes === false) {
            return false;
        }
        for($i = 0; $i < $length; ++$i) {
            $currentInt = 0;
            for($j = 0; $j < PHP_INT_SIZE; ++$j) {
                $currentInt = ($currentInt << 8) | (ord($bytes[$i * PHP_INT_SIZE + $j]) & 0xFF);
            }
            // Absolute value in two's compliment (with min int going to zero)
            $currentInt = $currentInt & PHP_INT_MAX;
            $ints[] = $currentInt;
        }
        return $ints;
    }

    // Returns the smallest bit mask of all 1s such that ($toRepresent & mask) = $toRepresent.
    // $toRepresent must be an integer greater than or equal to 1.
    private static function getMinimalBitMask($toRepresent) {
        if($toRepresent < 1) {
            return false;
        }
        $mask = 0x1;
        while($mask < $toRepresent) {
            $mask = ($mask << 1) | 1;
        }
        return $mask;
    }

    // Returns the character at $index in $string in constant time.
    private static function safeArrayIndex($string, $index) {
        // FIXME: Make the const-time hack below work for all integer sizes, or check it properly.
        if(count($string) > 65535 || $index > count($string)) {
            return false;
        }
        $character = 0;
        for($i = 0; $i < count($string); $i++) {
            $x = $i ^ $index;
            $mask = (((($x | ($x >> 16)) & 0xFFFF) + 0xFFFF) >> 16) - 1;
            $character |= ord($string[$i]) & $mask;
        }
        return chr($character);
    }

    // Returns a $bytes long string of cryptographically secure random bytes or false on failure
    public static function getBytes($length) {
        // OpenSSL is preferred
        if(extension_loaded("openssl")) {
            $random = openssl_random_pseudo_bytes($length, $strong);
            if($strong) {
                return $random;
            }
        }

        // mcrypt is the best alternative, does urandom stuff for us even on Windows when PHP is >= 5.3.7
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) == "WIN";
        if(extension_loaded("mcrypt") &&
           (version_compare(PHP_VERSION, "5.3.7", ">=") || !$isWindows)) {
            // urandom is only the default source when PHP >= 5.6.0
            $random = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if($random !== false) {
                return $random;
            }
        }

        // Last resort: read directly from /dev/urandom
        if(!$isWindows) {
            $urandom = fopen("/dev/urandom", "rb");
            $random = fread($urandom, $length);
            fclose($urandom);
            // Only a warning is generated if the read fails
            if($random !== false && strlen($random) === $length) {
                return $random;
            }
        }

        // Better to fail than to roll our own generator
        return false;
    }
}

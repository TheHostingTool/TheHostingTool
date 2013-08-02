<?php

// https://gist.github.com/kmark/4440574

/**************************************************************************************
 * Copyright (c) 2013, cPanel, Inc.                                                   *
 * All rights reserved.                                                               *
 *                                                                                    *
 * Redistribution and use in source and binary forms, with or without modification,   *
 * are permitted provided that the following conditions are met:                      *
 *                                                                                    *
 * Redistributions of source code must retain the above copyright notice, this list   *
 * of conditions and the following disclaimer. Redistributions in binary form must    *
 * reproduce the above copyright notice, this list of conditions and the following    *
 * disclaimer in the documentation and/or other materials provided with the           *
 * distribution. THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  *
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE  *
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE     *
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR   *
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES     *
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS  *
 * OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY      *
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING     *
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN  *
 * IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                      *
 *                                                                                    *
 * PHP implementation of cPanel's LogMeIn.pm by Kevin Mark                            *
 **************************************************************************************/

class LogMeIn {
    // The available services with their HTTPS ports
    private static $servicePorts = array('cpanel' => 2083, 'whm' => 2087, 'webmail' => 2096);
    public static function getLoggedInUrl($user, $pass, $hostname, $service, $goto = '/') {
        // If no valid service has been given, default to cPanel
        $port = isset(self::$servicePorts[$service]) ? self::$servicePorts[$service] : 2083;
        $ch = curl_init();
        $fields = array('user' => $user, 'pass' => $pass, 'goto_uri' => $goto);
        // Sets the POST URL to something like: https://example.com:2083/login/
        curl_setopt($ch, CURLOPT_URL, 'https://' . $hostname . ':' . $port . '/login/');
        curl_setopt($ch, CURLOPT_POST, true);
        // Turn our array of fields into a url encoded query string i.e.: ?user=foo&pass=bar
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        // RFC 2616 14.10 compliance
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection' => 'close'));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Execute POST query returning both the response headers and content into $page
        $page = curl_exec($ch);
        curl_close($ch);
        $session = $token = array();
        // Find the session cookie in the page headers
        if(!preg_match('/session=([^\;]+)/', $page, $session)) {
            // This will also fail if the login authentication failed. No need to explicitly check for it.
            return false;
        }
        // Find the cPanel session token in the page content
        if(!preg_match('|<META HTTP-EQUIV="refresh"[^>]+URL=/(cpsess\d+)/|i', $page, $token)) {
            return false;
        }
        // Append the goto_uri to the query string if it's been manually set
        $extra = $goto == '/' ? '' : '&goto_uri=' . urlencode($goto);
        return 'https://' . $hostname . ':' . $port . '/' . $token[1] . '/login/?session=' . $session[1] . $extra;
    }
}

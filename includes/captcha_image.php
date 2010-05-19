<?
// *** CAPTCHA image generation ***
// *** http://frikk.tk ***

session_start();

// *** Tell the browser what kind of file is come'n at 'em! ***
header("Content-Type: image/jpeg");

// *** Send a generated image to the browser ***
die(create_image());

// *** Function List ***
function create_image()
{
	// *** Generate a passcode using md5
	//	(it will be all lowercase hex letters and numbers ***
	$md5 = md5(rand(0,9999));
	$pass = substr($md5, 10, 5);

	// *** Set the session cookie so we know what the passcode is ***
	$_SESSION["pass"] = $pass;

	// *** Create the image resource ***
	$image = ImageCreatetruecolor(100, 20);

	// *** We are making two colors, white and black ***
	$clr_white = ImageColorAllocate($image, 255, 255, 255);
	$clr_black = ImageColorAllocate($image, 0, 0, 0);

	// *** Make the background black ***
	imagefill($image, 0, 0, $clr_black);

	// *** Set the image height and width ***
	imagefontheight(15);
	imagefontwidth(15);

	// *** Add the passcode in white to the image ***
	imagestring($image, 5, 30, 3, $pass, $clr_white);

	// *** Throw in some lines to trick those cheeky bots! ***
	imageline($image, 5, 1, 50, 20, $clr_white);
	imageline($image, 60, 1, 96, 20, $clr_white);

	// *** Return the newly created image in jpeg format ***
	return imagejpeg($image);

	// *** Just in case... ***
	imagedestroy($image);
}
?>
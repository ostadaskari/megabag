<?php
session_start();    
// Generate a 4-character CAPTCHA (numbers and letters without 0 o 1  )
$characters = 'ABCDEFHJKMNPRSTUVWXYZ234567';
$captcha_text = substr(str_shuffle($characters),0,4);

// Store lowercase version in session for case-insensitive comparison

$_SESSION['captcha'] = strtolower($captcha_text);

// Create the image
$width = 140;
$height = 40;

$image = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$noise_color = imagecolorallocate($image,100,120,180); //light blue
//background 
imagefilledrectangle($image,0,0, $width, $height, $bg_color);

// نویز نقطه‌ای
for ($i = 0; $i < 300; $i++) {
    imagesetpixel($image, rand(0, $width), rand(0, $height), $noise_color);
}

// نویز خطی
for ($i = 0; $i < 7; $i++) {
    imageline($image, rand(0,$width), rand(0,$height), rand(0,$width), rand(0,$height), $noise_color);
}

//write captcha in middle of image
$font= '../fonts/arial.ttf';
$font_size= 18;
// $textbox_width= imagefontwidth($font_size)* strlen($captcha_text);
$x= 10;
$y= 30;


for($i = 0; $i < strlen($captcha_text); $i++) {
    $angle = rand (-25, 25);
    $letter= $captcha_text[$i];
    imagettftext($image, $font_size,$angle, $x, $y, $text_color, $font , $letter);
    $x +=25;
}


// Add text to image
// imagestring($image, $font_size, $x, $y, $captcha_text, $text_color);

// Output image
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
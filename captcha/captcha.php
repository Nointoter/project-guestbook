<?php
session_start();

$captcha_code = '';
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$characters_length = strlen($characters);
$captcha_length = 6;
for ($i = 0; $i < $captcha_length; $i++) {
    $captcha_code .= $characters[rand(0, $characters_length - 1)];
}

$_SESSION['captcha_code'] = $captcha_code;

$width = 200;
$height = 50;
$image = imagecreatetruecolor($width, $height);

$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$line_color = imagecolorallocate($image, 64, 64, 64);
$pixel_color = imagecolorallocate($image, 128, 128, 128);

imagefilledrectangle($image, 0, 0, $width, $height, $background_color);

for ($i = 0; $i < 10; $i++) {
    imageline($image, 0, rand() % $height, $width, rand() % $height, $line_color);
}

for ($i = 0; $i < 1000; $i++) {
    imagesetpixel($image, rand() % $width, rand() % $height, $pixel_color);
}

imagettftext($image, 20, 0, 30, 30, $text_color, 'arial.ttf', $captcha_code);

header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>

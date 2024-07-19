<?php
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function create_captcha() {
    session_start();
    $captcha_code = '';
    $captcha_image_height = 50;
    $captcha_image_width = 130;
    $total_characters_on_image = 6;
    
    $possible_captcha_letters = '23456789bcdfghjkmnpqrstvwxyz';
    $captcha_font = './font.ttf';
    $captcha_code = '';
    
    for ($i = 0; $i < $total_characters_on_image; $i++) {
        $captcha_code .= substr($possible_captcha_letters, mt_rand(0, strlen($possible_captcha_letters)-1), 1);
    }
    
    $_SESSION['captcha_code'] = $captcha_code;
    
    $captcha_image = imagecreate($captcha_image_width, $captcha_image_height);
    
    $background_color = imagecolorallocate($captcha_image, 255, 255, 255);
    $text_color = imagecolorallocate($captcha_image, 0, 0, 0);
    $line_color = imagecolorallocate($captcha_image, 64, 64, 64);
    $pixel_color = imagecolorallocate($captcha_image, 0, 0, 255);
    
    imagefilledrectangle($captcha_image, 0, 0, $captcha_image_width, $captcha_image_height, $background_color);
    
    for($i = 0; $i < 6; $i++) {
        imageline($captcha_image, 0, rand()%50, 250, rand()%50, $line_color);
    }
    
    for($i = 0; $i < 1000; $i++) {
        imagesetpixel($captcha_image, rand()%200, rand()%50, $pixel_color);
    }
    
    imagettftext($captcha_image, 20, 0, 15, 30, $text_color, $captcha_font, $captcha_code);
    
    header('Content-Type: image/jpeg');
    imagejpeg($captcha_image);
    imagedestroy($captcha_image);
}
?>

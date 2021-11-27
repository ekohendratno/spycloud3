<?php
$src = isset($_GET['src']) ? $_GET['src'] : false;
$size = isset($_GET['size']) ? str_replace(array('<', 'x'), '', $_GET['size']) != '' ? $_GET['size'] : 100 : 100;

list($w,$h) = explode('x', str_replace('<', '', $size) . 'x');
$width = ($w != '') ? floor(max(8, min(1500, $w))) : '';
$height = ($h != '') ? floor(max(8, min(1500, $h))) : '';


// Content type
header('Content-Type: image/jpeg');

// Get new dimensions
list($width_orig, $height_orig) = getimagesize($src);

$ratio_orig = $width_orig/$height_orig;

if ($width/$height > $ratio_orig) {
    $width = $height*$ratio_orig;
} else {
    $height = $width/$ratio_orig;
}

// Resample
$image_p = imagecreatetruecolor($width, $height);
$image = imagecreatefromwebp($src);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
imagewebp($image_p, null, 100);
?>
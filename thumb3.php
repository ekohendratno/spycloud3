<?php
$src = isset($_GET['src']) ? $_GET['src'] : false;
$size = isset($_GET['size']) ? str_replace(array('<', 'x'), '', $_GET['size']) != '' ? $_GET['size'] : 100 : 100;

list($w,$h) = explode('x', str_replace('<', '', $size) . 'x');
$width = ($w != '') ? floor(max(8, min(1500, $w))) : '';
$height = ($h != '') ? floor(max(8, min(1500, $h))) : '';


// Get new dimensions
list($width_orig, $height_orig, $type) = getimagesize($src);

$ratio_orig = $width_orig/$height_orig;

if ($width/$height > $ratio_orig) {
    $width = $height*$ratio_orig;
} else {
    $height = $width/$ratio_orig;
}

// Resample
$image_p = imagecreatetruecolor($width, $height);


switch ($type) {
    case 1:
        header('Content-Type: image/gif');
        $image = imagecreatefromgif($src);

        // Resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        imagegif($image_p, $src);
        break;
    case 2:
        header('Content-Type: image/jpeg');
        $image = imagecreatefromjpeg($src);

        // Resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        imagejpeg($image_p, $src, 50);
        break;
    case 3:
        header('Content-Type: image/png');
        $image = imagecreatefrompng($src);

        // Resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        imagepng($image_p, $src);
        break;
    case 18:
        header('Content-Type: image/jpeg');
        $image = imagecreatefromwebp($src);

        // Resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        imagewebp($image_p, $src);
        break;
}

?>
<?php
$src = 'public/assets/logo.png';
$img = imagecreatefrompng($src);
$width = imagesx($img);
$height = imagesy($img);

$rowHasPixels = array();

for ($y = 0; $y < $height; $y++) {
    $hasPixel = false;
    for ($x = 0; $x < $width; $x++) {
        $color = imagecolorat($img, $x, $y);
        $alpha = ($color >> 24) & 0x7F;
        if ($alpha < 120) { // Not fully transparent
            $hasPixel = true;
            break;
        }
    }
    $rowHasPixels[$y] = $hasPixel;
}

// Find a gap (sequence of false) in the middle/lower half
$cropY = $height;
$inGap = false;
$gapStart = 0;

for ($y = intval($height * 0.4); $y < $height; $y++) {
    if (!$rowHasPixels[$y]) {
        if (!$inGap) {
            $inGap = true;
            $gapStart = $y;
        }
    } else {
        if ($inGap) {
            $inGap = false;
            $gapLength = $y - $gapStart;
            if ($gapLength > 2) {
                // We found a gap! We can crop at gapStart
                $cropY = $gapStart;
                break;
            }
        }
    }
}

echo "Height: $height, Crop Y: $cropY\n";

if ($cropY < $height) {
    $cropped = imagecrop($img, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $cropY]);
    imagepng($cropped, 'public/assets/logo_cropped.png');
    echo "Saved as logo_cropped.png\n";
} else {
    echo "No gap found to crop!\n";
}

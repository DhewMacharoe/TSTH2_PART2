<?php
$src = 'public/assets/logo.jpg';
$dest = 'public/assets/logo.png';

$img = imagecreatefromjpeg($src);
if (!$img) {
    die("Failed to load original logo.jpg\n");
}

$width = imagesx($img);
$height = imagesy($img);

// Let's find the gap to crop
$rowHasPixels = array();
for ($y = 0; $y < $height; $y++) {
    $hasPixel = false;
    for ($x = 0; $x < $width; $x++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        // Not white = pixel
        if (!($r > 240 && $g > 240 && $b > 240)) {
            $hasPixel = true;
            break;
        }
    }
    $rowHasPixels[$y] = $hasPixel;
}

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
                $cropY = $gapStart;
                break;
            }
        }
    }
}

echo "Original height: $height, Crop Y: $cropY\n";
// Create cropped and transparent image
$newImg = imagecreatetruecolor($width, $cropY);
imagealphablending($newImg, false);
imagesavealpha($newImg, true);

$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
imagefill($newImg, 0, 0, $transparent);

for ($y = 0; $y < $cropY; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // If it's close to white, make it transparent
        if ($r > 230 && $g > 230 && $b > 230) {
            imagesetpixel($newImg, $x, $y, $transparent);
        } else {
            $opaque = imagecolorallocatealpha($newImg, $r, $g, $b, 0);
            imagesetpixel($newImg, $x, $y, $opaque);
        }
    }
}

imagepng($newImg, $dest);
imagedestroy($img);
imagedestroy($newImg);

echo "Successfully created transparent cropped logo.png\n";

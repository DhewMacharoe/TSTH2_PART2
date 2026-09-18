<?php

$src = 'public/assets/logo.jpg';
$dest = 'public/assets/logo.png';

$img = imagecreatefromjpeg($src);

if ($img) {
    // Get image dimensions
    $width = imagesx($img);
    $height = imagesy($img);

    // Create a new true color image with alpha channel
    $newImg = imagecreatetruecolor($width, $height);
    imagesavealpha($newImg, true);
    
    // Fill with transparent background
    $transparent = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
    imagefill($newImg, 0, 0, $transparent);
    
    // Iterate over pixels
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            
            // If it's close to white, make it transparent
            if ($r > 240 && $g > 240 && $b > 240) {
                // transparent
                imagesetpixel($newImg, $x, $y, $transparent);
            } else {
                // Keep original pixel but set it on the new image
                $color = imagecolorallocate($newImg, $r, $g, $b);
                imagesetpixel($newImg, $x, $y, $color);
            }
        }
    }

    imagepng($newImg, $dest);
    imagedestroy($img);
    imagedestroy($newImg);
    echo "Successfully created transparent logo.png\n";
} else {
    echo "Failed to load logo.jpg\n";
}

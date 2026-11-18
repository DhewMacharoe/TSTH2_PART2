<?php
$src = 'public/assets/logo.png'; // The cropped logo
$dest = 'public/assets/logo.png'; // Overwrite it

$img = imagecreatefrompng($src);

if ($img) {
    $width = imagesx($img);
    $height = imagesy($img);

    $newImg = imagecreatetruecolor($width, $height);
    // CRITICAL: Disable alpha blending and save alpha
    imagealphablending($newImg, false);
    imagesavealpha($newImg, true);
    
    $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
    imagefill($newImg, 0, 0, $transparent);
    
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $color = imagecolorat($img, $x, $y);
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;
            
            // Treat almost white as transparent
            if ($r > 230 && $g > 230 && $b > 230) {
                imagesetpixel($newImg, $x, $y, $transparent);
            } else {
                // We keep the original pixel. Need to allocate with 0 alpha (opaque)
                $opaqueColor = imagecolorallocatealpha($newImg, $r, $g, $b, 0);
                imagesetpixel($newImg, $x, $y, $opaqueColor);
            }
        }
    }

    imagepng($newImg, $dest);
    imagedestroy($img);
    imagedestroy($newImg);
    echo "Successfully removed background from logo.png\n";
} else {
    echo "Failed to load logo.png\n";
}

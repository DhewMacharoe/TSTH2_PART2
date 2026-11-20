<?php
$src = 'public/assets/logo.png';
$dest = 'public/assets/logo.png';

$img = imagecreatefrompng($src);
if (!$img) {
    die("Failed to load logo.png\n");
}

$width = imagesx($img);
$height = imagesy($img);

$newImg = imagecreatetruecolor($width, $height);
imagealphablending($newImg, false);
imagesavealpha($newImg, true);

$transparent = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
imagefill($newImg, 0, 0, $transparent);

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $color = imagecolorat($img, $x, $y);
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;
        
        // If it's black or very dark, assume it was the white background that became black
        if ($r < 10 && $g < 10 && $b < 10) {
            imagesetpixel($newImg, $x, $y, $transparent);
        } else {
            // Keep the original color
            $opaque = imagecolorallocatealpha($newImg, $r, $g, $b, 0);
            imagesetpixel($newImg, $x, $y, $opaque);
        }
    }
}

imagepng($newImg, $dest);
imagedestroy($img);
imagedestroy($newImg);

echo "Successfully recovered transparent logo.png\n";

<?php
$file = 'public/assets/logo.png';
$img = @imagecreatefrompng($file);

if (!$img) {
    // maybe it's actually a jpeg?
    $img = @imagecreatefromjpeg($file);
    if (!$img) {
        die("Cannot read logo.png\n");
    }
}

$w = imagesx($img);
$h = imagesy($img);

$out = imagecreatetruecolor($w, $h);
imagealphablending($out, false);
imagesavealpha($out, true);

$transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
imagefill($out, 0, 0, $transparent);

// Sample top-left pixel to determine background color
$bgColor = imagecolorat($img, 0, 0);
$bgR = ($bgColor >> 16) & 0xFF;
$bgG = ($bgColor >> 8) & 0xFF;
$bgB = $bgColor & 0xFF;

$isBgBlack = ($bgR < 50 && $bgG < 50 && $bgB < 50);
$isBgWhite = ($bgR > 200 && $bgG > 200 && $bgB > 200);

echo "Background looks " . ($isBgBlack ? "Black" : ($isBgWhite ? "White" : "Unknown ($bgR, $bgG, $bgB)")) . "\n";

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $color = imagecolorat($img, $x, $y);
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;
        
        $makeTransparent = false;
        
        if ($isBgBlack && $r < 30 && $g < 30 && $b < 30) {
            $makeTransparent = true;
        } elseif ($isBgWhite && $r > 220 && $g > 220 && $b > 220) {
            $makeTransparent = true;
        }
        
        if ($makeTransparent) {
            imagesetpixel($out, $x, $y, $transparent);
        } else {
            $opaque = imagecolorallocatealpha($out, $r, $g, $b, 0);
            imagesetpixel($out, $x, $y, $opaque);
        }
    }
}

imagepng($out, 'public/assets/logo.png');
echo "Saved transparent logo.png\n";

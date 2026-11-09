<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\ImageService;

$uploadDirectory = __DIR__ . '/../storage/uploads';

// Pak het eerste png/jpg-bestand uit de uploadmap
$files = glob($uploadDirectory . '/*.{jpg,jpeg,png}', GLOB_BRACE);

if (empty($files)) {
    exit("Geen afbeelding gevonden in storage/uploads. Upload er eerst een.\n");
}

$original = $files[0];
echo "Testbestand: " . $original . PHP_EOL;

$imageService = new ImageService();

$result = $imageService->createVersions(
    $original,
    $uploadDirectory . '/resized',
    __DIR__ . '/../storage/thumbnails'
);

var_dump($result);
<?php

namespace App\Services;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Maakt een verkleinde versie en een thumbnail.
     * Het originele bestand wordt alleen gelezen, nooit aangepast.
     *
     * @return array{name: string, width: int, height: int}
     */
    public function createVersions(
        string $originalPath,
        string $resizedDirectory,
        string $thumbnailDirectory,
        int $maxWidth = 1200,
        int $maxHeight = 1200,
        string $resizedBaseName = 'afbeelding-resized'
    ): array {
        if (!is_file($originalPath)) {
            throw new \RuntimeException('De originele afbeelding bestaat niet.');
        }

        $this->createDirectory($resizedDirectory);
        $this->createDirectory($thumbnailDirectory);

        $filename  = basename($originalPath);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Verkleinde versie: past binnen max breedte én hoogte,
        // behoudt de verhouding en vergroot kleine afbeeldingen niet.
        $resizedPath = $this->uniquePath($resizedDirectory, $resizedBaseName, $extension);

        $resizedImage = $this->manager->read($originalPath);
        $resizedImage->scaleDown(width: $maxWidth, height: $maxHeight);
        $resizedImage->save($resizedPath);

        // Thumbnail 300x300, zelfde bestandsnaam als de upload (de lijst zoekt daarop)
        $thumbnailPath = $thumbnailDirectory . DIRECTORY_SEPARATOR . $filename;

        $thumbnailImage = $this->manager->read($originalPath);
        $thumbnailImage->cover(300, 300);
        $thumbnailImage->save($thumbnailPath);

        return [
            'name'   => basename($resizedPath),
            'width'  => $resizedImage->width(),
            'height' => $resizedImage->height(),
        ];
    }

    /** Geeft een pad terug dat nog niet bestaat: naam.jpg, naam-1.jpg, naam-2.jpg ... */
    private function uniquePath(string $directory, string $baseName, string $extension): string
    {
        $path = $directory . DIRECTORY_SEPARATOR . $baseName . '.' . $extension;
        $i = 1;

        while (file_exists($path)) {
            $path = $directory . DIRECTORY_SEPARATOR . $baseName . '-' . $i . '.' . $extension;
            $i++;
        }

        return $path;
    }

    private function createDirectory(string $directory): void
    {
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new \RuntimeException('De map kon niet worden aangemaakt: ' . $directory);
        }
    }
}
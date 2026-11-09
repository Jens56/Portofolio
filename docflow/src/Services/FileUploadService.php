<?php

namespace App\Services;

class FileUploadService
{
    public function store(array $upload): ?string
    {
        $extension = strtolower(pathinfo($upload['name'], PATHINFO_EXTENSION));
        $targetDir = __DIR__ . '/../../storage/uploads';
        $newName = uniqid('upload_', true) . '.' . $extension;

        if (move_uploaded_file($upload['tmp_name'], $targetDir . '/' . $newName)) {
            return $newName;
        }

        return null;
    }
}
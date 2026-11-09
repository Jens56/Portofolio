<?php

namespace App\Validators;

class UploadValidator
{
    private const MAX_SIZE = 2 * 1024 * 1024;
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'pdf'];
    private const MIME_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];

    public function validate(?array $upload): array
    {
        $errors = [];

        if (!$upload || $upload['error'] !== UPLOAD_ERR_OK) {
            return ['Er is geen geldig bestand geüpload.'];
        }

        if ($upload['size'] > self::MAX_SIZE) {
            $errors[] = 'Het bestand is te groot.';
        }

        $extension = strtolower(pathinfo($upload['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::EXTENSIONS, true)) {
            $errors[] = 'Dit bestandstype is niet toegestaan.';
        }

        return $errors;
    }
}
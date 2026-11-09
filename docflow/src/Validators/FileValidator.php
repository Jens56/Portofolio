<?php

namespace App\Validators;

class FileValidator
{
    public function isValidName(string $filename): bool
    {
        return $filename !== '';
    }
}
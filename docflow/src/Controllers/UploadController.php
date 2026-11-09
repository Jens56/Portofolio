<?php

namespace App\Controllers;

use App\Services\ImageService;

class UploadController
{
    private const DEFAULT_MAX_SIZE = 1200;
    private const MIN_DIMENSION    = 50;
    private const MAX_DIMENSION    = 5000;
    private const MAX_NAME_LENGTH  = 60;

    private string $storage;

    public function __construct()
    {
        $this->storage = __DIR__ . '/../../storage';
    }

    /** Verwerkt het uploadformulier. */
    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'upload') {
            return ['errors' => [], 'messages' => [], 'section' => 'upload'];
        }

        return $this->handleUpload() + ['section' => 'upload'];
    }

    private function handleUpload(): array
    {
        $errors   = [];
        $messages = [];

        $upload = $_FILES['document'] ?? null;

        if (!$upload || $upload['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Er is geen geldig bestand geüpload.';
            return ['errors' => $errors, 'messages' => $messages];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $maxSize = 2 * 1024 * 1024;

        $extension = strtolower(pathinfo($upload['name'], PATHINFO_EXTENSION));
        $isImage   = in_array($extension, ['jpg', 'jpeg', 'png'], true);

        if ($upload['size'] > $maxSize) {
            $errors[] = 'Het bestand is te groot.';
        }

        if (!in_array($extension, $allowedExtensions, true)) {
            $errors[] = 'Dit bestandstype is niet toegestaan.';
        }

        // Instellingen voor de verkleinde afbeelding valideren (alleen bij afbeeldingen).
        $maxWidth  = self::DEFAULT_MAX_SIZE;
        $maxHeight = self::DEFAULT_MAX_SIZE;
        $baseName  = 'afbeelding-resized';

        if ($isImage) {
            [$maxWidth, $widthError]   = $this->parseDimension($_POST['max_width'] ?? '', 'breedte');
            [$maxHeight, $heightError] = $this->parseDimension($_POST['max_height'] ?? '', 'hoogte');

            foreach ([$widthError, $heightError] as $error) {
                if ($error !== null) {
                    $errors[] = $error;
                }
            }

            [$customName, $nameError] = $this->parseCustomName($_POST['new_name'] ?? '');

            if ($nameError !== null) {
                $errors[] = $nameError;
            }

            if ($customName !== null) {
                $baseName = $customName;
            } else {
                // Standaardnaam: originele naam + "-resized"
                $original = $this->cleanName((string) $upload['name']);
                $baseName = ($original !== '' ? $original : 'afbeelding') . '-resized';
            }
        }

        // Pas opslaan als alle controles geslaagd zijn.
        if (!empty($errors)) {
            return ['errors' => $errors, 'messages' => $messages];
        }

        $targetDir = $this->storage . '/uploads';
        $newName = uniqid('upload_', true) . '.' . $extension;
        $targetPath = $targetDir . '/' . $newName;

        if (!move_uploaded_file($upload['tmp_name'], $targetPath)) {
            $errors[] = 'Het bestand kon niet worden opgeslagen.';
            return ['errors' => $errors, 'messages' => $messages];
        }

        $messages[] = 'Het bestand is succesvol geüpload.';

        $logFile = $this->storage . '/logs/upload_log.txt';
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Bestand geüpload: " . $newName . "\n", FILE_APPEND);
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Bestandsgrootte: " . $upload['size'] . " bytes\n\n", FILE_APPEND);

        if ($isImage) {
            try {
                $result = (new ImageService())->createVersions(
                    $targetPath,
                    $targetDir . '/resized',
                    $this->storage . '/thumbnails',
                    $maxWidth,
                    $maxHeight,
                    $baseName
                );

                // Onthoud welke naam bij deze upload hoort, zodat de lijst die kan tonen.
                $this->saveName($newName, $result['name']);

                $messages[] = sprintf(
                    'Aangepaste afbeelding opgeslagen als "%s" (%d × %d px, maximaal %d × %d px).',
                    $result['name'],
                    $result['width'],
                    $result['height'],
                    $maxWidth,
                    $maxHeight
                );
            } catch (\Throwable $e) {
                $errors[] = 'De afbeelding kon niet worden verwerkt: ' . $e->getMessage();
            }
        }

        return ['errors' => $errors, 'messages' => $messages];
    }

    /** Leest de koppeling upload-naam => naam van de verkleinde afbeelding. */
    private function readNames(): array
    {
        $path = $this->storage . '/names.json';

        if (!is_file($path)) {
            return [];
        }

        $data = json_decode((string) file_get_contents($path), true);

        return is_array($data) ? $data : [];
    }

    private function saveName(string $uploadName, string $resizedName): void
    {
        $map = $this->readNames();
        $map[$uploadName] = $resizedName;

        file_put_contents(
            $this->storage . '/names.json',
            json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }

    /**
     * Leest een afmeting uit het formulier. Leeg = standaard (1200).
     * @return array{0: int, 1: ?string} [waarde, foutmelding]
     */
    private function parseDimension($raw, string $label): array
    {
        if (!is_string($raw)) {
            return [self::DEFAULT_MAX_SIZE, "De maximale $label is ongeldig."];
        }

        $raw = trim($raw);

        if ($raw === '') {
            return [self::DEFAULT_MAX_SIZE, null];
        }

        $value = filter_var($raw, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => self::MIN_DIMENSION, 'max_range' => self::MAX_DIMENSION],
        ]);

        if ($value === false) {
            return [
                self::DEFAULT_MAX_SIZE,
                sprintf(
                    'De maximale %s moet een geheel getal zijn tussen %d en %d pixels.',
                    $label,
                    self::MIN_DIMENSION,
                    self::MAX_DIMENSION
                ),
            ];
        }

        return [$value, null];
    }

    /**
     * Valideert de gewenste naam. Leeg = geen eigen naam.
     * @return array{0: ?string, 1: ?string} [schone naam, foutmelding]
     */
    private function parseCustomName($raw): array
    {
        if (!is_string($raw)) {
            return [null, 'De opgegeven naam is ongeldig.'];
        }

        $raw = trim($raw);

        if ($raw === '') {
            return [null, null];
        }

        if (mb_strlen($raw) > self::MAX_NAME_LENGTH) {
            return [null, 'De naam mag maximaal ' . self::MAX_NAME_LENGTH . ' tekens bevatten.'];
        }

        $clean = $this->cleanName($raw);

        if ($clean === '') {
            return [null, 'De naam bevat geen geldige tekens (gebruik letters, cijfers, - of _).'];
        }

        return [$clean, null];
    }

    /** Haalt een eventuele afbeeldingsextensie weg en laat alleen veilige tekens over. */
    private function cleanName(string $raw): string
    {
        $raw  = preg_replace('/\.(jpe?g|png)$/i', '', trim($raw));
        $name = preg_replace('/[^A-Za-z0-9_-]+/', '-', $raw);
        $name = trim($name, '-_');
        $name = substr($name, 0, self::MAX_NAME_LENGTH);

        // Gereserveerde Windows-namen vermijden
        if (preg_match('/^(con|prn|aux|nul|com\d|lpt\d)$/i', $name)) {
            $name .= '-afbeelding';
        }

        return $name;
    }

    /**
     * @return array<array{name: string, display_name: string, resized_name: ?string, extension: string, size: int, modified: int, has_thumbnail: bool}>
     */
    public function getFiles(): array
    {
        $uploadDir  = $this->storage . '/uploads';
        $thumbDir   = $this->storage . '/thumbnails';
        $resizedDir = $uploadDir . '/resized';
        $names      = $this->readNames();
        $files = [];

        foreach (glob($uploadDir . '/*') ?: [] as $path) {
            if (!is_file($path)) {
                continue;
            }

            $name = basename($path);

            // Naam van de verkleinde afbeelding: uit de koppeling, of (oude uploads)
            // dezelfde naam als de upload als die in de resized-map staat.
            $resizedName = $names[$name] ?? null;

            if ($resizedName === null && is_file($resizedDir . '/' . $name)) {
                $resizedName = $name;
            }

            if ($resizedName !== null && !is_file($resizedDir . '/' . $resizedName)) {
                $resizedName = null;
            }

            $files[] = [
                'name'          => $name,
                'display_name'  => $resizedName ?? $name,
                'resized_name'  => $resizedName,
                'extension'     => strtolower(pathinfo($name, PATHINFO_EXTENSION)),
                'size'          => filesize($path),
                'modified'      => filemtime($path),
                'has_thumbnail' => is_file($thumbDir . '/' . $name),
            ];
        }

        usort($files, fn($a, $b) => $b['modified'] <=> $a['modified']);

        return $files;
    }

    /**
     * Aangepaste (verkleinde) afbeeldingen, nieuwste eerst.
     * @return array<array{name: string, width: int, height: int, size: int, modified: int}>
     */
    public function getResizedFiles(): array
    {
        $files = [];

        foreach (glob($this->storage . '/uploads/resized/*') ?: [] as $path) {
            if (!is_file($path)) {
                continue;
            }

            $info = @getimagesize($path);

            $files[] = [
                'name'     => basename($path),
                'width'    => $info[0] ?? 0,
                'height'   => $info[1] ?? 0,
                'size'     => filesize($path),
                'modified' => filemtime($path),
            ];
        }

        usort($files, fn($a, $b) => $b['modified'] <=> $a['modified']);

        return $files;
    }

    /** Cijfers voor het dashboard (zonder exports, die staan in ExportController). */
    public function getStats(): array
    {
        $files = $this->getFiles();

        return [
            'files'   => count($files),
            'resized' => count($this->getResizedFiles()),
            'bytes'   => array_sum(array_column($files, 'size')),
        ];
    }

    public function showThumbnail(string $name): void
    {
        $this->serveFile($this->storage . '/thumbnails', $name, ['image/jpeg', 'image/png']);
    }

    public function showResized(string $name): void
    {
        $this->serveFile($this->storage . '/uploads/resized', $name, ['image/jpeg', 'image/png']);
    }

    /** Opent het originele bestand uit de uploadmap (JPG, PNG of PDF). */
    public function showFile(string $name): void
    {
        $this->serveFile(
            $this->storage . '/uploads',
            $name,
            ['image/jpeg', 'image/png', 'application/pdf']
        );
    }

    private function serveFile(string $dir, string $name, array $allowedMimes): void
    {
        // basename voorkomt dat een ander pad wordt opgevraagd.
        $path = $dir . '/' . basename($name);

        if (!is_file($path)) {
            http_response_code(404);
            exit;
        }

        $mime = mime_content_type($path);

        if (!in_array($mime, $allowedMimes, true)) {
            http_response_code(404);
            exit;
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }

    public function paginate(array $files, int $page, int $perPage = 5): array
    {
        $total = count($files);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));

        return [
            'files'      => array_slice($files, ($page - 1) * $perPage, $perPage),
            'page'       => $page,
            'totalPages' => $totalPages,
            'total'      => $total,
        ];
    }
}
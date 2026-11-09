<?php

namespace App\Controllers;

use App\Services\ExportService;

class ExportController
{
    private string $exportDir;

    public function __construct(private UploadController $uploads)
    {
        $this->exportDir = __DIR__ . '/../../storage/exports';
    }

    /** Verwerkt het exportformulier en geeft meldingen terug voor de sectie Exports. */
    public function handle(): array
    {
        $errors   = [];
        $messages = [];

        try {
            $result = (new ExportService())->createPdf(
                $this->uploads->getFiles(),
                $this->exportDir
            );

            $messages[] = sprintf(
                'Het PDF-rapport "%s" is aangemaakt met %d bestanden.',
                $result['name'],
                $result['count']
            );
        } catch (\Throwable $e) {
            $errors[] = 'De export is mislukt: ' . $e->getMessage();
        }

        return ['errors' => $errors, 'messages' => $messages, 'section' => 'exports'];
    }

    /**
     * Gemaakte PDF-rapporten, nieuwste eerst.
     * @return array<array{name: string, size: int, modified: int}>
     */
    public function getExports(): array
    {
        $exports = [];

        foreach (glob($this->exportDir . '/*.pdf') ?: [] as $path) {
            if (!is_file($path)) {
                continue;
            }

            $exports[] = [
                'name'     => basename($path),
                'size'     => filesize($path),
                'modified' => filemtime($path),
            ];
        }

        usort($exports, fn($a, $b) => $b['modified'] <=> $a['modified']);

        return $exports;
    }

    /** Opent of downloadt een gemaakt PDF-rapport. */
    public function showExport(string $name, bool $download = false): void
    {
        // basename voorkomt dat een ander pad wordt opgevraagd.
        $path = $this->exportDir . '/' . basename($name);

        if (!is_file($path) || mime_content_type($path) !== 'application/pdf') {
            http_response_code(404);
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }
}
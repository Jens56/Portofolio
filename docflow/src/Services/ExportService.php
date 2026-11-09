<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class ExportService
{
    /**
     * Maakt een PDF-rapport van de geüploade bestanden.
     *
     * @param array $files  Lijst uit UploadController::getFiles()
     * @return array{name: string, count: int}
     */
    public function createPdf(array $files, string $exportDir): array
    {
        if (empty($files)) {
            throw new \RuntimeException('Er zijn geen bestanden om te exporteren.');
        }

        if (!is_dir($exportDir) && !mkdir($exportDir, 0775, true) && !is_dir($exportDir)) {
            throw new \RuntimeException('De exportmap kon niet worden aangemaakt.');
        }

        if (!is_writable($exportDir)) {
            throw new \RuntimeException('De exportmap is niet beschrijfbaar.');
        }

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->buildHtml($files), 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $name = $this->uniqueName($exportDir, 'export_' . date('Y-m-d_His'));

        if (file_put_contents($exportDir . '/' . $name, $dompdf->output()) === false) {
            throw new \RuntimeException('Het PDF-bestand kon niet worden opgeslagen.');
        }

        return ['name' => $name, 'count' => count($files)];
    }

    private function uniqueName(string $dir, string $base): string
    {
        $name = $base . '.pdf';
        $i = 1;

        while (file_exists($dir . '/' . $name)) {
            $name = $base . '-' . $i . '.pdf';
            $i++;
        }

        return $name;
    }

    private function buildHtml(array $files): string
    {
        $totalBytes = array_sum(array_column($files, 'size'));

        $rows = '';
        foreach ($files as $i => $file) {
            $rows .= sprintf(
                '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                $i + 1,
                $this->e($file['display_name']),
                $this->e($file['name']),
                $this->e(strtoupper($file['extension'])),
                $this->e($this->formatSize((int) $file['size'])),
                $this->e(date('d-m-Y H:i', (int) $file['modified']))
            );
        }

        return '<!doctype html><html lang="nl"><head><meta charset="utf-8"><style>
            body { font-family: "DejaVu Sans", sans-serif; font-size: 10px; color: #17324d; }
            h1 { font-size: 18px; margin: 0 0 4px; color: #0d3b66; }
            p { margin: 0 0 12px; color: #6f8295; }
            table { width: 100%; border-collapse: collapse; }
            th { background: #0d3b66; color: #fff; text-align: left; padding: 6px; }
            td { padding: 6px; border-bottom: 1px solid #dce7f2; word-break: break-all; }
            tr:nth-child(even) td { background: #f4f8fc; }
        </style></head><body>'
            . '<h1>DocFlow - overzicht van bestanden</h1>'
            . '<p>Gemaakt op ' . $this->e(date('d-m-Y H:i')) . ' &middot; '
            . count($files) . ' bestanden &middot; totaal ' . $this->e($this->formatSize($totalBytes)) . '</p>'
            . '<table><thead><tr><th>#</th><th>Naam</th><th>Bestand</th><th>Type</th><th>Grootte</th><th>Datum en tijd</th></tr></thead>'
            . '<tbody>' . $rows . '</tbody></table></body></html>';
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / (1024 * 1024), 1, ',', '.') . ' MB';
        }

        return number_format($bytes / 1024, 1, ',', '.') . ' KB';
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
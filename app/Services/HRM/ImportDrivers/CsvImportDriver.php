<?php

namespace App\Services\HRM\ImportDrivers;

use RuntimeException;

class CsvImportDriver implements ResumeImportDriverInterface
{
    public function supports(string $path): bool
    {
        $ext = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return in_array($ext, ['csv', 'txt'], true);
    }

    public function read(string $path): iterable
    {
        $handle = fopen($path, 'rb');
        if (! $handle) {
            throw new RuntimeException('Unable to read import file.');
        }

        try {
            $headers = null;
            $line = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $line++;

                if ($line === 1) {
                    $headers = array_map(static fn ($header) => trim((string) $header), $row);
                    continue;
                }

                $payload = [];
                foreach ($headers ?? [] as $index => $header) {
                    if ($header === '') {
                        continue;
                    }
                    $payload[$header] = $row[$index] ?? null;
                }

                yield $line => $payload;
            }
        } finally {
            fclose($handle);
        }
    }
}


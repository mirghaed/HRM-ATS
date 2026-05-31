<?php

namespace App\Services\HRM\ImportDrivers;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class XlsxImportDriver implements ResumeImportDriverInterface
{
    public function supports(string $path): bool
    {
        return strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) === 'xlsx';
    }

    public function read(string $path): iterable
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new RuntimeException('Unable to open XLSX file.');
        }

        try {
            $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
            if ($sheetXml === false) {
                throw new RuntimeException('XLSX worksheet sheet1.xml not found.');
            }

            $sharedStrings = $this->loadSharedStrings($zip);
            $sheet = simplexml_load_string($sheetXml);
            if (! $sheet instanceof SimpleXMLElement) {
                throw new RuntimeException('Invalid XLSX worksheet format.');
            }

            $rows = $sheet->xpath('//xmlns:sheetData/xmlns:row') ?: [];
            $headers = null;
            $line = 0;

            foreach ($rows as $rowNode) {
                $line++;
                $row = $this->extractRowValues($rowNode, $sharedStrings);

                if ($line === 1) {
                    $headers = array_map(static fn ($value) => trim((string) $value), $row);
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
            $zip->close();
        }
    }

    /**
     * @return array<int, string>
     */
    private function loadSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml === false) {
            return [];
        }

        $doc = simplexml_load_string($xml);
        if (! $doc instanceof SimpleXMLElement) {
            return [];
        }

        $result = [];
        foreach (($doc->si ?? []) as $index => $si) {
            $parts = [];
            if (isset($si->t)) {
                $parts[] = (string) $si->t;
            } elseif (isset($si->r)) {
                foreach ($si->r as $run) {
                    $parts[] = (string) ($run->t ?? '');
                }
            }
            $result[(int) $index] = implode('', $parts);
        }

        return $result;
    }

    /**
     * @param array<int, string> $sharedStrings
     * @return array<int, string|null>
     */
    private function extractRowValues(SimpleXMLElement $rowNode, array $sharedStrings): array
    {
        $cells = [];

        foreach (($rowNode->c ?? []) as $cell) {
            $ref = (string) ($cell['r'] ?? '');
            $columnIndex = $this->columnToIndex($ref);
            $type = (string) ($cell['t'] ?? '');
            $raw = isset($cell->v) ? (string) $cell->v : null;

            if ($type === 's' && $raw !== null) {
                $value = $sharedStrings[(int) $raw] ?? null;
            } else {
                $value = $raw;
            }

            $cells[$columnIndex] = $value;
        }

        if ($cells === []) {
            return [];
        }

        $max = max(array_keys($cells));
        $result = [];
        for ($i = 0; $i <= $max; $i++) {
            $result[$i] = $cells[$i] ?? null;
        }

        return $result;
    }

    private function columnToIndex(string $cellRef): int
    {
        $letters = preg_replace('/[^A-Z]/', '', strtoupper($cellRef)) ?: 'A';
        $index = 0;
        $len = strlen($letters);

        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - 64);
        }

        return max(0, $index - 1);
    }
}


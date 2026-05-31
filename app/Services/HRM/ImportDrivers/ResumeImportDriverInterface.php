<?php

namespace App\Services\HRM\ImportDrivers;

interface ResumeImportDriverInterface
{
    public function supports(string $path): bool;

    /**
     * @return iterable<int, array<string, mixed>>
     */
    public function read(string $path): iterable;
}


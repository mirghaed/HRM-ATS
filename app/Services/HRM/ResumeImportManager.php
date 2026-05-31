<?php

namespace App\Services\HRM;

use App\Jobs\HRM\ImportApplicationsJob;
use App\Models\ApplicationSource;
use App\Models\ImportItem;
use App\Models\ImportRun;
use App\Services\HRM\ImportDrivers\CsvImportDriver;
use App\Services\HRM\ImportDrivers\ResumeImportDriverInterface;
use App\Services\HRM\ImportDrivers\XlsxImportDriver;
use RuntimeException;
use Throwable;

class ResumeImportManager
{
    /**
     * @var array<int, ResumeImportDriverInterface>
     */
    private array $drivers;

    public function __construct()
    {
        $this->drivers = [
            new CsvImportDriver(),
            new XlsxImportDriver(),
        ];
    }

    public function queueImport(ApplicationSource $source, string $path, ?int $userId = null): ImportRun
    {
        $run = ImportRun::create([
            'source_id' => $source->id,
            'status' => 'pending',
            'meta' => ['path' => $path],
            'created_by' => $userId,
        ]);

        ImportApplicationsJob::dispatch($run->id, $path);

        return $run;
    }

    public function retry(ImportRun $run): ImportRun
    {
        $path = (string) data_get($run->meta, 'path', '');
        if ($path === '') {
            throw new RuntimeException('Import path is not available for retry.');
        }

        $run->update([
            'status' => 'pending',
            'total_items' => 0,
            'created_items' => 0,
            'updated_items' => 0,
            'duplicate_items' => 0,
            'failed_items' => 0,
            'error_message' => null,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $run->items()->delete();
        ImportApplicationsJob::dispatch($run->id, $path);

        return $run->fresh();
    }

    public function processRun(ImportRun $run, string $path, ApplicationCreator $creator): void
    {
        $driver = $this->resolveDriver($path);

        foreach ($driver->read($path) as $rowNumber => $payload) {
            try {
                $application = $creator->create([
                    'full_name' => $payload['full_name'] ?? null,
                    'mobile' => $payload['mobile'] ?? null,
                    'email' => $payload['email'] ?? null,
                    'job_position_id' => $payload['job_position_id'] ?? null,
                    'department_id' => $payload['department_id'] ?? null,
                    'source_id' => $run->source_id ?: ApplicationSource::query()->where('key', 'excel_import')->value('id'),
                    'source_reference' => $payload['source_reference'] ?? null,
                    'expected_salary_min' => $payload['expected_salary_min'] ?? null,
                    'expected_salary_max' => $payload['expected_salary_max'] ?? null,
                    'raw_payload' => $payload,
                ]);

                ImportItem::create([
                    'import_run_id' => $run->id,
                    'application_id' => $application->id,
                    'row_number' => (int) $rowNumber,
                    'status' => 'created',
                    'raw_payload' => $payload,
                    'normalized_payload' => $payload,
                ]);

                $run->increment('created_items');
            } catch (Throwable $exception) {
                ImportItem::create([
                    'import_run_id' => $run->id,
                    'row_number' => (int) $rowNumber,
                    'status' => 'failed',
                    'raw_payload' => $payload,
                    'error_message' => $exception->getMessage(),
                ]);

                $run->increment('failed_items');
            }

            $run->increment('total_items');
        }
    }

    private function resolveDriver(string $path): ResumeImportDriverInterface
    {
        foreach ($this->drivers as $driver) {
            if ($driver->supports($path)) {
                return $driver;
            }
        }

        throw new RuntimeException('Unsupported import file format.');
    }
}

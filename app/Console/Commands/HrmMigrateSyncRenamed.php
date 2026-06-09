<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HrmMigrateSyncRenamed extends Command
{
    protected $signature = 'hrm:migrate-sync-renamed {--dry-run : Show actions without writing}';

    protected $description = 'Mark accidentally renamed duplicate migrations as run when tables already exist';

    /**
     * Renamed migration files that duplicate already-applied migrations on production.
     *
     * @var array<string, string>
     */
    private array $phantomMigrations = [
        '2026_05_30_044521_create_job_position_questions_table' => 'job_position_questions',
        '2026_05_30_044522_create_candidates_table' => 'candidates',
        '2026_05_30_044523_create_candidate_files_table' => 'candidate_files',
        '2026_05_30_044524_create_import_runs_table' => 'import_runs',
        '2026_05_30_044525_create_import_items_table' => 'import_items',
        '2026_05_30_044531_create_interview_reminders_table' => 'interview_reminders',
        '2026_05_30_044532_create_sms_logs_table' => 'sms_logs',
    ];

    public function handle(): int
    {
        if (! Schema::hasTable('migrations')) {
            $this->error('The migrations table does not exist.');

            return self::FAILURE;
        }

        $batch = (int) DB::table('migrations')->max('batch') + 1;
        $marked = 0;

        foreach ($this->phantomMigrations as $migration => $table) {
            $alreadyRecorded = DB::table('migrations')->where('migration', $migration)->exists();

            if ($alreadyRecorded) {
                $this->line("Skip: {$migration} (already recorded)");

                continue;
            }

            if (! Schema::hasTable($table)) {
                $this->warn("Skip: {$migration} (table {$table} does not exist yet)");

                continue;
            }

            if ($this->option('dry-run')) {
                $this->info("Would mark as ran: {$migration}");
                $marked++;

                continue;
            }

            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => $batch,
            ]);

            $this->info("Marked as ran: {$migration}");
            $marked++;
        }

        if ($marked === 0) {
            $this->comment('Nothing to sync.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->info("Synced {$marked} phantom migration(s). Now run: php artisan migrate --force");

        return self::SUCCESS;
    }
}

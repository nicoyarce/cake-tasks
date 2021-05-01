<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Backup the database';

    protected $process;

    public function __construct()
    {
        parent::__construct();

        $today = today()->format('Y-m-d');
        if (!is_dir(storage_path('backups'))) mkdir(storage_path('backups'));

        $this->process = new Process(sprintf(
            'mysqldump --compact --skip-comments -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path("backups/{$today}.sql")
        ));
    }

    public function handle()
    {
        try {
            $this->process->mustRun();
            $this->info('The backup has been proceed successfully.');
            Log::info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process has been failed. '.$exception);
            Log::error('The backup process has been failed.', $exception);
        }
    }
}
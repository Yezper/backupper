<?php

namespace Yezper\Backupper;

class Backup
{
    public function run(): int
    {
        $jobs = config('sources');

        log('Starting backup');

        $mainStartTime = microtime(true);

        foreach ($jobs as $job) {
            log("Starting backup job '{$job['job_name']}'");

            $this->performBackup($job);
            $this->cleanupBackups($job);
        }

        log("Cleaning up log");
        $this->cleanupLog();

        $totalDuration = microtime(true) - $mainStartTime;
        log("Finished all backup jobs in " . format_duration($totalDuration));

        return 0;
    }

    protected function performBackup(array $job): bool
    {
        $jobStartTime = microtime(true);
        log("Starting backup job '{$job['job_name']}'");

        // Configure backup
        $timestamp = date('YmdHis');
        $backupFileName = "{$job['destination_file_name']}-{$timestamp}.tar.gz";
        $backupDestination = "{$job['destination_folder']}/{$backupFileName}";

        // Perform backup
        $command = "tar -czf '$backupDestination' -C '{$job['source_folder']}' --one-file-system --exclude='./lost+found' --exclude='./proc' --exclude='./sys' --exclude='./dev/pts' --exclude='./dev' --exclude='./run' --exclude='./tmp' --exclude='./var/tmp' --exclude='./mnt' --exclude='./media' --exclude='./srv' .";
        exec($command);

        $jobDuration = microtime(true) - $jobStartTime;
        log("Finished backup job '{$job['job_name']}' in " . format_duration($jobDuration));

        return true;
    }

    protected function cleanupBackups(array $job): bool
    {
        // Perform cleanup, if necessary
        $daysToKeep = $job['days_to_keep'];
        $existingBackups = glob("{$job['destination_folder']}/{$job['destination_file_name']}-*.tar.gz");
        $cutoffTime = strtotime("-{$job['days_to_keep']} days");
        $deletedBackup = false;

        foreach ($existingBackups as $existingBackup) {
            if (preg_match('/(\d{14})\.tar\.gz$/', $existingBackup, $matches)) {
                $existingBackupDate = \DateTime::createFromFormat('YmdHis', $matches[1]);

                if ($existingBackupDate->getTimestamp() <= $cutoffTime) {
                    $fileName = basename($existingBackup);
                    log("Deleting backup $fileName as it is older than $daysToKeep days");
                    unlink($existingBackup);
                    $deletedBackup = true;
                }
            }
        }

        if (!$deletedBackup) {
            log("Skipping cleanup; no existing backups older than $daysToKeep days");
        }

        return true;
    }

    protected function cleanupLog(): bool
    {
        $logFile = config('app.logging.file_name');
        $linesToKeep = config('app.logging.lines_to_keep');

        // Remove lines from log file so that only the last $linesToKeep lines remain
        $logFileContents = file($logFile);
        $logFileContents = array_slice($logFileContents, -$linesToKeep);
        file_put_contents($logFile, $logFileContents);

        return true;
    }
}
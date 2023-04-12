<?php

namespace {

    if (!function_exists('config')) {
        function config(string $key, $default = null): mixed
        {
            $config = require __DIR__ . '/../../config/config.php';

            // $key is something like 'logging.log_file'
            $keys = explode('.', $key);

            // $config is the array from config.php
            // $keys is an array of keys to traverse the array
            // $default is the default value to return if the key is not found
            return array_reduce($keys, static function ($config, $key) use ($default) {
                return $config[$key] ?? $default;
            }, $config);
        }
    }

    if (!function_exists('format_duration')) {
        function format_duration($duration): string
        {
            $hours = floor(round($duration) / 3600);
            $minutes = floor((round($duration) / 60) % 60);
            $seconds = round($duration) % 60;

            $timeComponents = [];

            if ($hours > 0) {
                $timeComponents[] = "{$hours} hour" . ($hours === 1 ? '' : 's');
            }

            if ($minutes > 0) {
                $timeComponents[] = "{$minutes} minute" . ($minutes === 1 ? '' : 's');
            }

            if ($seconds > 0) {
                $timeComponents[] = "{$seconds} second" . ($seconds === 1 ? '' : 's');
            }

            return implode(', ', $timeComponents) ?: 'less than 1 second';
        }
    }

    if (!function_exists('log_message')) {
        function log_message(string $message, bool $outputToScreen = true): bool
        {
            $logFile = config('app.logging.file_name');
            $timestamp = date('Y-m-d H:i:s');
            $formattedMessage = "$timestamp $message\n";

            // Write the message to the log file
            file_put_contents($logFile, $formattedMessage, FILE_APPEND);

            // Output the message to the screen
            if ($outputToScreen) {
                echo $formattedMessage;
            }

            return true;
        }
    }

    if (!function_exists('dd')) {
        function dd(...$value): void
        {
            var_dump($value);
            die();
        }
    }
}



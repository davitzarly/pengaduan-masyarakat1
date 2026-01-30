<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use PDO;
use Throwable;

class SetupDatabase extends Command
{
    protected $signature = 'app:setup';
    protected $description = 'Create database if missing, run migrations and seeders.';

    public function handle(): int
    {
        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        if (!is_array($dbConfig)) {
            $this->error('Database configuration not found.');
            return self::FAILURE;
        }

        $database = $dbConfig['database'] ?? null;
        if (!$database) {
            $this->error('DB_DATABASE is not set.');
            return self::FAILURE;
        }

        if ($connection === 'mysql') {
            if (!$this->createMysqlDatabase($dbConfig)) {
                return self::FAILURE;
            }
        } else {
            $this->warn("Skipping database create for connection: {$connection}");
        }

        $this->info('Running migrations and seeders...');
        Artisan::call('migrate', ['--seed' => true, '--force' => true]);
        $this->line(Artisan::output());

        $this->info('Done.');
        return self::SUCCESS;
    }

    private function createMysqlDatabase(array $dbConfig): bool
    {
        $host = $dbConfig['host'] ?? '127.0.0.1';
        $port = $dbConfig['port'] ?? 3306;
        $username = $dbConfig['username'] ?? null;
        $password = $dbConfig['password'] ?? null;
        $database = $dbConfig['database'] ?? null;

        if (!$database) {
            $this->error('DB_DATABASE is not set.');
            return false;
        }

        $dsn = "mysql:host={$host};port={$port}";

        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $dbName = str_replace('`', '``', $database);
            $pdo->exec(
                "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            );
            $this->info("Database ready: {$database}");
            return true;
        } catch (Throwable $e) {
            $this->error("Failed to create database: {$e->getMessage()}");
            return false;
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnsureReverbRunning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reverb:ensure-running
                            {--dry-run : Check status without starting the Reverb server}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure the Laravel Reverb WebSocket server is running (for cPanel cron/scheduler).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $host = (string) config('reverb.servers.reverb.host', '0.0.0.0');
        $port = (int) config('reverb.servers.reverb.port', 8080);
        $checkHost = $host === '0.0.0.0' ? '127.0.0.1' : $host;

        if ($this->isPortListening($checkHost, $port)) {
            $this->info("Reverb is already running on {$checkHost}:{$port}.");

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn("Reverb is not running on {$checkHost}:{$port} (dry-run).");

            return self::SUCCESS;
        }

        $phpBinary = escapeshellarg(PHP_BINARY);
        $artisan = escapeshellarg(base_path('artisan'));
        $logFile = escapeshellarg(storage_path('logs/reverb.log'));
        $pidFile = escapeshellarg(storage_path('app/reverb.pid'));

        $command = "nohup {$phpBinary} {$artisan} reverb:start >> {$logFile} 2>&1 & echo \$! > {$pidFile}";

        exec($command);

        Log::info('Started Laravel Reverb server via reverb:ensure-running.', [
            'host' => $checkHost,
            'port' => $port,
        ]);

        $this->info("Started Reverb server in background on {$checkHost}:{$port}.");

        return self::SUCCESS;
    }

    /**
     * Check whether a TCP socket is accepting connections on the given host and port.
     */
    protected function isPortListening(string $host, int $port): bool
    {
        $connection = @stream_socket_client(
            "tcp://{$host}:{$port}",
            $errorCode,
            $errorMessage,
            1.0
        );

        if (is_resource($connection)) {
            fclose($connection);

            return true;
        }

        return false;
    }
}

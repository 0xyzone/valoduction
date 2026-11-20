<?php

namespace App\Support;

class ReverbStatus
{
    /**
     * Get the local host and port that the Reverb server listens on.
     *
     * @return array{host: string, port: int}
     */
    public static function localEndpoint(): array
    {
        $host = (string) config('reverb.servers.reverb.host', '0.0.0.0');
        $port = (int) config('reverb.servers.reverb.port', 8080);

        return [
            'host' => $host === '0.0.0.0' ? '127.0.0.1' : $host,
            'port' => $port,
        ];
    }

    /**
     * Check whether the Laravel Reverb server is currently accepting TCP connections.
     */
    public static function isRunning(float $timeoutSeconds = 0.35): bool
    {
        ['host' => $host, 'port' => $port] = self::localEndpoint();

        $connection = @stream_socket_client(
            "tcp://{$host}:{$port}",
            $errorCode,
            $errorMessage,
            $timeoutSeconds
        );

        if (is_resource($connection)) {
            fclose($connection);

            return true;
        }

        return false;
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;

class Changelog
{
    /**
     * Get the latest release version from changelog.md.
     */
    public static function latestVersion(): string
    {
        $releases = self::releases();

        return $releases[0]['version'] ?? 'v1.0.0';
    }

    /**
     * Parse releases from changelog.md.
     *
     * @return list<array{
     *     version: string,
     *     date: ?string,
     *     is_latest: bool,
     *     sections: list<array{type: string, items: list<string>}>
     * }>
     */
    public static function releases(): array
    {
        $path = base_path('changelog.md');

        if (! File::exists($path)) {
            return [];
        }

        $lines = preg_split('/\R/', File::get($path)) ?: [];
        $releases = [];
        $currentReleaseIndex = null;
        $currentSectionIndex = null;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (preg_match('/^##\s+\[?(v\d+\.\d+\.\d+(?:-[\w.]+)?)\]?(?:\s*-\s*(.+))?$/i', $trimmed, $matches) === 1) {
                $releases[] = [
                    'version' => $matches[1],
                    'date' => isset($matches[2]) ? trim($matches[2]) : null,
                    'is_latest' => count($releases) === 0,
                    'sections' => [],
                ];
                $currentReleaseIndex = array_key_last($releases);
                $currentSectionIndex = null;

                continue;
            }

            if ($currentReleaseIndex === null) {
                continue;
            }

            if (preg_match('/^###\s+(.+)$/', $trimmed, $matches) === 1) {
                $releases[$currentReleaseIndex]['sections'][] = [
                    'type' => trim($matches[1]),
                    'items' => [],
                ];
                $currentSectionIndex = array_key_last($releases[$currentReleaseIndex]['sections']);

                continue;
            }

            if (preg_match('/^[-*]\s+(.+)$/', $trimmed, $matches) === 1) {
                if ($currentSectionIndex === null) {
                    $releases[$currentReleaseIndex]['sections'][] = [
                        'type' => 'Changes',
                        'items' => [],
                    ];
                    $currentSectionIndex = array_key_last($releases[$currentReleaseIndex]['sections']);
                }

                $releases[$currentReleaseIndex]['sections'][$currentSectionIndex]['items'][] = trim($matches[1]);
            }
        }

        return $releases;
    }

    /**
     * Format inline markdown (code spans and bold text) safely to HTML.
     */
    public static function formatInline(string $text): HtmlString
    {
        $escaped = e($text);

        $formatted = preg_replace(
            '/`([^`]+)`/',
            '<code class="cl-code">$1</code>',
            $escaped
        ) ?? $escaped;

        $formatted = preg_replace(
            '/\*\*([^*]+)\*\*/',
            '<strong>$1</strong>',
            $formatted
        ) ?? $formatted;

        return new HtmlString($formatted);
    }
}

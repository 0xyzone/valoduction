<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Support\MatchPayload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OverlayController extends Controller
{
    public function veto(Request $request, GameMatch $gameMatch): View
    {
        return view('overlays.veto', [
            'matchData' => MatchPayload::forMatch($gameMatch),
            'reverb' => $this->reverbConfig(),
            'previewBg' => $request->query('bg', 'transparent'),
            'accent' => $request->query('accent', '#FF4655'),
        ]);
    }

    public function postMatch(Request $request, GameMatch $gameMatch): View
    {
        return view('overlays.post-match', [
            'matchData' => MatchPayload::forMatch($gameMatch),
            'reverb' => $this->reverbConfig(),
            'previewBg' => $request->query('bg', 'transparent'),
            'accent' => $request->query('accent', '#FF4655'),
        ]);
    }

    /**
     * @return array{key: ?string, host: ?string, port: int, scheme: string}
     */
    private function reverbConfig(): array
    {
        $options = config('broadcasting.connections.reverb.options', []);

        return [
            'key' => config('broadcasting.connections.reverb.key'),
            'host' => $options['host'] ?? 'localhost',
            'port' => (int) ($options['port'] ?? 8080),
            'scheme' => (string) ($options['scheme'] ?? 'http'),
        ];
    }
}

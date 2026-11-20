<?php

namespace App\Http\Controllers;

use App\Support\Changelog;
use Illuminate\Contracts\View\View;

class ChangelogController extends Controller
{
    /**
     * Display the public changelog timeline page.
     */
    public function __invoke(): View
    {
        return view('changelog', [
            'releases' => Changelog::releases(),
            'latestVersion' => Changelog::latestVersion(),
            'appName' => config('app.name', 'Valoduction'),
        ]);
    }
}

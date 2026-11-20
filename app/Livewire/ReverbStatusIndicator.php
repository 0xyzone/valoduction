<?php

namespace App\Livewire;

use App\Support\ReverbStatus;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ReverbStatusIndicator extends Component
{
    public bool $isRunning = false;

    public string $endpoint = '';

    public function mount(): void
    {
        $this->refreshStatus();
    }

    public function refreshStatus(): void
    {
        ['host' => $host, 'port' => $port] = ReverbStatus::localEndpoint();

        $this->endpoint = "{$host}:{$port}";
        $this->isRunning = ReverbStatus::isRunning();
    }

    public function render(): View
    {
        return view('livewire.reverb-status-indicator');
    }
}

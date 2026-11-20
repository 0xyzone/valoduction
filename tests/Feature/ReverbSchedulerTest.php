<?php

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class ReverbSchedulerTest extends TestCase
{
    public function test_reverb_ensure_running_is_scheduled_every_minute(): void
    {
        $schedule = app(Schedule::class);

        $events = collect($schedule->events())->filter(
            fn ($event): bool => str_contains($event->command ?? '', 'reverb:ensure-running')
        );

        $this->assertCount(1, $events);
        $this->assertSame('* * * * *', $events->first()->expression);
    }

    public function test_reverb_ensure_running_dry_run_succeeds(): void
    {
        $this->artisan('reverb:ensure-running', ['--dry-run' => true])
            ->assertSuccessful();
    }
}

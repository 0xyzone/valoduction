<?php

namespace Tests\Feature;

use App\Events\MatchUpdated;
use App\Events\VetoUpdated;
use App\Filament\Imports\PlayerImporter;
use App\Filament\Imports\TeamImporter;
use App\Filament\Resources\GameMatches\Pages\ManageGameMatches;
use App\Filament\Resources\Players\Pages\ManagePlayers;
use App\Filament\Resources\Teams\Pages\ManageTeams;
use App\Filament\Resources\Tournaments\Pages\ManageTournaments;
use App\Models\GameMatch;
use App\Models\MatchMap;
use App\Models\MatchVeto;
use App\Models\Player;
use App\Models\PlayerMatchStat;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Support\MatchPayload;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class TournamentOpsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel('admin');
    }

    public function test_teams_can_be_reused_across_multiple_tournaments(): void
    {
        $team = Team::factory()->create(['short_name' => 'SEN']);
        $tournamentA = Tournament::factory()->create();
        $tournamentB = Tournament::factory()->create();

        $tournamentA->teams()->attach($team, ['seed' => 1, 'group_name' => 'Group A']);
        $tournamentB->teams()->attach($team, ['seed' => 2, 'group_name' => 'Group B']);

        $this->assertCount(2, $team->tournaments);
    }

    public function test_filament_tournament_ops_pages_load_and_create_records(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        Livewire::test(ManageTournaments::class)
            ->assertOk()
            ->callAction(CreateAction::class, data: [
                'name' => 'Valoduction Masters',
                'slug' => 'valoduction-masters',
                'status' => 'live',
                'format' => 'single_elimination',
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('tournaments', ['slug' => 'valoduction-masters']);

        $swatchesHtml = view('filament.components.color-swatches', ['statePath' => 'data.primary_color'])->render();
        $this->assertStringContainsString('Select color #FF4655', $swatchesHtml);

        Livewire::test(ManageTeams::class)
            ->assertOk()
            ->callAction(CreateAction::class, data: [
                'name' => 'Team Alpha',
                'short_name' => 'ALP',
                'primary_color' => '#FF4655',
                'region' => 'APAC',
            ])
            ->assertHasNoActionErrors();

        $team = Team::query()->where('short_name', 'ALP')->firstOrFail();

        Livewire::test(ManagePlayers::class)
            ->assertOk()
            ->callAction(CreateAction::class, data: [
                'team_id' => $team->id,
                'name' => 'Player One',
                'riot_id' => 'PlayerOne',
                'riot_tag' => 'VAL',
                'role' => 'Duelist',
                'is_active' => true,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('players', ['riot_id' => 'PlayerOne']);

        Livewire::test(ManageGameMatches::class)->assertOk();
    }

    public function test_veto_and_match_updates_broadcast_events_and_render_obs_overlays(): void
    {
        Event::fake([VetoUpdated::class, MatchUpdated::class]);

        $tournament = Tournament::factory()->create(['name' => 'VCT Test Cup']);
        $teamA = Team::factory()->create(['name' => 'Sentinels', 'short_name' => 'SEN']);
        $teamB = Team::factory()->create(['name' => 'Paper Rex', 'short_name' => 'PRX']);
        $player = Player::factory()->create([
            'team_id' => $teamA->id,
            'name' => 'TenZ',
            'riot_id' => 'TenZ',
            'riot_tag' => '001',
        ]);

        $match = GameMatch::factory()->create([
            'tournament_id' => $tournament->id,
            'team_a_id' => $teamA->id,
            'team_b_id' => $teamB->id,
            'team_a_score' => 2,
            'team_b_score' => 1,
            'status' => 'completed',
        ]);

        MatchVeto::factory()->create([
            'game_match_id' => $match->id,
            'step_order' => 1,
            'team_id' => $teamA->id,
            'action' => 'pick',
            'map_name' => 'Ascent',
            'side' => 'attack',
        ]);

        $map = MatchMap::factory()->create([
            'game_match_id' => $match->id,
            'map_number' => 1,
            'map_name' => 'Ascent',
            'team_a_score' => 13,
            'team_b_score' => 9,
            'winner_team_id' => $teamA->id,
            'status' => 'completed',
        ]);

        PlayerMatchStat::factory()->create([
            'match_map_id' => $map->id,
            'player_id' => $player->id,
            'team_id' => $teamA->id,
            'agent' => 'Jett',
            'acs' => 285,
            'kills' => 24,
            'deaths' => 14,
            'assists' => 6,
            'adr' => 178.5,
            'hs_percentage' => 31.2,
            'first_kills' => 5,
            'first_deaths' => 2,
            'credits_spent' => 52000,
        ]);

        Event::assertDispatched(MatchUpdated::class);
        Event::assertDispatched(VetoUpdated::class);

        $payload = MatchPayload::forMatch($match);
        $this->assertSame('Sentinels', $payload['team_a']['name']);
        $this->assertCount(1, $payload['vetos']);
        $this->assertCount(1, $payload['maps']);
        $this->assertCount(1, $payload['maps'][0]['stats']);

        $this->get(route('overlays.veto', $match))
            ->assertOk()
            ->assertSee('Sentinels')
            ->assertSee('Paper Rex')
            ->assertSee('Ascent');

        $this->get(route('overlays.post-match', $match))
            ->assertOk()
            ->assertSee('Sentinels')
            ->assertSee('TenZ')
            ->assertSee('Jett');
    }

    public function test_team_and_player_importers_provide_sample_examples(): void
    {
        $teamColumns = TeamImporter::getColumns();
        $playerColumns = PlayerImporter::getColumns();

        $this->assertNotEmpty($teamColumns[0]->getExamples());
        $this->assertNotEmpty($playerColumns[0]->getExamples());
    }
}

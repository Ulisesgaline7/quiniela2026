<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecialEvent extends Model
{
    protected $fillable = ['type','label','points','team_id','match_id','player_name','resolved'];
    protected $casts = ['resolved' => 'boolean'];

    public function team(): BelongsTo  { return $this->belongsTo(Team::class); }
    public function match(): BelongsTo { return $this->belongsTo(WorldMatch::class, 'match_id'); }
    public function picks(): HasMany   { return $this->hasMany(SpecialEventPick::class, 'event_type', 'type'); }

    // All defined special events with their points
    public static function allDefinitions(): array
    {
        return [
            // 🎯 EXTRAS DIVERTIDOS
            ['type'=>'first_eliminated',    'label'=>'Primer equipo eliminado del Mundial',          'points'=>10, 'pick_type'=>'team'],
            ['type'=>'first_qualified',     'label'=>'Primera selección en clasificar a siguiente ronda','points'=>8,  'pick_type'=>'team'],
            ['type'=>'first_00',            'label'=>'Primer empate 0-0',                            'points'=>6,  'pick_type'=>'match'],
            ['type'=>'first_hattrick',      'label'=>'Primer hat-trick del torneo',                  'points'=>8,  'pick_type'=>'player'],
            ['type'=>'first_own_goal',      'label'=>'Primer autogol',                               'points'=>5,  'pick_type'=>'team'],
            ['type'=>'first_injury',        'label'=>'Primer jugador lesionado del torneo',          'points'=>5,  'pick_type'=>'player'],
            ['type'=>'top_scorer_group',    'label'=>'Equipo con más goles en fase de grupos',       'points'=>8,  'pick_type'=>'team'],
            ['type'=>'best_defense',        'label'=>'Equipo menos goleado del torneo',              'points'=>8,  'pick_type'=>'team'],
            ['type'=>'most_goals_match',    'label'=>'Partido con más goles del Mundial',            'points'=>10, 'pick_type'=>'match'],
            ['type'=>'disappointment',      'label'=>'Selección decepción del torneo',               'points'=>10, 'pick_type'=>'team'],
            ['type'=>'revelation_team',     'label'=>'Selección revelación',                         'points'=>10, 'pick_type'=>'team'],
            ['type'=>'revelation_player',   'label'=>'Jugador revelación',                           'points'=>8,  'pick_type'=>'player'],
            ['type'=>'most_red_cards',      'label'=>'País con más tarjetas rojas',                  'points'=>6,  'pick_type'=>'team'],
            ['type'=>'first_coach_fired',   'label'=>'Primer DT despedido o renunciante',            'points'=>12, 'pick_type'=>'team'],
            // 😂 RETOS RANDOM
            ['type'=>'pitch_invader',       'label'=>'Hincha invade cancha',                         'points'=>5,  'pick_type'=>'boolean'],
            ['type'=>'long_range_goal',     'label'=>'Gol desde fuera del área',                     'points'=>2,  'pick_type'=>'boolean'],
            ['type'=>'goalkeeper_goal',     'label'=>'Arquero anota gol',                            'points'=>15, 'pick_type'=>'boolean'],
            ['type'=>'match_suspended',     'label'=>'Partido suspendido/retrasado',                 'points'=>8,  'pick_type'=>'boolean'],
            ['type'=>'goal_before_min5',    'label'=>'Gol antes del minuto 5',                       'points'=>3,  'pick_type'=>'boolean'],
            ['type'=>'comeback_2goals',     'label'=>'Remontada tras ir perdiendo por 2 goles',      'points'=>6,  'pick_type'=>'boolean'],
            ['type'=>'last_penalty_wins',   'label'=>'Partido definido por último penal',            'points'=>5,  'pick_type'=>'boolean'],
        ];
    }
}

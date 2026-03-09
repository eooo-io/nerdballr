<?php

namespace Database\Seeders;

use App\Models\GlossaryTerm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GlossaryTermSeeder extends Seeder
{
    public function run(): void
    {
        $terms = $this->terms();

        foreach ($terms as $term) {
            GlossaryTerm::updateOrCreate(
                ['slug' => Str::slug($term['term'])],
                [
                    'term' => $term['term'],
                    'slug' => Str::slug($term['term']),
                    'definition' => $term['definition'],
                    'category' => $term['category'],
                    'related_terms' => $term['related_terms'] ?? [],
                    'related_concepts' => $term['related_concepts'] ?? [],
                ],
            );
        }
    }

    /** @return array<int, array{term: string, definition: string, category: string, related_terms?: string[], related_concepts?: string[]}> */
    private function terms(): array
    {
        return [
            // ─── Positions ────────────────────────────────────────
            [
                'term' => 'Quarterback',
                'definition' => 'The offensive player who receives the snap and directs the offense. Responsible for reading the defense, calling audibles, handing off to running backs, or throwing passes to receivers. The most important position in football.',
                'category' => 'offense',
                'related_terms' => ['snap', 'audible', 'pocket', 'play-action'],
                'related_concepts' => ['shotgun-spread', 'i-formation', 'pistol'],
            ],
            [
                'term' => 'Running Back',
                'definition' => 'An offensive player who lines up behind or beside the quarterback and primarily carries the ball on rushing plays. Can also catch passes out of the backfield and block for the quarterback.',
                'category' => 'offense',
                'related_terms' => ['handoff', 'rush', 'backfield'],
                'related_concepts' => ['i-formation'],
            ],
            [
                'term' => 'Wide Receiver',
                'definition' => 'An offensive player who lines up near the sideline and runs routes to catch passes. The "X" receiver lines up on the line of scrimmage, the "Z" receiver is off the line, and the "slot" receiver lines up between the line and the outside receiver.',
                'category' => 'offense',
                'related_terms' => ['route', 'slot', 'split-end', 'flanker'],
                'related_concepts' => ['mesh', 'four-verticals', 'smash', 'slant-flat'],
            ],
            [
                'term' => 'Tight End',
                'definition' => 'A versatile offensive player who lines up on the line of scrimmage next to the offensive tackle. Combines blocking duties with pass-catching ability. A "hybrid" between a lineman and a receiver.',
                'category' => 'offense',
                'related_terms' => ['inline', 'flexed'],
                'related_concepts' => ['i-formation', '11-personnel'],
            ],
            [
                'term' => 'Offensive Line',
                'definition' => 'The five offensive players (center, two guards, two tackles) who line up on the line of scrimmage. Their job is to block defenders on running plays and protect the quarterback on passing plays. They cannot catch forward passes.',
                'category' => 'offense',
                'related_terms' => ['center', 'guard', 'tackle', 'pocket', 'snap'],
            ],
            [
                'term' => 'Defensive Line',
                'definition' => 'The front-line defenders who line up across from the offensive line. Defensive tackles play inside and defensive ends play outside. Their job is to stop the run and pressure the quarterback.',
                'category' => 'defense',
                'related_terms' => ['defensive-tackle', 'defensive-end', 'pass-rush'],
                'related_concepts' => ['4-3-base', '3-4-base'],
            ],
            [
                'term' => 'Linebacker',
                'definition' => 'Defenders who line up behind the defensive line. In a 4-3, there are three: Will (weak side), Mike (middle), and Sam (strong side). They defend the run, cover receivers, and sometimes rush the passer.',
                'category' => 'defense',
                'related_terms' => ['will', 'mike', 'sam', 'blitz'],
                'related_concepts' => ['4-3-base', '3-4-base', 'zone-blitz'],
            ],
            [
                'term' => 'Cornerback',
                'definition' => 'A defensive back who primarily covers wide receivers. Cornerbacks play on the outside edges of the defense and are responsible for man-to-man or zone coverage against the pass.',
                'category' => 'defense',
                'related_terms' => ['man-coverage', 'press', 'off-coverage', 'jam'],
                'related_concepts' => ['cover-0', 'cover-1', 'cover-2', 'cover-3'],
            ],
            [
                'term' => 'Safety',
                'definition' => 'A defensive back who lines up deep behind the linebackers. The free safety (FS) reads the quarterback and covers deep zones. The strong safety (SS) plays closer to the line and is more involved in run support.',
                'category' => 'defense',
                'related_terms' => ['free-safety', 'strong-safety', 'deep-third', 'robber'],
                'related_concepts' => ['cover-2', 'cover-3', 'cover-4-quarters'],
            ],

            // ─── Formations ──────────────────────────────────────
            [
                'term' => 'Shotgun',
                'definition' => 'An offensive formation where the quarterback stands 5-7 yards behind the center to receive the snap. Provides better vision of the defense and more time to throw, but sacrifices the run-fake of being under center.',
                'category' => 'offense',
                'related_terms' => ['under-center', 'snap', 'pistol'],
                'related_concepts' => ['shotgun-spread'],
            ],
            [
                'term' => 'Pistol',
                'definition' => 'A hybrid formation where the quarterback stands about 4 yards behind the center — shorter than shotgun but not under center. The running back lines up directly behind the QB, preserving downhill run options with passing advantages.',
                'category' => 'offense',
                'related_terms' => ['shotgun', 'under-center'],
                'related_concepts' => ['pistol'],
            ],
            [
                'term' => 'I-Formation',
                'definition' => 'A traditional offensive formation with the quarterback under center, a fullback directly behind the QB, and a tailback behind the fullback, forming an "I" shape. A power running formation.',
                'category' => 'offense',
                'related_terms' => ['fullback', 'tailback', 'under-center', 'power-run'],
                'related_concepts' => ['i-formation'],
            ],
            [
                'term' => 'Nickel',
                'definition' => 'A defensive formation that replaces one linebacker with a fifth defensive back (the nickel corner). Used against offensive formations with three or more receivers. Named because five DBs = a nickel (five cents).',
                'category' => 'defense',
                'related_terms' => ['dime', 'sub-package', 'base-defense'],
                'related_concepts' => ['nickel', 'dime'],
            ],
            [
                'term' => 'Dime',
                'definition' => 'A defensive formation with six defensive backs on the field, replacing two linebackers. Used in obvious passing situations. Named as a step up from nickel (a dime = ten cents).',
                'category' => 'defense',
                'related_terms' => ['nickel', 'sub-package', 'prevent'],
                'related_concepts' => ['dime'],
            ],

            // ─── Coverages ───────────────────────────────────────
            [
                'term' => 'Man Coverage',
                'definition' => 'A coverage scheme where each defensive back is assigned to cover a specific offensive player one-on-one. Requires athletic, disciplined defenders who can stay with their man throughout the route.',
                'category' => 'scheme',
                'related_terms' => ['zone-coverage', 'press', 'off-coverage', 'trail'],
                'related_concepts' => ['cover-0', 'cover-1'],
            ],
            [
                'term' => 'Zone Coverage',
                'definition' => 'A coverage scheme where defenders are responsible for areas of the field rather than specific players. Defenders read the quarterback\'s eyes and react to receivers entering their zone.',
                'category' => 'scheme',
                'related_terms' => ['man-coverage', 'deep-third', 'flat', 'hook-zone'],
                'related_concepts' => ['cover-2', 'cover-3', 'cover-4-quarters'],
            ],
            [
                'term' => 'Cover 0',
                'definition' => 'Pure man-to-man coverage with no deep safety help. Every defensive back covers a specific receiver, and all other defenders rush the quarterback. Maximum pressure, maximum risk.',
                'category' => 'scheme',
                'related_terms' => ['man-coverage', 'blitz', 'all-out-rush'],
                'related_concepts' => ['cover-0'],
            ],
            [
                'term' => 'Cover 1',
                'definition' => 'Man-to-man coverage with a single deep safety providing help over the top. Allows aggressive man coverage with a safety net against deep passes.',
                'category' => 'scheme',
                'related_terms' => ['man-coverage', 'free-safety', 'robber'],
                'related_concepts' => ['cover-1'],
            ],
            [
                'term' => 'Cover 2',
                'definition' => 'A zone coverage with two deep safeties splitting the deep field in half. Five defenders cover underneath zones. Strong against deep passes but can be vulnerable in the deep middle and along the sideline.',
                'category' => 'scheme',
                'related_terms' => ['zone-coverage', 'tampa-2', 'deep-half'],
                'related_concepts' => ['cover-2'],
            ],
            [
                'term' => 'Cover 3',
                'definition' => 'A zone coverage with three deep defenders (two corners and a safety) each covering a deep third of the field. Four underneath defenders cover short zones. The most common coverage in football.',
                'category' => 'scheme',
                'related_terms' => ['zone-coverage', 'deep-third', 'flat-defender'],
                'related_concepts' => ['cover-3'],
            ],
            [
                'term' => 'Cover 4',
                'definition' => 'Also called "Quarters" coverage. Four deep defenders each cover a quarter of the deep field. Provides maximum deep protection but can be vulnerable to short and intermediate routes.',
                'category' => 'scheme',
                'related_terms' => ['zone-coverage', 'quarters', 'pattern-match'],
                'related_concepts' => ['cover-4-quarters'],
            ],

            // ─── Routes ──────────────────────────────────────────
            [
                'term' => 'Route',
                'definition' => 'A predetermined path a receiver runs after the snap. Routes are designed to create separation from defenders and target specific areas of the field. The "route tree" describes all standard routes from a single position.',
                'category' => 'offense',
                'related_terms' => ['route-tree', 'break', 'stem'],
                'related_concepts' => ['mesh', 'four-verticals', 'smash', 'slant-flat'],
            ],
            [
                'term' => 'Slant',
                'definition' => 'A quick route where the receiver takes one or two steps forward, then cuts sharply at a 45-degree angle toward the middle of the field. Effective against off-coverage and zone defenses.',
                'category' => 'offense',
                'related_terms' => ['route', 'quick-game', 'hot-route'],
                'related_concepts' => ['slant-flat'],
            ],
            [
                'term' => 'Go Route',
                'definition' => 'Also called a "fly" or "streak" route. The receiver runs straight down the field at full speed, trying to beat the defender deep. The simplest route but requires speed and good deep ball throwing.',
                'category' => 'offense',
                'related_terms' => ['vertical', 'deep-ball', 'nine-route'],
                'related_concepts' => ['four-verticals'],
            ],
            [
                'term' => 'Out Route',
                'definition' => 'A route where the receiver runs upfield, then cuts sharply toward the sideline. The depth of the cut determines the type: a "speed out" is short (5 yards), while a "deep out" is at 12-15 yards.',
                'category' => 'offense',
                'related_terms' => ['route', 'comeback', 'curl'],
            ],
            [
                'term' => 'Post Route',
                'definition' => 'A deep route where the receiver runs upfield 12-15 yards, then cuts at a 45-degree angle toward the goalposts (the "post"). Attacks the deep middle of the field, especially effective against Cover 2.',
                'category' => 'offense',
                'related_terms' => ['route', 'corner-route', 'deep-ball'],
            ],
            [
                'term' => 'Mesh',
                'definition' => 'A route concept where two receivers cross each other\'s paths at a shallow depth (about 5-6 yards), creating natural picks and confusion for man coverage defenders. One of the most effective concepts against man coverage.',
                'category' => 'scheme',
                'related_terms' => ['crossing-route', 'pick', 'rub'],
                'related_concepts' => ['mesh'],
            ],

            // ─── Blitz & Pressure ────────────────────────────────
            [
                'term' => 'Blitz',
                'definition' => 'A defensive play where more defenders rush the quarterback than the offense can block. Creates pressure but leaves fewer defenders in coverage, creating a risk/reward tradeoff.',
                'category' => 'defense',
                'related_terms' => ['pass-rush', 'hot-route', 'sight-adjust'],
                'related_concepts' => ['zone-blitz', 'a-gap-blitz'],
            ],
            [
                'term' => 'Zone Blitz',
                'definition' => 'A blitz scheme where one or more rushers drop into zone coverage while linebackers or defensive backs blitz. Disguises where the pressure is coming from and keeps zone defenders in the passing lanes.',
                'category' => 'scheme',
                'related_terms' => ['blitz', 'zone-coverage', 'fire-zone'],
                'related_concepts' => ['zone-blitz'],
            ],
            [
                'term' => 'A-Gap',
                'definition' => 'The gap between the center and either guard on the offensive line. An "A-gap blitz" sends a defender through this interior gap, creating pressure up the middle — the most dangerous kind for a quarterback.',
                'category' => 'general',
                'related_terms' => ['b-gap', 'gap', 'blitz'],
                'related_concepts' => ['a-gap-blitz'],
            ],
            [
                'term' => 'Pass Rush',
                'definition' => 'The defensive effort to tackle the quarterback before he can throw the ball. Can come from defensive linemen (as part of their normal job) or from blitzing linebackers and defensive backs.',
                'category' => 'defense',
                'related_terms' => ['sack', 'pressure', 'hurry', 'blitz'],
            ],

            // ─── Down & Distance ─────────────────────────────────
            [
                'term' => 'Down',
                'definition' => 'One of four attempts (plays) the offense has to advance the ball 10 yards. First down resets the count. If the offense fails to gain 10 yards in four downs, the other team gets the ball.',
                'category' => 'general',
                'related_terms' => ['first-down', 'fourth-down', 'conversion'],
            ],
            [
                'term' => 'First Down',
                'definition' => 'The first of four attempts to advance 10 yards. Also refers to successfully gaining enough yards to earn a new set of downs. "Getting a first down" means resetting the count back to 1st-and-10.',
                'category' => 'general',
                'related_terms' => ['down', 'chain-gang', 'line-to-gain'],
            ],
            [
                'term' => 'Line of Scrimmage',
                'definition' => 'The imaginary line across the field where the ball is placed before each play. The offense and defense line up on opposite sides. No player (except the center) may cross it before the snap.',
                'category' => 'general',
                'related_terms' => ['snap', 'neutral-zone', 'offsides'],
            ],
            [
                'term' => 'Snap',
                'definition' => 'The action that starts every play. The center passes the ball backward between his legs to the quarterback (or punter/holder). Can be done from under center or as a shotgun snap.',
                'category' => 'general',
                'related_terms' => ['center', 'line-of-scrimmage', 'shotgun'],
            ],
            [
                'term' => 'Red Zone',
                'definition' => 'The area from the 20-yard line to the end zone. Offenses that enter the red zone are expected to score. "Red zone efficiency" is a key stat — the percentage of red zone trips that result in touchdowns.',
                'category' => 'general',
                'related_terms' => ['end-zone', 'touchdown', 'field-goal'],
            ],

            // ─── Scoring ─────────────────────────────────────────
            [
                'term' => 'Touchdown',
                'definition' => 'Worth 6 points. Scored when the ball crosses the plane of the opponent\'s goal line while in possession of an offensive player, either by carrying it in or catching it in the end zone.',
                'category' => 'general',
                'related_terms' => ['end-zone', 'extra-point', 'two-point-conversion'],
            ],
            [
                'term' => 'Field Goal',
                'definition' => 'Worth 3 points. Scored by kicking the ball through the opponent\'s goalposts (uprights). Typically attempted on 4th down when the offense is close enough but unlikely to score a touchdown.',
                'category' => 'general',
                'related_terms' => ['kicker', 'uprights', 'fourth-down'],
            ],
            [
                'term' => 'Extra Point',
                'definition' => 'A kick attempt worth 1 point, taken after a touchdown from the 15-yard line. The alternative is a two-point conversion attempt. Also called a "PAT" (point after touchdown).',
                'category' => 'general',
                'related_terms' => ['touchdown', 'two-point-conversion', 'pat'],
            ],

            // ─── Offensive Concepts ──────────────────────────────
            [
                'term' => 'Play-Action',
                'definition' => 'A passing play that begins with a fake handoff to a running back. Designed to deceive the defense into committing to the run, opening up passing lanes behind them. Most effective when the team has an established running game.',
                'category' => 'offense',
                'related_terms' => ['handoff', 'play-fake', 'bootleg'],
            ],
            [
                'term' => 'Audible',
                'definition' => 'A change to the play call made by the quarterback at the line of scrimmage, after seeing the defensive alignment. The QB uses coded words or signals to communicate the new play to teammates.',
                'category' => 'offense',
                'related_terms' => ['pre-snap', 'check', 'hot-route', 'kill'],
            ],
            [
                'term' => 'Pocket',
                'definition' => 'The protective area formed by the offensive line around the quarterback during a passing play. The QB stands in the pocket to throw. "Pocket presence" refers to a QB\'s ability to move within the pocket to avoid pressure.',
                'category' => 'offense',
                'related_terms' => ['offensive-line', 'pass-rush', 'scramble'],
            ],
            [
                'term' => 'Handoff',
                'definition' => 'The act of the quarterback giving the ball directly to a running back. The QB extends the ball into the runner\'s path and the RB secures it. The fundamental action of every rushing play.',
                'category' => 'offense',
                'related_terms' => ['rush', 'running-back', 'play-action'],
            ],
            [
                'term' => 'Personnel',
                'definition' => 'The grouping of players on the field, described by number of running backs and tight ends. "11 personnel" means 1 RB, 1 TE (and therefore 3 WR). "21 personnel" means 2 RB, 1 TE. The most common grouping in modern football is 11 personnel.',
                'category' => 'offense',
                'related_terms' => ['formation', 'sub-package'],
                'related_concepts' => ['11-personnel'],
            ],
            [
                'term' => 'Slot',
                'definition' => 'The area between the last offensive lineman and the outside wide receiver. A "slot receiver" lines up in this area, creating favorable matchups against linebackers or slower defensive backs.',
                'category' => 'offense',
                'related_terms' => ['wide-receiver', 'nickel', 'formation'],
            ],

            // ─── Defensive Concepts ─────────────────────────────
            [
                'term' => 'Gap',
                'definition' => 'The space between offensive linemen. Gaps are labeled A through D from inside out: A-gap (center to guard), B-gap (guard to tackle), C-gap (tackle to tight end), D-gap (outside tight end). Defensive gap assignments determine run defense.',
                'category' => 'defense',
                'related_terms' => ['a-gap', 'b-gap', 'gap-integrity', 'one-gap'],
            ],
            [
                'term' => 'Press Coverage',
                'definition' => 'When a cornerback lines up directly at the line of scrimmage and makes physical contact with the receiver at the snap, trying to disrupt the route\'s timing. Legal only within 5 yards of the line of scrimmage.',
                'category' => 'defense',
                'related_terms' => ['jam', 'off-coverage', 'bail', 'cornerback'],
            ],
            [
                'term' => 'Contain',
                'definition' => 'A defensive responsibility to prevent the ball carrier or quarterback from getting to the outside edge of the defense. The "contain" player forces the play back inside toward help.',
                'category' => 'defense',
                'related_terms' => ['edge', 'defensive-end', 'pursuit-angle'],
                'related_concepts' => ['pursuit-angle-triangle'],
            ],
            [
                'term' => 'Run Fit',
                'definition' => 'The defensive assignment each player has against the run. Every gap must be accounted for by a defender. When all defenders fill their assigned gaps correctly, the defense has "good run fits."',
                'category' => 'defense',
                'related_terms' => ['gap', 'spill', 'force', 'alley'],
            ],

            // ─── Spatial / Geometry ──────────────────────────────
            [
                'term' => 'Pursuit Angle',
                'definition' => 'The angle a defender takes to intercept a ball carrier. The correct angle cuts off the runner\'s path efficiently. Too flat and you overshoot; too steep and the runner gets past you.',
                'category' => 'general',
                'related_terms' => ['contain', 'tackling', 'leverage'],
                'related_concepts' => ['pursuit-angle-triangle'],
            ],
            [
                'term' => 'Leverage',
                'definition' => 'A defender\'s position relative to the receiver — inside leverage (between receiver and QB) or outside leverage (between receiver and sideline). Dictates which routes the defender can effectively cover.',
                'category' => 'defense',
                'related_terms' => ['inside-leverage', 'outside-leverage', 'press-coverage'],
            ],
            [
                'term' => 'Window',
                'definition' => 'The open space between defenders where a pass can be completed. Quarterbacks must "throw into windows" that exist momentarily as defenders shift. Smaller windows require greater accuracy.',
                'category' => 'general',
                'related_terms' => ['passing-lane', 'zone-coverage', 'timing'],
            ],

            // ─── Turnovers & Special ─────────────────────────────
            [
                'term' => 'Interception',
                'definition' => 'When a defensive player catches a pass intended for an offensive player. The defense immediately gains possession, and the interceptor can run with the ball. One of the most impactful plays in football.',
                'category' => 'general',
                'related_terms' => ['turnover', 'pick-six', 'ball-hawk'],
            ],
            [
                'term' => 'Fumble',
                'definition' => 'When a ball carrier loses possession of the football. Either team can recover a fumble. A "forced fumble" is caused by a defender stripping the ball or making a hard tackle.',
                'category' => 'general',
                'related_terms' => ['turnover', 'strip', 'recovery'],
            ],
            [
                'term' => 'Sack',
                'definition' => 'When the quarterback is tackled behind the line of scrimmage while attempting to pass. Results in a loss of yards and the next down. A major defensive achievement.',
                'category' => 'defense',
                'related_terms' => ['pass-rush', 'blitz', 'pocket'],
            ],
            [
                'term' => 'Offsides',
                'definition' => 'A penalty called when a player crosses the line of scrimmage before the ball is snapped. On defense it\'s a 5-yard penalty; on offense it\'s a "false start" and is also 5 yards.',
                'category' => 'general',
                'related_terms' => ['line-of-scrimmage', 'snap', 'neutral-zone', 'false-start'],
            ],
        ];
    }
}

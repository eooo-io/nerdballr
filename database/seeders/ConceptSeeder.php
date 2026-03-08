<?php

namespace Database\Seeders;

use App\Models\Concept;
use Illuminate\Database\Seeder;

class ConceptSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->concepts() as $data) {
            Concept::updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );
        }
    }

    private function concepts(): array
    {
        return [
            $this->shotgunSpread(),
            $this->iFormation(),
            $this->pistol(),
            $this->elevenPersonnel(),
            $this->fourThreeBase(),
            $this->threeFourBase(),
            $this->nickel(),
            $this->dime(),
            $this->coverZero(),
            $this->coverOne(),
            $this->coverTwo(),
            $this->coverThree(),
            $this->coverFour(),
            $this->zoneBlitz(),
            $this->aGapBlitz(),
            $this->mesh(),
            $this->fourVerticals(),
            $this->smash(),
            $this->slantFlat(),
            $this->pursuitAngle(),
        ];
    }

    // ─── Offensive Formations ────────────────────────────────────

    private function shotgunSpread(): array
    {
        return [
            'slug' => 'shotgun-spread',
            'label' => 'Shotgun Spread',
            'category' => 'formation-offense',
            'subcategory' => 'shotgun',
            'tags' => ['pass', 'spread', 'shotgun'],
            'difficulty' => 'beginner',
            'layers' => [1],
            'description' => 'Five-receiver shotgun formation that spreads the defense horizontally.',
            'explanation' => "The Shotgun Spread places the QB 5 yards behind center with receivers split wide to both sides. This formation forces the defense to account for the entire width of the field, creating natural spacing for quick passing concepts.\n\nThe empty backfield means no run-fake threat, but the spread alignment makes it difficult for defenses to disguise coverage. The QB can read the defensive alignment pre-snap to identify coverage and find the best matchup.\n\nKey advantages: forces the defense to declare coverage, creates natural throwing lanes, and allows the QB to survey the field from the snap.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Shotgun alignment with 4 WR spread wide', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 550, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 540, 'y' => 240]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 600, 'y' => 100]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 440]],
                ]),
                $this->phase(1, 'Snap', 'QB receives shotgun snap', 500, [
                    ['playerId' => 'QB', 'position' => ['x' => 550, 'y' => 266]],
                ]),
                $this->phase(2, 'Route release', 'Receivers release into routes', 1500, [
                    ['playerId' => 'WR1', 'position' => ['x' => 660, 'y' => 30], 'paths' => [$this->straightPath(600, 30, 660, 30)]],
                    ['playerId' => 'WR2', 'position' => ['x' => 660, 'y' => 503], 'paths' => [$this->straightPath(600, 503, 660, 503)]],
                    ['playerId' => 'WR3', 'position' => ['x' => 650, 'y' => 140], 'paths' => [$this->straightPath(600, 100, 650, 140)]],
                    ['playerId' => 'TE', 'position' => ['x' => 650, 'y' => 400], 'paths' => [$this->straightPath(600, 440, 650, 400)]],
                    ['playerId' => 'RB', 'position' => ['x' => 560, 'y' => 200], 'paths' => [$this->straightPath(540, 240, 560, 200)]],
                ]),
            ],
            'counters' => ['cover-one', 'a-gap-blitz'],
            'related' => ['pistol', 'eleven-personnel'],
            'ai_context' => "Shotgun Spread: 5-wide formation with QB in shotgun (5 yards behind center). Forces defense to cover full field width. No run threat from empty backfield. Best against zone coverage where spacing creates natural windows. Vulnerable to man press coverage and interior blitz pressure due to empty backfield.",
        ];
    }

    private function iFormation(): array
    {
        return [
            'slug' => 'i-formation',
            'label' => 'I-Formation',
            'category' => 'formation-offense',
            'subcategory' => 'under-center',
            'tags' => ['run', 'power', 'under-center'],
            'difficulty' => 'beginner',
            'layers' => [1],
            'description' => 'Traditional power running formation with QB under center, FB and RB aligned behind.',
            'explanation' => "The I-Formation is the foundational power running set. The quarterback is under center, the fullback aligned 4 yards behind the QB, and the halfback 7 yards deep — all in a straight vertical line (the 'I').\n\nThe fullback serves as the lead blocker on power and isolation plays. The depth of the halfback gives him time to read blocks and choose his running lane. Two receivers split wide and a tight end provide enough passing threat to prevent the defense from loading the box.\n\nKey advantage: balanced formation that threatens both run and pass, with a natural lead blocker for downhill running.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'I-Formation alignment under center', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 590, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 530, 'y' => 266]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 560, 'y' => 266]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 344]],
                ]),
                $this->phase(1, 'Handoff', 'QB hands to RB behind FB lead block', 800, [
                    ['playerId' => 'QB', 'position' => ['x' => 585, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 560, 'y' => 266], 'paths' => [$this->straightPath(530, 266, 560, 266)]],
                    ['playerId' => 'WR3', 'position' => ['x' => 580, 'y' => 292], 'paths' => [$this->straightPath(560, 266, 580, 292)]],
                ]),
                $this->phase(2, 'Downhill', 'FB leads through hole, RB follows', 1500, [
                    ['playerId' => 'RB', 'position' => ['x' => 640, 'y' => 280], 'paths' => [$this->straightPath(560, 266, 640, 280)]],
                    ['playerId' => 'WR3', 'position' => ['x' => 630, 'y' => 300], 'paths' => [$this->straightPath(580, 292, 630, 300)]],
                ]),
            ],
            'counters' => ['nickel', 'a-gap-blitz'],
            'related' => ['eleven-personnel', 'shotgun-spread'],
            'ai_context' => "I-Formation: QB under center, FB at 4 yards, RB at 7 yards in vertical alignment. Primary run formation with lead-blocking fullback. Tight end provides extra blocker on run side. Two wide receivers keep defense honest. Strong against light boxes (5-6 defenders). Weak against 8-man fronts and run-blitz schemes.",
        ];
    }

    private function pistol(): array
    {
        return [
            'slug' => 'pistol',
            'label' => 'Pistol',
            'category' => 'formation-offense',
            'subcategory' => 'pistol',
            'tags' => ['run', 'pass', 'pistol', 'zone-read'],
            'difficulty' => 'intermediate',
            'layers' => [1],
            'description' => 'Hybrid formation with QB at 4 yards, RB directly behind — combines shotgun vision with downhill running.',
            'explanation' => "The Pistol places the QB at 4 yards behind center (shorter than shotgun's 5) with the RB directly behind the QB at 7 yards. This creates the shotgun's passing advantages while preserving the RB's ability to run downhill in either direction.\n\nUnlike shotgun, where the RB is beside the QB, the Pistol's behind-QB alignment gives the RB a north-south running angle and hides the run direction. Zone-read concepts are natural fits.\n\nKey advantage: the defense cannot determine run direction based on RB alignment, and the QB retains pre-snap read ability.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Pistol alignment with RB behind QB', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 560, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 530, 'y' => 266]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 600, 'y' => 100]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 344]],
                ]),
                $this->phase(1, 'Mesh point', 'QB reads DE for zone-read decision', 800, [
                    ['playerId' => 'QB', 'position' => ['x' => 570, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 570, 'y' => 250], 'paths' => [$this->straightPath(530, 266, 570, 250)]],
                ]),
                $this->phase(2, 'Give', 'RB takes handoff and runs to daylight', 1500, [
                    ['playerId' => 'RB', 'position' => ['x' => 640, 'y' => 240], 'paths' => [$this->straightPath(570, 250, 640, 240)]],
                    ['playerId' => 'QB', 'position' => ['x' => 560, 'y' => 290]],
                ]),
            ],
            'counters' => ['four-three-base', 'cover-one'],
            'related' => ['i-formation', 'shotgun-spread'],
            'ai_context' => "Pistol: QB at 4 yards, RB directly behind at 7 yards. Hybrid formation combining shotgun passing vision with downhill running. RB alignment hides run direction (unlike offset shotgun). Ideal for zone-read, power, and RPO concepts. Forces the defense to respect both run and pass on every snap.",
        ];
    }

    private function elevenPersonnel(): array
    {
        return [
            'slug' => 'eleven-personnel',
            'label' => '11 Personnel',
            'category' => 'formation-offense',
            'subcategory' => 'personnel-grouping',
            'tags' => ['pass', 'spread', 'personnel'],
            'difficulty' => 'beginner',
            'layers' => [1],
            'description' => '1 RB, 1 TE, 3 WR — the most common personnel grouping in modern football.',
            'explanation' => "11 Personnel (1 RB, 1 TE, 3 WR) is the base personnel grouping in modern football, used on roughly 60% of NFL plays. It provides maximum formation flexibility: the same 11 players can line up in shotgun spread, trips, bunch, or tight formations.\n\nThe tight end can align inline (as a blocker), in the slot, or split wide as a receiver. This creates a 4th receiving option with the size to win contested catches and block in the run game.\n\nDefenses typically counter with their base or nickel package, giving the offense a simple pre-snap read: if the defense shows nickel (5 DBs), the run game has a numbers advantage; if they show base (4 DBs), the passing game has a matchup advantage.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', '11 Personnel in 2x2 alignment', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 555, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 545, 'y' => 240]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 600, 'y' => 140]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 344]],
                ]),
                $this->phase(1, 'TE motion', 'TE motions to slot — same personnel, different look', 1200, [
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 170], 'paths' => [$this->straightPath(600, 344, 600, 170)]],
                ]),
            ],
            'counters' => ['nickel', 'cover-two'],
            'related' => ['shotgun-spread', 'pistol'],
            'ai_context' => "11 Personnel: 1 RB, 1 TE, 3 WR. Most common NFL grouping (~60% of snaps). Maximum formation flexibility — same personnel can deploy in spread, bunch, trips, tight. TE versatility (inline blocker or receiver) creates pre-snap ambiguity. Forces defense into nickel-or-base decision that reveals coverage intent.",
        ];
    }

    // ─── Defensive Formations ────────────────────────────────────

    private function fourThreeBase(): array
    {
        return [
            'slug' => 'four-three-base',
            'label' => '4-3 Base Defense',
            'category' => 'formation-defense',
            'subcategory' => '4-man-front',
            'tags' => ['run', 'zone', 'base'],
            'difficulty' => 'beginner',
            'layers' => [1],
            'description' => 'Four defensive linemen, three linebackers — the standard run-stopping front.',
            'explanation' => "The 4-3 defense aligns four down linemen and three linebackers. The front four are responsible for controlling gaps and generating pass rush, while the three linebackers fill run gaps and drop into coverage.\n\nThe MIKE (middle) linebacker is the defensive quarterback, responsible for making formation calls and adjustments. The WILL (weakside) and SAM (strongside) linebackers have dual run/pass responsibilities.\n\nStrengths: strong against the run with 4 linemen occupying blockers, versatile coverage options, natural pass rush from the front four without blitzing.",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', '4-3 base alignment', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 630, 'y' => 220]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 630, 'y' => 312]],
                    ['playerId' => 'CB1', 'position' => ['x' => 605, 'y' => 30]],
                    ['playerId' => 'CB2', 'position' => ['x' => 605, 'y' => 503]],
                    ['playerId' => 'FS', 'position' => ['x' => 680, 'y' => 200]],
                    ['playerId' => 'SS', 'position' => ['x' => 680, 'y' => 332]],
                ]),
                $this->phase(1, 'Run fill', 'Linebackers fill gaps on run play', 1200, [
                    ['playerId' => 'LB1', 'position' => ['x' => 615, 'y' => 220], 'paths' => [$this->straightPath(630, 220, 615, 220)]],
                    ['playerId' => 'LB2', 'position' => ['x' => 620, 'y' => 266], 'paths' => [$this->straightPath(635, 266, 620, 266)]],
                    ['playerId' => 'LB3', 'position' => ['x' => 615, 'y' => 312], 'paths' => [$this->straightPath(630, 312, 615, 312)]],
                ]),
            ],
            'counters' => ['shotgun-spread', 'eleven-personnel'],
            'related' => ['three-four-base', 'nickel'],
            'ai_context' => "4-3 Base: Four down linemen (2 DE, 2 DT), three linebackers (WILL, MIKE, SAM), four defensive backs (2 CB, FS, SS). Standard run-defense front. Front four controls gaps, linebackers fill. Strong against power running and pro-style formations. Vulnerable to spread formations that create space in the second level.",
        ];
    }

    private function threeFourBase(): array
    {
        return [
            'slug' => 'three-four-base',
            'label' => '3-4 Base Defense',
            'category' => 'formation-defense',
            'subcategory' => '3-man-front',
            'tags' => ['run', 'blitz', 'versatile'],
            'difficulty' => 'intermediate',
            'layers' => [1],
            'description' => 'Three linemen, four linebackers — a versatile front that disguises blitz pressure.',
            'explanation' => "The 3-4 defense uses three down linemen and four linebackers. The nose tackle occupies the center, while two defensive ends control the B-gaps. Four linebackers provide tremendous pre-snap versatility — any combination can rush or drop into coverage.\n\nThe key advantage is disguise: the offense cannot predict which linebacker(s) will blitz until the snap. The 3-4 can show the same pre-snap look and send 3, 4, 5, or 6 rushers.\n\nRequires a dominant nose tackle who can handle double teams, and athletic outside linebackers who can rush the passer and drop into coverage.",
            'roster' => $this->defenseRoster34(),
            'phases' => [
                $this->phase(0, 'Pre-snap', '3-4 base alignment', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 230]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 266]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 302]],
                    ['playerId' => 'LB1', 'position' => ['x' => 615, 'y' => 190]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 240]],
                    ['playerId' => 'LB3', 'position' => ['x' => 635, 'y' => 292]],
                    ['playerId' => 'LB4', 'position' => ['x' => 615, 'y' => 342]],
                    ['playerId' => 'CB1', 'position' => ['x' => 605, 'y' => 30]],
                    ['playerId' => 'CB2', 'position' => ['x' => 605, 'y' => 503]],
                    ['playerId' => 'FS', 'position' => ['x' => 680, 'y' => 200]],
                    ['playerId' => 'SS', 'position' => ['x' => 680, 'y' => 332]],
                ]),
                $this->phase(1, 'OLB walk-up', 'OLB walks to line of scrimmage — shows 4-man front look', 1000, [
                    ['playerId' => 'LB1', 'position' => ['x' => 612, 'y' => 190], 'paths' => [$this->straightPath(615, 190, 612, 190)]],
                    ['playerId' => 'LB4', 'position' => ['x' => 612, 'y' => 342], 'paths' => [$this->straightPath(615, 342, 612, 342)]],
                ]),
            ],
            'counters' => ['shotgun-spread', 'mesh'],
            'related' => ['four-three-base', 'zone-blitz'],
            'ai_context' => "3-4 Base: Three down linemen (NT, 2 DE), four linebackers (2 OLB, 2 ILB), four DBs. Primary advantage is blitz disguise — any LB can rush. Requires dominant NT to anchor center. OLBs must be athletic pass rushers who can also drop into coverage. Versatile against both run and pass. Can morph into 4-man front by walking OLB to line.",
        ];
    }

    private function nickel(): array
    {
        return [
            'slug' => 'nickel',
            'label' => 'Nickel Defense',
            'category' => 'formation-defense',
            'subcategory' => 'sub-package',
            'tags' => ['pass', 'zone', 'man', 'sub-package'],
            'difficulty' => 'beginner',
            'layers' => [1],
            'description' => 'Five defensive backs — the standard response to 3-WR sets.',
            'explanation' => "The Nickel defense replaces a linebacker with a fifth defensive back (the 'nickel corner' or 'nickel back'). It's the most common sub-package in modern football, deployed against 11 personnel (3 WR) sets.\n\nWith 5 DBs, the defense matches the offense's receiver count and maintains coverage integrity against 3-wide formations. The trade-off is one fewer linebacker, which can weaken run defense.\n\nThe nickel back typically covers the slot receiver and must be comfortable in both man and zone coverage. Many teams now use nickel as their base defense due to the pass-heavy nature of modern offenses.",
            'roster' => $this->defenseRosterNickel(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Nickel alignment vs 3-WR set', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 230]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 266]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 302]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 338]],
                    ['playerId' => 'LB1', 'position' => ['x' => 635, 'y' => 240]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 292]],
                    ['playerId' => 'CB1', 'position' => ['x' => 605, 'y' => 30]],
                    ['playerId' => 'CB2', 'position' => ['x' => 605, 'y' => 503]],
                    ['playerId' => 'NCB', 'position' => ['x' => 620, 'y' => 140]],
                    ['playerId' => 'FS', 'position' => ['x' => 680, 'y' => 200]],
                    ['playerId' => 'SS', 'position' => ['x' => 680, 'y' => 332]],
                ]),
                $this->phase(1, 'Zone drop', 'Nickel back and LBs settle into zone coverage zones', 1200, [
                    ['playerId' => 'NCB', 'position' => ['x' => 640, 'y' => 140], 'paths' => [$this->straightPath(620, 140, 640, 140)]],
                    ['playerId' => 'LB1', 'position' => ['x' => 650, 'y' => 210], 'paths' => [$this->straightPath(635, 240, 650, 210)]],
                    ['playerId' => 'LB2', 'position' => ['x' => 650, 'y' => 320], 'paths' => [$this->straightPath(635, 292, 650, 320)]],
                ]),
            ],
            'counters' => ['i-formation', 'pistol'],
            'related' => ['dime', 'four-three-base'],
            'ai_context' => "Nickel: 4 DL, 2 LB, 5 DB (adds slot corner). Standard defense against 3-WR formations. Matches receiver count with defensive backs. Trade-off: weaker run defense with only 2 LBs. Nickel corner must handle slot receivers in both man and zone. Now functionally the base defense for many NFL teams due to pass-heavy offenses.",
        ];
    }

    private function dime(): array
    {
        return [
            'slug' => 'dime',
            'label' => 'Dime Defense',
            'category' => 'formation-defense',
            'subcategory' => 'sub-package',
            'tags' => ['pass', 'zone', 'man', 'sub-package'],
            'difficulty' => 'intermediate',
            'layers' => [1],
            'description' => 'Six defensive backs — deployed against 4-WR and obvious passing situations.',
            'explanation' => "The Dime defense adds a sixth defensive back (the 'dime back'), typically replacing a second linebacker. It's the standard response to 4-WR formations and obvious passing downs (3rd-and-long).\n\nWith 6 DBs, the defense can match any receiver distribution while maintaining safety help deep. The trade-off is significant: only one linebacker means very limited run-stopping ability.\n\nDime is a situational package, not a base defense. It signals to the offense that the defense expects a pass, which can be exploited with draw plays and screens.",
            'roster' => $this->defenseRosterDime(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Dime alignment vs 4-WR set', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 230]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 266]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 302]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 338]],
                    ['playerId' => 'LB1', 'position' => ['x' => 635, 'y' => 266]],
                    ['playerId' => 'CB1', 'position' => ['x' => 605, 'y' => 30]],
                    ['playerId' => 'CB2', 'position' => ['x' => 605, 'y' => 503]],
                    ['playerId' => 'NCB', 'position' => ['x' => 620, 'y' => 120]],
                    ['playerId' => 'DCB', 'position' => ['x' => 620, 'y' => 412]],
                    ['playerId' => 'FS', 'position' => ['x' => 680, 'y' => 200]],
                    ['playerId' => 'SS', 'position' => ['x' => 680, 'y' => 332]],
                ]),
                $this->phase(1, 'Deep shell', 'Safeties and dime back settle into deep coverage shell', 1200, [
                    ['playerId' => 'FS', 'position' => ['x' => 700, 'y' => 180], 'paths' => [$this->straightPath(680, 200, 700, 180)]],
                    ['playerId' => 'SS', 'position' => ['x' => 700, 'y' => 350], 'paths' => [$this->straightPath(680, 332, 700, 350)]],
                    ['playerId' => 'DCB', 'position' => ['x' => 640, 'y' => 412], 'paths' => [$this->straightPath(620, 412, 640, 412)]],
                ]),
            ],
            'counters' => ['i-formation', 'pistol'],
            'related' => ['nickel', 'cover-two'],
            'ai_context' => "Dime: 4 DL, 1 LB, 6 DB (adds dime back). Situational passing-down package vs 4-WR sets. Maximum coverage ability with 6 defensive backs. Extremely weak against the run — only 1 LB. Signals pass expectation to offense, making draws and screens effective counters. Used on 3rd-and-long and obvious passing situations.",
        ];
    }

    // ─── Coverages ───────────────────────────────────────────────

    private function coverZero(): array
    {
        return [
            'slug' => 'cover-zero',
            'label' => 'Cover 0 (No Safety Help)',
            'category' => 'coverage',
            'subcategory' => 'man',
            'tags' => ['man', 'blitz', 'press', 'aggressive'],
            'difficulty' => 'intermediate',
            'layers' => [1, 2],
            'description' => 'Pure man coverage with no deep safety — all-out pressure.',
            'explanation' => "Cover 0 is the most aggressive coverage in football. Every defensive back plays man-to-man with no safety help over the top. This frees both safeties (and sometimes linebackers) to blitz.\n\nThe advantage is maximum pressure — the QB faces 6 or 7 rushers with no time to throw. The risk is equally maximum: if any receiver beats man coverage, there is no safety net. One broken coverage means a touchdown.\n\nCover 0 is a calculated gamble: the defense bets it can get to the QB before any receiver gets open. It's most effective on short-yardage and goal-line situations.",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Cover 0 — press man, no deep safety', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 615, 'y' => 220]],
                    ['playerId' => 'LB2', 'position' => ['x' => 615, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 615, 'y' => 312]],
                    ['playerId' => 'CB1', 'position' => ['x' => 603, 'y' => 32]],
                    ['playerId' => 'CB2', 'position' => ['x' => 603, 'y' => 501]],
                    ['playerId' => 'FS', 'position' => ['x' => 620, 'y' => 180]],
                    ['playerId' => 'SS', 'position' => ['x' => 620, 'y' => 350]],
                ]),
                $this->phase(1, 'Blitz', 'Safeties and LB rush — CBs in press man', 1500, [
                    ['playerId' => 'FS', 'position' => ['x' => 600, 'y' => 230], 'paths' => [$this->straightPath(620, 180, 600, 230)]],
                    ['playerId' => 'SS', 'position' => ['x' => 600, 'y' => 302], 'paths' => [$this->straightPath(620, 350, 600, 302)]],
                    ['playerId' => 'LB2', 'position' => ['x' => 600, 'y' => 266], 'paths' => [$this->straightPath(615, 266, 600, 266)]],
                ]),
            ],
            'counters' => ['slant-flat', 'mesh', 'four-verticals'],
            'related' => ['cover-one', 'a-gap-blitz'],
            'ai_context' => "Cover 0: Pure man coverage, no deep safety help. All DBs in man-to-man. Safeties blitz rather than playing deep. Maximum pressure (6-7 rushers) but maximum risk (no safety net). If a receiver beats man coverage, it's a touchdown. Best on short yardage and goal line. Beaten by quick-release concepts (slants, mesh) and speed verticals.",
        ];
    }

    private function coverOne(): array
    {
        return [
            'slug' => 'cover-one',
            'label' => 'Cover 1 (Man Free)',
            'category' => 'coverage',
            'subcategory' => 'man',
            'tags' => ['man', 'press', 'single-high'],
            'difficulty' => 'beginner',
            'layers' => [1, 2],
            'description' => 'Man-to-man coverage with a single deep safety — the most common man scheme.',
            'explanation' => "Cover 1, also called 'Man Free,' assigns each defensive back to cover a specific receiver man-to-man, with one safety playing deep center field ('free safety'). This gives the defense the aggression of man coverage with a single safety net against deep throws.\n\nThe free safety reads the QB's eyes and breaks on the throw. This helps against deep balls but leaves the middle of the field open to crossing routes and seam concepts.\n\nCover 1 is the default man coverage scheme because it balances aggression with safety, allowing the defense to blitz a linebacker while maintaining a deep defender.",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Cover 1 — man-to-man, free safety deep', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 630, 'y' => 220]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 630, 'y' => 312]],
                    ['playerId' => 'CB1', 'position' => ['x' => 604, 'y' => 32]],
                    ['playerId' => 'CB2', 'position' => ['x' => 604, 'y' => 501]],
                    ['playerId' => 'FS', 'position' => ['x' => 700, 'y' => 266]],
                    ['playerId' => 'SS', 'position' => ['x' => 635, 'y' => 350]],
                ]),
                $this->phase(1, 'Post-snap', 'DBs trail receivers, FS reads QB eyes', 2000, [
                    ['playerId' => 'FS', 'position' => ['x' => 710, 'y' => 266]],
                    ['playerId' => 'CB1', 'position' => ['x' => 640, 'y' => 40]],
                    ['playerId' => 'CB2', 'position' => ['x' => 640, 'y' => 493]],
                ]),
            ],
            'counters' => ['mesh', 'slant-flat'],
            'related' => ['cover-zero', 'cover-two'],
            'ai_context' => "Cover 1 (Man Free): Man-to-man on all receivers, single free safety deep center. Most common man coverage. FS reads QB eyes and breaks on throw. Allows 1-2 LBs to blitz while maintaining deep help. Vulnerable to crossing routes (mesh, drag) that create picks. Middle of field open to seams and crossers. Strong against vertical and outside routes with FS help.",
        ];
    }

    private function coverTwo(): array
    {
        return [
            'slug' => 'cover-two',
            'label' => 'Cover 2',
            'category' => 'coverage',
            'subcategory' => 'zone',
            'tags' => ['zone', 'two-high'],
            'difficulty' => 'beginner',
            'layers' => [1, 2],
            'description' => 'Two deep safeties split the field, five underneath zones — the foundational zone defense.',
            'explanation' => "Cover 2 is the foundational zone defense. Two safeties split the deep field in half, each responsible for their half of the deep zone. Five defenders (typically 3 LBs and 2 CBs) cover five underneath zones.\n\nThe cornerbacks play the flat zones, funneling receivers inside to the linebackers. This creates a strong run-support defense with both safeties available as extra tacklers on outside runs.\n\nThe structural weakness is the deep middle — the seam between the two safeties. A receiver running a post or seam route can split the two safeties and find the hole in the defense.",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Cover 2 — two-high safety shell', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 630, 'y' => 220]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 630, 'y' => 312]],
                    ['playerId' => 'CB1', 'position' => ['x' => 608, 'y' => 50]],
                    ['playerId' => 'CB2', 'position' => ['x' => 608, 'y' => 483]],
                    ['playerId' => 'FS', 'position' => ['x' => 700, 'y' => 133]],
                    ['playerId' => 'SS', 'position' => ['x' => 700, 'y' => 399]],
                ]),
                $this->phase(1, 'Zone drop', 'CBs squat in flats, safeties split deep halves', 2000, [
                    ['playerId' => 'CB1', 'position' => ['x' => 630, 'y' => 60]],
                    ['playerId' => 'CB2', 'position' => ['x' => 630, 'y' => 473]],
                    ['playerId' => 'LB1', 'position' => ['x' => 650, 'y' => 180]],
                    ['playerId' => 'LB2', 'position' => ['x' => 660, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 650, 'y' => 352]],
                    ['playerId' => 'FS', 'position' => ['x' => 730, 'y' => 133]],
                    ['playerId' => 'SS', 'position' => ['x' => 730, 'y' => 399]],
                ]),
            ],
            'counters' => ['four-verticals', 'smash'],
            'related' => ['cover-three', 'cover-four'],
            'ai_context' => "Cover 2: Two deep safeties split field in half, 5 underneath zones (2 CB flats, 3 LB hooks/curls). Foundational zone defense. CBs play flat zones and funnel receivers inside. Strong run support with both safeties. Weakness: deep middle seam between safeties. Beaten by four verticals (stretches 2 deep into 4), smash concept (high-low on CB), and post routes splitting the safeties.",
        ];
    }

    private function coverThree(): array
    {
        return [
            'slug' => 'cover-three',
            'label' => 'Cover 3',
            'category' => 'coverage',
            'subcategory' => 'zone',
            'tags' => ['zone', 'single-high'],
            'difficulty' => 'beginner',
            'layers' => [1, 2],
            'description' => 'Three deep zones, four underneath — the most common zone coverage in football.',
            'explanation' => "Cover 3 divides the deep field into thirds, with two cornerbacks and one safety each responsible for a deep third. Four defenders play underneath zones, and the strong safety rolls down into the box as an extra run defender.\n\nThis is the most common zone coverage because it's simple, sound, and versatile. Three deep defenders eliminate most deep passing threats, while the rolled-down strong safety adds run support.\n\nThe weakness is the flat — with CBs playing deep thirds, the flat zones (sideline, 5-15 yards deep) are covered by linebackers or a rolled-down safety, creating soft spots for out routes, flat routes, and corner routes.",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Cover 3 — single-high look, SS in box', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 630, 'y' => 220]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 630, 'y' => 312]],
                    ['playerId' => 'CB1', 'position' => ['x' => 608, 'y' => 40]],
                    ['playerId' => 'CB2', 'position' => ['x' => 608, 'y' => 493]],
                    ['playerId' => 'FS', 'position' => ['x' => 700, 'y' => 266]],
                    ['playerId' => 'SS', 'position' => ['x' => 635, 'y' => 360]],
                ]),
                $this->phase(1, 'Zone drop', 'CBs and FS drop to deep thirds, SS to flat', 2000, [
                    ['playerId' => 'CB1', 'position' => ['x' => 720, 'y' => 80]],
                    ['playerId' => 'CB2', 'position' => ['x' => 720, 'y' => 453]],
                    ['playerId' => 'FS', 'position' => ['x' => 730, 'y' => 266]],
                    ['playerId' => 'SS', 'position' => ['x' => 650, 'y' => 400]],
                ]),
            ],
            'counters' => ['smash', 'slant-flat'],
            'related' => ['cover-two', 'cover-four'],
            'ai_context' => "Cover 3: Field split into deep thirds (2 CBs + FS), 4 underneath zones. SS rolls down as extra run defender. Most common zone coverage. Simple, sound, versatile. Weakness: flat zones and seam-to-flat high-low concepts (smash). CBs play deep so underneath flat coverage depends on LBs. Strong against deep passes but vulnerable to curl-flat and smash concepts.",
        ];
    }

    private function coverFour(): array
    {
        return [
            'slug' => 'cover-four',
            'label' => 'Cover 4 (Quarters)',
            'category' => 'coverage',
            'subcategory' => 'zone',
            'tags' => ['zone', 'two-high', 'quarters'],
            'difficulty' => 'advanced',
            'layers' => [1, 2],
            'description' => 'Four deep quarter-zones — a pattern-matching zone that adjusts to offensive routes.',
            'explanation' => "Cover 4, or Quarters, divides the deep field into four zones: each cornerback and each safety takes a quarter. Underneath, three linebackers cover the hooks and curls.\n\nThe key innovation is pattern matching: each deep defender reads the route of the #1 and #2 receivers and adjusts responsibilities based on vertical threats. If no receiver goes vertical in a quarter, that defender can help elsewhere.\n\nQuarters is strong against the run (both safeties can play downhill on run reads) and against deep passes (4 deep defenders). The weakness is underneath — with only 3 linebackers in the short zones, there are windows for curls, digs, and crossing routes.",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Cover 4 — two-high shell, quarters rules', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 630, 'y' => 220]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 630, 'y' => 312]],
                    ['playerId' => 'CB1', 'position' => ['x' => 615, 'y' => 40]],
                    ['playerId' => 'CB2', 'position' => ['x' => 615, 'y' => 493]],
                    ['playerId' => 'FS', 'position' => ['x' => 690, 'y' => 160]],
                    ['playerId' => 'SS', 'position' => ['x' => 690, 'y' => 372]],
                ]),
                $this->phase(1, 'Pattern read', 'Deep 4 read vertical threats, adjust quarters', 2000, [
                    ['playerId' => 'CB1', 'position' => ['x' => 710, 'y' => 60]],
                    ['playerId' => 'FS', 'position' => ['x' => 720, 'y' => 180]],
                    ['playerId' => 'SS', 'position' => ['x' => 720, 'y' => 352]],
                    ['playerId' => 'CB2', 'position' => ['x' => 710, 'y' => 473]],
                ]),
            ],
            'counters' => ['mesh', 'slant-flat'],
            'related' => ['cover-two', 'cover-three'],
            'ai_context' => "Cover 4 (Quarters): Four deep quarter-zones (2 CB + 2 S), 3 underneath. Pattern-matching zone that reads vertical threats and adjusts. Strong run support (safeties can play downhill on run). Strong deep pass defense (4 deep). Weakness: short-to-intermediate zones with only 3 LBs underneath. Beaten by crossing routes, mesh, and flood concepts that overload short zones.",
        ];
    }

    // ─── Blitz Concepts ──────────────────────────────────────────

    private function zoneBlitz(): array
    {
        return [
            'slug' => 'zone-blitz',
            'label' => 'Zone Blitz',
            'category' => 'blitz',
            'subcategory' => 'zone-blitz',
            'tags' => ['blitz', 'zone', 'pressure'],
            'difficulty' => 'advanced',
            'layers' => [1, 2],
            'description' => 'Blitz with zone coverage behind it — sending unexpected rushers while dropping linemen into zones.',
            'explanation' => "The Zone Blitz is a deceptive pressure scheme that sends rushers from unexpected positions while dropping defensive linemen into coverage. The result: the offense sees 5+ rushers but can't predict where they come from, and the coverage behind the blitz is zone (not man), which is harder to exploit with hot routes.\n\nThe classic Zone Blitz drops a defensive end into a short zone (replacing the blitzing linebacker's coverage) while bringing a safety, cornerback, or linebacker from the opposite side. The offense blocks what it expects to be a 4-man rush, but the actual rushers come from different gaps.\n\nKey principle: the total number of rushers may be the same (4-5), but the identity of the rushers changes, creating confusion in pass protection.",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Standard 4-3 look — blitz disguised', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 630, 'y' => 220]],
                    ['playerId' => 'LB2', 'position' => ['x' => 635, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 630, 'y' => 312]],
                    ['playerId' => 'CB1', 'position' => ['x' => 608, 'y' => 40]],
                    ['playerId' => 'CB2', 'position' => ['x' => 608, 'y' => 493]],
                    ['playerId' => 'FS', 'position' => ['x' => 700, 'y' => 200]],
                    ['playerId' => 'SS', 'position' => ['x' => 660, 'y' => 350]],
                ]),
                $this->phase(1, 'Blitz reveal', 'DE drops to zone, LB and SS rush', 800, [
                    ['playerId' => 'DL4', 'position' => ['x' => 625, 'y' => 370], 'paths' => [$this->straightPath(610, 318, 625, 370)]],
                    ['playerId' => 'LB3', 'position' => ['x' => 605, 'y' => 295], 'paths' => [$this->straightPath(630, 312, 605, 295)]],
                    ['playerId' => 'SS', 'position' => ['x' => 610, 'y' => 330], 'paths' => [$this->straightPath(660, 350, 610, 330)]],
                ]),
                $this->phase(2, 'Zone coverage', 'Zone drops settle, pressure arrives', 1500, [
                    ['playerId' => 'DL4', 'position' => ['x' => 640, 'y' => 400]],
                    ['playerId' => 'LB3', 'position' => ['x' => 590, 'y' => 280]],
                    ['playerId' => 'SS', 'position' => ['x' => 595, 'y' => 300]],
                    ['playerId' => 'LB1', 'position' => ['x' => 650, 'y' => 180]],
                    ['playerId' => 'LB2', 'position' => ['x' => 660, 'y' => 266]],
                ]),
            ],
            'counters' => ['shotgun-spread', 'mesh'],
            'related' => ['a-gap-blitz', 'three-four-base'],
            'ai_context' => "Zone Blitz: Sends unexpected rushers while dropping DL into coverage zones behind them. Creates pass protection confusion — offense blocks expected rushers but pressure comes from different gaps. Zone coverage behind the blitz prevents hot-route exploitation. Total rushers often the same (4-5) but from unexpected positions. Countered by quick-release passing and sight adjustments.",
        ];
    }

    private function aGapBlitz(): array
    {
        return [
            'slug' => 'a-gap-blitz',
            'label' => 'A-Gap Blitz',
            'category' => 'blitz',
            'subcategory' => 'interior-blitz',
            'tags' => ['blitz', 'pressure', 'interior'],
            'difficulty' => 'intermediate',
            'layers' => [1, 2],
            'description' => 'Interior pressure through the A-gaps (between center and guards) — fastest path to the QB.',
            'explanation' => "The A-Gap Blitz sends one or two linebackers through the A-gaps — the spaces between the center and each guard. These are the shortest paths to the quarterback and create immediate interior pressure.\n\nThe center can only block one A-gap, so if both are threatened, the offense must identify and pick up the correct rusher. Many A-Gap Blitz designs 'show' both A-gaps but only send one, forcing the center to guess.\n\nThe A-Gap Blitz collapses the pocket from the inside, eliminating the QB's ability to step up in the pocket (the normal escape route from edge pressure).",
            'roster' => $this->defenseRoster43(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'LBs creep to A-gaps, showing double-A pressure', 1000, [
                    ['playerId' => 'DL1', 'position' => ['x' => 610, 'y' => 214]],
                    ['playerId' => 'DL2', 'position' => ['x' => 610, 'y' => 250]],
                    ['playerId' => 'DL3', 'position' => ['x' => 610, 'y' => 282]],
                    ['playerId' => 'DL4', 'position' => ['x' => 610, 'y' => 318]],
                    ['playerId' => 'LB1', 'position' => ['x' => 615, 'y' => 253]],
                    ['playerId' => 'LB2', 'position' => ['x' => 620, 'y' => 266]],
                    ['playerId' => 'LB3', 'position' => ['x' => 615, 'y' => 279]],
                    ['playerId' => 'CB1', 'position' => ['x' => 608, 'y' => 40]],
                    ['playerId' => 'CB2', 'position' => ['x' => 608, 'y' => 493]],
                    ['playerId' => 'FS', 'position' => ['x' => 700, 'y' => 200]],
                    ['playerId' => 'SS', 'position' => ['x' => 700, 'y' => 332]],
                ]),
                $this->phase(1, 'Blitz', 'LBs fire through A-gaps', 800, [
                    ['playerId' => 'LB1', 'position' => ['x' => 595, 'y' => 253], 'paths' => [$this->straightPath(615, 253, 595, 253)]],
                    ['playerId' => 'LB3', 'position' => ['x' => 595, 'y' => 279], 'paths' => [$this->straightPath(615, 279, 595, 279)]],
                ]),
                $this->phase(2, 'Pocket collapse', 'Interior pressure forces quick throw', 1200, [
                    ['playerId' => 'LB1', 'position' => ['x' => 570, 'y' => 253]],
                    ['playerId' => 'LB3', 'position' => ['x' => 570, 'y' => 279]],
                ]),
            ],
            'counters' => ['shotgun-spread', 'slant-flat'],
            'related' => ['zone-blitz', 'cover-zero'],
            'ai_context' => "A-Gap Blitz: Interior blitz through the gaps between center and guards. Shortest path to the QB. Center can only block one gap, creating a numbers advantage. Collapses pocket from inside, eliminating step-up escape route. Often shown as double-A threat (both gaps) with only one actually blitzing. Countered by quick passing, hot routes, and center identification.",
        ];
    }

    // ─── Route Concepts ──────────────────────────────────────────

    private function mesh(): array
    {
        return [
            'slug' => 'mesh',
            'label' => 'Mesh Concept',
            'category' => 'route-concept',
            'subcategory' => 'crossing',
            'tags' => ['pass', 'man-beater', 'crossing'],
            'difficulty' => 'intermediate',
            'layers' => [1, 2],
            'description' => 'Two receivers cross underneath at 5-6 yards, creating natural picks against man coverage.',
            'explanation' => "The Mesh concept sends two receivers on shallow crossing routes at 5-6 yards depth, running opposite directions. The receivers cross paths, which creates natural 'rub' routes against man coverage — the trailing defender gets caught in traffic.\n\nAgainst man coverage, the mesh is devastating because defenders must navigate through or around the crossing receivers. Against zone, the crossers find windows between zone defenders.\n\nThe concept typically includes a vertical route (clear-out) and a flat route (checkdown), giving the QB a high-low read if the crossers are covered.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Shotgun with slot receivers', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 550, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 540, 'y' => 240]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 600, 'y' => 130]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 400]],
                ]),
                $this->phase(1, 'Route release', 'Crossers begin shallow routes', 1000, [
                    ['playerId' => 'WR3', 'position' => ['x' => 630, 'y' => 200], 'paths' => [$this->straightPath(600, 130, 630, 200)]],
                    ['playerId' => 'TE', 'position' => ['x' => 630, 'y' => 330], 'paths' => [$this->straightPath(600, 400, 630, 330)]],
                    ['playerId' => 'WR1', 'position' => ['x' => 670, 'y' => 30], 'paths' => [$this->straightPath(600, 30, 670, 30)]],
                ]),
                $this->phase(2, 'Mesh point', 'Crossers intersect at 5-6 yards depth', 1200, [
                    ['playerId' => 'WR3', 'position' => ['x' => 640, 'y' => 350], 'paths' => [$this->straightPath(630, 200, 640, 350)]],
                    ['playerId' => 'TE', 'position' => ['x' => 640, 'y' => 180], 'paths' => [$this->straightPath(630, 330, 640, 180)]],
                    ['playerId' => 'RB', 'position' => ['x' => 580, 'y' => 160], 'paths' => [$this->straightPath(540, 240, 580, 160)]],
                ]),
            ],
            'counters' => ['cover-two', 'zone-blitz'],
            'related' => ['slant-flat', 'four-verticals'],
            'ai_context' => "Mesh: Two shallow crossing routes at 5-6 yards crossing each other. Creates natural picks/rubs against man coverage — defenders get caught in traffic. Effective against both man (rub) and zone (finds windows). Includes vertical clear-out and flat checkdown. QB reads crossers first, then checkdown. Primary man-coverage beater.",
        ];
    }

    private function fourVerticals(): array
    {
        return [
            'slug' => 'four-verticals',
            'label' => 'Four Verticals',
            'category' => 'route-concept',
            'subcategory' => 'vertical',
            'tags' => ['pass', 'deep', 'vertical'],
            'difficulty' => 'intermediate',
            'layers' => [1, 2],
            'description' => 'Four receivers run vertical routes — stretches the deep coverage horizontally.',
            'explanation' => "Four Verticals sends four receivers straight down the field, spaced across the width of the field. This forces the deep defenders to cover the entire field vertically and horizontally simultaneously.\n\nAgainst Cover 2 (2 deep safeties), four verticals creates a 4-on-2 advantage deep — four receivers vs two safeties. The safeties cannot cover all four vertical threats.\n\nAgainst Cover 3 (3 deep), the four verticals creates windows between the deep defenders. Against Cover 4 (4 deep), the concept is matched, but the underneath coverage is weakened.\n\nThe QB reads the safeties: whichever receiver the safety doesn't cover gets the ball.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Spread formation with 4 receivers', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 550, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 540, 'y' => 292]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 600, 'y' => 130]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 400]],
                ]),
                $this->phase(1, 'Vertical stems', 'All 4 receivers push vertical', 1500, [
                    ['playerId' => 'WR1', 'position' => ['x' => 680, 'y' => 30], 'paths' => [$this->straightPath(600, 30, 680, 30)]],
                    ['playerId' => 'WR2', 'position' => ['x' => 680, 'y' => 503], 'paths' => [$this->straightPath(600, 503, 680, 503)]],
                    ['playerId' => 'WR3', 'position' => ['x' => 680, 'y' => 160], 'paths' => [$this->straightPath(600, 130, 680, 160)]],
                    ['playerId' => 'TE', 'position' => ['x' => 680, 'y' => 373], 'paths' => [$this->straightPath(600, 400, 680, 373)]],
                ]),
                $this->phase(2, 'Deep stretch', 'Verticals stretch deep coverage', 2000, [
                    ['playerId' => 'WR1', 'position' => ['x' => 780, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 780, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 780, 'y' => 180]],
                    ['playerId' => 'TE', 'position' => ['x' => 780, 'y' => 353]],
                ]),
            ],
            'counters' => ['cover-four', 'cover-one'],
            'related' => ['smash', 'mesh'],
            'ai_context' => "Four Verticals: 4 receivers run deep vertical routes spread across field width. Creates 4-on-2 advantage vs Cover 2 (two safeties can't cover four verticals). Stretches Cover 3 between thirds. Matched by Cover 4 but weakens underneath. QB reads safeties to find uncovered vertical. Requires strong-armed QB and time in the pocket.",
        ];
    }

    private function smash(): array
    {
        return [
            'slug' => 'smash',
            'label' => 'Smash Concept',
            'category' => 'route-concept',
            'subcategory' => 'high-low',
            'tags' => ['pass', 'zone-beater', 'high-low'],
            'difficulty' => 'intermediate',
            'layers' => [1, 2],
            'description' => 'High-low concept: hitch underneath with a corner route over the top — attacks the CB in Cover 2/3.',
            'explanation' => "The Smash concept is a two-man high-low read on the cornerback. The outside receiver runs a hitch (5-6 yards), while the slot receiver runs a corner route (12-15 yards) over the top.\n\nAgainst Cover 2, the cornerback must choose: jump the hitch (leaving the corner route open deep) or stay deep with the corner route (leaving the hitch open underneath). Either way, one receiver is open.\n\nAgainst Cover 3, the corner route attacks the seam between the CB's deep third and the safety's deep third. The hitch remains the checkdown.\n\nSmash is the quintessential zone-coverage beater and is a staple of every playbook.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Shotgun with paired receivers', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 550, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 540, 'y' => 240]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 600, 'y' => 120]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 344]],
                ]),
                $this->phase(1, 'Route development', 'WR1 hitches, WR3 runs corner route', 1500, [
                    ['playerId' => 'WR1', 'position' => ['x' => 640, 'y' => 30]],
                    ['playerId' => 'WR3', 'position' => ['x' => 660, 'y' => 80], 'paths' => [$this->quadraticPath(600, 120, 640, 100, 660, 80)]],
                ]),
                $this->phase(2, 'High-low read', 'QB reads CB — hitch or corner', 1500, [
                    ['playerId' => 'WR1', 'position' => ['x' => 640, 'y' => 30]],
                    ['playerId' => 'WR3', 'position' => ['x' => 720, 'y' => 50], 'paths' => [$this->straightPath(660, 80, 720, 50)]],
                ]),
            ],
            'counters' => ['cover-one', 'cover-four'],
            'related' => ['four-verticals', 'slant-flat'],
            'ai_context' => "Smash: High-low concept on the cornerback. Outside WR runs hitch (5-6 yards), slot WR runs corner route (12-15 yards). CB must choose: play hitch or corner — one is always open. Primary Cover 2 beater (CB in flat can't cover both). Also attacks Cover 3 seam between CB deep third and safety. QB reads CB's depth to determine throw.",
        ];
    }

    private function slantFlat(): array
    {
        return [
            'slug' => 'slant-flat',
            'label' => 'Slant-Flat',
            'category' => 'route-concept',
            'subcategory' => 'quick-game',
            'tags' => ['pass', 'quick-game', 'man-beater'],
            'difficulty' => 'beginner',
            'layers' => [1, 2],
            'description' => 'Quick slant inside with a flat route outside — a fast-release concept that beats press and blitz.',
            'explanation' => "Slant-Flat pairs a slant route (outside receiver breaking inside at 45 degrees) with a flat route (inside receiver or RB releasing to the sideline). This creates a high-low on the flat defender.\n\nAgainst man coverage and blitz, the slant is devastating — the receiver breaks inside where the defender has no help, and the ball comes out fast (before pressure arrives). The flat route is the checkdown if the slant is covered.\n\nAgainst zone, the QB reads the flat defender: if he sinks with the slant, throw the flat; if he stays in the flat, throw the slant.\n\nSlant-Flat is often the first play called against Cover 0 or heavy blitz because of its quick release and guaranteed open receiver.",
            'roster' => $this->offenseRoster(),
            'phases' => [
                $this->phase(0, 'Pre-snap', 'Shotgun with receivers to one side', 1000, [
                    ['playerId' => 'C', 'position' => ['x' => 600, 'y' => 266]],
                    ['playerId' => 'LG', 'position' => ['x' => 600, 'y' => 240]],
                    ['playerId' => 'RG', 'position' => ['x' => 600, 'y' => 292]],
                    ['playerId' => 'LT', 'position' => ['x' => 600, 'y' => 214]],
                    ['playerId' => 'RT', 'position' => ['x' => 600, 'y' => 318]],
                    ['playerId' => 'QB', 'position' => ['x' => 550, 'y' => 266]],
                    ['playerId' => 'RB', 'position' => ['x' => 540, 'y' => 240]],
                    ['playerId' => 'WR1', 'position' => ['x' => 600, 'y' => 30]],
                    ['playerId' => 'WR2', 'position' => ['x' => 600, 'y' => 503]],
                    ['playerId' => 'WR3', 'position' => ['x' => 600, 'y' => 120]],
                    ['playerId' => 'TE', 'position' => ['x' => 600, 'y' => 344]],
                ]),
                $this->phase(1, 'Quick release', 'WR1 slants inside, RB flares to flat', 800, [
                    ['playerId' => 'WR1', 'position' => ['x' => 630, 'y' => 80], 'paths' => [$this->straightPath(600, 30, 630, 80)]],
                    ['playerId' => 'RB', 'position' => ['x' => 570, 'y' => 160], 'paths' => [$this->straightPath(540, 240, 570, 160)]],
                ]),
                $this->phase(2, 'Throw', 'QB delivers to slant or flat based on read', 800, [
                    ['playerId' => 'WR1', 'position' => ['x' => 650, 'y' => 130]],
                    ['playerId' => 'RB', 'position' => ['x' => 590, 'y' => 80]],
                ]),
            ],
            'counters' => ['cover-two', 'cover-three'],
            'related' => ['mesh', 'smash'],
            'ai_context' => "Slant-Flat: Quick-release concept. Outside WR runs 45-degree slant inside, RB/slot flares to flat. High-low on flat defender. Against man/blitz: slant beats press inside with quick throw. Against zone: read flat defender — if he sinks, throw flat; if he stays, throw slant. First-call blitz beater. Ball out in under 2 seconds.",
        ];
    }

    // ─── Geometry ────────────────────────────────────────────────

    private function pursuitAngle(): array
    {
        return [
            'slug' => 'pursuit-angle',
            'label' => 'Pursuit Angle Triangle',
            'category' => 'geometry',
            'subcategory' => 'spatial-mechanics',
            'tags' => ['geometry', 'tackling', 'angles'],
            'difficulty' => 'beginner',
            'layers' => [3],
            'description' => 'The geometry of defensive pursuit — how defenders calculate angles to intercept ball carriers.',
            'explanation' => "The pursuit angle is the geometric path a defender takes to intercept a ball carrier running across the field. Rather than running directly at the ball carrier (which allows the runner to outrun the defender), the defender calculates an intercept point ahead of the runner.\n\nThe pursuit angle forms a triangle: the defender's position, the runner's current position, and the intercept point. The correct angle depends on: the relative speeds of the defender and runner, the runner's direction, and the distance between them.\n\nA too-flat angle (running parallel to the runner) results in the defender never catching up. A too-steep angle (running directly at the runner) allows the runner to change direction and escape. The correct pursuit angle intersects the runner's path at the earliest possible point.",
            'roster' => [
                ['id' => 'RB', 'role' => 'RB', 'label' => 'Ball Carrier', 'side' => 'offense'],
                ['id' => 'LB1', 'role' => 'LB', 'label' => 'Pursuing LB', 'side' => 'defense'],
            ],
            'phases' => [
                $this->phase(0, 'Setup', 'Ball carrier running across field, LB in pursuit', 1000, [
                    ['playerId' => 'RB', 'position' => ['x' => 600, 'y' => 200]],
                    ['playerId' => 'LB1', 'position' => ['x' => 650, 'y' => 350]],
                ], null, [[
                    'type' => 'geometry',
                    'id' => 'pursuit-triangle',
                    'label' => 'Pursuit Triangle',
                    'lines' => [
                        ['from' => ['x' => 650, 'y' => 350], 'to' => ['x' => 700, 'y' => 200], 'style' => ['color' => '#22C55E', 'width' => 2, 'opacity' => 0.8, 'arrowHead' => true]],
                        ['from' => ['x' => 600, 'y' => 200], 'to' => ['x' => 700, 'y' => 200], 'style' => ['color' => '#3B82F6', 'width' => 2, 'opacity' => 0.8, 'arrowHead' => true]],
                        ['from' => ['x' => 650, 'y' => 350], 'to' => ['x' => 600, 'y' => 200], 'style' => ['color' => '#EF4444', 'width' => 2, 'dashArray' => [8, 4], 'opacity' => 0.5]],
                    ],
                ]]),
                $this->phase(1, 'Correct angle', 'LB takes pursuit angle to intercept point', 1500, [
                    ['playerId' => 'RB', 'position' => ['x' => 650, 'y' => 200], 'paths' => [$this->straightPath(600, 200, 650, 200)]],
                    ['playerId' => 'LB1', 'position' => ['x' => 670, 'y' => 280], 'paths' => [$this->straightPath(650, 350, 670, 280)]],
                ]),
                $this->phase(2, 'Intercept', 'Paths converge at intercept point', 1200, [
                    ['playerId' => 'RB', 'position' => ['x' => 700, 'y' => 200]],
                    ['playerId' => 'LB1', 'position' => ['x' => 700, 'y' => 210]],
                ], null, null, [
                    ['id' => 'intercept', 'position' => ['x' => 700, 'y' => 170], 'text' => 'Intercept Point', 'style' => 'callout', 'color' => '#22C55E'],
                ]),
            ],
            'counters' => [],
            'related' => [],
            'ai_context' => "Pursuit Angle: The geometric path a defender takes to intercept a ball carrier. Forms a triangle: defender position, runner position, intercept point. Too flat = never catches up. Too steep = runner changes direction and escapes. Correct angle intersects runner's path at earliest point. Depends on relative speed, direction, and distance. Fundamental defensive geometry concept.",
        ];
    }

    // ─── Roster Helpers ──────────────────────────────────────────

    private function offenseRoster(): array
    {
        return [
            ['id' => 'QB', 'role' => 'QB', 'label' => 'QB', 'side' => 'offense'],
            ['id' => 'RB', 'role' => 'RB', 'label' => 'RB', 'side' => 'offense'],
            ['id' => 'WR1', 'role' => 'WR', 'label' => 'X', 'side' => 'offense'],
            ['id' => 'WR2', 'role' => 'WR', 'label' => 'Z', 'side' => 'offense'],
            ['id' => 'WR3', 'role' => 'WR', 'label' => 'Y', 'side' => 'offense'],
            ['id' => 'TE', 'role' => 'TE', 'label' => 'TE', 'side' => 'offense'],
            ['id' => 'LT', 'role' => 'OL', 'label' => 'LT', 'side' => 'offense'],
            ['id' => 'LG', 'role' => 'OL', 'label' => 'LG', 'side' => 'offense'],
            ['id' => 'C', 'role' => 'OL', 'label' => 'C', 'side' => 'offense'],
            ['id' => 'RG', 'role' => 'OL', 'label' => 'RG', 'side' => 'offense'],
            ['id' => 'RT', 'role' => 'OL', 'label' => 'RT', 'side' => 'offense'],
        ];
    }

    private function defenseRoster43(): array
    {
        return [
            ['id' => 'DL1', 'role' => 'DL', 'label' => 'LE', 'side' => 'defense'],
            ['id' => 'DL2', 'role' => 'DL', 'label' => 'DT', 'side' => 'defense'],
            ['id' => 'DL3', 'role' => 'DL', 'label' => 'DT', 'side' => 'defense'],
            ['id' => 'DL4', 'role' => 'DL', 'label' => 'RE', 'side' => 'defense'],
            ['id' => 'LB1', 'role' => 'LB', 'label' => 'WILL', 'side' => 'defense'],
            ['id' => 'LB2', 'role' => 'LB', 'label' => 'MIKE', 'side' => 'defense'],
            ['id' => 'LB3', 'role' => 'LB', 'label' => 'SAM', 'side' => 'defense'],
            ['id' => 'CB1', 'role' => 'CB', 'label' => 'LCB', 'side' => 'defense'],
            ['id' => 'CB2', 'role' => 'CB', 'label' => 'RCB', 'side' => 'defense'],
            ['id' => 'FS', 'role' => 'S', 'label' => 'FS', 'side' => 'defense'],
            ['id' => 'SS', 'role' => 'S', 'label' => 'SS', 'side' => 'defense'],
        ];
    }

    private function defenseRoster34(): array
    {
        return [
            ['id' => 'DL1', 'role' => 'DL', 'label' => 'DE', 'side' => 'defense'],
            ['id' => 'DL2', 'role' => 'DL', 'label' => 'NT', 'side' => 'defense'],
            ['id' => 'DL3', 'role' => 'DL', 'label' => 'DE', 'side' => 'defense'],
            ['id' => 'LB1', 'role' => 'LB', 'label' => 'LOLB', 'side' => 'defense'],
            ['id' => 'LB2', 'role' => 'LB', 'label' => 'LILB', 'side' => 'defense'],
            ['id' => 'LB3', 'role' => 'LB', 'label' => 'RILB', 'side' => 'defense'],
            ['id' => 'LB4', 'role' => 'LB', 'label' => 'ROLB', 'side' => 'defense'],
            ['id' => 'CB1', 'role' => 'CB', 'label' => 'LCB', 'side' => 'defense'],
            ['id' => 'CB2', 'role' => 'CB', 'label' => 'RCB', 'side' => 'defense'],
            ['id' => 'FS', 'role' => 'S', 'label' => 'FS', 'side' => 'defense'],
            ['id' => 'SS', 'role' => 'S', 'label' => 'SS', 'side' => 'defense'],
        ];
    }

    private function defenseRosterNickel(): array
    {
        return [
            ['id' => 'DL1', 'role' => 'DL', 'label' => 'LE', 'side' => 'defense'],
            ['id' => 'DL2', 'role' => 'DL', 'label' => 'DT', 'side' => 'defense'],
            ['id' => 'DL3', 'role' => 'DL', 'label' => 'DT', 'side' => 'defense'],
            ['id' => 'DL4', 'role' => 'DL', 'label' => 'RE', 'side' => 'defense'],
            ['id' => 'LB1', 'role' => 'LB', 'label' => 'WILL', 'side' => 'defense'],
            ['id' => 'LB2', 'role' => 'LB', 'label' => 'MIKE', 'side' => 'defense'],
            ['id' => 'CB1', 'role' => 'CB', 'label' => 'LCB', 'side' => 'defense'],
            ['id' => 'CB2', 'role' => 'CB', 'label' => 'RCB', 'side' => 'defense'],
            ['id' => 'NCB', 'role' => 'CB', 'label' => 'NCB', 'side' => 'defense'],
            ['id' => 'FS', 'role' => 'S', 'label' => 'FS', 'side' => 'defense'],
            ['id' => 'SS', 'role' => 'S', 'label' => 'SS', 'side' => 'defense'],
        ];
    }

    private function defenseRosterDime(): array
    {
        return [
            ['id' => 'DL1', 'role' => 'DL', 'label' => 'LE', 'side' => 'defense'],
            ['id' => 'DL2', 'role' => 'DL', 'label' => 'DT', 'side' => 'defense'],
            ['id' => 'DL3', 'role' => 'DL', 'label' => 'DT', 'side' => 'defense'],
            ['id' => 'DL4', 'role' => 'DL', 'label' => 'RE', 'side' => 'defense'],
            ['id' => 'LB1', 'role' => 'LB', 'label' => 'MIKE', 'side' => 'defense'],
            ['id' => 'CB1', 'role' => 'CB', 'label' => 'LCB', 'side' => 'defense'],
            ['id' => 'CB2', 'role' => 'CB', 'label' => 'RCB', 'side' => 'defense'],
            ['id' => 'NCB', 'role' => 'CB', 'label' => 'NCB', 'side' => 'defense'],
            ['id' => 'DCB', 'role' => 'CB', 'label' => 'DCB', 'side' => 'defense'],
            ['id' => 'FS', 'role' => 'S', 'label' => 'FS', 'side' => 'defense'],
            ['id' => 'SS', 'role' => 'S', 'label' => 'SS', 'side' => 'defense'],
        ];
    }

    // ─── Phase & Path Helpers ────────────────────────────────────

    private function phase(int $id, string $label, string $description, int $durationMs, array $players, ?array $ball = null, ?array $overlays = null, ?array $annotations = null): array
    {
        $phase = [
            'id' => $id,
            'label' => $label,
            'description' => $description,
            'durationMs' => $durationMs,
            'players' => $players,
        ];

        if ($ball !== null) {
            $phase['ball'] = $ball;
        }
        if ($overlays !== null) {
            $phase['overlays'] = $overlays;
        }
        if ($annotations !== null) {
            $phase['annotations'] = $annotations;
        }

        return $phase;
    }

    private function straightPath(int $fromX, int $fromY, int $toX, int $toY): array
    {
        return [
            'type' => 'straight',
            'from' => ['x' => $fromX, 'y' => $fromY],
            'to' => ['x' => $toX, 'y' => $toY],
            'style' => ['color' => '#FFFFFF', 'width' => 2, 'opacity' => 0.8, 'arrowHead' => true],
        ];
    }

    private function quadraticPath(int $fromX, int $fromY, int $ctrlX, int $ctrlY, int $toX, int $toY): array
    {
        return [
            'type' => 'quadratic',
            'from' => ['x' => $fromX, 'y' => $fromY],
            'control' => ['x' => $ctrlX, 'y' => $ctrlY],
            'to' => ['x' => $toX, 'y' => $toY],
            'style' => ['color' => '#FFFFFF', 'width' => 2, 'opacity' => 0.8, 'arrowHead' => true],
        ];
    }
}

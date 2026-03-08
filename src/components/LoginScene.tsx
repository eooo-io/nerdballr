/**
 * Background SVG scene from the login mockup — stadium at night with
 * player tokens and route paths animating in.
 */
export function LoginScene() {
  return (
    <div className="fixed inset-0 z-0">
      <svg
        viewBox="0 0 1600 900"
        xmlns="http://www.w3.org/2000/svg"
        preserveAspectRatio="xMidYMid slice"
        className="w-full h-full block"
      >
        <defs>
          <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#020810" />
            <stop offset="45%" stopColor="#050f22" />
            <stop offset="100%" stopColor="#081630" />
          </linearGradient>
          <linearGradient id="fieldGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#061a0e" />
            <stop offset="40%" stopColor="#082010" />
            <stop offset="100%" stopColor="#0d2e17" />
          </linearGradient>
          <linearGradient id="stripeA" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#072114" />
            <stop offset="100%" stopColor="#0a2918" />
          </linearGradient>
          <linearGradient id="stripeB" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#061c11" />
            <stop offset="100%" stopColor="#082014" />
          </linearGradient>
          <radialGradient id="fieldLight" cx="50%" cy="0%" r="70%">
            <stop offset="0%" stopColor="#1a5c28" stopOpacity="0.8" />
            <stop offset="100%" stopColor="#061a0e" stopOpacity="0" />
          </radialGradient>
          <radialGradient id="horizonGlow" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stopColor="#004466" stopOpacity="0.6" />
            <stop offset="100%" stopColor="#001122" stopOpacity="0" />
          </radialGradient>
          <radialGradient id="crowdGlowL" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stopColor="#ff6b00" stopOpacity="0.2" />
            <stop offset="100%" stopColor="#ff6b00" stopOpacity="0" />
          </radialGradient>
          <radialGradient id="crowdGlowR" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stopColor="#00a0ff" stopOpacity="0.17" />
            <stop offset="100%" stopColor="#00a0ff" stopOpacity="0" />
          </radialGradient>
          <radialGradient id="lightFlareL" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stopColor="#ffe8a0" stopOpacity="0.95" />
            <stop offset="40%" stopColor="#ffb800" stopOpacity="0.3" />
            <stop offset="100%" stopColor="#ffb800" stopOpacity="0" />
          </radialGradient>
          <radialGradient id="lightFlareR" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stopColor="#ffe8a0" stopOpacity="0.95" />
            <stop offset="40%" stopColor="#ffb800" stopOpacity="0.3" />
            <stop offset="100%" stopColor="#ffb800" stopOpacity="0" />
          </radialGradient>
          <filter id="starBlur"><feGaussianBlur stdDeviation="0.4" /></filter>
          <filter id="lightGlow" x="-200%" y="-200%" width="500%" height="500%">
            <feGaussianBlur stdDeviation="14" />
          </filter>
          <filter id="softGlow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="3" result="blur" />
            <feComposite in="SourceGraphic" in2="blur" operator="over" />
          </filter>
          <filter id="tokenGlow" x="-100%" y="-100%" width="300%" height="300%">
            <feGaussianBlur stdDeviation="3.5" result="blur" />
            <feComposite in="SourceGraphic" in2="blur" operator="over" />
          </filter>
          <marker id="arrowCyan" markerWidth="7" markerHeight="7" refX="5" refY="3.5" orient="auto">
            <polygon points="0 0,6 3.5,0 7" fill="#00e5ff" opacity="0.9" />
          </marker>
          <marker id="arrowAmber" markerWidth="7" markerHeight="7" refX="5" refY="3.5" orient="auto">
            <polygon points="0 0,6 3.5,0 7" fill="#ffb800" opacity="0.9" />
          </marker>
          <marker id="arrowRed" markerWidth="7" markerHeight="7" refX="5" refY="3.5" orient="auto">
            <polygon points="0 0,6 3.5,0 7" fill="#ef4444" opacity="0.8" />
          </marker>
          <marker id="arrowGreen" markerWidth="7" markerHeight="7" refX="5" refY="3.5" orient="auto">
            <polygon points="0 0,6 3.5,0 7" fill="#22c55e" opacity="0.8" />
          </marker>
          <clipPath id="fieldClip">
            <polygon points="0,900 1600,900 1600,440 800,330 0,440" />
          </clipPath>
          <linearGradient id="bottomFade" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#060c17" stopOpacity="0" />
            <stop offset="100%" stopColor="#060c17" stopOpacity="0.6" />
          </linearGradient>
          <linearGradient id="topFade" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#020810" stopOpacity="0.75" />
            <stop offset="100%" stopColor="#020810" stopOpacity="0" />
          </linearGradient>
          <linearGradient id="leftFade" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stopColor="#020810" stopOpacity="0.55" />
            <stop offset="100%" stopColor="#020810" stopOpacity="0" />
          </linearGradient>
          <linearGradient id="rightFade" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stopColor="#020810" stopOpacity="0" />
            <stop offset="100%" stopColor="#020810" stopOpacity="0.55" />
          </linearGradient>
          <radialGradient id="centreVeil" cx="50%" cy="55%" r="35%">
            <stop offset="0%" stopColor="#020810" stopOpacity="0.55" />
            <stop offset="100%" stopColor="#020810" stopOpacity="0" />
          </radialGradient>
        </defs>

        {/* Sky */}
        <rect width="1600" height="900" fill="url(#skyGrad)" />

        {/* Stars */}
        <g filter="url(#starBlur)" opacity="0.7">
          <circle cx="120" cy="40" r="1.2" fill="white" opacity="0.8" />
          <circle cx="280" cy="80" r="0.8" fill="white" opacity="0.6" />
          <circle cx="440" cy="30" r="1.0" fill="white" opacity="0.7" />
          <circle cx="600" cy="60" r="0.6" fill="white" opacity="0.5" />
          <circle cx="750" cy="20" r="1.1" fill="white" opacity="0.8" />
          <circle cx="950" cy="50" r="0.9" fill="white" opacity="0.6" />
          <circle cx="1100" cy="25" r="1.3" fill="white" opacity="0.7" />
          <circle cx="1280" cy="70" r="0.7" fill="white" opacity="0.6" />
          <circle cx="1450" cy="35" r="1.0" fill="white" opacity="0.8" />
          <circle cx="200" cy="120" r="0.6" fill="white" opacity="0.4" />
          <circle cx="380" cy="100" r="0.8" fill="white" opacity="0.5" />
          <circle cx="560" cy="140" r="1.0" fill="white" opacity="0.6" />
          <circle cx="860" cy="110" r="0.7" fill="white" opacity="0.5" />
          <circle cx="1020" cy="90" r="0.9" fill="white" opacity="0.7" />
          <circle cx="1320" cy="130" r="0.6" fill="white" opacity="0.4" />
          <circle cx="1500" cy="95" r="1.1" fill="white" opacity="0.6" />
          <circle cx="70" cy="200" r="0.7" fill="white" opacity="0.3" />
          <circle cx="680" cy="165" r="0.8" fill="white" opacity="0.5" />
          <circle cx="1150" cy="190" r="0.6" fill="white" opacity="0.3" />
        </g>

        {/* Horizon glow */}
        <ellipse cx="800" cy="345" rx="700" ry="80" fill="url(#horizonGlow)" />

        {/* Stadium */}
        <path d="M260,370 L1340,370 L1340,330 L800,295 L260,330 Z" fill="#0a1a2e" stroke="#0d2244" strokeWidth="1" />
        <path d="M0,420 L380,370 L380,290 L0,270 Z" fill="#071428" stroke="#0d2244" strokeWidth="0.5" />
        <g opacity="0.35" stroke="#1a3a5c" strokeWidth="0.5">
          {[278,285,292,299,306,313,320,327,334,341,348].map(y => (
            <line key={`ul-${y}`} x1="0" y1={y} x2="380" y2={y + 19} />
          ))}
        </g>
        <path d="M1600,420 L1220,370 L1220,290 L1600,270 Z" fill="#071428" stroke="#0d2244" strokeWidth="0.5" />
        <g opacity="0.35" stroke="#1a3a5c" strokeWidth="0.5">
          {[278,285,292,299,306,313,320,327,334,341].map(y => (
            <line key={`ur-${y}`} x1="1600" y1={y} x2="1220" y2={y + 19} />
          ))}
        </g>
        <path d="M0,530 L420,440 L380,370 L0,420 Z" fill="#0c1e38" stroke="#0d2244" strokeWidth="0.5" />
        <path d="M1600,530 L1180,440 L1220,370 L1600,420 Z" fill="#0c1e38" stroke="#0d2244" strokeWidth="0.5" />
        <path d="M380,370 L1220,370 L1220,290 L800,260 L380,290 Z" fill="#091729" stroke="#0d2244" strokeWidth="0.5" />

        {/* Crowd glow */}
        <ellipse cx="100" cy="450" rx="220" ry="100" fill="url(#crowdGlowL)" opacity="0.7" />
        <ellipse cx="1500" cy="450" rx="220" ry="100" fill="url(#crowdGlowR)" opacity="0.7" />

        {/* Light towers */}
        <rect x="340" y="200" width="8" height="100" fill="#0d2244" />
        <rect x="330" y="195" width="28" height="6" fill="#1a3a5a" />
        <ellipse cx="344" cy="194" rx="18" ry="5" fill="url(#lightFlareL)" filter="url(#lightGlow)" opacity="0.9" />
        <rect x="1252" y="200" width="8" height="100" fill="#0d2244" />
        <rect x="1242" y="195" width="28" height="6" fill="#1a3a5a" />
        <ellipse cx="1256" cy="194" rx="18" ry="5" fill="url(#lightFlareR)" filter="url(#lightGlow)" opacity="0.9" />

        {/* Field surface */}
        <polygon points="0,900 1600,900 1600,440 800,330 0,440" fill="url(#fieldGrad)" />
        <polygon points="0,900 1600,900 1600,440 800,330 0,440" fill="url(#fieldLight)" />

        {/* Mow stripes */}
        <g opacity="0.55">
          <polygon points="0,900 160,900 152,440 0,440" fill="url(#stripeA)" />
          <polygon points="160,900 320,900 304,440 152,440" fill="url(#stripeB)" />
          <polygon points="320,900 480,900 456,440 304,440" fill="url(#stripeA)" />
          <polygon points="480,900 640,900 608,440 456,440" fill="url(#stripeB)" />
          <polygon points="640,900 800,900 760,440 608,440" fill="url(#stripeA)" />
          <polygon points="800,900 960,900 840,440 760,440" fill="url(#stripeB)" />
          <polygon points="960,900 1120,900 1040,440 840,440" fill="url(#stripeA)" />
          <polygon points="1120,900 1280,900 1144,440 1040,440" fill="url(#stripeB)" />
          <polygon points="1280,900 1440,900 1296,440 1144,440" fill="url(#stripeA)" />
          <polygon points="1440,900 1600,900 1600,440 1296,440" fill="url(#stripeB)" />
        </g>

        {/* Yard lines */}
        <g stroke="rgba(255,255,255,0.11)" strokeWidth="1" fill="none">
          {[460,490,520,555,595,640,690,748,816].map(y => (
            <line key={`yl-${y}`} x1="0" y1={y} x2="1600" y2={y} />
          ))}
        </g>

        <g clipPath="url(#fieldClip)">
          {/* Centre logo */}
          <ellipse cx="800" cy="700" rx="220" ry="70" fill="none" stroke="rgba(0,229,255,0.06)" strokeWidth="1.5" />
          <text x="800" y="715" fontFamily="monospace" fontSize="40" fontWeight="bold" fill="rgba(0,229,255,0.05)" textAnchor="middle">NB</text>

          {/* LOS */}
          <line x1="0" y1="690" x2="1600" y2="690" stroke="#ffb800" strokeWidth="1.5" opacity="0.35" />
          <line x1="0" y1="690" x2="1600" y2="690" stroke="#ffb800" strokeWidth="6" opacity="0.07" filter="url(#softGlow)" />
          <line x1="0" y1="640" x2="1600" y2="640" stroke="#00e5ff" strokeWidth="1" strokeDasharray="8,6" opacity="0.2" />
        </g>

        {/* Players + routes */}
        <g clipPath="url(#fieldClip)">
          {/* OL */}
          <g className="token-pulse t2">
            {[675,720,765,810,855].map(x => (
              <rect key={`ol-${x}`} x={x} y={681} width="20" height="20" rx="2" fill="#9ca3af" stroke="#4b5563" strokeWidth="1.5" filter="url(#tokenGlow)" />
            ))}
          </g>
          {/* QB */}
          <g className="token-pulse t3" filter="url(#tokenGlow)">
            <circle cx="775" cy="735" r="13" fill="#3b82f6" stroke="#1d4ed8" strokeWidth="2" />
          </g>
          {/* RB */}
          <g className="token-pulse t4" filter="url(#tokenGlow)">
            <rect x="758" y="760" width="22" height="22" rx="2" fill="#22c55e" stroke="#15803d" strokeWidth="1.5" />
          </g>
          {/* WRs */}
          <g className="token-pulse t5" filter="url(#tokenGlow)">
            <polygon points="420,671 435,696 405,696" fill="#eab308" stroke="#a16207" strokeWidth="1.5" />
          </g>
          <g className="token-pulse t6" filter="url(#tokenGlow)">
            <polygon points="600,676 615,701 585,701" fill="#eab308" stroke="#a16207" strokeWidth="1.5" />
          </g>
          {/* TE */}
          <g className="token-pulse t7" filter="url(#tokenGlow)">
            <polygon points="915,691 928,704 915,717 902,704" fill="#ef4444" stroke="#b91c1c" strokeWidth="1.5" />
          </g>
          {/* WR Z */}
          <g className="token-pulse t8" filter="url(#tokenGlow)">
            <polygon points="1145,671 1160,696 1130,696" fill="#eab308" stroke="#a16207" strokeWidth="1.5" />
          </g>
          {/* DL */}
          <g className="token-pulse t9">
            {[700,745,820,865].map(x => (
              <rect key={`dl-${x}`} x={x} y={659} width="18" height="18" rx="2" fill="#374151" stroke="#111827" strokeWidth="1.5" filter="url(#tokenGlow)" />
            ))}
          </g>
          {/* LBs */}
          <g className="token-pulse t10">
            {[680,769,870].map(x => (
              <g key={`lb-${x}`} filter="url(#tokenGlow)">
                <rect x={x} y={628} width="20" height="20" rx="2" fill="#a855f7" stroke="#7e22ce" strokeWidth="1.5" />
              </g>
            ))}
          </g>
          {/* CBs */}
          <g className="token-pulse t11">
            <g filter="url(#tokenGlow)">
              <circle cx="420" cy="637" r="12" fill="#ef4444" stroke="#b91c1c" strokeWidth="1.5" />
            </g>
            <g filter="url(#tokenGlow)">
              <circle cx="1145" cy="637" r="12" fill="#ef4444" stroke="#b91c1c" strokeWidth="1.5" />
            </g>
          </g>
          {/* Safeties */}
          <g className="token-pulse t11">
            <g filter="url(#tokenGlow)">
              <circle cx="800" cy="540" r="13" fill="#f97316" stroke="#c2410c" strokeWidth="1.8" />
            </g>
            <g filter="url(#tokenGlow)">
              <circle cx="1000" cy="567" r="12" fill="#f97316" stroke="#c2410c" strokeWidth="1.5" />
            </g>
          </g>

          {/* Routes */}
          <path className="route-path d2" d="M420,671 Q420,620 480,600 Q560,575 620,520" stroke="#eab308" strokeWidth="2" fill="none" opacity="0.85" markerEnd="url(#arrowAmber)" />
          <path className="route-path d3" d="M600,676 Q600,645 640,640 Q720,635 820,640" stroke="#eab308" strokeWidth="2" fill="none" opacity="0.8" markerEnd="url(#arrowAmber)" />
          <path className="route-path d4" d="M915,691 Q950,680 980,660 Q1010,640 1020,620" stroke="#ef4444" strokeWidth="2" fill="none" opacity="0.8" markerEnd="url(#arrowRed)" />
          <path className="route-path d5" d="M1145,671 Q1145,620 1100,590 Q1070,570 1090,610" stroke="#eab308" strokeWidth="2" fill="none" opacity="0.85" markerEnd="url(#arrowAmber)" />
          <path className="route-path d6" d="M769,771 Q720,770 680,750 Q660,740 650,725" stroke="#22c55e" strokeWidth="2" fill="none" opacity="0.7" strokeDasharray="6,4" markerEnd="url(#arrowGreen)" />

          {/* Coverage zone */}
          <polygon points="500,460 1100,460 1100,530 800,560 500,530" fill="#f97316" fillOpacity="0.04" stroke="#f97316" strokeWidth="0.5" strokeOpacity="0.14" />
          <ellipse cx="800" cy="540" rx="90" ry="40" fill="none" stroke="#f97316" strokeWidth="1" strokeDasharray="4,4" opacity="0.25" />
        </g>

        {/* Atmospheric overlays */}
        <polygon points="0,900 1600,900 1600,440 800,330 0,440" fill="url(#fieldLight)" opacity="0.35" />
        <rect x="0" y="700" width="1600" height="200" fill="url(#bottomFade)" />
        <rect x="0" y="0" width="1600" height="180" fill="url(#topFade)" />
        <rect x="0" y="0" width="200" height="900" fill="url(#leftFade)" />
        <rect x="1400" y="0" width="200" height="900" fill="url(#rightFade)" />
        <rect width="1600" height="900" fill="url(#centreVeil)" />
      </svg>
    </div>
  );
}

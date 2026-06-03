<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🎰 Raffle Draw System</title>
<style>
  :root {
    --gold:    #FFD700;
    --gold2:   #FFA500;
    --darker:  #050510;
    --card-bg: rgba(255,255,255,0.05);
    --border:  rgba(255,215,0,0.3);
    --item-h:  72px;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    background: var(--darker);
    color: #fff;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
  }

  /* ── BG GLOW ── */
  #bg-glow {
    position: fixed; inset: 0; pointer-events: none; z-index: 0;
    background:
      radial-gradient(ellipse at 20% 50%, rgba(120,0,255,.09) 0%, transparent 60%),
      radial-gradient(ellipse at 80% 20%, rgba(255,100,0,.07) 0%, transparent 50%),
      var(--darker);
  }

  .wrapper { position: relative; z-index: 1; max-width: 1300px; margin: 0 auto; padding: 20px 24px; }

  /* ── TOPBAR ── */
  .topbar {
    display: flex; align-items: center; justify-content: space-between; padding-bottom: 20px;
  }
  .brand {
    font-size: 1rem; font-weight: 900; letter-spacing: 3px;
    background: linear-gradient(135deg, #fff 0%, var(--gold) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    text-decoration: none;
  }
  .nav-links { display: flex; gap: 8px; }
  .nav-link {
    padding: 7px 16px; border-radius: 8px; font-size: .82rem;
    font-weight: 600; letter-spacing: .5px; text-decoration: none;
    border: 1px solid transparent; transition: all .2s;
  }
  .nav-link.active { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: #000; }
  .nav-link:not(.active) { color: rgba(255,255,255,.55); border-color: rgba(255,255,255,.15); }
  .nav-link:not(.active):hover { color: #fff; border-color: rgba(255,255,255,.4); }

  /* ── HEADER ── */
  header { text-align: center; padding: 10px 0 26px; }
  header h1 {
    font-size: clamp(2.2rem, 5vw, 4rem);
    font-weight: 900; letter-spacing: 5px;
    background: linear-gradient(135deg, #fff 0%, var(--gold) 50%, var(--gold2) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 0 30px rgba(255,215,0,.5));
  }
  header p { color: rgba(255,255,255,.4); margin-top: 6px; letter-spacing: 3px; font-size: .8rem; }

  /* ── ALERTS ── */
  .alert {
    padding: 11px 16px; border-radius: 10px; font-size: .88rem;
    margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
  }
  .alert-success { background: rgba(46,213,115,.1); border: 1px solid rgba(46,213,115,.3); color: #2ed573; }
  .alert-error   { background: rgba(255,71,87,.1);  border: 1px solid rgba(255,71,87,.3);  color: #ff4757; }

  /* ── GRID ── */
  .grid { display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: start; }
  @media(max-width:900px) { .grid { grid-template-columns: 1fr; } }

  /* ── PANEL ── */
  .panel {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 18px; padding: 24px; backdrop-filter: blur(12px);
  }
  .panel-title {
    font-size: .68rem; letter-spacing: 3px; color: var(--gold);
    text-transform: uppercase; margin-bottom: 16px; opacity: .8;
  }

  /* ── STATS ── */
  .stats { display: flex; gap: 10px; margin-bottom: 18px; }
  .stat-box {
    flex: 1; text-align: center; padding: 14px 8px;
    background: rgba(255,255,255,.04); border-radius: 12px;
    border: 1px solid rgba(255,255,255,.07);
  }
  .stat-box .val { font-size: 1.8rem; font-weight: 900; color: var(--gold); line-height: 1; }
  .stat-box .lbl { font-size: .6rem; color: rgba(255,255,255,.4); letter-spacing: 2px; margin-top: 4px; }

  /* ── SLOT MACHINE ── */
  .slot-outer { position: relative; }

  .btn-fullscreen {
    position: absolute; top: 12px; right: 12px; z-index: 10;
    background: rgba(0,0,0,.5); border: 1px solid rgba(255,215,0,.4);
    color: var(--gold); border-radius: 8px; padding: 7px 13px;
    font-size: .82rem; cursor: pointer; transition: all .2s;
    backdrop-filter: blur(6px); font-weight: 600; letter-spacing: .5px;
  }
  .btn-fullscreen:hover { background: rgba(255,215,0,.15); border-color: var(--gold); }

  .slot-wrapper {
    position: relative; width: 100%; height: 520px;
    border-radius: 14px; overflow: hidden;
    border: 2px solid var(--border); background: rgba(0,0,0,.55);
  }
  .slot-wrapper::before,
  .slot-wrapper::after {
    content: ''; position: absolute; left: 0; right: 0; height: 120px;
    z-index: 3; pointer-events: none;
  }
  .slot-wrapper::before { top:    0; background: linear-gradient(to bottom, rgba(5,5,16,1), transparent); }
  .slot-wrapper::after  { bottom: 0; background: linear-gradient(to top,   rgba(5,5,16,1), transparent); }

  .slot-highlight {
    position: absolute; top: 50%; left: 0; right: 0;
    transform: translateY(-50%); height: var(--item-h);
    border-top: 2px solid var(--gold); border-bottom: 2px solid var(--gold);
    background: rgba(255,215,0,.05); z-index: 2; pointer-events: none;
    box-shadow: 0 0 30px rgba(255,215,0,.15) inset;
  }

  .slot-track {
    position: absolute; top: 0; left: 0; right: 0;
    display: flex; flex-direction: column; align-items: center;
    will-change: transform;
  }

  .slot-item {
    width: 100%; height: var(--item-h);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 700; letter-spacing: 1px;
    color: #fff; flex-shrink: 0; padding: 0 80px;
    text-align: center; transition: color .1s;
  }
  .slot-item.won-item { color: rgba(255,255,255,.2); text-decoration: line-through; }

  /* ── WINNER BOX ── */
  #winner-box {
    display: none; text-align: center; margin-top: 20px; padding: 22px 24px;
    border-radius: 14px;
    background: linear-gradient(135deg, rgba(255,215,0,.14), rgba(255,100,0,.08));
    border: 2px solid var(--gold);
    animation: winnerGlow 1s ease-in-out infinite alternate;
  }
  @keyframes winnerGlow {
    from { box-shadow: 0 0 20px rgba(255,215,0,.3); }
    to   { box-shadow: 0 0 60px rgba(255,215,0,.75), 0 0 100px rgba(255,100,0,.25); }
  }
  #winner-box .trophy { font-size: 2.8rem; margin-bottom: 6px; }
  #winner-box .wlabel { font-size: .68rem; letter-spacing: 4px; color: var(--gold); opacity: .8; }
  #winner-name {
    font-size: clamp(1.6rem, 3.5vw, 2.4rem); font-weight: 900;
    letter-spacing: 2px; color: var(--gold); margin-top: 6px;
    text-shadow: 0 0 40px rgba(255,215,0,.9);
  }
  #winner-extra { font-size: .88rem; color: rgba(255,255,255,.5); margin-top: 5px; }

  /* ── BUTTONS ── */
  .btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 15px 28px; border: none; border-radius: 11px;
    font-size: 1.05rem; font-weight: 700; letter-spacing: 1px;
    cursor: pointer; transition: all .2s; width: 100%; margin-top: 14px;
  }
  .btn-spin {
    background: linear-gradient(135deg, var(--gold), var(--gold2));
    color: #000; box-shadow: 0 4px 24px rgba(255,215,0,.45); font-size: 1.2rem;
  }
  .btn-spin:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 36px rgba(255,215,0,.65); }
  .btn-spin:disabled { opacity: .4; cursor: not-allowed; transform: none; }
  .btn-sm { font-size: .82rem; padding: 9px 16px; margin-top: 0; width: auto; border-radius: 8px; }
  .btn-outline { background: transparent; color: rgba(255,255,255,.55); border: 1px solid rgba(255,255,255,.2); }
  .btn-outline:hover { border-color: rgba(255,255,255,.5); color: #fff; }
  .btn-danger { background: transparent; color: #ff6b6b; border: 1px solid rgba(255,107,107,.3); }
  .btn-danger:hover { background: rgba(255,107,107,.1); border-color: #ff6b6b; }

  /* ── SIDEBAR PARTICIPANTS ── */
  .entries-list {
    max-height: 460px; overflow-y: auto; margin-top: 4px;
    scrollbar-width: thin; scrollbar-color: var(--gold) transparent;
  }
  .entries-list::-webkit-scrollbar { width: 3px; }
  .entries-list::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 4px; }

  .entry-item {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 8px; font-size: .86rem; transition: background .15s;
  }
  .entry-item:hover { background: rgba(255,255,255,.04); }
  .entry-item .num { font-size: .68rem; color: rgba(255,255,255,.28); min-width: 24px; }
  .entry-item .ename { flex: 1; }
  .entry-item .badge-won {
    font-size: .62rem; padding: 2px 7px; border-radius: 20px;
    background: rgba(255,215,0,.12); color: var(--gold); border: 1px solid rgba(255,215,0,.3);
  }
  .entry-item.won .ename { color: rgba(255,255,255,.3); text-decoration: line-through; }

  /* ── CONFETTI ── */
  .confetti-piece {
    position: fixed; top: -10px; pointer-events: none; z-index: 9999;
    border-radius: 2px; animation: confettiFall linear forwards;
  }
  @keyframes confettiFall {
    to { transform: translateY(110vh) rotate(720deg); opacity: 0; }
  }

  /* ════════════════════════
     FULLSCREEN OVERLAY
  ════════════════════════ */
  body.is-fullscreen { overflow: hidden; }

  #fs-overlay {
    display: none; position: fixed; inset: 0; z-index: 500;
    background: var(--darker);
    flex-direction: column; align-items: center; justify-content: center;
    padding: 20px;
    gap: 0;
  }
  body.is-fullscreen #fs-overlay { display: flex; }

  #fs-overlay::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background:
      radial-gradient(ellipse at 15% 50%, rgba(120,0,255,.14) 0%, transparent 55%),
      radial-gradient(ellipse at 85% 30%, rgba(255,100,0,.1) 0%, transparent 50%);
  }

  .btn-exit-fs {
    position: absolute; top: 20px; right: 24px; z-index: 10;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.2);
    color: rgba(255,255,255,.6); border-radius: 10px;
    padding: 8px 18px; font-size: .85rem; cursor: pointer; transition: all .2s;
  }
  .btn-exit-fs:hover { color: #fff; border-color: rgba(255,255,255,.5); background: rgba(255,255,255,.12); }

  .fs-header { text-align: center; margin-bottom: 20px; position: relative; z-index: 1; }
  .fs-header h2 {
    font-size: clamp(2.5rem, 6vw, 5rem);
    font-weight: 900; letter-spacing: 6px;
    background: linear-gradient(135deg, #fff 0%, var(--gold) 50%, var(--gold2) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 0 40px rgba(255,215,0,.6));
  }

  .fs-stats {
    display: flex; gap: 20px; margin-bottom: 22px; position: relative; z-index: 1;
  }
  .fs-stat {
    text-align: center; padding: 12px 30px;
    background: rgba(255,255,255,.05); border-radius: 12px;
    border: 1px solid rgba(255,215,0,.2);
  }
  .fs-stat .val { font-size: 2rem; font-weight: 900; color: var(--gold); }
  .fs-stat .lbl { font-size: .65rem; color: rgba(255,255,255,.4); letter-spacing: 2px; }

  /* FS slot */
  #fs-slot-wrapper {
    position: relative; width: min(720px, 92vw); height: 52vh;
    border-radius: 18px; overflow: hidden;
    border: 2px solid var(--border); background: rgba(0,0,0,.6); z-index: 1;
  }
  #fs-slot-wrapper::before,
  #fs-slot-wrapper::after {
    content: ''; position: absolute; left: 0; right: 0; height: 130px;
    z-index: 3; pointer-events: none;
  }
  #fs-slot-wrapper::before { top:    0; background: linear-gradient(to bottom, rgba(5,5,16,1), transparent); }
  #fs-slot-wrapper::after  { bottom: 0; background: linear-gradient(to top,   rgba(5,5,16,1), transparent); }

  #fs-slot-highlight {
    position: absolute; top: 50%; left: 0; right: 0;
    transform: translateY(-50%); height: 90px;
    border-top: 2px solid var(--gold); border-bottom: 2px solid var(--gold);
    background: rgba(255,215,0,.05); z-index: 2; pointer-events: none;
    box-shadow: 0 0 50px rgba(255,215,0,.2) inset;
  }

  #fs-track {
    position: absolute; top: 0; left: 0; right: 0;
    display: flex; flex-direction: column; align-items: center;
    will-change: transform;
  }
  #fs-track .slot-item { height: 90px; font-size: 2.4rem; letter-spacing: 2px; }

  /* FS winner */
  #fs-winner-box {
    display: none; text-align: center; margin-top: 22px; padding: 22px 48px;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(255,215,0,.16), rgba(255,100,0,.09));
    border: 2px solid var(--gold);
    animation: winnerGlow 1s ease-in-out infinite alternate;
    position: relative; z-index: 1;
  }
  #fs-winner-box .trophy { font-size: 3.2rem; margin-bottom: 8px; }
  #fs-winner-box .wlabel { font-size: .75rem; letter-spacing: 4px; color: var(--gold); opacity: .8; }
  #fs-winner-name {
    font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900;
    letter-spacing: 3px; color: var(--gold);
    margin-top: 8px; text-shadow: 0 0 60px rgba(255,215,0,1);
  }
  #fs-winner-extra { font-size: 1rem; color: rgba(255,255,255,.5); margin-top: 6px; }

  /* FS spin button */
  .fs-controls { display: flex; gap: 14px; margin-top: 22px; position: relative; z-index: 1; }
  .btn-fs-spin {
    background: linear-gradient(135deg, var(--gold), var(--gold2));
    color: #000; border: none; border-radius: 14px;
    padding: 18px 56px; font-size: 1.5rem; font-weight: 900;
    letter-spacing: 2px; cursor: pointer; transition: all .2s;
    box-shadow: 0 6px 32px rgba(255,215,0,.5);
  }
  .btn-fs-spin:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 12px 48px rgba(255,215,0,.7); }
  .btn-fs-spin:disabled { opacity: .4; cursor: not-allowed; transform: none; }
</style>
</head>
<body>

<div id="bg-glow"></div>

<!-- ══════════════════════════════════════
     FULLSCREEN OVERLAY
══════════════════════════════════════ -->
<div id="fs-overlay">

  <button class="btn-exit-fs" onclick="exitFullscreen()">✕ Exit Fullscreen</button>

  <div class="fs-header">
    <h2>🎰 RAFFLE DRAW</h2>
  </div>

  <div class="fs-stats">
    <div class="fs-stat">
      <div class="val" id="fs-stat-total"><?= count($entries) ?></div>
      <div class="lbl">TOTAL</div>
    </div>
    <div class="fs-stat">
      <div class="val" id="fs-stat-eligible"><?= count(array_filter($entries, fn($e) => !$e['has_won'])) ?></div>
      <div class="lbl">ELIGIBLE</div>
    </div>
    <div class="fs-stat">
      <div class="val" id="fs-stat-won"><?= count(array_filter($entries, fn($e) => $e['has_won'])) ?></div>
      <div class="lbl">WON</div>
    </div>
  </div>

  <div id="fs-slot-wrapper">
    <div id="fs-slot-highlight"></div>
    <div id="fs-track"><!-- cloned from main track by JS --></div>
  </div>

  <div id="fs-winner-box">
    <div class="trophy">🏆</div>
    <div class="wlabel">WINNER</div>
    <div id="fs-winner-name"></div>
    <div id="fs-winner-extra"></div>
  </div>

  <div class="fs-controls">
    <button class="btn-fs-spin" id="btn-fs-spin">🎲 SPIN!</button>
  </div>

</div>

<!-- ══════════════════════════════════════
     NORMAL PAGE
══════════════════════════════════════ -->
<div class="wrapper">

  <!-- Topbar -->
  <div class="topbar">
    <a href="<?= site_url('raffle') ?>" class="brand">🎰 RAFFLE DRAW</a>
    <div class="nav-links">
      <a href="<?= site_url('raffle') ?>"              class="nav-link active">Draw</a>
      <a href="<?= site_url('raffle/participants') ?>" class="nav-link">Participants</a>
    </div>
  </div>

  <!-- Flash messages -->
  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($this->session->flashdata('success')) ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($this->session->flashdata('error')) ?></div>
  <?php endif; ?>

  <header>
    <h1>🎰 RAFFLE DRAW</h1>
    <p>MAY THE LUCKY ONE WIN</p>
  </header>

  <div class="grid">

    <!-- ══ LEFT — DRAW MACHINE ══ -->
    <div>
      <div class="panel">
        <div class="panel-title">🎰 Draw Machine</div>

        <!-- Stats row -->
        <div class="stats">
          <div class="stat-box">
            <div class="val" id="stat-total"><?= count($entries) ?></div>
            <div class="lbl">TOTAL</div>
          </div>
          <div class="stat-box">
            <div class="val" id="stat-eligible"><?= count(array_filter($entries, fn($e) => !$e['has_won'])) ?></div>
            <div class="lbl">ELIGIBLE</div>
          </div>
          <div class="stat-box">
            <div class="val" id="stat-won"><?= count(array_filter($entries, fn($e) => $e['has_won'])) ?></div>
            <div class="lbl">WON</div>
          </div>
        </div>

        <!-- Slot machine -->
        <div class="slot-outer">
          <button class="btn-fullscreen" onclick="enterFullscreen()">⛶ Fullscreen</button>

          <div class="slot-wrapper" id="slot-wrapper">
            <div class="slot-highlight"></div>
            <div class="slot-track" id="slot-track">
              <?php if (empty($entries)): ?>
                <div class="slot-item" style="color:rgba(255,255,255,.3);font-size:.95rem;padding:0 40px;">
                  No participants yet —
                  <a href="<?= site_url('raffle/participants') ?>" style="color:var(--gold);">add some here</a>.
                </div>
              <?php else: ?>
                <?php foreach ($entries as $entry): ?>
                  <div class="slot-item <?= $entry['has_won'] ? 'won-item' : '' ?>"
                       data-id="<?= $entry['id'] ?>"
                       data-name="<?= htmlspecialchars($entry['name'], ENT_QUOTES) ?>">
                    <?= htmlspecialchars($entry['name']) ?>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Winner reveal -->
        <div id="winner-box">
          <div class="trophy">🏆</div>
          <div class="wlabel">WINNER</div>
          <div id="winner-name"></div>
          <div id="winner-extra"></div>
        </div>

        <!-- Spin -->
        <button class="btn btn-spin" id="btn-spin" <?= empty($entries) ? 'disabled' : '' ?>>
          <span id="spin-label">🎲 SPIN!</span>
        </button>

        <!-- Actions -->
        <!-- <div style="display:flex;gap:8px;margin-top:10px;">
          <a href="<?= site_url('raffle/reset_winners') ?>"
             class="btn btn-outline btn-sm" style="flex:1;text-decoration:none;"
             onclick="return confirm('Reset all winners? They become eligible again.')">
            ↺ Reset Winners
          </a>
          <a href="<?= site_url('raffle/clear_all') ?>"
             class="btn btn-danger btn-sm" style="flex:1;text-decoration:none;"
             onclick="return confirm('Delete ALL entries? This cannot be undone.')">
            🗑 Clear All
          </a>
        </div> -->
      </div>
    </div>

    <!-- ══ RIGHT — PARTICIPANTS SIDEBAR ══ -->
    <div>
      <div class="panel" style="position:sticky;top:20px;">
        <div class="panel-title" style="display:flex;align-items:center;justify-content:space-between;">
          <span>📋 Participants (<span id="list-count"><?= count($entries) ?></span>)</span>
          <a href="<?= site_url('raffle/participants') ?>"
             style="color:var(--gold);font-size:.65rem;letter-spacing:1px;text-decoration:none;opacity:.75;">
            Manage →
          </a>
        </div>

        <div class="entries-list" id="entries-list">
          <?php if (empty($entries)): ?>
            <div style="text-align:center;color:rgba(255,255,255,.3);padding:40px 0;font-size:.85rem;">
              No entries yet.
            </div>
          <?php else: ?>
            <?php foreach ($entries as $i => $entry): ?>
              <div class="entry-item <?= $entry['has_won'] ? 'won' : '' ?>"
                   id="list-item-<?= $entry['id'] ?>">
                <span class="num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                <span class="ename"><?= htmlspecialchars($entry['name']) ?></span>
                <?php if ($entry['has_won']): ?>
                  <span class="badge-won">🏆</span>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div><!-- /grid -->
</div><!-- /wrapper -->

<script>
(function () {

  const PICK_URL  = '<?= site_url('raffle/pick_winner') ?>';
  const STATS_URL = '<?= site_url('raffle/get_entries') ?>';
  const ITEM_H_N  = 72;   // normal slot item height
  const ITEM_H_FS = 90;   // fullscreen slot item height
  const SPIN_MS   = 3000;

  /* ── elements ── */
  const track       = document.getElementById('slot-track');
  const btnSpin     = document.getElementById('btn-spin');
  const spinLabel   = document.getElementById('spin-label');
  const winnerBox   = document.getElementById('winner-box');
  const winnerName  = document.getElementById('winner-name');
  const winnerExtra = document.getElementById('winner-extra');

  const fsTrack       = document.getElementById('fs-track');
  const btnFsSpin     = document.getElementById('btn-fs-spin');
  const fsWinnerBox   = document.getElementById('fs-winner-box');
  const fsWinnerName  = document.getElementById('fs-winner-name');
  const fsWinnerExtra = document.getElementById('fs-winner-extra');

  let isSpinning   = false;
  let animFrame    = null;
  let inFullscreen = false;

  /* ────────────────────────────
     FULLSCREEN
  ──────────────────────────── */
  window.enterFullscreen = function () {
    cloneTrackToFs();
    document.body.classList.add('is-fullscreen');
    inFullscreen = true;
    document.documentElement.requestFullscreen && document.documentElement.requestFullscreen().catch(() => {});
  };

  window.exitFullscreen = function () {
    document.body.classList.remove('is-fullscreen');
    inFullscreen = false;
    if (document.fullscreenElement) document.exitFullscreen().catch(() => {});
  };

  document.addEventListener('keydown', e => { if (e.key === 'Escape' && inFullscreen) exitFullscreen(); });
  document.addEventListener('fullscreenchange', () => { if (!document.fullscreenElement && inFullscreen) exitFullscreen(); });

  function cloneTrackToFs() {
    fsTrack.innerHTML = '';
    Array.from(track.children).forEach(el => {
      const clone = el.cloneNode(true);
      clone.style.height     = ITEM_H_FS + 'px';
      clone.style.fontSize   = '2.4rem';
      clone.style.letterSpacing = '2px';
      fsTrack.appendChild(clone);
    });
    // reset position
    fsTrack.style.transform = '';
  }

  /* ────────────────────────────
     SPIN
  ──────────────────────────── */
  btnSpin.addEventListener('click',   () => startSpin(false));
  btnFsSpin.addEventListener('click', () => startSpin(true));

  function startSpin(fs) {
    if (isSpinning) return;

    const activeTrack = fs ? fsTrack : track;
    const eligible    = activeTrack.querySelectorAll('.slot-item:not(.won-item)');
    if (!eligible.length) { alert('No eligible entries left!'); return; }

    isSpinning = true;
    hideWinner(fs);
    setSpinLabel(fs, '⏳ Spinning…', true);

    const ITEM_H   = fs ? ITEM_H_FS : ITEM_H_N;
    const wrapper  = document.getElementById(fs ? 'fs-slot-wrapper' : 'slot-wrapper');
    const totalH   = activeTrack.children.length * ITEM_H;
    const wrapH    = wrapper.clientHeight;
    const centerOff = Math.floor(wrapH / 2) - Math.floor(ITEM_H / 2);

    let pos = 0, speed = fs ? 22 : 18, startTime = null;

    function doAnim(ts) {
      if (!startTime) startTime = ts;
      const elapsed = ts - startTime;
      if (elapsed > SPIN_MS - 1200) speed = Math.max(1, speed * 0.952);
      pos = (pos + speed) % totalH;
      activeTrack.style.transform = `translateY(${centerOff - pos}px)`;
      if (elapsed < SPIN_MS) {
        animFrame = requestAnimationFrame(doAnim);
      } else {
        fetchWinner(fs, activeTrack, centerOff, ITEM_H);
      }
    }
    animFrame = requestAnimationFrame(doAnim);
  }

  /* ────────────────────────────
     FETCH & SNAP
  ──────────────────────────── */
  function fetchWinner(fs, activeTrack, centerOff, ITEM_H) {
    fetch(PICK_URL, { method: 'POST' })
      .then(r => r.json())
      .then(data => {
        if (!data.success) { alert(data.message || 'No eligible entries.'); stopSpin(fs, null); return; }
        snapToWinner(fs, activeTrack, centerOff, ITEM_H, data.winner);
      })
      .catch(() => { alert('Server error. Please try again.'); stopSpin(fs, null); });
  }

  function snapToWinner(fs, activeTrack, centerOff, ITEM_H, winner) {
    const allItems = Array.from(activeTrack.children);
    const targetEl = allItems.find(el => el.dataset.id == winner.id);
    if (!targetEl) { stopSpin(fs, winner); return; }

    const idx      = allItems.indexOf(targetEl);
    const currentY = new DOMMatrix(getComputedStyle(activeTrack).transform).m42;
    const targetY  = centerOff - idx * ITEM_H;
    const diff     = targetY - currentY;
    let prog = 0;

    (function snap() {
      prog++;
      const ease = 1 - Math.pow(1 - prog / 45, 3);
      activeTrack.style.transform = `translateY(${currentY + diff * ease}px)`;
      if (prog < 45) requestAnimationFrame(snap);
      else stopSpin(fs, winner);
    })();
  }

  /* ────────────────────────────
     STOP / REVEAL
  ──────────────────────────── */
  function stopSpin(fs, winner) {
    cancelAnimationFrame(animFrame);
    isSpinning = false;
    setSpinLabel(fs, '🎲 SPIN!', false);
    if (!winner) return;

    // Highlight in both tracks
    [track, fsTrack].forEach(t => {
      const el = t.querySelector(`[data-id="${winner.id}"]`);
      if (!el) return;
      el.style.color        = '#FFD700';
      el.style.textShadow   = '0 0 30px rgba(255,215,0,1)';
    });

    // Show winner box
    showWinner(fs, winner);

    // Mark in sidebar
    const listEl = document.getElementById('list-item-' + winner.id);
    if (listEl && !listEl.querySelector('.badge-won')) {
      listEl.classList.add('won');
      const b = document.createElement('span');
      b.className = 'badge-won'; b.textContent = '🏆';
      listEl.appendChild(b);
    }

    refreshStats();

    setTimeout(() => {
      [track, fsTrack].forEach(t => {
        const el = t.querySelector(`[data-id="${winner.id}"]`);
        if (el) el.classList.add('won-item');
      });
      fireConfetti();
    }, 2200);
  }

  /* ── helpers ── */
  function setSpinLabel(fs, text, disabled) {
    if (fs) { btnFsSpin.textContent = text; btnFsSpin.disabled = disabled; }
    else     { spinLabel.textContent = text; btnSpin.disabled = disabled; }
  }
  function showWinner(fs, w) {
    if (fs) {
      fsWinnerName.textContent  = w.name;
      fsWinnerExtra.textContent = w.extra_info || '';
      fsWinnerBox.style.display = 'block';
    } else {
      winnerName.textContent  = w.name;
      winnerExtra.textContent = w.extra_info || '';
      winnerBox.style.display = 'block';
    }
  }
  function hideWinner(fs) {
    if (fs) fsWinnerBox.style.display = 'none';
    else    winnerBox.style.display   = 'none';
  }

  /* ────────────────────────────
     STATS REFRESH
  ──────────────────────────── */
  function refreshStats() {
    fetch(STATS_URL).then(r => r.json()).then(entries => {
      const total    = entries.length;
      const won      = entries.filter(e => e.has_won == 1).length;
      const eligible = total - won;

      set('stat-total',    total);    set('fs-stat-total',    total);
      set('stat-eligible', eligible); set('fs-stat-eligible', eligible);
      set('stat-won',      won);      set('fs-stat-won',      won);
      set('list-count',    total);

      if (eligible === 0) { btnSpin.disabled = true; btnFsSpin.disabled = true; }
    });
  }
  function set(id, v) { const el = document.getElementById(id); if (el) el.textContent = v; }

  /* ────────────────────────────
     CONFETTI
  ──────────────────────────── */
  function fireConfetti() {
    const colors = ['#FFD700','#FFA500','#fff','#ff6b6b','#4ecdc4','#a78bfa'];
    for (let i = 0; i < 160; i++) {
      const el = document.createElement('div');
      el.className = 'confetti-piece';
      const size = 6 + Math.random() * 12;
      el.style.cssText = `
        left:${Math.random()*100}vw;
        width:${size}px;height:${size*(1.4+Math.random())}px;
        background:${colors[Math.floor(Math.random()*colors.length)]};
        animation-duration:${1.5+Math.random()*2.5}s;
        animation-delay:${Math.random()*.9}s;
        transform:rotate(${Math.random()*360}deg);
      `;
      document.body.appendChild(el);
      el.addEventListener('animationend', () => el.remove());
    }
  }

})();
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🎰 Raffle Draw System</title>
<style>
  :root {
    --gold:   #FFD700; --gold2:  #FFA500;
    --darker: #050510; --card-bg: rgba(255,255,255,.05);
    --border: rgba(255,215,0,.3);
    --green:  #2ed573; --red: #ff4757;
    --item-h: 72px;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: var(--darker); color: #fff; font-family: 'Segoe UI', sans-serif; min-height: 100vh; overflow-x: hidden; }
  #bg { position: fixed; inset: 0; pointer-events: none; z-index: 0;
    background: radial-gradient(ellipse at 20% 50%, rgba(120,0,255,.09) 0%,transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(255,100,0,.07) 0%,transparent 50%), var(--darker); }
  .wrapper { position: relative; z-index: 1; max-width: 1300px; margin: 0 auto; padding: 20px 24px; }

  /* NAV */
  .topbar { display:flex; align-items:center; justify-content:space-between; padding-bottom:20px; }
  .brand { font-size:1rem; font-weight:900; letter-spacing:3px;
    background:linear-gradient(135deg,#fff,var(--gold)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; text-decoration:none; }
  .nav-links { display:flex; gap:8px; }
  .nav-link { padding:7px 16px; border-radius:8px; font-size:.82rem; font-weight:600; letter-spacing:.5px; text-decoration:none; border:1px solid transparent; transition:all .2s; }
  .nav-link.active { background:linear-gradient(135deg,var(--gold),var(--gold2)); color:#000; }
  .nav-link:not(.active) { color:rgba(255,255,255,.55); border-color:rgba(255,255,255,.15); }
  .nav-link:not(.active):hover { color:#fff; border-color:rgba(255,255,255,.4); }

  /* HEADER */
  header { text-align:center; padding:10px 0 22px; }
  header h1 { font-size:clamp(2.2rem,5vw,4rem); font-weight:900; letter-spacing:5px;
    background:linear-gradient(135deg,#fff 0%,var(--gold) 50%,var(--gold2) 100%);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    filter:drop-shadow(0 0 30px rgba(255,215,0,.5)); }
  header p { color:rgba(255,255,255,.4); margin-top:6px; letter-spacing:3px; font-size:.8rem; }

  /* ALERTS */
  .alert { padding:11px 16px; border-radius:10px; font-size:.88rem; margin-bottom:16px; display:flex; align-items:center; gap:10px; }
  .alert-success { background:rgba(46,213,115,.1); border:1px solid rgba(46,213,115,.3); color:var(--green); }
  .alert-error   { background:rgba(255,71,87,.1);  border:1px solid rgba(255,71,87,.3);  color:var(--red); }

  /* GRID */
  .grid { display:grid; grid-template-columns:1fr 300px; gap:24px; align-items:start; }
  @media(max-width:900px){ .grid { grid-template-columns:1fr; } }

  /* PANEL */
  .panel { background:var(--card-bg); border:1px solid var(--border); border-radius:18px; padding:24px; backdrop-filter:blur(12px); }
  .panel-title { font-size:.68rem; letter-spacing:3px; color:var(--gold); text-transform:uppercase; margin-bottom:16px; opacity:.8; }

  /* STATS */
  .stats { display:flex; gap:10px; margin-bottom:16px; }
  .stat-box { flex:1; text-align:center; padding:14px 8px; background:rgba(255,255,255,.04); border-radius:12px; border:1px solid rgba(255,255,255,.07); }
  .stat-box .val { font-size:1.8rem; font-weight:900; color:var(--gold); line-height:1; }
  .stat-box .lbl { font-size:.6rem; color:rgba(255,255,255,.4); letter-spacing:2px; margin-top:4px; }

  /* ACTIVE PRIZE BANNER */
  .prize-banner {
    display:flex; align-items:center; gap:12px; padding:12px 16px;
    border-radius:12px; margin-bottom:16px;
    background:linear-gradient(135deg, rgba(255,215,0,.1), rgba(255,100,0,.06));
    border:1px solid rgba(255,215,0,.35);
  }
  .prize-banner .pb-icon { font-size:1.6rem; flex-shrink:0; }
  .prize-banner .pb-body { flex:1; min-width:0; }
  .prize-banner .pb-label { font-size:.6rem; letter-spacing:2px; color:rgba(255,255,255,.4); text-transform:uppercase; }
  .prize-banner .pb-name  { font-size:1.05rem; font-weight:800; color:var(--gold); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .prize-banner .pb-meta  { font-size:.75rem; color:rgba(255,255,255,.45); margin-top:2px; }
  .prize-banner .pb-remaining { color:var(--green); font-weight:700; }
  .prize-banner .pb-remaining.warn { color:var(--red); }
  .prize-banner .pb-change { font-size:.72rem; color:rgba(255,215,0,.55); text-decoration:none; white-space:nowrap; border:1px solid rgba(255,215,0,.25); border-radius:6px; padding:4px 10px; transition:all .2s; flex-shrink:0; }
  .prize-banner .pb-change:hover { color:var(--gold); border-color:var(--gold); background:rgba(255,215,0,.08); }
  .prize-banner.no-prize {
    background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.12);
  }
  .prize-banner.no-prize .pb-name { color:rgba(255,255,255,.4); font-weight:600; font-size:.9rem; }

  /* SLOT MACHINE */
  .slot-outer { position:relative; }
  .btn-fullscreen {
    position:absolute; top:12px; right:12px; z-index:10;
    background:rgba(0,0,0,.5); border:1px solid rgba(255,215,0,.4); color:var(--gold);
    border-radius:8px; padding:7px 13px; font-size:.82rem; cursor:pointer; transition:all .2s;
    backdrop-filter:blur(6px); font-weight:600;
  }
  .btn-fullscreen:hover { background:rgba(255,215,0,.15); border-color:var(--gold); }
  .slot-wrapper { position:relative; width:100%; height:520px; border-radius:14px; overflow:hidden; border:2px solid var(--border); background:rgba(0,0,0,.55); }
  .slot-wrapper::before,.slot-wrapper::after { content:''; position:absolute; left:0; right:0; height:120px; z-index:3; pointer-events:none; }
  .slot-wrapper::before { top:0; background:linear-gradient(to bottom,rgba(5,5,16,1),transparent); }
  .slot-wrapper::after  { bottom:0; background:linear-gradient(to top,rgba(5,5,16,1),transparent); }
  .slot-highlight { position:absolute; top:50%; left:0; right:0; transform:translateY(-50%); height:var(--item-h);
    border-top:2px solid var(--gold); border-bottom:2px solid var(--gold);
    background:rgba(255,215,0,.05); z-index:2; pointer-events:none; box-shadow:0 0 30px rgba(255,215,0,.15) inset; }
  .slot-track { position:absolute; top:0; left:0; right:0; display:flex; flex-direction:column; align-items:center; will-change:transform; }
  .slot-item { width:100%; height:var(--item-h); display:flex; align-items:center; justify-content:center;
    font-size:1.4rem; font-weight:700; letter-spacing:1px; color:#fff; flex-shrink:0; padding:0 80px; text-align:center; }
  .slot-item.won-item { color:rgba(255,255,255,.2); text-decoration:line-through; }

  /* WINNER BOX */
  #winner-box { display:none; text-align:center; margin-top:20px; padding:22px 24px; border-radius:14px;
    background:linear-gradient(135deg,rgba(255,215,0,.14),rgba(255,100,0,.08));
    border:2px solid var(--gold); animation:winnerGlow 1s ease-in-out infinite alternate; }
  @keyframes winnerGlow {
    from { box-shadow:0 0 20px rgba(255,215,0,.3); }
    to   { box-shadow:0 0 60px rgba(255,215,0,.75),0 0 100px rgba(255,100,0,.25); }
  }
  #winner-box .trophy { font-size:2.8rem; margin-bottom:6px; }
  #winner-box .wlabel { font-size:.68rem; letter-spacing:4px; color:var(--gold); opacity:.8; }
  #winner-name  { font-size:clamp(1.6rem,3.5vw,2.4rem); font-weight:900; letter-spacing:2px; color:var(--gold); margin-top:6px; text-shadow:0 0 40px rgba(255,215,0,.9); }
  #winner-extra { font-size:.88rem; color:rgba(255,255,255,.5); margin-top:4px; }
  #winner-prize { display:none; margin-top:10px; padding:6px 18px;
    background:rgba(255,215,0,.15); border:1px solid rgba(255,215,0,.4);
    border-radius:20px; font-size:.85rem; color:var(--gold); font-weight:700; }

  /* BUTTONS */
  .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:15px 28px; border:none; border-radius:11px; font-size:1.05rem; font-weight:700; letter-spacing:1px; cursor:pointer; transition:all .2s; width:100%; margin-top:14px; }
  .btn-spin { background:linear-gradient(135deg,var(--gold),var(--gold2)); color:#000; box-shadow:0 4px 24px rgba(255,215,0,.45); font-size:1.2rem; }
  .btn-spin:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 36px rgba(255,215,0,.65); }
  .btn-spin:disabled { opacity:.4; cursor:not-allowed; transform:none; }
  .btn-sm { font-size:.82rem; padding:9px 16px; margin-top:0; width:auto; border-radius:8px; }
  .btn-outline { background:transparent; color:rgba(255,255,255,.55); border:1px solid rgba(255,255,255,.2); }
  .btn-outline:hover { border-color:rgba(255,255,255,.5); color:#fff; }
  .btn-danger { background:transparent; color:var(--red); border:1px solid rgba(255,107,107,.3); }
  .btn-danger:hover { background:rgba(255,107,107,.1); border-color:var(--red); }

  /* SIDEBAR */
  .entries-list { max-height:500px; overflow-y:auto; scrollbar-width:thin; scrollbar-color:var(--gold) transparent; }
  .entries-list::-webkit-scrollbar { width:3px; }
  .entries-list::-webkit-scrollbar-thumb { background:var(--gold); border-radius:4px; }
  .entry-item { display:flex; align-items:center; gap:8px; padding:7px 8px; border-radius:8px; font-size:.82rem; transition:background .15s; }
  .entry-item:hover { background:rgba(255,255,255,.04); }
  .entry-item .num { font-size:.65rem; color:rgba(255,255,255,.28); min-width:22px; }
  .entry-item .ename { flex:1; }
  .entry-item .epz { font-size:.64rem; color:var(--gold); background:rgba(255,215,0,.1); padding:1px 7px; border-radius:10px; white-space:nowrap; max-width:90px; overflow:hidden; text-overflow:ellipsis; }
  .entry-item.won .ename { color:rgba(255,255,255,.3); text-decoration:line-through; }

  /* CONFETTI */
  .confetti-piece { position:fixed; top:-10px; pointer-events:none; z-index:9999; border-radius:2px; animation:confettiFall linear forwards; }
  @keyframes confettiFall { to { transform:translateY(110vh) rotate(720deg); opacity:0; } }

  /* FULLSCREEN */
  body.is-fullscreen { overflow:hidden; }
  #fs-overlay { display:none; position:fixed; inset:0; z-index:500; background:var(--darker);
    flex-direction:column; align-items:center; justify-content:center; padding:20px; }
  body.is-fullscreen #fs-overlay { display:flex; }
  #fs-overlay::before { content:''; position:absolute; inset:0; pointer-events:none;
    background:radial-gradient(ellipse at 15% 50%,rgba(120,0,255,.14) 0%,transparent 55%),
               radial-gradient(ellipse at 85% 30%,rgba(255,100,0,.1) 0%,transparent 50%); }
  .btn-exit-fs { position:absolute; top:20px; right:24px; z-index:10; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.2); color:rgba(255,255,255,.6); border-radius:10px; padding:8px 18px; font-size:.85rem; cursor:pointer; transition:all .2s; }
  .btn-exit-fs:hover { color:#fff; border-color:rgba(255,255,255,.5); background:rgba(255,255,255,.12); }
  .fs-header { text-align:center; margin-bottom:14px; position:relative; z-index:1; }
  .fs-header h2 { font-size:clamp(2.5rem,6vw,5rem); font-weight:900; letter-spacing:6px;
    background:linear-gradient(135deg,#fff 0%,var(--gold) 50%,var(--gold2) 100%);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    filter:drop-shadow(0 0 40px rgba(255,215,0,.6)); }

  /* FS active prize badge */
  .fs-prize-badge { position:relative; z-index:1; margin-bottom:14px; display:flex; align-items:center; justify-content:center; }
  .fs-prize-badge .fpb-inner {
    display:flex; align-items:center; gap:10px; padding:10px 22px;
    background:linear-gradient(135deg,rgba(255,215,0,.12),rgba(255,100,0,.07));
    border:1px solid rgba(255,215,0,.35); border-radius:30px;
  }
  .fs-prize-badge .fpb-name { font-size:1.1rem; font-weight:800; color:var(--gold); }
  .fs-prize-badge .fpb-meta { font-size:.78rem; color:rgba(255,255,255,.5); }
  .fs-prize-badge.no-prize .fpb-inner { background:rgba(255,255,255,.04); border-color:rgba(255,255,255,.12); }
  .fs-prize-badge.no-prize .fpb-name  { color:rgba(255,255,255,.4); font-size:.9rem; font-weight:600; }

  .fs-stats { display:flex; gap:20px; margin-bottom:16px; position:relative; z-index:1; }
  .fs-stat { text-align:center; padding:10px 28px; background:rgba(255,255,255,.05); border-radius:12px; border:1px solid rgba(255,215,0,.2); }
  .fs-stat .val { font-size:2rem; font-weight:900; color:var(--gold); }
  .fs-stat .lbl { font-size:.65rem; color:rgba(255,255,255,.4); letter-spacing:2px; }

  #fs-slot-wrapper { position:relative; width:min(720px,92vw); height:50vh;
    border-radius:18px; overflow:hidden; border:2px solid var(--border); background:rgba(0,0,0,.6); z-index:1; }
  #fs-slot-wrapper::before,#fs-slot-wrapper::after { content:''; position:absolute; left:0; right:0; height:130px; z-index:3; pointer-events:none; }
  #fs-slot-wrapper::before { top:0; background:linear-gradient(to bottom,rgba(5,5,16,1),transparent); }
  #fs-slot-wrapper::after  { bottom:0; background:linear-gradient(to top,rgba(5,5,16,1),transparent); }
  #fs-slot-highlight { position:absolute; top:50%; left:0; right:0; transform:translateY(-50%); height:90px;
    border-top:2px solid var(--gold); border-bottom:2px solid var(--gold);
    background:rgba(255,215,0,.05); z-index:2; pointer-events:none; box-shadow:0 0 50px rgba(255,215,0,.2) inset; }
  #fs-track { position:absolute; top:0; left:0; right:0; display:flex; flex-direction:column; align-items:center; will-change:transform; }
  #fs-track .slot-item { height:90px; font-size:2.4rem; letter-spacing:2px; }

  #fs-winner-box { display:none; text-align:center; margin-top:18px; padding:18px 48px; border-radius:16px;
    background:linear-gradient(135deg,rgba(255,215,0,.16),rgba(255,100,0,.09));
    border:2px solid var(--gold); animation:winnerGlow 1s ease-in-out infinite alternate;
    position:relative; z-index:1; }
  #fs-winner-box .trophy { font-size:3rem; margin-bottom:6px; }
  #fs-winner-box .wlabel { font-size:.75rem; letter-spacing:4px; color:var(--gold); opacity:.8; }
  #fs-winner-name  { font-size:clamp(2rem,5vw,3.5rem); font-weight:900; letter-spacing:3px; color:var(--gold); margin-top:8px; text-shadow:0 0 60px rgba(255,215,0,1); }
  #fs-winner-extra { font-size:1rem; color:rgba(255,255,255,.5); margin-top:5px; }
  #fs-winner-prize { display:none; margin-top:10px; padding:7px 22px; background:rgba(255,215,0,.15); border:1px solid rgba(255,215,0,.4); border-radius:20px; font-size:.95rem; color:var(--gold); font-weight:700; }

  .fs-controls { display:flex; gap:14px; margin-top:18px; position:relative; z-index:1; }
  .btn-fs-spin { background:linear-gradient(135deg,var(--gold),var(--gold2)); color:#000; border:none; border-radius:14px;
    padding:18px 56px; font-size:1.5rem; font-weight:900; letter-spacing:2px; cursor:pointer; transition:all .2s;
    box-shadow:0 6px 32px rgba(255,215,0,.5); }
  .btn-fs-spin:hover:not(:disabled) { transform:translateY(-3px); box-shadow:0 12px 48px rgba(255,215,0,.7); }
  .btn-fs-spin:disabled { opacity:.4; cursor:not-allowed; transform:none; }
</style>
</head>
<body>
<div id="bg"></div>

<!-- ═══════════════ FULLSCREEN OVERLAY ═══════════════ -->
<div id="fs-overlay">
  <button class="btn-exit-fs" onclick="exitFullscreen()">✕ Exit Fullscreen</button>
  <div class="fs-header"><h2>🎰 RAFFLE DRAW</h2></div>

  <div class="fs-stats">
    <div class="fs-stat"><div class="val" id="fs-total"><?= count($entries) ?></div><div class="lbl">TOTAL</div></div>
    <div class="fs-stat"><div class="val" id="fs-eligible"><?= count(array_filter($entries, fn($e) => !$e['has_won'])) ?></div><div class="lbl">ELIGIBLE</div></div>
    <div class="fs-stat"><div class="val" id="fs-won"><?= count(array_filter($entries, fn($e) => $e['has_won'])) ?></div><div class="lbl">WON</div></div>
  </div>

  <div id="fs-slot-wrapper">
    <div id="fs-slot-highlight"></div>
    <div id="fs-track"></div>
  </div>

  <div id="fs-winner-box">
    <div class="trophy">🏆</div>
    <div class="wlabel">WINNER</div>
    <div id="fs-winner-name"></div>
    <div id="fs-winner-extra"></div>
    <div id="fs-winner-prize"></div>
  </div>

  <div class="fs-controls">
    <button class="btn-fs-spin" id="btn-fs-spin">🎲 SPIN!</button>
  </div>
</div>

<!-- ═══════════════ NORMAL PAGE ═══════════════ -->
<div class="wrapper">
  <div class="topbar">
    <a href="<?= site_url('raffle') ?>" class="brand">🎰 RAFFLE DRAW</a>
    <div class="nav-links">
      <a href="<?= site_url('raffle') ?>"              class="nav-link active">Draw</a>
      <a href="<?= site_url('raffle/participants') ?>" class="nav-link">Participants</a>
    </div>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($this->session->flashdata('success')) ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($this->session->flashdata('error')) ?></div>
  <?php endif; ?>

  <header>
    <h1>🎰 RAFFLE DRAW</h1>
    <p>SET THE ACTIVE PRIZE IN PARTICIPANTS · THEN SPIN</p>
  </header>

  <div class="grid">
    <!-- ══ LEFT: DRAW MACHINE ══ -->
    <div>
      <div class="panel">
        <div class="panel-title">🎰 Draw Machine</div>

        <!-- Stats -->
        <div class="stats">
          <div class="stat-box"><div class="val" id="stat-total"><?= count($entries) ?></div><div class="lbl">TOTAL</div></div>
          <div class="stat-box"><div class="val" id="stat-eligible"><?= count(array_filter($entries, fn($e) => !$e['has_won'])) ?></div><div class="lbl">ELIGIBLE</div></div>
          <div class="stat-box"><div class="val" id="stat-won"><?= count(array_filter($entries, fn($e) => $e['has_won'])) ?></div><div class="lbl">WON</div></div>
        </div>

        <!-- Slot Machine -->
        <div class="slot-outer">
          <button class="btn-fullscreen" onclick="enterFullscreen()">⛶ Fullscreen</button>
          <div class="slot-wrapper" id="slot-wrapper">
            <div class="slot-highlight"></div>
            <div class="slot-track" id="slot-track">
              <?php if (empty($entries)): ?>
                <div class="slot-item" style="color:rgba(255,255,255,.3);font-size:.95rem;padding:0 40px;">
                  No participants yet — <a href="<?= site_url('raffle/participants') ?>" style="color:var(--gold);">add some here</a>.
                </div>
              <?php else: ?>
                <?php foreach ($entries as $e): ?>
                  <div class="slot-item <?= $e['has_won'] ? 'won-item' : '' ?>"
                       data-id="<?= $e['id'] ?>" data-name="<?= htmlspecialchars($e['name'], ENT_QUOTES) ?>">
                    <?= htmlspecialchars($e['name']) ?>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Winner Box -->
        <div id="winner-box">
          <div class="trophy">🏆</div>
          <div class="wlabel">WINNER</div>
          <div id="winner-name"></div>
          <div id="winner-extra"></div>
          <div id="winner-prize"></div>
        </div>

        <button class="btn btn-spin" id="btn-spin" <?= empty($entries) ? 'disabled' : '' ?>>
          <span id="spin-label">🎲 SPIN!</span>
        </button>

      </div>
    </div>

    <!-- ══ RIGHT: SIDEBAR ══ -->
    <div>
      <div class="panel" style="position:sticky;top:20px;">
        <div class="panel-title" style="display:flex;align-items:center;justify-content:space-between;">
          <span>📋 Participants (<span id="list-count"><?= count($entries) ?></span>)</span>
          <a href="<?= site_url('raffle/participants') ?>" style="color:var(--gold);font-size:.65rem;letter-spacing:1px;text-decoration:none;opacity:.75;">Manage →</a>
        </div>
        <div class="entries-list" id="entries-list">
          <?php if (empty($entries)): ?>
            <div style="text-align:center;color:rgba(255,255,255,.3);padding:40px 0;font-size:.85rem;">No entries yet.</div>
          <?php else: ?>
            <?php foreach ($entries as $i => $e): ?>
              <div class="entry-item <?= $e['has_won'] ? 'won' : '' ?>" id="list-item-<?= $e['id'] ?>">
                <span class="num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                <span class="ename"><?= htmlspecialchars($e['name']) ?></span>
                <?php if ($e['has_won']): ?>
                  <span class="epz">🏆 <?= htmlspecialchars($e['prize_name'] ?: 'Won') ?></span>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const PICK_URL  = '<?= site_url('raffle/pick_winner') ?>';
  const STATS_URL = '<?= site_url('raffle/get_entries') ?>';
  const PRIZE_URL = '<?= site_url('raffle/get_active_prize') ?>';
  const ITEM_H_N = 72, ITEM_H_FS = 90, SPIN_MS = 3000;

  const track      = document.getElementById('slot-track');
  const btnSpin    = document.getElementById('btn-spin');
  const spinLabel  = document.getElementById('spin-label');
  const winnerBox  = document.getElementById('winner-box');
  const winnerName = document.getElementById('winner-name');
  const winnerXtra = document.getElementById('winner-extra');
  const winnerPrz  = document.getElementById('winner-prize');

  const fsTrack    = document.getElementById('fs-track');
  const btnFsSpin  = document.getElementById('btn-fs-spin');
  const fsWinBox   = document.getElementById('fs-winner-box');
  const fsWinName  = document.getElementById('fs-winner-name');
  const fsWinXtra  = document.getElementById('fs-winner-extra');
  const fsWinPrz   = document.getElementById('fs-winner-prize');

  let isSpinning = false, animFrame = null, inFs = false;

  // ── FULLSCREEN ──
  window.enterFullscreen = function(){
    cloneFs(); document.body.classList.add('is-fullscreen'); inFs = true;
    document.documentElement.requestFullscreen && document.documentElement.requestFullscreen().catch(()=>{});
  };
  window.exitFullscreen = function(){
    document.body.classList.remove('is-fullscreen'); inFs = false;
    if(document.fullscreenElement) document.exitFullscreen().catch(()=>{});
  };
  document.addEventListener('keydown', e=>{ if(e.key==='Escape'&&inFs) exitFullscreen(); });
  document.addEventListener('fullscreenchange', ()=>{ if(!document.fullscreenElement&&inFs) exitFullscreen(); });

  function cloneFs(){
    fsTrack.innerHTML='';
    Array.from(track.children).forEach(el=>{
      const c=el.cloneNode(true); c.style.height=ITEM_H_FS+'px'; c.style.fontSize='2.4rem'; c.style.letterSpacing='2px';
      fsTrack.appendChild(c);
    });
    fsTrack.style.transform='';
  }

  // ── SPIN ──
  btnSpin.addEventListener('click',   ()=>startSpin(false));
  btnFsSpin.addEventListener('click', ()=>startSpin(true));

  function startSpin(fs){
    if(isSpinning) return;
    const at = fs?fsTrack:track;
    if(!at.querySelectorAll('.slot-item:not(.won-item)').length){ alert('No eligible entries left!'); return; }

    // Check prize availability before starting the animation
    fetch(PRIZE_URL).then(r=>r.json()).then(d=>{
      const p = d.prize;
      if(p && p.won_count >= p.quantity){ alert(`"${p.name}" has no slots remaining (${p.quantity}/${p.quantity} awarded). Set a new active prize.`); return; }

      // Prize OK — begin animation
      isSpinning=true; hideWin(fs); setLbl(fs,'⏳ Spinning…',true);
      const IH=fs?ITEM_H_FS:ITEM_H_N;
      const wr=document.getElementById(fs?'fs-slot-wrapper':'slot-wrapper');
      const totalH=at.children.length*IH, cOff=Math.floor(wr.clientHeight/2)-Math.floor(IH/2);
      let pos=0, spd=fs?22:18, t0=null;
      function anim(ts){
        if(!t0) t0=ts; const el=ts-t0;
        if(el>SPIN_MS-1200) spd=Math.max(1,spd*0.952);
        pos=(pos+spd)%totalH; at.style.transform=`translateY(${cOff-pos}px)`;
        animFrame = el<SPIN_MS ? requestAnimationFrame(anim) : (doFetch(fs,at,cOff,IH),null);
      }
      requestAnimationFrame(anim);
    }).catch(()=>alert('Could not verify prize availability. Please try again.'));
  }

  function doFetch(fs,at,cOff,IH){
    fetch(PICK_URL,{method:'POST'}).then(r=>r.json()).then(d=>{
      if(!d.success){ alert(d.message||'Error'); stopSpin(fs,null); return; }
      snapTo(fs,at,cOff,IH,d.winner);
    }).catch(()=>{ alert('Server error.'); stopSpin(fs,null); });
  }

  function snapTo(fs,at,cOff,IH,winner){
    const all=Array.from(at.children), el=all.find(e=>e.dataset.id==winner.id);
    if(!el){ stopSpin(fs,winner); return; }
    const curY=new DOMMatrix(getComputedStyle(at).transform).m42;
    const targY=cOff-all.indexOf(el)*IH, diff=targY-curY; let p=0;
    (function s(){ p++;
      const ease=1-Math.pow(1-p/45,3);
      at.style.transform=`translateY(${curY+diff*ease}px)`;
      if(p<45) requestAnimationFrame(s); else stopSpin(fs,winner);
    })();
  }

  function stopSpin(fs,winner){
    cancelAnimationFrame(animFrame); isSpinning=false; setLbl(fs,'🎲 SPIN!',false);
    if(!winner) return;
    [track,fsTrack].forEach(t=>{ const e=t.querySelector(`[data-id="${winner.id}"]`); if(e){ e.style.color='#FFD700'; e.style.textShadow='0 0 30px rgba(255,215,0,1)'; } });
    showWin(fs,winner);
    const li=document.getElementById('list-item-'+winner.id);
    if(li){ li.classList.add('won'); const ex=li.querySelector('.epz'); if(ex) ex.remove(); const b=document.createElement('span'); b.className='epz'; b.textContent='🏆 '+(winner.prize_name||'Won'); li.appendChild(b); }
    refreshStats();
    setTimeout(()=>{ [track,fsTrack].forEach(t=>{ const e=t.querySelector(`[data-id="${winner.id}"]`); if(e) e.classList.add('won-item'); }); confetti(); },2200);
  }

  function showWin(fs,w){
    const pz=w.prize_name?`🎁 ${w.prize_name}`:'';
    if(fs){ fsWinName.textContent=w.name; fsWinXtra.textContent=w.extra_info||''; fsWinPrz.textContent=pz; fsWinPrz.style.display=pz?'inline-block':'none'; fsWinBox.style.display='block'; }
    else   { winnerName.textContent=w.name; winnerXtra.textContent=w.extra_info||''; winnerPrz.textContent=pz; winnerPrz.style.display=pz?'inline-block':'none'; winnerBox.style.display='block'; }
  }
  function hideWin(fs){ if(fs) fsWinBox.style.display='none'; else winnerBox.style.display='none'; }
  function setLbl(fs,t,d){ if(fs){btnFsSpin.textContent=t;btnFsSpin.disabled=d;}else{spinLabel.textContent=t;btnSpin.disabled=d;} }

  // ── STATS ──
  function refreshStats(){
    fetch(STATS_URL).then(r=>r.json()).then(entries=>{
      const tot=entries.length, won=entries.filter(e=>e.has_won==1).length, eli=tot-won;
      $s('stat-total',tot); $s('fs-total',tot);
      $s('stat-eligible',eli); $s('fs-eligible',eli);
      $s('stat-won',won); $s('fs-won',won);
      $s('list-count',tot);
      if(eli===0){btnSpin.disabled=true;btnFsSpin.disabled=true;}
    });
    // Also refresh active prize display
    fetch(PRIZE_URL).then(r=>r.json()).then(d=>{
      const p=d.prize;
      const pb=document.getElementById('pb-name'), pbn=document.getElementById('fs-pb-name');
      if(p && pb) pb.textContent=p.name;
      if(p && pbn) pbn.textContent=p.name;
    });
  }
  function $s(id,v){ const e=document.getElementById(id); if(e) e.textContent=v; }

  // ── CONFETTI ──
  function confetti(){
    const c=['#FFD700','#FFA500','#fff','#ff6b6b','#4ecdc4','#a78bfa'];
    for(let i=0;i<160;i++){
      const el=document.createElement('div'); el.className='confetti-piece';
      const sz=6+Math.random()*12;
      el.style.cssText=`left:${Math.random()*100}vw;width:${sz}px;height:${sz*(1.4+Math.random())}px;background:${c[Math.floor(Math.random()*c.length)]};animation-duration:${1.5+Math.random()*2.5}s;animation-delay:${Math.random()*.9}s;transform:rotate(${Math.random()*360}deg);`;
      document.body.appendChild(el); el.addEventListener('animationend',()=>el.remove());
    }
  }
})();
</script>
</body>
</html>
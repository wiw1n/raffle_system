<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>👥 Participants — Raffle System</title>
<style>
  :root {
    --gold:#FFD700; --gold2:#FFA500; --darker:#050510; --dark:#0a0a1a;
    --card-bg:rgba(255,255,255,.05); --border:rgba(255,215,0,.25);
    --green:#2ed573; --red:#ff4757; --blue:#4a90e2; --purple:#7b2ff7;
    --row-hover:rgba(255,255,255,.04);
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  body{background:var(--darker);color:#fff;font-family:'Segoe UI',sans-serif;min-height:100vh;}
  body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
    background:radial-gradient(ellipse at 15% 40%,rgba(123,47,247,.07) 0%,transparent 55%),
               radial-gradient(ellipse at 85% 70%,rgba(74,144,226,.06) 0%,transparent 50%);}
  .wrapper{position:relative;z-index:1;max-width:1260px;margin:0 auto;padding:20px 24px;}

  /* NAV */
  .topbar{display:flex;align-items:center;justify-content:space-between;padding-bottom:22px;}
  .brand{font-size:1rem;font-weight:900;letter-spacing:3px;background:linear-gradient(135deg,#fff,var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none;}
  .nav-links{display:flex;gap:8px;}
  .nav-link{padding:7px 16px;border-radius:8px;font-size:.82rem;font-weight:600;letter-spacing:.5px;text-decoration:none;border:1px solid transparent;transition:all .2s;}
  .nav-link.active{background:linear-gradient(135deg,var(--gold),var(--gold2));color:#000;}
  .nav-link:not(.active){color:rgba(255,255,255,.55);border-color:rgba(255,255,255,.15);}
  .nav-link:not(.active):hover{color:#fff;border-color:rgba(255,255,255,.4);}

  /* PAGE HEADER */
  .page-header{margin-bottom:24px;}
  .page-header h1{font-size:1.8rem;font-weight:900;letter-spacing:2px;background:linear-gradient(135deg,#fff,var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;filter:drop-shadow(0 0 20px rgba(255,215,0,.3));}
  .page-header p{font-size:.82rem;color:rgba(255,255,255,.4);margin-top:4px;}

  /* ALERTS */
  .alert{padding:12px 16px;border-radius:10px;font-size:.88rem;margin-bottom:16px;display:flex;align-items:center;gap:10px;}
  .alert-success{background:rgba(46,213,115,.1);border:1px solid rgba(46,213,115,.3);color:var(--green);}
  .alert-error  {background:rgba(255,71,87,.1); border:1px solid rgba(255,71,87,.3); color:var(--red);}

  /* PANEL */
  .panel{background:var(--card-bg);border:1px solid var(--border);border-radius:16px;padding:22px;backdrop-filter:blur(10px);margin-bottom:24px;}
  .panel-title{font-size:.68rem;letter-spacing:3px;color:var(--gold);text-transform:uppercase;margin-bottom:16px;opacity:.8;display:flex;align-items:center;justify-content:space-between;}

  /* FORM */
  .form-group{margin-bottom:13px;}
  .form-group label{display:block;font-size:.72rem;font-weight:600;letter-spacing:1px;color:rgba(255,255,255,.5);text-transform:uppercase;margin-bottom:5px;}
  .form-control{width:100%;padding:10px 13px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:9px;color:#fff;font-size:.92rem;outline:none;transition:border .2s,background .2s;}
  .form-control:focus{border-color:var(--gold);background:rgba(255,215,0,.06);}
  .form-control::placeholder{color:rgba(255,255,255,.3);}
  .form-row{display:flex;gap:10px;}
  .form-row .form-group{flex:1;}

  /* EDIT BANNER */
  .edit-banner{display:none;background:rgba(74,144,226,.1);border:1px solid rgba(74,144,226,.3);border-radius:8px;padding:9px 13px;font-size:.82rem;color:var(--blue);margin-bottom:13px;align-items:center;gap:10px;}
  .edit-banner.show{display:flex;}
  .cancel-edit{margin-left:auto;cursor:pointer;font-size:.75rem;color:rgba(255,255,255,.4);text-decoration:underline;}
  .cancel-edit:hover{color:#fff;}

  /* BUTTONS */
  .btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 18px;border:none;border-radius:9px;font-size:.88rem;font-weight:700;letter-spacing:.5px;cursor:pointer;transition:all .2s;}
  .btn-primary{background:linear-gradient(135deg,var(--blue),var(--purple));color:#fff;box-shadow:0 4px 16px rgba(74,144,226,.3);}
  .btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(74,144,226,.5);}
  .btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold2));color:#000;box-shadow:0 4px 16px rgba(255,215,0,.3);}
  .btn-gold:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(255,215,0,.5);}
  .btn-green{background:linear-gradient(135deg,#2ed573,#1abc9c);color:#000;box-shadow:0 4px 14px rgba(46,213,115,.3);}
  .btn-green:hover{transform:translateY(-1px);}
  .btn-w100{width:100%;justify-content:center;padding:11px;}
  .btn-sm{padding:6px 12px;font-size:.78rem;}
  .btn-outline{background:transparent;color:rgba(255,255,255,.55);border:1px solid rgba(255,255,255,.2);}
  .btn-outline:hover{color:#fff;border-color:rgba(255,255,255,.5);}
  .btn-danger-sm{background:transparent;color:var(--red);border:1px solid rgba(255,71,87,.3);padding:5px 10px;font-size:.75rem;border-radius:7px;cursor:pointer;transition:all .2s;}
  .btn-danger-sm:hover{background:rgba(255,71,87,.1);}

  /* STATS BAR */
  .stats-bar{display:flex;gap:10px;margin-bottom:16px;}
  .stat{flex:1;text-align:center;padding:10px 8px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;}
  .stat .val{font-size:1.4rem;font-weight:900;color:var(--gold);}
  .stat .lbl{font-size:.6rem;color:rgba(255,255,255,.4);letter-spacing:2px;}

  /* TOOLBAR */
  .toolbar{display:flex;gap:10px;margin-bottom:13px;flex-wrap:wrap;}
  .search-box{flex:1;min-width:140px;display:flex;align-items:center;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:9px;padding:0 12px;gap:8px;}
  .search-box input{flex:1;background:transparent;border:none;outline:none;color:#fff;font-size:.88rem;padding:9px 0;}
  .search-box input::placeholder{color:rgba(255,255,255,.3);}
  .ftab{padding:7px 13px;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.5);background:transparent;transition:all .2s;}
  .ftab.active,.ftab:hover{color:#fff;border-color:var(--gold);background:rgba(255,215,0,.08);}

  /* TABLE */
  .table-wrap{border-radius:10px;overflow:hidden;border:1px solid rgba(255,255,255,.08);max-height:400px;overflow-y:auto;scrollbar-width:thin;scrollbar-color:var(--gold) transparent;}
  .table-wrap::-webkit-scrollbar{width:4px;}
  .table-wrap::-webkit-scrollbar-thumb{background:var(--gold);border-radius:4px;}
  table{width:100%;border-collapse:collapse;}
  thead th{background:rgba(255,255,255,.06);padding:9px 13px;font-size:.65rem;letter-spacing:2px;font-weight:700;color:rgba(255,255,255,.45);text-transform:uppercase;text-align:left;white-space:nowrap;position:sticky;top:0;z-index:2;}
  tbody tr{border-top:1px solid rgba(255,255,255,.05);transition:background .15s;}
  tbody tr:hover{background:var(--row-hover);}
  tbody tr.won-row{opacity:.55;}
  td{padding:10px 13px;font-size:.86rem;vertical-align:middle;}
  td.td-num{color:rgba(255,255,255,.28);font-size:.72rem;width:36px;}
  td.td-name{font-weight:600;}
  td.td-name.crossed{text-decoration:line-through;color:rgba(255,255,255,.4);}
  td.td-extra{color:rgba(255,255,255,.5);font-size:.8rem;}
  td.td-prize{font-size:.8rem;}
  .badge{display:inline-block;padding:2px 9px;border-radius:20px;font-size:.62rem;font-weight:700;letter-spacing:1px;}
  .badge-eligible{background:rgba(46,213,115,.12);color:var(--green);border:1px solid rgba(46,213,115,.3);}
  .badge-won{background:rgba(255,215,0,.12);color:var(--gold);border:1px solid rgba(255,215,0,.3);}
  .prize-pill{background:rgba(255,215,0,.1);color:var(--gold);border:1px solid rgba(255,215,0,.25);padding:2px 9px;border-radius:20px;font-size:.7rem;font-weight:600;}
  .action-btn{background:transparent;border:1px solid rgba(255,255,255,.15);border-radius:7px;cursor:pointer;padding:4px 9px;font-size:.78rem;color:rgba(255,255,255,.5);transition:all .2s;margin-left:3px;}
  .action-btn.edit-btn:hover{border-color:var(--blue);color:var(--blue);background:rgba(74,144,226,.1);}
  .action-btn.del-btn:hover{border-color:var(--red);color:var(--red);background:rgba(255,71,87,.1);}

  /* EMPTY STATE */
  .empty-state{text-align:center;padding:50px 20px;color:rgba(255,255,255,.3);font-size:.88rem;}
  .empty-state .ico{font-size:2.5rem;margin-bottom:10px;}

  /* ════════════════════════════
     PRIZES TABLE
  ════════════════════════════ */
  .prizes-table-wrap{border-radius:10px;overflow:hidden;border:1px solid rgba(255,215,0,.15);}
  .prizes-table-wrap table{width:100%;border-collapse:collapse;}
  .prizes-table-wrap thead th{background:rgba(255,215,0,.06);padding:9px 14px;font-size:.64rem;letter-spacing:2px;font-weight:700;color:rgba(255,215,0,.55);text-transform:uppercase;text-align:left;white-space:nowrap;}
  .prizes-table-wrap tbody tr{border-top:1px solid rgba(255,215,0,.08);transition:background .15s;}
  .prizes-table-wrap tbody tr:hover{background:rgba(255,215,0,.03);}
  .prizes-table-wrap td{padding:11px 14px;font-size:.86rem;vertical-align:middle;}
  .td-prize-name{font-weight:700;}
  .td-prize-desc{color:rgba(255,255,255,.45);font-size:.78rem;}
  .td-prize-qty .qty-val{font-weight:700;color:var(--gold);}
  .td-prize-qty .won-val{color:rgba(255,255,255,.4);font-size:.75rem;}

  /* ACTIVE TOGGLE BUTTON */
  .btn-set-active {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 20px; font-size: .78rem; font-weight: 700;
    cursor: pointer; transition: all .25s; border: 2px solid transparent; letter-spacing: .3px;
  }
  .btn-set-active.inactive {
    background: rgba(255,255,255,.06);
    border-color: rgba(255,255,255,.15);
    color: rgba(255,255,255,.45);
  }
  .btn-set-active.inactive:hover {
    background: rgba(255,215,0,.1);
    border-color: rgba(255,215,0,.4);
    color: var(--gold);
  }
  .btn-set-active.active-state {
    background: linear-gradient(135deg, rgba(255,215,0,.25), rgba(255,100,0,.15));
    border-color: var(--gold);
    color: var(--gold);
    box-shadow: 0 0 16px rgba(255,215,0,.3);
    animation: activePulse 2s ease-in-out infinite;
  }
  @keyframes activePulse {
    0%,100%{ box-shadow: 0 0 12px rgba(255,215,0,.25); }
    50%     { box-shadow: 0 0 24px rgba(255,215,0,.55); }
  }
  .btn-set-active .dot {
    width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
  }
  .btn-set-active.inactive .dot   { background: rgba(255,255,255,.3); }
  .btn-set-active.active-state .dot { background: var(--gold); box-shadow: 0 0 6px var(--gold); }

  /* prize form inline */
  .prize-form-row{display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;}
  .prize-form-row .form-control{flex:1;min-width:120px;padding:9px 12px;font-size:.85rem;}
  .prize-form-row .form-control.qty{width:70px;flex:none;}

  /* TOAST */
  #toast{position:fixed;bottom:28px;right:28px;background:rgba(20,20,40,.95);border:1px solid var(--border);color:#fff;padding:12px 20px;border-radius:12px;font-size:.88rem;backdrop-filter:blur(12px);transform:translateY(80px);opacity:0;transition:all .3s;z-index:999;max-width:300px;}
  #toast.show{transform:translateY(0);opacity:1;}
  #toast.t-success{border-color:rgba(46,213,115,.4);}
  #toast.t-error{border-color:rgba(255,71,87,.4);}

  /* MODAL */
  #modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:200;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
  #modal.show{display:flex;}
  .modal-box{background:#10101f;border:1px solid var(--border);border-radius:16px;padding:28px 32px;max-width:360px;width:90%;text-align:center;}
  .modal-box .ico{font-size:2.5rem;margin-bottom:10px;}
  .modal-box h3{font-size:1.05rem;margin-bottom:7px;}
  .modal-box p{color:rgba(255,255,255,.5);font-size:.86rem;margin-bottom:18px;}
  .modal-actions{display:flex;gap:10px;}
  .btn-modal-cancel{flex:1;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,.2);background:transparent;color:rgba(255,255,255,.6);cursor:pointer;font-size:.88rem;transition:all .2s;}
  .btn-modal-cancel:hover{color:#fff;border-color:rgba(255,255,255,.5);}
  .btn-modal-confirm{flex:1;padding:10px;border-radius:8px;border:none;background:var(--red);color:#fff;cursor:pointer;font-size:.88rem;font-weight:700;transition:all .2s;}
  .btn-modal-confirm:hover{background:#ff2233;}

  /* PRIZE EDIT MODAL */
  #prize-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:200;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
  #prize-modal.show{display:flex;}
  .prize-modal-box{background:#10101f;border:1px solid rgba(255,215,0,.3);border-radius:16px;padding:26px 28px;max-width:440px;width:95%;}
  .prize-modal-box h3{font-size:1rem;font-weight:700;color:var(--gold);margin-bottom:16px;letter-spacing:1px;}

  /* TWO-COLUMN LAYOUT */
  .two-col{display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;}
  @media(max-width:880px){.two-col{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="wrapper">

  <!-- NAV -->
  <div class="topbar">
    <a href="<?= site_url('raffle') ?>" class="brand">🎰 RAFFLE DRAW</a>
    <div class="nav-links">
      <a href="<?= site_url('raffle') ?>"              class="nav-link">Draw</a>
      <a href="<?= site_url('raffle/participants') ?>" class="nav-link active">Participants</a>
    </div>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($this->session->flashdata('success')) ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($this->session->flashdata('error')) ?></div>
  <?php endif; ?>

  <div class="page-header">
    <h1>👥 Participants &amp; Prizes</h1>
    <p>Manage entries, set prizes, and export winners</p>
  </div>

  <div class="two-col">

    <!-- ══ LEFT COLUMN — ADD PARTICIPANT ══ -->
    <div>

      <!-- ADD / EDIT PARTICIPANT -->
      <div class="panel">
        <div class="panel-title"><span id="form-title">➕ Add Participant</span></div>

        <div class="edit-banner" id="edit-banner">
          ✏️ Editing: <strong id="editing-name"></strong>
          <span class="cancel-edit" onclick="cancelEdit()">✕ Cancel</span>
        </div>

        <form id="participant-form">
          <input type="hidden" id="entry-id">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" class="form-control" id="field-name" placeholder="e.g. Juan dela Cruz" autocomplete="off" required>
          </div>
          <div class="form-group">
            <label>Extra Info <span style="opacity:.5;">(optional)</span></label>
            <input type="text" class="form-control" id="field-extra" placeholder="e.g. Department, Employee ID…">
          </div>
          <button type="submit" class="btn btn-primary btn-w100">
            <span id="submit-label">➕ Add Participant</span>
          </button>
        </form>

        <div style="border-top:1px solid rgba(255,255,255,.08);margin:18px 0;"></div>

        <!-- BULK IMPORT -->
        <div style="font-size:.68rem;letter-spacing:3px;color:var(--gold);text-transform:uppercase;margin-bottom:12px;opacity:.8;">📥 Bulk Import</div>
        <?= form_open_multipart(site_url('raffle/upload')) ?>
          <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" style="font-size:.8rem;color:rgba(255,255,255,.55);width:100%;margin-bottom:10px;">
          <button type="submit" class="btn btn-gold btn-w100">⬆ Upload Excel / CSV</button>
          <p style="font-size:.7rem;color:rgba(255,255,255,.3);margin-top:8px;line-height:1.7;">Col A = Name &nbsp;·&nbsp; Col B = Extra Info &nbsp;·&nbsp; Row 1 skipped<br>⚠️ Replaces all current entries.</p>
        <?= form_close() ?>
      </div>

    </div><!-- /left-col -->

    <!-- ══ RIGHT COLUMN — PARTICIPANTS TABLE + PRIZES ══ -->
    <div>
      <div class="panel">
        <div class="panel-title">
          <span>📋 All Participants (<span id="list-count"><?= count($entries) ?></span>)</span>
          <a href="<?= site_url('raffle/export_winners') ?>" class="btn btn-sm btn-green" style="text-decoration:none;">⬇ Export Winners</a>
        </div>

        <!-- Stats -->
        <div class="stats-bar">
          <div class="stat"><div class="val" id="s-total"><?= count($entries) ?></div><div class="lbl">TOTAL</div></div>
          <div class="stat"><div class="val" id="s-eligible"><?= count(array_filter($entries, fn($e) => !$e['has_won'])) ?></div><div class="lbl">ELIGIBLE</div></div>
          <div class="stat"><div class="val" id="s-won"><?= count(array_filter($entries, fn($e) => $e['has_won'])) ?></div><div class="lbl">WON</div></div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
          <div class="search-box">
            <span style="color:rgba(255,255,255,.3);">🔍</span>
            <input type="text" id="search-input" placeholder="Search name…">
          </div>
          <button class="ftab active" data-filter="all">All</button>
          <button class="ftab" data-filter="eligible">Eligible</button>
          <button class="ftab" data-filter="won">Won</button>
        </div>

        <!-- Table -->
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Extra Info</th>
                <th>Prize Won</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="table-body">
              <?php if (empty($entries)): ?>
                <tr><td colspan="6"><div class="empty-state"><div class="ico">🙈</div>No participants yet.</div></td></tr>
              <?php else: ?>
                <?php foreach ($entries as $i => $e): ?>
                  <tr class="entry-row <?= $e['has_won'] ? 'won-row' : '' ?>"
                      data-id="<?= $e['id'] ?>"
                      data-name="<?= htmlspecialchars($e['name'], ENT_QUOTES) ?>"
                      data-extra="<?= htmlspecialchars($e['extra_info'] ?? '', ENT_QUOTES) ?>"
                      data-won="<?= $e['has_won'] ?>">
                    <td class="td-num"><?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></td>
                    <td class="td-name <?= $e['has_won'] ? 'crossed' : '' ?>"><?= htmlspecialchars($e['name']) ?></td>
                    <td class="td-extra"><?= htmlspecialchars($e['extra_info'] ?? '—') ?></td>
                    <td class="td-prize">
                      <?php if ($e['prize_name']): ?>
                        <span class="prize-pill">🎁 <?= htmlspecialchars($e['prize_name']) ?></span>
                      <?php else: ?>
                        <span style="color:rgba(255,255,255,.2);font-size:.78rem;">—</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($e['has_won']): ?>
                        <span class="badge badge-won">🏆 Won</span>
                      <?php else: ?>
                        <span class="badge badge-eligible">✅ Eligible</span>
                      <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;">
                      <button class="action-btn edit-btn" onclick="editRow(this)" title="Edit">✏️</button>
                      <button class="action-btn del-btn"  onclick="confirmDelEntry(<?= $e['id'] ?>, '<?= htmlspecialchars($e['name'], ENT_QUOTES) ?>')" title="Delete">🗑</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Bottom actions -->
        <div style="display:flex;gap:8px;margin-top:14px;justify-content:flex-end;">
          <a href="<?= site_url('raffle/reset_winners') ?>" class="btn btn-sm btn-outline" style="text-decoration:none;" onclick="return confirm('Reset all winners?')">↺ Reset Winners</a>
          <a href="<?= site_url('raffle/clear_all') ?>"     class="btn btn-sm" style="text-decoration:none;background:transparent;color:var(--red);border:1px solid rgba(255,71,87,.3);border-radius:8px;padding:6px 14px;font-size:.78rem;font-weight:700;" onclick="return confirm('Delete ALL entries?')">🗑 Clear All</a>
        </div>
      </div>

      <!-- ══ PRIZES PANEL ══ -->
      <div class="panel" style="margin-top:24px;">
        <div class="panel-title">
          <span>🎁 Prizes</span>
          <button class="btn btn-sm btn-gold" onclick="openAddPrize()">＋ Add Prize</button>
        </div>

        <?php if (empty($prizes)): ?>
          <div class="empty-state"><div class="ico">🎁</div>No prizes yet. Add one above.</div>
        <?php else: ?>
          <div class="prizes-table-wrap">
            <table id="prizes-table">
              <thead>
                <tr>
                  <th>Prize</th>
                  <th>Slots</th>
                  <th>Won</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="prizes-tbody">
                <?php foreach ($prizes as $p):
                  $is_active = (int)$p['is_active'];
                ?>
                <tr id="prize-row-<?= $p['id'] ?>"
                    data-id="<?= $p['id'] ?>"
                    data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>"
                    data-desc="<?= htmlspecialchars($p['description'] ?? '', ENT_QUOTES) ?>"
                    data-qty="<?= $p['quantity'] ?>"
                    data-active="<?= $is_active ?>">
                  <td>
                    <div class="td-prize-name"><?= htmlspecialchars($p['name']) ?></div>
                    <?php if ($p['description']): ?>
                      <div class="td-prize-desc"><?= htmlspecialchars($p['description']) ?></div>
                    <?php endif; ?>
                  </td>
                  <td class="td-prize-qty"><span class="qty-val"><?= (int)$p['quantity'] ?></span></td>
                  <td><span class="prize-won-count" style="color:rgba(255,255,255,.4);font-size:.8rem;">—</span></td>
                  <td>
                    <button class="btn-set-active <?= $is_active ? 'active-state' : 'inactive' ?>"
                            onclick="setActivePrize(<?= $p['id'] ?>)"
                            id="active-btn-<?= $p['id'] ?>">
                      <span class="dot"></span>
                      <span class="lbl-txt"><?= $is_active ? 'ACTIVE' : 'Set Active' ?></span>
                    </button>
                  </td>
                  <td style="white-space:nowrap;">
                    <button class="action-btn edit-btn" onclick="editPrize(<?= $p['id'] ?>)" title="Edit">✏️</button>
                    <button class="action-btn del-btn"  onclick="confirmDelPrize(<?= $p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>')" title="Delete">🗑</button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

    </div><!-- /right-col -->

  </div><!-- /two-col -->
</div><!-- /wrapper -->

<!-- DELETE CONFIRM MODAL -->
<div id="modal">
  <div class="modal-box">
    <div class="ico">⚠️</div>
    <h3>Confirm Delete</h3>
    <p id="modal-msg">This cannot be undone.</p>
    <div class="modal-actions">
      <button class="btn-modal-cancel" onclick="closeModal()">Cancel</button>
      <button class="btn-modal-confirm" id="modal-confirm">Delete</button>
    </div>
  </div>
</div>

<!-- PRIZE ADD/EDIT MODAL -->
<div id="prize-modal">
  <div class="prize-modal-box">
    <h3 id="prize-modal-title">➕ Add Prize</h3>
    <input type="hidden" id="pm-id">
    <div class="form-group">
      <label>Prize Name *</label>
      <input type="text" class="form-control" id="pm-name" placeholder="e.g. Grand Prize, 1st Prize…">
    </div>
    <div class="form-group">
      <label>Description <span style="opacity:.5;">(optional)</span></label>
      <input type="text" class="form-control" id="pm-desc" placeholder="e.g. iPhone 16, Gift Card…">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Slots (winners)</label>
        <input type="number" class="form-control" id="pm-qty" value="1" min="1">
      </div>
      <div class="form-group">
        <label>Sort Order</label>
        <input type="number" class="form-control" id="pm-sort" value="0" min="0">
      </div>
    </div>
    <div style="display:flex;gap:10px;margin-top:4px;">
      <button class="btn btn-outline" onclick="closePrizeModal()" style="flex:1;">Cancel</button>
      <button class="btn btn-gold" onclick="submitPrize()" style="flex:1;" id="pm-submit">💾 Save Prize</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast"></div>

<script>
const BASE = '<?= site_url() ?>';
let editMode = false;
let modalAction = null;
let currentFilter = 'all';

// ════════════════════════════════
// PARTICIPANTS CRUD
// ════════════════════════════════

document.getElementById('participant-form').addEventListener('submit', function(e){
  e.preventDefault();
  const id    = document.getElementById('entry-id').value;
  const name  = document.getElementById('field-name').value.trim();
  const extra = document.getElementById('field-extra').value.trim();
  if(!name){ toast('Name is required.','error'); return; }
  const ep   = id ? 'raffle/ajax_update_participant' : 'raffle/ajax_add_participant';
  const body = new URLSearchParams({name, extra_info:extra});
  if(id) body.append('id',id);
  fetch(BASE+ep,{method:'POST',body}).then(r=>r.json()).then(d=>{
    if(!d.success){ toast(d.message,'error'); return; }
    if(id){ updateEntryRow(d.entry); toast('Entry updated!','success'); cancelEdit(); }
    else  { appendEntryRow(d.entry); toast(`"${name}" added!`,'success'); document.getElementById('field-name').value=''; document.getElementById('field-extra').value=''; }
    refreshStats(); reNumber();
  }).catch(()=>toast('Server error.','error'));
});

function editRow(btn){
  const row=btn.closest('tr');
  document.getElementById('entry-id').value=row.dataset.id;
  document.getElementById('field-name').value=row.dataset.name;
  document.getElementById('field-extra').value=row.dataset.extra;
  document.getElementById('submit-label').textContent='💾 Save Changes';
  document.getElementById('form-title').textContent='✏️ Edit Participant';
  document.getElementById('editing-name').textContent=row.dataset.name;
  document.getElementById('edit-banner').classList.add('show');
  document.getElementById('field-name').focus();
  editMode=true;
}
function cancelEdit(){
  document.getElementById('entry-id').value='';
  document.getElementById('field-name').value='';
  document.getElementById('field-extra').value='';
  document.getElementById('submit-label').textContent='➕ Add Participant';
  document.getElementById('form-title').textContent='➕ Add Participant';
  document.getElementById('edit-banner').classList.remove('show');
  editMode=false;
}

function updateEntryRow(e){
  const row=document.querySelector(`tr[data-id="${e.id}"]`); if(!row) return;
  row.dataset.name=e.name; row.dataset.extra=e.extra_info||'';
  row.querySelector('.td-name').textContent=e.name;
  row.querySelector('.td-extra').textContent=e.extra_info||'—';
}
function appendEntryRow(e){
  const tbody=document.getElementById('table-body');
  const empty=tbody.querySelector('td[colspan]'); if(empty) empty.closest('tr').remove();
  const won=e.has_won==1;
  const idx=tbody.querySelectorAll('tr').length+1;
  const tr=document.createElement('tr');
  tr.className='entry-row'+(won?' won-row':'');
  tr.dataset.id=e.id; tr.dataset.name=e.name; tr.dataset.extra=e.extra_info||''; tr.dataset.won=e.has_won;
  tr.innerHTML=`
    <td class="td-num">${String(idx).padStart(2,'0')}</td>
    <td class="td-name${won?' crossed':''}">${esc(e.name)}</td>
    <td class="td-extra">${esc(e.extra_info)||'—'}</td>
    <td class="td-prize"><span style="color:rgba(255,255,255,.2);font-size:.78rem;">—</span></td>
    <td><span class="badge ${won?'badge-won':'badge-eligible'}">${won?'🏆 Won':'✅ Eligible'}</span></td>
    <td style="white-space:nowrap;">
      <button class="action-btn edit-btn" onclick="editRow(this)" title="Edit">✏️</button>
      <button class="action-btn del-btn"  onclick="confirmDelEntry(${e.id},'${esc(e.name)}')" title="Delete">🗑</button>
    </td>`;
  tbody.appendChild(tr);
  tr.style.background='rgba(255,215,0,.07)';
  setTimeout(()=>{tr.style.transition='background 1s';tr.style.background='';},50);
  applyFilter(currentFilter); applySearch(document.getElementById('search-input').value);
}

function confirmDelEntry(id, name){
  document.getElementById('modal-msg').textContent=`Remove "${name}" from the raffle?`;
  modalAction=()=>doDelEntry(id);
  document.getElementById('modal').classList.add('show');
}
function doDelEntry(id){
  fetch(BASE+'raffle/ajax_delete_participant',{method:'POST',body:new URLSearchParams({id})})
    .then(r=>r.json()).then(d=>{
      if(!d.success){toast(d.message||'Error','error');return;}
      const row=document.querySelector(`tr[data-id="${id}"]`);
      if(row){row.style.transition='opacity .3s,transform .3s';row.style.opacity='0';row.style.transform='translateX(20px)';setTimeout(()=>{row.remove();reNumber();refreshStats();},300);}
      toast('Entry removed.','success');
    }).catch(()=>toast('Server error.','error'));
}

// ════════════════════════════════
// PRIZES
// ════════════════════════════════

// Load won counts on page load
(function loadPrizeWonCounts(){
  fetch(BASE+'raffle/get_prizes').then(r=>r.json()).then(prizes=>{
    prizes.forEach(p=>{
      const row=document.getElementById('prize-row-'+p.id); if(!row) return;
      const wc=row.querySelector('.prize-won-count');
      if(wc) wc.textContent=`${p.won_count}/${p.quantity} awarded`;
    });
  });
})();

// Toggle a prize active/inactive
function setActivePrize(id){
  const btn=document.getElementById('active-btn-'+id);
  const isAlreadyActive = btn && btn.classList.contains('active-state');
  const sendId = isAlreadyActive ? 0 : id; // 0 = deactivate all

  fetch(BASE+'raffle/ajax_set_active_prize',{method:'POST',body:new URLSearchParams({id:sendId})})
    .then(r=>r.json()).then(d=>{
      if(!d.success){toast('Error setting prize.','error');return;}
      // Reset all buttons to inactive
      document.querySelectorAll('.btn-set-active').forEach(b=>{
        b.classList.remove('active-state'); b.classList.add('inactive');
        b.querySelector('.lbl-txt').textContent='Set Active';
        b.closest('tr').dataset.active='0';
      });
      // If activating (not deactivating), mark the chosen one
      if(!isAlreadyActive && d.prize){
        const activeBtn=document.getElementById('active-btn-'+id);
        if(activeBtn){
          activeBtn.classList.remove('inactive'); activeBtn.classList.add('active-state');
          activeBtn.querySelector('.lbl-txt').textContent='ACTIVE';
          activeBtn.closest('tr').dataset.active='1';
        }
        toast(`"${d.prize.name}" is now the active prize!`,'success');
      } else {
        toast('Prize deactivated.','success');
      }
    }).catch(()=>toast('Server error.','error'));
}

function openAddPrize(){
  document.getElementById('pm-id').value='';
  document.getElementById('pm-name').value='';
  document.getElementById('pm-desc').value='';
  document.getElementById('pm-qty').value='1';
  document.getElementById('pm-sort').value='0';
  document.getElementById('prize-modal-title').textContent='➕ Add Prize';
  document.getElementById('pm-submit').textContent='➕ Add Prize';
  document.getElementById('prize-modal').classList.add('show');
}
function editPrize(id){
  const row=document.getElementById('prize-row-'+id); if(!row) return;
  document.getElementById('pm-id').value=id;
  document.getElementById('pm-name').value=row.dataset.name;
  document.getElementById('pm-desc').value=row.dataset.desc;
  document.getElementById('pm-qty').value=row.dataset.qty;
  document.getElementById('pm-sort').value='0';
  document.getElementById('prize-modal-title').textContent='✏️ Edit Prize';
  document.getElementById('pm-submit').textContent='💾 Save Changes';
  document.getElementById('prize-modal').classList.add('show');
}
function closePrizeModal(){ document.getElementById('prize-modal').classList.remove('show'); }

function submitPrize(){
  const id   = document.getElementById('pm-id').value;
  const name = document.getElementById('pm-name').value.trim();
  const desc = document.getElementById('pm-desc').value.trim();
  const qty  = document.getElementById('pm-qty').value;
  const sort = document.getElementById('pm-sort').value;
  if(!name){toast('Prize name is required.','error');return;}
  const ep   = id ? 'raffle/ajax_update_prize' : 'raffle/ajax_add_prize';
  const body = new URLSearchParams({name,description:desc,quantity:qty,sort_order:sort});
  if(id) body.append('id',id);
  fetch(BASE+ep,{method:'POST',body}).then(r=>r.json()).then(d=>{
    if(!d.success){toast(d.message,'error');return;}
    closePrizeModal();
    toast(id?'Prize updated!':'Prize added!','success');
    if(id) updatePrizeRow(d.prize); else appendPrizeRow(d.prize);
  }).catch(()=>toast('Server error.','error'));
}

function updatePrizeRow(p){
  const row=document.getElementById('prize-row-'+p.id); if(!row) return;
  row.dataset.name=p.name; row.dataset.desc=p.description||''; row.dataset.qty=p.quantity;
  const td=row.querySelector('td:first-child');
  td.innerHTML=`<div class="td-prize-name">${esc(p.name)}</div>`+(p.description?`<div class="td-prize-desc">${esc(p.description)}</div>`:'');
  row.querySelector('.qty-val').textContent=p.quantity;
}

function appendPrizeRow(p){
  const tbody=document.getElementById('prizes-tbody');
  if(!tbody){location.reload();return;} // fallback
  const tr=document.createElement('tr');
  tr.id='prize-row-'+p.id;
  tr.dataset.id=p.id; tr.dataset.name=p.name; tr.dataset.desc=p.description||''; tr.dataset.qty=p.quantity; tr.dataset.active='0';
  tr.innerHTML=`
    <td>
      <div class="td-prize-name">${esc(p.name)}</div>
      ${p.description?`<div class="td-prize-desc">${esc(p.description)}</div>`:''}
    </td>
    <td class="td-prize-qty"><span class="qty-val">${p.quantity}</span></td>
    <td><span class="prize-won-count" style="color:rgba(255,255,255,.4);font-size:.8rem;">0/${p.quantity} awarded</span></td>
    <td>
      <button class="btn-set-active inactive" onclick="setActivePrize(${p.id})" id="active-btn-${p.id}">
        <span class="dot"></span><span class="lbl-txt">Set Active</span>
      </button>
    </td>
    <td style="white-space:nowrap;">
      <button class="action-btn edit-btn" onclick="editPrize(${p.id})" title="Edit">✏️</button>
      <button class="action-btn del-btn"  onclick="confirmDelPrize(${p.id},'${esc(p.name)}')" title="Delete">🗑</button>
    </td>`;
  tbody.appendChild(tr);
  tr.style.background='rgba(255,215,0,.06)';
  setTimeout(()=>{tr.style.transition='background 1s';tr.style.background='';},50);

  // Show table if it was hidden (no prizes state)
  const empty=document.querySelector('.prizes-table-wrap ~ .empty-state');
  if(empty) empty.remove();
}

function confirmDelPrize(id, name){
  document.getElementById('modal-msg').textContent=`Delete prize "${name}"?`;
  modalAction=()=>doDelPrize(id);
  document.getElementById('modal').classList.add('show');
}
function doDelPrize(id){
  fetch(BASE+'raffle/ajax_delete_prize',{method:'POST',body:new URLSearchParams({id})})
    .then(r=>r.json()).then(d=>{
      if(!d.success){toast('Error deleting prize.','error');return;}
      const row=document.getElementById('prize-row-'+id);
      if(row){row.style.transition='opacity .3s';row.style.opacity='0';setTimeout(()=>row.remove(),300);}
      toast('Prize deleted.','success');
    }).catch(()=>toast('Server error.','error'));
}

// ════════════════════════════════
// MODAL
// ════════════════════════════════
document.getElementById('modal-confirm').addEventListener('click',()=>{ if(modalAction){modalAction();closeModal();} });
function closeModal(){ document.getElementById('modal').classList.remove('show'); modalAction=null; }
document.getElementById('modal').addEventListener('click',e=>{ if(e.target===document.getElementById('modal')) closeModal(); });
document.getElementById('prize-modal').addEventListener('click',e=>{ if(e.target===document.getElementById('prize-modal')) closePrizeModal(); });

// ════════════════════════════════
// SEARCH & FILTER
// ════════════════════════════════
document.getElementById('search-input').addEventListener('input',function(){ applySearch(this.value); });
function applySearch(q){ q=q.toLowerCase(); document.querySelectorAll('.entry-row').forEach(r=>{ r.style.display=(r.dataset.name.toLowerCase().includes(q)||(r.dataset.extra||'').toLowerCase().includes(q))?'':'none'; }); }

document.querySelectorAll('.ftab').forEach(btn=>btn.addEventListener('click',function(){
  document.querySelectorAll('.ftab').forEach(b=>b.classList.remove('active')); this.classList.add('active');
  currentFilter=this.dataset.filter; applyFilter(currentFilter);
}));
function applyFilter(f){
  document.querySelectorAll('.entry-row').forEach(r=>{
    const won=r.dataset.won==1;
    r.style.display=(f==='all'||(f==='won'&&won)||(f==='eligible'&&!won))?'':'none';
  });
  applySearch(document.getElementById('search-input').value);
}

// ════════════════════════════════
// STATS
// ════════════════════════════════
function refreshStats(){
  const rows=document.querySelectorAll('.entry-row');
  const total=rows.length, won=[...rows].filter(r=>r.dataset.won==1).length;
  $s('s-total',total); $s('s-eligible',total-won); $s('s-won',won); $s('list-count',total);
}
function reNumber(){ let n=1; document.querySelectorAll('.entry-row').forEach(r=>{ const c=r.querySelector('.td-num'); if(c) c.textContent=String(n++).padStart(2,'0'); }); }
function $s(id,v){ const e=document.getElementById(id); if(e) e.textContent=v; }

// ════════════════════════════════
// TOAST
// ════════════════════════════════
let toastTimer;
function toast(msg,type='success'){
  const t=document.getElementById('toast');
  t.textContent=(type==='success'?'✅ ':'⚠️ ')+msg;
  t.className=`show t-${type}`; clearTimeout(toastTimer);
  toastTimer=setTimeout(()=>t.className='',3200);
}

// ════════════════════════════════
// ESCAPE HELPERS
// ════════════════════════════════
function esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }
</script>
</body>
</html>
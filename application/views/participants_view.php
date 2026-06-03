<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>👥 Participants & Prizes — Raffle System</title>
<style>
  :root {
    --gold:   #FFD700;
    --gold2:  #FFA500;
    --dark:   #0a0a1a;
    --darker: #050510;
    --accent: #7b2ff7;
    --accent2:#4a90e2;
    --green:  #2ed573;
    --red:    #ff4757;
    --card-bg: rgba(255,255,255,0.05);
    --border:  rgba(255,215,0,0.25);
    --row-hover: rgba(255,255,255,0.04);
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    background: var(--darker);
    color: #fff;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
  }

  body::before {
    content: '';
    position: fixed; inset: 0; z-index: 0; pointer-events: none;
    background:
      radial-gradient(ellipse at 15% 40%, rgba(123,47,247,.07) 0%, transparent 55%),
      radial-gradient(ellipse at 85% 70%, rgba(74,144,226,.06) 0%, transparent 50%);
  }

  .wrapper { position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; padding: 20px; }

  /* ── NAV ── */
  .topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 0 24px;
  }
  .brand {
    font-size: 1.1rem; font-weight: 900; letter-spacing: 3px;
    background: linear-gradient(135deg, #fff 0%, var(--gold) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    text-decoration: none;
  }
  .nav-links { display: flex; gap: 8px; }
  .nav-link {
    padding: 8px 18px; border-radius: 8px; font-size: .85rem;
    font-weight: 600; letter-spacing: .5px; text-decoration: none;
    border: 1px solid transparent; transition: all .2s;
  }
  .nav-link.active {
    background: linear-gradient(135deg, var(--gold), var(--gold2));
    color: #000;
  }
  .nav-link:not(.active) {
    color: rgba(255,255,255,.6);
    border-color: rgba(255,255,255,.15);
  }
  .nav-link:not(.active):hover { color: #fff; border-color: rgba(255,255,255,.4); }

  /* ── PAGE TITLE ── */
  .page-header { margin-bottom: 24px; }
  .page-header h1 {
    font-size: 1.8rem; font-weight: 900; letter-spacing: 2px;
    background: linear-gradient(135deg, #fff, var(--gold));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 0 20px rgba(255,215,0,.3));
  }
  .page-header p { font-size: .85rem; color: rgba(255,255,255,.4); margin-top: 4px; }

  /* ── SECTION LABEL ── */
  .section-label {
    font-size: .7rem; letter-spacing: 4px; color: rgba(255,255,255,.3);
    text-transform: uppercase; margin: 28px 0 14px; padding-bottom: 8px;
    border-bottom: 1px solid rgba(255,255,255,.06);
  }

  /* ── LAYOUT ── */
  .layout { display: grid; grid-template-columns: 340px 1fr; gap: 24px; align-items: start; }
  @media(max-width:860px){ .layout { grid-template-columns: 1fr; } }

  /* ── PANELS ── */
  .panel {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px; padding: 24px;
    backdrop-filter: blur(10px);
  }
  .panel-title {
    font-size: .68rem; letter-spacing: 3px;
    color: var(--gold); text-transform: uppercase; margin-bottom: 18px; opacity: .85;
  }

  /* ── FORM ── */
  .form-group { margin-bottom: 14px; }
  .form-group label {
    display: block; font-size: .75rem; font-weight: 600;
    letter-spacing: 1px; color: rgba(255,255,255,.55);
    text-transform: uppercase; margin-bottom: 6px;
  }
  .form-control {
    width: 100%; padding: 11px 14px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 9px; color: #fff; font-size: .95rem;
    outline: none; transition: border .2s, background .2s;
  }
  .form-control:focus {
    border-color: var(--gold);
    background: rgba(255,215,0,.06);
  }
  .form-control::placeholder { color: rgba(255,255,255,.3); }

  /* editing mode highlight */
  .panel.editing { border-color: rgba(74,144,226,.5); }
  .panel.editing .panel-title { color: var(--accent2); }

  .edit-banner {
    display: none; background: rgba(74,144,226,.1);
    border: 1px solid rgba(74,144,226,.3);
    border-radius: 8px; padding: 10px 14px;
    font-size: .82rem; color: var(--accent2);
    margin-bottom: 14px; align-items: center; gap: 10px;
  }
  .edit-banner.show { display: flex; }
  .edit-banner .cancel-edit {
    margin-left: auto; cursor: pointer; font-size: .75rem;
    color: rgba(255,255,255,.4); text-decoration: underline;
  }
  .edit-banner .cancel-edit:hover { color: #fff; }

  /* form row */
  .form-row { display: flex; gap: 10px; }
  .form-row .form-group { flex: 1; }

  .btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 11px 20px; border: none; border-radius: 9px;
    font-size: .9rem; font-weight: 700; letter-spacing: .5px;
    cursor: pointer; transition: all .2s; width: 100%;
  }
  .btn-primary {
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    color: #fff; box-shadow: 0 4px 16px rgba(74,144,226,.3);
  }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(74,144,226,.5); }
  .btn-gold {
    background: linear-gradient(135deg, var(--gold), var(--gold2));
    color: #000; box-shadow: 0 4px 16px rgba(255,215,0,.3);
  }
  .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,215,0,.5); }

  /* ── STATS BAR ── */
  .stats-bar { display: flex; gap: 10px; margin-bottom: 18px; }
  .stat {
    flex: 1; text-align: center; padding: 10px 8px;
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
    border-radius: 10px;
  }
  .stat .val { font-size: 1.4rem; font-weight: 900; color: var(--gold); }
  .stat .lbl { font-size: .6rem; color: rgba(255,255,255,.4); letter-spacing: 2px; }

  /* ── SEARCH / FILTER ── */
  .toolbar {
    display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap;
  }
  .search-box {
    flex: 1; min-width: 160px;
    display: flex; align-items: center;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.15);
    border-radius: 9px; padding: 0 12px; gap: 8px;
  }
  .search-box input {
    flex: 1; background: transparent; border: none; outline: none;
    color: #fff; font-size: .9rem; padding: 10px 0;
  }
  .search-box input::placeholder { color: rgba(255,255,255,.3); }
  .search-icon { color: rgba(255,255,255,.3); font-size: .9rem; }

  .filter-tabs { display: flex; gap: 6px; }
  .ftab {
    padding: 8px 14px; border-radius: 8px; font-size: .78rem;
    font-weight: 600; cursor: pointer; border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.5); background: transparent; transition: all .2s;
  }
  .ftab.active, .ftab:hover { color: #fff; border-color: var(--gold); background: rgba(255,215,0,.08); }
  .ftab.f-won.active { border-color: var(--gold); background: rgba(255,215,0,.12); color: var(--gold); }
  .ftab.f-eligible.active { border-color: var(--green); background: rgba(46,213,115,.08); color: var(--green); }

  /* ── TABLE ── */
  .table-wrap {
    border-radius: 10px; overflow: hidden;
    border: 1px solid rgba(255,255,255,.08);
    max-height: 520px; overflow-y: auto;
    scrollbar-width: thin; scrollbar-color: var(--gold) transparent;
  }
  .table-wrap::-webkit-scrollbar { width: 4px; }
  .table-wrap::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 4px; }

  table { width: 100%; border-collapse: collapse; }
  thead th {
    background: rgba(255,255,255,.06); padding: 10px 14px;
    font-size: .68rem; letter-spacing: 2px; font-weight: 700;
    color: rgba(255,255,255,.45); text-transform: uppercase;
    text-align: left; white-space: nowrap;
    position: sticky; top: 0; z-index: 2;
  }
  tbody tr {
    border-top: 1px solid rgba(255,255,255,.05);
    transition: background .15s;
  }
  tbody tr:hover { background: var(--row-hover); }
  tbody tr.won-row { opacity: .65; }

  td {
    padding: 11px 14px; font-size: .88rem; vertical-align: middle;
  }
  td.td-num    { color: rgba(255,255,255,.3); font-size: .75rem; width: 40px; }
  td.td-name   { font-weight: 600; }
  td.td-name.crossed { text-decoration: line-through; color: rgba(255,255,255,.4); }
  td.td-extra  { color: rgba(255,255,255,.5); font-size: .82rem; }
  td.td-status { width: 90px; }
  td.td-prize  { min-width: 130px; }
  td.td-actions { width: 90px; white-space: nowrap; }

  .badge {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    font-size: .65rem; font-weight: 700; letter-spacing: 1px;
  }
  .badge-eligible { background: rgba(46,213,115,.12); color: var(--green); border: 1px solid rgba(46,213,115,.3); }
  .badge-won      { background: rgba(255,215,0,.12);  color: var(--gold);  border: 1px solid rgba(255,215,0,.3); }
  .badge-prize    { background: rgba(255,165,0,.12);  color: var(--gold2); border: 1px solid rgba(255,165,0,.3); font-size: .7rem; }

  .action-btn {
    background: transparent; border: 1px solid rgba(255,255,255,.15);
    border-radius: 7px; cursor: pointer; padding: 5px 10px;
    font-size: .8rem; color: rgba(255,255,255,.55); transition: all .2s;
    margin-left: 4px;
  }
  .action-btn:hover.edit-btn  { border-color: var(--accent2); color: var(--accent2); background: rgba(74,144,226,.1); }
  .action-btn:hover.del-btn   { border-color: var(--red);     color: var(--red);     background: rgba(255,71,87,.1); }

  /* ── EMPTY STATE ── */
  .empty-state {
    text-align: center; padding: 60px 20px;
    color: rgba(255,255,255,.3); font-size: .9rem;
  }
  .empty-state .icon { font-size: 3rem; margin-bottom: 12px; }

  /* ── ALERTS ── */
  .alert {
    padding: 12px 16px; border-radius: 10px; font-size: .88rem;
    margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
  }
  .alert-success { background: rgba(46,213,115,.1); border: 1px solid rgba(46,213,115,.3); color: var(--green); }
  .alert-error   { background: rgba(255,71,87,.1);  border: 1px solid rgba(255,71,87,.3);  color: var(--red); }

  /* ── TOAST ── */
  #toast {
    position: fixed; bottom: 28px; right: 28px;
    background: rgba(20,20,40,.95); border: 1px solid var(--border);
    color: #fff; padding: 12px 20px; border-radius: 12px;
    font-size: .88rem; backdrop-filter: blur(12px);
    transform: translateY(80px); opacity: 0; transition: all .3s;
    z-index: 999; max-width: 320px;
  }
  #toast.show { transform: translateY(0); opacity: 1; }
  #toast.toast-success { border-color: rgba(46,213,115,.4); }
  #toast.toast-error   { border-color: rgba(255,71,87,.4); }

  /* ── MODAL ── */
  .modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.7); z-index: 100;
    align-items: center; justify-content: center;
    backdrop-filter: blur(4px);
  }
  .modal-overlay.show { display: flex; }
  .modal {
    background: #10101f; border: 1px solid var(--border);
    border-radius: 16px; padding: 28px 32px;
    max-width: 380px; width: 90%; text-align: center;
  }
  .modal .icon { font-size: 2.5rem; margin-bottom: 12px; }
  .modal h3 { font-size: 1.1rem; margin-bottom: 8px; }
  .modal p  { color: rgba(255,255,255,.5); font-size: .88rem; margin-bottom: 20px; }
  .modal-actions { display: flex; gap: 10px; }
  .btn-cancel-modal {
    flex: 1; padding: 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,.2);
    background: transparent; color: rgba(255,255,255,.6); cursor: pointer; font-size: .9rem;
    transition: all .2s;
  }
  .btn-cancel-modal:hover { border-color: rgba(255,255,255,.5); color: #fff; }
  .btn-confirm-del {
    flex: 1; padding: 10px; border-radius: 8px; border: none;
    background: var(--red); color: #fff; cursor: pointer; font-size: .9rem;
    font-weight: 700; transition: all .2s;
  }
  .btn-confirm-del:hover { background: #ff2233; }

  /* ── BULK ACTIONS ── */
  .bulk-bar {
    display: none; align-items: center; gap: 12px;
    padding: 10px 14px; border-radius: 9px; margin-bottom: 10px;
    background: rgba(255,215,0,.07); border: 1px solid rgba(255,215,0,.2);
    font-size: .85rem;
  }
  .bulk-bar.show { display: flex; }
  .bulk-bar .bulk-count { color: var(--gold); font-weight: 700; }
  .btn-bulk-del {
    margin-left: auto; padding: 7px 14px; border-radius: 7px;
    background: rgba(255,71,87,.15); border: 1px solid rgba(255,71,87,.3);
    color: var(--red); font-size: .8rem; font-weight: 700;
    cursor: pointer; transition: all .2s;
  }
  .btn-bulk-del:hover { background: rgba(255,71,87,.3); }

  input[type="checkbox"] { accent-color: var(--gold); width: 15px; height: 15px; cursor: pointer; }

  /* ── PRIZE TABLE ── */
  .prize-table-wrap {
    border-radius: 10px; overflow: hidden;
    border: 1px solid rgba(255,255,255,.08);
    max-height: 400px; overflow-y: auto;
    scrollbar-width: thin; scrollbar-color: var(--gold) transparent;
  }
  .prize-table-wrap::-webkit-scrollbar { width: 4px; }
  .prize-table-wrap::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 4px; }

  .prize-qty-bar {
    display: flex; align-items: center; gap: 6px; font-size: .82rem;
  }
  .prize-qty-track {
    flex: 1; height: 4px; border-radius: 4px;
    background: rgba(255,255,255,.1); overflow: hidden;
  }
  .prize-qty-fill {
    height: 4px; border-radius: 4px;
    background: linear-gradient(90deg, var(--gold), var(--gold2));
    transition: width .3s;
  }

  /* ── EXPORT BUTTON ── */
  .btn-export {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 8px; font-size: .8rem; font-weight: 600;
    text-decoration: none; transition: all .2s;
    background: rgba(46,213,115,.1); border: 1px solid rgba(46,213,115,.3);
    color: var(--green);
  }
  .btn-export:hover { background: rgba(46,213,115,.2); color: #fff; }
</style>
</head>
<body>

<div class="wrapper">

  <!-- NAV -->
  <div class="topbar">
    <a href="<?= site_url('raffle') ?>" class="brand">🎰 RAFFLE DRAW</a>
    <div class="nav-links">
      <a href="<?= site_url('raffle') ?>" class="nav-link">Draw</a>
      <a href="<?= site_url('raffle/participants') ?>" class="nav-link active">Participants</a>
    </div>
  </div>

  <!-- Alerts -->
  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($this->session->flashdata('success')) ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($this->session->flashdata('error')) ?></div>
  <?php endif; ?>

  <!-- PAGE HEADER -->
  <div class="page-header">
    <h1>👥 Participants & Prizes</h1>
    <p>Manage raffle entries and prize list</p>
  </div>

  <!-- ══════════════════════════════════════════════════════ -->
  <!-- PARTICIPANTS SECTION                                   -->
  <!-- ══════════════════════════════════════════════════════ -->
  <div class="section-label">Participants</div>

  <div class="layout">

    <!-- ══ LEFT: ADD / EDIT FORM ══ -->
    <div>
      <div class="panel" id="form-panel" <?= $edit_entry ? 'class="panel editing"' : '' ?>>
        <div class="panel-title" id="form-panel-title">
          <?= $edit_entry ? '✏️ Edit Participant' : '➕ Add Participant' ?>
        </div>

        <!-- Edit banner -->
        <div class="edit-banner <?= $edit_entry ? 'show' : '' ?>" id="edit-banner">
          <span>✏️ Editing: <strong id="editing-name"><?= $edit_entry ? htmlspecialchars($edit_entry['name']) : '' ?></strong></span>
          <span class="cancel-edit" onclick="cancelEdit()">✕ Cancel</span>
        </div>

        <!-- Form (single form, action changes via JS) -->
        <form id="participant-form">
          <input type="hidden" id="entry-id" name="id" value="<?= $edit_entry ? $edit_entry['id'] : '' ?>">

          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" class="form-control" id="field-name" name="name"
                   placeholder="e.g. Juan dela Cruz"
                   value="<?= $edit_entry ? htmlspecialchars($edit_entry['name']) : '' ?>"
                   autocomplete="off" required>
          </div>

          <div class="form-group">
            <label>Extra Info <span style="opacity:.5;">(optional)</span></label>
            <input type="text" class="form-control" id="field-extra" name="extra_info"
                   placeholder="e.g. Department, Employee ID, Team…"
                   value="<?= $edit_entry ? htmlspecialchars($edit_entry['extra_info'] ?? '') : '' ?>">
          </div>

          <button type="submit" class="btn btn-primary" id="submit-btn">
            <span id="submit-label"><?= $edit_entry ? '💾 Save Changes' : '➕ Add Participant' ?></span>
          </button>
        </form>

        <!-- Divider -->
        <div style="border-top:1px solid rgba(255,255,255,.08);margin:20px 0;"></div>

        <!-- Bulk import shortcut -->
        <div class="panel-title">📥 Bulk Import</div>
        <?= form_open_multipart(site_url('raffle/upload')) ?>
          <div style="display:flex;gap:8px;align-items:center;">
            <input type="file" name="excel_file" accept=".xlsx,.xls,.csv"
                   style="flex:1;font-size:.8rem;color:rgba(255,255,255,.6);">
          </div>
          <button type="submit" class="btn btn-gold" style="margin-top:10px;">
            ⬆ Upload Excel / CSV
          </button>
          <p style="font-size:.72rem;color:rgba(255,255,255,.3);margin-top:8px;line-height:1.6;">
            Col A = Name, Col B = Extra Info. Row 1 skipped (header).<br>
            ⚠️ This will <strong style="color:rgba(255,100,100,.7);">replace</strong> all current entries.
          </p>
        <?= form_close() ?>
      </div>
    </div>

    <!-- ══ RIGHT: ENTRIES TABLE ══ -->
    <div>
      <div class="panel">
        <div class="panel-title">📋 All Participants</div>

        <!-- Stats -->
        <div class="stats-bar">
          <div class="stat">
            <div class="val" id="s-total"><?= count($entries) ?></div>
            <div class="lbl">TOTAL</div>
          </div>
          <div class="stat">
            <div class="val" id="s-eligible"><?= count(array_filter($entries, fn($e) => !$e['has_won'])) ?></div>
            <div class="lbl">ELIGIBLE</div>
          </div>
          <div class="stat">
            <div class="val" id="s-won"><?= count(array_filter($entries, fn($e) => $e['has_won'])) ?></div>
            <div class="lbl">WON</div>
          </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
          <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" id="search-input" placeholder="Search name…">
          </div>
          <div class="filter-tabs">
            <button class="ftab active" data-filter="all">All</button>
            <button class="ftab f-eligible" data-filter="eligible">Eligible</button>
            <button class="ftab f-won" data-filter="won">Won</button>
          </div>
        </div>

        <!-- Bulk bar -->
        <div class="bulk-bar" id="bulk-bar">
          <input type="checkbox" id="chk-all" title="Select all visible">
          <span class="bulk-count"><span id="bulk-count-num">0</span> selected</span>
          <button class="btn-bulk-del" onclick="bulkDelete()">🗑 Delete Selected</button>
        </div>

        <!-- Table -->
        <div class="table-wrap">
          <table id="entries-table">
            <thead>
              <tr>
                <th style="width:36px;"><input type="checkbox" id="chk-all-head" title="Select all"></th>
                <th>#</th>
                <th>Name</th>
                <th>Extra Info</th>
                <th>Status</th>
                <th>Prize Won</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="table-body">
              <?php if (empty($entries)): ?>
                <tr>
                  <td colspan="7">
                    <div class="empty-state">
                      <div class="icon">🙈</div>
                      No participants yet. Add one using the form!
                    </div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($entries as $i => $entry): ?>
                  <tr class="entry-row <?= $entry['has_won'] ? 'won-row' : '' ?>"
                      data-id="<?= $entry['id'] ?>"
                      data-name="<?= htmlspecialchars($entry['name'], ENT_QUOTES) ?>"
                      data-extra="<?= htmlspecialchars($entry['extra_info'] ?? '', ENT_QUOTES) ?>"
                      data-prize="<?= htmlspecialchars($entry['prize_name'] ?? '', ENT_QUOTES) ?>"
                      data-won="<?= $entry['has_won'] ?>">
                    <td><input type="checkbox" class="row-chk" value="<?= $entry['id'] ?>"></td>
                    <td class="td-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></td>
                    <td class="td-name <?= $entry['has_won'] ? 'crossed' : '' ?>">
                      <?= htmlspecialchars($entry['name']) ?>
                    </td>
                    <td class="td-extra"><?= htmlspecialchars($entry['extra_info'] ?? '—') ?></td>
                    <td class="td-status">
                      <?php if ($entry['has_won']): ?>
                        <span class="badge badge-won">🏆 Won</span>
                      <?php else: ?>
                        <span class="badge badge-eligible">✅ Eligible</span>
                      <?php endif; ?>
                    </td>
                    <td class="td-prize">
                      <?php if ($entry['has_won'] && !empty($entry['prize_name'])): ?>
                        <span class="badge badge-prize">🎁 <?= htmlspecialchars($entry['prize_name']) ?></span>
                      <?php else: ?>
                        <span style="color:rgba(255,255,255,.25);font-size:.8rem;">—</span>
                      <?php endif; ?>
                    </td>
                    <td class="td-actions">
                      <button class="action-btn edit-btn" onclick="editRow(this)" title="Edit">✏️</button>
                      <button class="action-btn del-btn"  onclick="confirmDelete(<?= $entry['id'] ?>, '<?= htmlspecialchars($entry['name'], ENT_QUOTES) ?>')" title="Delete">🗑</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Bottom actions -->
        <div style="display:flex;gap:8px;margin-top:14px;justify-content:flex-end;flex-wrap:wrap;align-items:center;">
          <a href="<?= site_url('raffle/export_winners') ?>" class="btn-export">
            📥 Export Winners CSV
          </a>
          <a href="<?= site_url('raffle/reset_winners') ?>"
             onclick="return confirm('Reset all winners? They become eligible again.')"
             style="text-decoration:none;padding:8px 16px;border-radius:8px;font-size:.8rem;font-weight:600;
                    border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.55);transition:all .2s;"
             onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.55)'">
            ↺ Reset Winners
          </a>
          <a href="<?= site_url('raffle/clear_all') ?>"
             onclick="return confirm('Delete ALL entries permanently?')"
             style="text-decoration:none;padding:8px 16px;border-radius:8px;font-size:.8rem;font-weight:600;
                    border:1px solid rgba(255,71,87,.3);color:#ff4757;transition:all .2s;"
             onmouseover="this.style.background='rgba(255,71,87,.1)'" onmouseout="this.style.background='transparent'">
            🗑 Clear All
          </a>
        </div>
      </div>
    </div>

  </div><!-- /participants layout -->

  <!-- ══════════════════════════════════════════════════════ -->
  <!-- PRIZES SECTION                                         -->
  <!-- ══════════════════════════════════════════════════════ -->
  <div class="section-label">🎁 Prize List</div>

  <div class="layout">

    <!-- ══ LEFT: ADD / EDIT PRIZE FORM ══ -->
    <div>
      <div class="panel" id="prize-form-panel">
        <div class="panel-title" id="prize-form-title">➕ Add Prize</div>

        <!-- Prize edit banner -->
        <div class="edit-banner" id="prize-edit-banner">
          <span>✏️ Editing: <strong id="prize-editing-name"></strong></span>
          <span class="cancel-edit" onclick="cancelPrizeEdit()">✕ Cancel</span>
        </div>

        <form id="prize-form">
          <input type="hidden" id="prize-id" name="id" value="">

          <div class="form-group">
            <label>Prize Name *</label>
            <input type="text" class="form-control" id="prize-name" name="name"
                   placeholder="e.g. 1st Prize – Laptop" autocomplete="off" required>
          </div>

          <div class="form-group">
            <label>Description <span style="opacity:.5;">(optional)</span></label>
            <input type="text" class="form-control" id="prize-desc" name="description"
                   placeholder="e.g. Brand new, 16GB RAM…">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Quantity *</label>
              <input type="number" class="form-control" id="prize-qty" name="quantity"
                     value="1" min="1" placeholder="1">
            </div>
            <div class="form-group">
              <label>Sort Order</label>
              <input type="number" class="form-control" id="prize-sort" name="sort_order"
                     value="0" placeholder="0">
            </div>
          </div>

          <button type="submit" class="btn btn-gold">
            <span id="prize-submit-label">➕ Add Prize</span>
          </button>
        </form>
      </div>
    </div>

    <!-- ══ RIGHT: PRIZE LIST TABLE ══ -->
    <div>
      <div class="panel">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
          <div class="panel-title" style="margin-bottom:0;">
            🏆 Prizes <span id="prize-count" style="opacity:.5;font-size:.6rem;">(<?= count($prizes) ?>)</span>
          </div>
        </div>

        <div class="prize-table-wrap">
          <table id="prizes-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Prize Name</th>
                <th>Description</th>
                <th>Winners / Qty</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="prizes-body">
              <?php if (empty($prizes)): ?>
                <tr>
                  <td colspan="5">
                    <div class="empty-state">
                      <div class="icon">🎁</div>
                      No prizes yet. Add one using the form!
                    </div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($prizes as $pi => $prize): ?>
                  <?php
                    $p_won = (int)($prize['won_count'] ?? 0);
                    $p_qty = (int)$prize['quantity'];
                    $p_pct = $p_qty > 0 ? min(100, round($p_won / $p_qty * 100)) : 0;
                  ?>
                  <tr class="prize-row"
                      data-id="<?= $prize['id'] ?>"
                      data-name="<?= htmlspecialchars($prize['name'], ENT_QUOTES) ?>"
                      data-desc="<?= htmlspecialchars($prize['description'] ?? '', ENT_QUOTES) ?>"
                      data-qty="<?= $p_qty ?>"
                      data-sort="<?= (int)$prize['sort_order'] ?>"
                      data-won="<?= $p_won ?>">
                    <td class="td-num"><?= str_pad($pi + 1, 2, '0', STR_PAD_LEFT) ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($prize['name']) ?></td>
                    <td style="color:rgba(255,255,255,.5);font-size:.82rem;">
                      <?= htmlspecialchars($prize['description'] ?? '—') ?>
                    </td>
                    <td>
                      <div class="prize-qty-bar">
                        <span style="color:var(--gold);font-weight:700;"><?= $p_won ?></span>
                        <span style="color:rgba(255,255,255,.3);">/</span>
                        <span><?= $p_qty ?></span>
                        <div class="prize-qty-track">
                          <div class="prize-qty-fill" style="width:<?= $p_pct ?>%;"></div>
                        </div>
                      </div>
                    </td>
                    <td class="td-actions">
                      <button class="action-btn edit-btn" onclick="editPrize(this)" title="Edit">✏️</button>
                      <button class="action-btn del-btn"  onclick="confirmPrizeDelete(<?= $prize['id'] ?>, '<?= htmlspecialchars($prize['name'], ENT_QUOTES) ?>')" title="Delete">🗑</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div><!-- /prizes layout -->

</div><!-- /wrapper -->

<!-- Delete Participant modal -->
<div class="modal-overlay" id="modal-overlay">
  <div class="modal">
    <div class="icon">⚠️</div>
    <h3>Delete Participant?</h3>
    <p id="modal-msg">This action cannot be undone.</p>
    <div class="modal-actions">
      <button class="btn-cancel-modal" onclick="closeModal()">Cancel</button>
      <button class="btn-confirm-del" id="modal-confirm-btn">Delete</button>
    </div>
  </div>
</div>

<!-- Delete Prize modal -->
<div class="modal-overlay" id="prize-modal-overlay">
  <div class="modal">
    <div class="icon">⚠️</div>
    <h3>Delete Prize?</h3>
    <p id="prize-modal-msg">This action cannot be undone.</p>
    <div class="modal-actions">
      <button class="btn-cancel-modal" onclick="closePrizeModal()">Cancel</button>
      <button class="btn-confirm-del" id="prize-modal-confirm-btn">Delete</button>
    </div>
  </div>
</div>

<!-- Toast -->
<div id="toast"></div>

<script>
const BASE = '<?= site_url() ?>';
let editMode       = false;
let currentFilter  = 'all';
let deleteTargetId = null;
let selectedIds    = new Set();
let prizeDeleteTargetId = null;

// ── PARTICIPANT FORM SUBMIT ──
document.getElementById('participant-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const id    = document.getElementById('entry-id').value;
  const name  = document.getElementById('field-name').value.trim();
  const extra = document.getElementById('field-extra').value.trim();

  if (!name) { showToast('Name is required.', 'error'); return; }

  const endpoint = id ? BASE + 'raffle/ajax_update_participant' : BASE + 'raffle/ajax_add_participant';
  const body = new URLSearchParams({ name, extra_info: extra });
  if (id) body.append('id', id);

  fetch(endpoint, { method: 'POST', body })
    .then(r => r.json())
    .then(data => {
      if (!data.success) { showToast(data.message, 'error'); return; }
      if (id) {
        updateRow(data.entry);
        showToast('Entry updated!', 'success');
        cancelEdit();
      } else {
        appendRow(data.entry);
        showToast(`"${name}" added!`, 'success');
        document.getElementById('field-name').value  = '';
        document.getElementById('field-extra').value = '';
      }
      refreshStats();
      reNumberRows();
    })
    .catch(() => showToast('Server error.', 'error'));
});

// ── EDIT ROW ──
function editRow(btn) {
  const row   = btn.closest('tr');
  const id    = row.dataset.id;
  const name  = row.dataset.name;
  const extra = row.dataset.extra;

  document.getElementById('entry-id').value    = id;
  document.getElementById('field-name').value  = name;
  document.getElementById('field-extra').value = extra;
  document.getElementById('submit-label').textContent      = '💾 Save Changes';
  document.getElementById('form-panel-title').textContent  = '✏️ Edit Participant';
  document.getElementById('editing-name').textContent      = name;
  document.getElementById('edit-banner').classList.add('show');
  document.getElementById('form-panel').classList.add('editing');
  document.getElementById('field-name').focus();
  editMode = true;

  document.getElementById('form-panel').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function cancelEdit() {
  document.getElementById('entry-id').value    = '';
  document.getElementById('field-name').value  = '';
  document.getElementById('field-extra').value = '';
  document.getElementById('submit-label').textContent     = '➕ Add Participant';
  document.getElementById('form-panel-title').textContent = '➕ Add Participant';
  document.getElementById('edit-banner').classList.remove('show');
  document.getElementById('form-panel').classList.remove('editing');
  editMode = false;
}

// ── UPDATE TABLE ROW ──
function updateRow(entry) {
  const row = document.querySelector(`tr[data-id="${entry.id}"]`);
  if (!row) return;
  row.dataset.name  = entry.name;
  row.dataset.extra = entry.extra_info || '';
  row.dataset.won   = entry.has_won;
  row.dataset.prize = entry.prize_name || '';
  row.querySelector('.td-name').textContent  = entry.name;
  row.querySelector('.td-extra').textContent = entry.extra_info || '—';
}

// ── APPEND NEW ROW ──
function appendRow(entry) {
  const tbody = document.getElementById('table-body');

  const emptyRow = tbody.querySelector('td[colspan]');
  if (emptyRow) emptyRow.closest('tr').remove();

  const won   = entry.has_won == 1;
  const prize = entry.prize_name || '';
  const idx   = tbody.querySelectorAll('tr').length + 1;
  const num   = String(idx).padStart(2, '0');

  const tr = document.createElement('tr');
  tr.className     = `entry-row${won ? ' won-row' : ''}`;
  tr.dataset.id    = entry.id;
  tr.dataset.name  = entry.name;
  tr.dataset.extra = entry.extra_info || '';
  tr.dataset.prize = prize;
  tr.dataset.won   = entry.has_won;

  tr.innerHTML = `
    <td><input type="checkbox" class="row-chk" value="${entry.id}"></td>
    <td class="td-num">${num}</td>
    <td class="td-name${won ? ' crossed' : ''}">${esc(entry.name)}</td>
    <td class="td-extra">${esc(entry.extra_info) || '—'}</td>
    <td class="td-status">
      ${won
        ? '<span class="badge badge-won">🏆 Won</span>'
        : '<span class="badge badge-eligible">✅ Eligible</span>'}
    </td>
    <td class="td-prize">
      ${won && prize
        ? `<span class="badge badge-prize">🎁 ${esc(prize)}</span>`
        : '<span style="color:rgba(255,255,255,.25);font-size:.8rem;">—</span>'}
    </td>
    <td class="td-actions">
      <button class="action-btn edit-btn" onclick="editRow(this)" title="Edit">✏️</button>
      <button class="action-btn del-btn" onclick="confirmDelete(${entry.id}, '${esc(entry.name)}')" title="Delete">🗑</button>
    </td>`;
  tbody.appendChild(tr);

  tr.querySelector('.row-chk').addEventListener('change', onCheckChange);

  tr.style.background = 'rgba(255,215,0,.08)';
  setTimeout(() => { tr.style.transition = 'background 1s'; tr.style.background = ''; }, 50);

  applyFilter(currentFilter);
  applySearch(document.getElementById('search-input').value);
}

// ── DELETE PARTICIPANT ──
function confirmDelete(id, name) {
  deleteTargetId = id;
  document.getElementById('modal-msg').textContent = `Remove "${name}" from the raffle?`;
  document.getElementById('modal-overlay').classList.add('show');
}

document.getElementById('modal-confirm-btn').addEventListener('click', function() {
  if (!deleteTargetId) return;
  doDelete(deleteTargetId);
  closeModal();
});

function doDelete(id) {
  fetch(BASE + 'raffle/ajax_delete_participant', {
    method: 'POST',
    body: new URLSearchParams({ id })
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast(data.message || 'Error.', 'error'); return; }
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
      row.style.transition = 'opacity .3s, transform .3s';
      row.style.opacity = '0'; row.style.transform = 'translateX(20px)';
      setTimeout(() => { row.remove(); reNumberRows(); refreshStats(); }, 300);
    }
    selectedIds.delete(id);
    updateBulkBar();
    showToast('Entry removed.', 'success');
  })
  .catch(() => showToast('Server error.', 'error'));
}

function closeModal() {
  document.getElementById('modal-overlay').classList.remove('show');
  deleteTargetId = null;
}
document.getElementById('modal-overlay').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// ── SEARCH ──
document.getElementById('search-input').addEventListener('input', function() {
  applySearch(this.value);
});

function applySearch(q) {
  q = q.toLowerCase();
  document.querySelectorAll('.entry-row').forEach(row => {
    const match = row.dataset.name.toLowerCase().includes(q) ||
                  (row.dataset.extra || '').toLowerCase().includes(q);
    row.style.display = match ? '' : 'none';
  });
}

// ── FILTER TABS ──
document.querySelectorAll('.ftab').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    currentFilter = this.dataset.filter;
    applyFilter(currentFilter);
  });
});

function applyFilter(filter) {
  document.querySelectorAll('.entry-row').forEach(row => {
    const won = row.dataset.won == 1;
    if (filter === 'all')      row.style.display = '';
    else if (filter === 'won') row.style.display = won ? '' : 'none';
    else                       row.style.display = !won ? '' : 'none';
  });
  applySearch(document.getElementById('search-input').value);
}

// ── CHECKBOXES / BULK ──
document.getElementById('chk-all-head').addEventListener('change', function() {
  const visible = Array.from(document.querySelectorAll('.entry-row'))
    .filter(r => r.style.display !== 'none');
  visible.forEach(row => {
    const chk = row.querySelector('.row-chk');
    chk.checked = this.checked;
    if (this.checked) selectedIds.add(Number(chk.value));
    else selectedIds.delete(Number(chk.value));
  });
  updateBulkBar();
});

document.getElementById('table-body').addEventListener('change', function(e) {
  if (e.target.classList.contains('row-chk')) onCheckChange(e);
});

function onCheckChange(e) {
  const id = Number(e.target.value);
  if (e.target.checked) selectedIds.add(id);
  else selectedIds.delete(id);
  updateBulkBar();
}

function updateBulkBar() {
  const bar = document.getElementById('bulk-bar');
  const n   = selectedIds.size;
  document.getElementById('bulk-count-num').textContent = n;
  bar.classList.toggle('show', n > 0);
}

async function bulkDelete() {
  if (selectedIds.size === 0) return;
  if (!confirm(`Delete ${selectedIds.size} selected entries?`)) return;
  for (const id of [...selectedIds]) await doDelete(id);
  selectedIds.clear();
  updateBulkBar();
}

// ── STATS ──
function refreshStats() {
  const rows  = document.querySelectorAll('.entry-row');
  const total = rows.length;
  const won   = [...rows].filter(r => r.dataset.won == 1).length;
  document.getElementById('s-total').textContent    = total;
  document.getElementById('s-eligible').textContent = total - won;
  document.getElementById('s-won').textContent      = won;
}

// ── RE-NUMBER ──
function reNumberRows() {
  let n = 1;
  document.querySelectorAll('.entry-row').forEach(row => {
    const cell = row.querySelector('.td-num');
    if (cell) cell.textContent = String(n++).padStart(2, '0');
  });
}

// ══════════════════════════════════════════════════════
// PRIZE MANAGEMENT
// ══════════════════════════════════════════════════════

// ── PRIZE FORM SUBMIT ──
document.getElementById('prize-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const id   = document.getElementById('prize-id').value;
  const name = document.getElementById('prize-name').value.trim();
  const desc = document.getElementById('prize-desc').value.trim();
  const qty  = parseInt(document.getElementById('prize-qty').value)  || 1;
  const sort = parseInt(document.getElementById('prize-sort').value) || 0;

  if (!name) { showToast('Prize name is required.', 'error'); return; }

  const endpoint = id ? BASE + 'raffle/ajax_update_prize' : BASE + 'raffle/ajax_add_prize';
  const body = new URLSearchParams({ name, description: desc, quantity: qty, sort_order: sort });
  if (id) body.append('id', id);

  fetch(endpoint, { method: 'POST', body })
    .then(r => r.json())
    .then(data => {
      if (!data.success) { showToast(data.message || 'Error.', 'error'); return; }
      if (id) {
        updatePrizeRow(data.prize);
        showToast('Prize updated!', 'success');
        cancelPrizeEdit();
      } else {
        appendPrizeRow(data.prize);
        showToast(`Prize "${name}" added!`, 'success');
        document.getElementById('prize-name').value = '';
        document.getElementById('prize-desc').value = '';
        document.getElementById('prize-qty').value  = '1';
        document.getElementById('prize-sort').value = '0';
      }
      updatePrizeCount();
    })
    .catch(() => showToast('Server error.', 'error'));
});

// ── EDIT PRIZE ──
function editPrize(btn) {
  const row  = btn.closest('tr');

  document.getElementById('prize-id').value   = row.dataset.id;
  document.getElementById('prize-name').value = row.dataset.name;
  document.getElementById('prize-desc').value = row.dataset.desc;
  document.getElementById('prize-qty').value  = row.dataset.qty;
  document.getElementById('prize-sort').value = row.dataset.sort;

  document.getElementById('prize-submit-label').textContent  = '💾 Save Prize';
  document.getElementById('prize-form-title').textContent    = '✏️ Edit Prize';
  document.getElementById('prize-editing-name').textContent  = row.dataset.name;
  document.getElementById('prize-edit-banner').classList.add('show');
  document.getElementById('prize-form-panel').classList.add('editing');
  document.getElementById('prize-name').focus();

  document.getElementById('prize-form-panel').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function cancelPrizeEdit() {
  document.getElementById('prize-id').value   = '';
  document.getElementById('prize-name').value = '';
  document.getElementById('prize-desc').value = '';
  document.getElementById('prize-qty').value  = '1';
  document.getElementById('prize-sort').value = '0';
  document.getElementById('prize-submit-label').textContent = '➕ Add Prize';
  document.getElementById('prize-form-title').textContent   = '➕ Add Prize';
  document.getElementById('prize-edit-banner').classList.remove('show');
  document.getElementById('prize-form-panel').classList.remove('editing');
}

// ── UPDATE PRIZE ROW ──
function updatePrizeRow(prize) {
  const row = document.querySelector(`.prize-row[data-id="${prize.id}"]`);
  if (!row) return;

  const won  = parseInt(prize.won_count) || 0;
  const qty  = parseInt(prize.quantity)  || 1;
  const pct  = qty > 0 ? Math.min(100, Math.round(won / qty * 100)) : 0;

  row.dataset.name = prize.name;
  row.dataset.desc = prize.description || '';
  row.dataset.qty  = qty;
  row.dataset.sort = prize.sort_order  || 0;
  row.dataset.won  = won;

  const cells = row.querySelectorAll('td');
  cells[1].textContent = prize.name;
  cells[2].textContent = prize.description || '—';
  cells[3].innerHTML   = prizeQtyHTML(won, qty, pct);
}

// ── APPEND PRIZE ROW ──
function appendPrizeRow(prize) {
  const tbody = document.getElementById('prizes-body');

  const emptyRow = tbody.querySelector('td[colspan]');
  if (emptyRow) emptyRow.closest('tr').remove();

  const won  = parseInt(prize.won_count) || 0;
  const qty  = parseInt(prize.quantity)  || 1;
  const pct  = qty > 0 ? Math.min(100, Math.round(won / qty * 100)) : 0;
  const idx  = tbody.querySelectorAll('tr').length + 1;
  const num  = String(idx).padStart(2, '0');

  const tr = document.createElement('tr');
  tr.className    = 'prize-row';
  tr.dataset.id   = prize.id;
  tr.dataset.name = prize.name;
  tr.dataset.desc = prize.description  || '';
  tr.dataset.qty  = qty;
  tr.dataset.sort = prize.sort_order   || 0;
  tr.dataset.won  = won;

  tr.innerHTML = `
    <td class="td-num">${num}</td>
    <td style="font-weight:600;">${esc(prize.name)}</td>
    <td style="color:rgba(255,255,255,.5);font-size:.82rem;">${esc(prize.description) || '—'}</td>
    <td>${prizeQtyHTML(won, qty, pct)}</td>
    <td class="td-actions">
      <button class="action-btn edit-btn" onclick="editPrize(this)" title="Edit">✏️</button>
      <button class="action-btn del-btn" onclick="confirmPrizeDelete(${prize.id}, '${esc(prize.name)}')" title="Delete">🗑</button>
    </td>`;
  tbody.appendChild(tr);

  tr.style.background = 'rgba(255,215,0,.08)';
  setTimeout(() => { tr.style.transition = 'background 1s'; tr.style.background = ''; }, 50);
}

function prizeQtyHTML(won, qty, pct) {
  return `<div class="prize-qty-bar">
    <span style="color:var(--gold);font-weight:700;">${won}</span>
    <span style="color:rgba(255,255,255,.3);">/</span>
    <span>${qty}</span>
    <div class="prize-qty-track">
      <div class="prize-qty-fill" style="width:${pct}%;"></div>
    </div>
  </div>`;
}

// ── DELETE PRIZE ──
function confirmPrizeDelete(id, name) {
  prizeDeleteTargetId = id;
  document.getElementById('prize-modal-msg').textContent = `Delete prize "${name}"? This cannot be undone.`;
  document.getElementById('prize-modal-overlay').classList.add('show');
}

document.getElementById('prize-modal-confirm-btn').addEventListener('click', function() {
  if (!prizeDeleteTargetId) return;
  doPrizeDelete(prizeDeleteTargetId);
  closePrizeModal();
});

function doPrizeDelete(id) {
  fetch(BASE + 'raffle/ajax_delete_prize', {
    method: 'POST',
    body: new URLSearchParams({ id })
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast('Error deleting prize.', 'error'); return; }
    const row = document.querySelector(`.prize-row[data-id="${id}"]`);
    if (row) {
      row.style.transition = 'opacity .3s, transform .3s';
      row.style.opacity = '0'; row.style.transform = 'translateX(20px)';
      setTimeout(() => { row.remove(); reNumberPrizeRows(); updatePrizeCount(); }, 300);
    }
    showToast('Prize deleted.', 'success');
  })
  .catch(() => showToast('Server error.', 'error'));
}

function closePrizeModal() {
  document.getElementById('prize-modal-overlay').classList.remove('show');
  prizeDeleteTargetId = null;
}
document.getElementById('prize-modal-overlay').addEventListener('click', function(e) {
  if (e.target === this) closePrizeModal();
});

function reNumberPrizeRows() {
  let n = 1;
  document.querySelectorAll('.prize-row').forEach(row => {
    const cell = row.querySelector('.td-num');
    if (cell) cell.textContent = String(n++).padStart(2, '0');
  });
}

function updatePrizeCount() {
  const el = document.getElementById('prize-count');
  if (el) el.textContent = `(${document.querySelectorAll('.prize-row').length})`;
}

// ── TOAST ──
let toastTimer;
function showToast(msg, type = 'success') {
  const t = document.getElementById('toast');
  t.textContent = (type === 'success' ? '✅ ' : '⚠️ ') + msg;
  t.className = `show toast-${type}`;
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => { t.className = ''; }, 3200);
}

// ── ESC helper ──
function esc(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
</script>
</body>
</html>

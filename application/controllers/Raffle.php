<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raffle extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Raffle_model');
        $this->load->library(['upload', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }

    // ── DRAW PAGE ──────────────────────────────────────────────────────

    public function index()
    {
        $data['entries']      = $this->Raffle_model->get_all_entries();
        $data['active_prize'] = $this->Raffle_model->get_active_prize();
        $this->load->view('raffle_view', $data);
    }

    // AJAX: all entries as JSON
    public function get_entries()
    {
        $this->output->set_content_type('application/json')
             ->set_output(json_encode($this->Raffle_model->get_all_entries()));
    }

    // AJAX: current active prize
    public function get_active_prize()
    {
        $prize = $this->Raffle_model->get_active_prize();
        if ($prize) {
            $prize['won_count'] = $this->Raffle_model->prize_won_count($prize['id']);
        }
        $this->output->set_content_type('application/json')
             ->set_output(json_encode(['prize' => $prize]));
    }

    // AJAX: pick winner using active prize from DB
    public function pick_winner()
    {
        $active = $this->Raffle_model->get_active_prize();
        $prize_id   = NULL;
        $prize_name = NULL;

        if ($active) {
            $won_count = $this->Raffle_model->prize_won_count($active['id']);
            if ($won_count >= (int) $active['quantity']) {
                $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => FALSE,
                         'message' => "Prize \"{$active['name']}\" has been fully awarded ({$active['quantity']} winner(s) max). Set a new active prize.",
                     ]));
                return;
            }
            $prize_id   = $active['id'];
            $prize_name = $active['name'];
        }

        $winner = $this->Raffle_model->pick_random_winner();

        if ( ! $winner) {
            $this->output->set_content_type('application/json')
                 ->set_output(json_encode(['success' => FALSE, 'message' => 'No eligible entries left.']));
            return;
        }

        $this->Raffle_model->mark_as_won($winner['id'], $prize_id, $prize_name);
        $winner['prize_id']   = $prize_id;
        $winner['prize_name'] = $prize_name;

        $this->output->set_content_type('application/json')
             ->set_output(json_encode(['success' => TRUE, 'winner' => $winner]));
    }

    // Reset all winners
    public function reset_winners()
    {
        $this->Raffle_model->reset_winners();
        $this->session->set_flashdata('success', 'All winners have been reset.');
        redirect('raffle');
    }

    // Clear all entries
    public function clear_all()
    {
        $this->Raffle_model->clear_entries();
        $this->session->set_flashdata('success', 'All entries cleared.');
        redirect('raffle');
    }

    // Excel/CSV upload
    public function upload()
    {
        $config = ['upload_path' => './uploads/', 'allowed_types' => 'xlsx|xls|csv', 'max_size' => 5120, 'file_name' => 'raffle_entries_'.time()];
        if ( ! is_dir('./uploads/')) mkdir('./uploads/', 0755, TRUE);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('excel_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('raffle/participants'); return;
        }

        $file_data = $this->upload->data();
        $file_path = './uploads/' . $file_data['file_name'];
        $entries   = $this->Raffle_model->parse_excel($file_path);
        @unlink($file_path);

        if (empty($entries)) {
            $this->session->set_flashdata('error', 'No valid entries found. Make sure Column A has names.');
            redirect('raffle/participants'); return;
        }

        $this->Raffle_model->clear_entries();
        $this->Raffle_model->insert_entries($entries);
        $this->session->set_flashdata('success', count($entries) . ' entries loaded successfully!');
        redirect('raffle/participants');
    }

    // ── PRIZES ────────────────────────────────────────────────────────

    public function get_prizes()
    {
        $prizes = $this->Raffle_model->get_all_prizes();
        foreach ($prizes as &$p) $p['won_count'] = $this->Raffle_model->prize_won_count($p['id']);
        $this->output->set_content_type('application/json')->set_output(json_encode($prizes));
    }

    // AJAX: set a prize as active (or pass id=0 to deactivate all)
    public function ajax_set_active_prize()
    {
        $id = (int) $this->input->post('id');
        $this->Raffle_model->set_active_prize($id ?: NULL);
        $prize = $id ? $this->Raffle_model->get_prize($id) : NULL;
        if ($prize) $prize['won_count'] = $this->Raffle_model->prize_won_count($id);
        $this->output->set_content_type('application/json')
             ->set_output(json_encode(['success' => TRUE, 'prize' => $prize]));
    }

    public function ajax_add_prize()
    {
        $name = trim($this->input->post('name', TRUE));
        $desc = trim($this->input->post('description', TRUE));
        $qty  = max(1, (int) $this->input->post('quantity'));
        $sort = (int) $this->input->post('sort_order');

        if ($name === '') { echo json_encode(['success' => FALSE, 'message' => 'Prize name is required.']); return; }

        $id    = $this->Raffle_model->insert_prize(['name' => $name, 'description' => $desc ?: NULL, 'quantity' => $qty, 'sort_order' => $sort]);
        $prize = $this->Raffle_model->get_prize($id);
        $prize['won_count'] = 0;
        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => TRUE, 'prize' => $prize]));
    }

    public function ajax_update_prize()
    {
        $id   = (int) $this->input->post('id');
        $name = trim($this->input->post('name', TRUE));
        $desc = trim($this->input->post('description', TRUE));
        $qty  = max(1, (int) $this->input->post('quantity'));
        $sort = (int) $this->input->post('sort_order');

        if ($name === '') { echo json_encode(['success' => FALSE, 'message' => 'Prize name is required.']); return; }

        $this->Raffle_model->update_prize($id, ['name' => $name, 'description' => $desc ?: NULL, 'quantity' => $qty, 'sort_order' => $sort]);
        $prize = $this->Raffle_model->get_prize($id);
        $prize['won_count'] = $this->Raffle_model->prize_won_count($id);
        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => TRUE, 'prize' => $prize]));
    }

    public function ajax_delete_prize()
    {
        $id = (int) $this->input->post('id');
        $this->Raffle_model->delete_prize($id);
        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => TRUE]));
    }

    // ── PARTICIPANTS PAGE ──────────────────────────────────────────────

    public function participants()
    {
        $data['entries']      = $this->Raffle_model->get_all_entries();
        $data['prizes']       = $this->Raffle_model->get_all_prizes();
        $data['active_prize'] = $this->Raffle_model->get_active_prize();
        $data['edit_entry']   = NULL;

        $edit_id = $this->input->get('edit');
        if ($edit_id) $data['edit_entry'] = $this->Raffle_model->get_entry($edit_id);

        $this->load->view('participants_view', $data);
    }

    public function ajax_add_participant()
    {
        $name  = trim($this->input->post('name', TRUE));
        $extra = trim($this->input->post('extra_info', TRUE));
        if ($name === '') { echo json_encode(['success' => FALSE, 'message' => 'Name is required.']); return; }
        if ($this->Raffle_model->name_exists($name)) { echo json_encode(['success' => FALSE, 'message' => "\"$name\" already exists."]); return; }
        $this->Raffle_model->insert_entries([['name' => $name, 'extra_info' => $extra ?: NULL]]);
        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => TRUE, 'entry' => $this->Raffle_model->get_last_inserted()]));
    }

    public function ajax_update_participant()
    {
        $id    = (int) $this->input->post('id');
        $name  = trim($this->input->post('name', TRUE));
        $extra = trim($this->input->post('extra_info', TRUE));
        if ($name === '') { echo json_encode(['success' => FALSE, 'message' => 'Name is required.']); return; }
        if ($this->Raffle_model->name_exists($name, $id)) { echo json_encode(['success' => FALSE, 'message' => "\"$name\" already exists."]); return; }
        $this->Raffle_model->update_entry($id, ['name' => $name, 'extra_info' => $extra ?: NULL]);
        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => TRUE, 'entry' => $this->Raffle_model->get_entry($id)]));
    }

    public function ajax_delete_participant()
    {
        $id    = (int) $this->input->post('id');
        $entry = $this->Raffle_model->get_entry($id);
        if ( ! $entry) { echo json_encode(['success' => FALSE, 'message' => 'Not found.']); return; }
        $this->Raffle_model->delete_entry($id);
        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => TRUE]));
    }

    // ── EXPORT WINNERS ─────────────────────────────────────────────────

    public function export_winners()
    {
        $winners = $this->Raffle_model->get_winners();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="raffle_winners_'.date('Ymd_His').'.csv"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
        fputcsv($out, ['#', 'Name', 'Extra Info', 'Prize', 'Won At']);
        foreach ($winners as $i => $w) {
            fputcsv($out, [$i + 1, $w['name'], $w['extra_info'] ?? '', $w['prize_name'] ?? '(No Prize)', $w['won_at'] ?? '']);
        }
        fclose($out);
        exit;
    }
}
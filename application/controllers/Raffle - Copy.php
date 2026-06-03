<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raffle extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Raffle_model');
        $this->load->library('upload');
        $this->load->helper(['url', 'form']);
    }

    // Main raffle page
    public function index()
    {
        $data['entries'] = $this->Raffle_model->get_all_entries();
        $data['winner']  = $this->session->flashdata('winner');
        $this->load->view('raffle_view', $data);
    }

    // Handle Excel upload
    public function upload()
    {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size']      = 5120;
        $config['file_name']     = 'raffle_entries_' . time();

        // Create upload dir if missing
        if ( ! is_dir('./uploads/')) {
            mkdir('./uploads/', 0755, TRUE);
        }

        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('excel_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('raffle');
        }

        $file_data = $this->upload->data();
        $file_path = './uploads/' . $file_data['file_name'];

        $entries = $this->Raffle_model->parse_excel($file_path);

        if (empty($entries)) {
            $this->session->set_flashdata('error', 'No valid entries found in the file. Make sure column A has names.');
            redirect('raffle');
        }

        // Clear old entries and insert new
        $this->Raffle_model->clear_entries();
        $this->Raffle_model->insert_entries($entries);

        // Clean up uploaded file
        @unlink($file_path);

        $this->session->set_flashdata('success', count($entries) . ' entries loaded successfully!');
        redirect('raffle');
    }

    // AJAX: return all entries as JSON for the spinning animation
    public function get_entries()
    {
        $entries = $this->Raffle_model->get_all_entries();
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($entries));
    }

    // AJAX: pick a random winner (excludes already-won entries)
    public function pick_winner()
    {
        $winner = $this->Raffle_model->pick_random_winner();

        if ( ! $winner) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => FALSE, 'message' => 'No eligible entries left.']));
            return;
        }

        // Mark as won
        $this->Raffle_model->mark_as_won($winner['id']);

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => TRUE, 'winner' => $winner]));
    }

    // Reset — clear all winners (keeps entries)
    public function reset_winners()
    {
        $this->Raffle_model->reset_winners();
        $this->session->set_flashdata('success', 'All winners have been reset.');
        redirect('raffle');
    }

    // Clear everything
    public function clear_all()
    {
        $this->Raffle_model->clear_entries();
        $this->session->set_flashdata('success', 'All entries cleared.');
        redirect('raffle');
    }

    // ── PARTICIPANTS PAGE ──────────────────────────────────────────────

    // Show participants list + add/edit form
    public function participants()
    {
        $data['entries']       = $this->Raffle_model->get_all_entries();
        $data['edit_entry']    = NULL;
        $data['search']        = $this->input->get('q');
        $data['filter_status'] = $this->input->get('status');

        if ($data['search'] || $data['filter_status']) {
            $data['entries'] = $this->Raffle_model->search_entries(
                $data['search'],
                $data['filter_status']
            );
        }

        // If editing, load that entry
        $edit_id = $this->input->get('edit');
        if ($edit_id) {
            $data['edit_entry'] = $this->Raffle_model->get_entry($edit_id);
        }

        $this->load->view('participants_view', $data);
    }

    // Add a single participant
    public function add_participant()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name',       'Name',       'required|trim|max_length[255]');
        $this->form_validation->set_rules('extra_info', 'Extra Info', 'trim|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            redirect('raffle/participants');
        }

        $name  = $this->input->post('name', TRUE);
        $extra = $this->input->post('extra_info', TRUE);

        // Duplicate check
        if ($this->Raffle_model->name_exists($name)) {
            $this->session->set_flashdata('error', "\"$name\" is already in the list.");
            redirect('raffle/participants');
        }

        $this->Raffle_model->insert_entries([[
            'name'       => $name,
            'extra_info' => $extra !== '' ? $extra : NULL,
        ]]);

        $this->session->set_flashdata('success', "\"$name\" added successfully!");
        redirect('raffle/participants');
    }

    // Update a participant
    public function update_participant()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id',         'ID',         'required|integer');
        $this->form_validation->set_rules('name',       'Name',       'required|trim|max_length[255]');
        $this->form_validation->set_rules('extra_info', 'Extra Info', 'trim|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            redirect('raffle/participants');
        }

        $id    = (int) $this->input->post('id');
        $name  = $this->input->post('name', TRUE);
        $extra = $this->input->post('extra_info', TRUE);

        // Duplicate check (exclude self)
        if ($this->Raffle_model->name_exists($name, $id)) {
            $this->session->set_flashdata('error', "\"$name\" already exists.");
            redirect('raffle/participants?edit=' . $id);
        }

        $this->Raffle_model->update_entry($id, [
            'name'       => $name,
            'extra_info' => $extra !== '' ? $extra : NULL,
        ]);

        $this->session->set_flashdata('success', "Entry updated successfully!");
        redirect('raffle/participants');
    }

    // Delete a participant
    public function delete_participant($id)
    {
        $id = (int) $id;
        $entry = $this->Raffle_model->get_entry($id);
        if ($entry) {
            $this->Raffle_model->delete_entry($id);
            $this->session->set_flashdata('success', "\"" . htmlspecialchars($entry['name']) . "\" removed.");
        }
        redirect('raffle/participants');
    }

    // AJAX: add participant inline
    public function ajax_add_participant()
    {
        $name  = trim($this->input->post('name', TRUE));
        $extra = trim($this->input->post('extra_info', TRUE));

        if ($name === '') {
            echo json_encode(['success' => FALSE, 'message' => 'Name is required.']);
            return;
        }

        if ($this->Raffle_model->name_exists($name)) {
            echo json_encode(['success' => FALSE, 'message' => "\"$name\" already exists."]);
            return;
        }

        $this->Raffle_model->insert_entries([[
            'name'       => $name,
            'extra_info' => $extra !== '' ? $extra : NULL,
        ]]);

        $new_entry = $this->Raffle_model->get_last_inserted();

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => TRUE, 'entry' => $new_entry]));
    }

    // AJAX: delete participant
    public function ajax_delete_participant()
    {
        $id    = (int) $this->input->post('id');
        $entry = $this->Raffle_model->get_entry($id);
        if ( ! $entry) {
            echo json_encode(['success' => FALSE, 'message' => 'Entry not found.']);
            return;
        }
        $this->Raffle_model->delete_entry($id);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => TRUE]));
    }

    // AJAX: update participant
    public function ajax_update_participant()
    {
        $id    = (int) $this->input->post('id');
        $name  = trim($this->input->post('name', TRUE));
        $extra = trim($this->input->post('extra_info', TRUE));

        if ($name === '') {
            echo json_encode(['success' => FALSE, 'message' => 'Name is required.']);
            return;
        }

        if ($this->Raffle_model->name_exists($name, $id)) {
            echo json_encode(['success' => FALSE, 'message' => "\"$name\" already exists."]);
            return;
        }

        $this->Raffle_model->update_entry($id, [
            'name'       => $name,
            'extra_info' => $extra !== '' ? $extra : NULL,
        ]);

        $updated = $this->Raffle_model->get_entry($id);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => TRUE, 'entry' => $updated]));
    }
}
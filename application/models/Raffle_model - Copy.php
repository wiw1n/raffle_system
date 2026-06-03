<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raffle_model extends CI_Model {

    private $table = 'raffle_entries';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->_create_table();
    }

    // Auto-create table if not exists
    private function _create_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name`       VARCHAR(255)     NOT NULL,
            `extra_info` VARCHAR(255)     DEFAULT NULL,
            `has_won`    TINYINT(1)       NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->db->query($sql);
    }

    // Parse Excel / CSV file and return array of entry arrays
    public function parse_excel($file_path)
    {
        $ext     = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $entries = [];

        if ($ext === 'csv') {
            // Parse CSV
            if (($handle = fopen($file_path, 'r')) !== FALSE) {
                $row_num = 0;
                while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $row_num++;
                    if ($row_num === 1) continue; // skip header
                    $name = isset($row[0]) ? trim($row[0]) : '';
                    if ($name === '') continue;
                    $entries[] = [
                        'name'       => $name,
                        'extra_info' => isset($row[1]) ? trim($row[1]) : NULL,
                    ];
                }
                fclose($handle);
            }
        } else {
            // Parse XLSX / XLS using PhpSpreadsheet (must be installed via Composer)
            // Fallback: try to use PhpSpreadsheet if available, otherwise instruct user
            if ( ! file_exists(APPPATH . '../vendor/autoload.php')) {
                // Provide a helpful error — composer install required
                log_message('error', 'PhpSpreadsheet not found. Run: composer require phpoffice/phpspreadsheet');
                return [];
            }

            require_once APPPATH . '../vendor/autoload.php';

            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
                $sheet       = $spreadsheet->getActiveSheet();
                $highest_row = $sheet->getHighestRow();

                for ($row = 2; $row <= $highest_row; $row++) {
                    $name = trim((string) $sheet->getCell('A' . $row)->getValue());
                    if ($name === '') continue;
                    $extra = trim((string) $sheet->getCell('B' . $row)->getValue());
                    $entries[] = [
                        'name'       => $name,
                        'extra_info' => $extra !== '' ? $extra : NULL,
                    ];
                }
            } catch (Exception $e) {
                log_message('error', 'Excel parse error: ' . $e->getMessage());
                return [];
            }
        }

        return $entries;
    }

    // Insert multiple entries
    public function insert_entries(array $entries)
    {
        if ( ! empty($entries)) {
            $this->db->insert_batch($this->table, $entries);
        }
    }

    // Get all entries
    public function get_all_entries()
    {
        return $this->db->get($this->table)->result_array();
    }

    // Pick a random entry that hasn't won yet
    public function pick_random_winner()
    {
        $result = $this->db
            ->where('has_won', 0)
            ->order_by('RAND()')
            ->limit(1)
            ->get($this->table)
            ->row_array();

        return $result ?: NULL;
    }

    // Mark an entry as won
    public function mark_as_won($id)
    {
        $this->db->where('id', $id)->update($this->table, ['has_won' => 1]);
    }

    // Reset all winners
    public function reset_winners()
    {
        $this->db->update($this->table, ['has_won' => 0]);
    }

    // Delete all entries
    public function clear_entries()
    {
        $this->db->truncate($this->table);
    }

    // ── PARTICIPANT CRUD ───────────────────────────────────────────────

    // Get a single entry by ID
    public function get_entry($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    // Get the most recently inserted entry
    public function get_last_inserted()
    {
        return $this->db->order_by('id', 'DESC')->limit(1)->get($this->table)->row_array();
    }

    // Update a single entry
    public function update_entry($id, array $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    // Delete a single entry
    public function delete_entry($id)
    {
        $this->db->where('id', $id)->delete($this->table);
    }

    // Check if a name already exists (optionally exclude an ID for edit checks)
    public function name_exists($name, $exclude_id = NULL)
    {
        $this->db->where('name', $name);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    // Search / filter entries
    public function search_entries($query = NULL, $status = NULL)
    {
        if ($query) {
            $this->db->like('name', $query);
        }
        if ($status === 'eligible') {
            $this->db->where('has_won', 0);
        } elseif ($status === 'won') {
            $this->db->where('has_won', 1);
        }
        return $this->db->order_by('id', 'ASC')->get($this->table)->result_array();
    }
}
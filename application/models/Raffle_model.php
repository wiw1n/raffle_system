<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raffle_model extends CI_Model {

    private $table        = 'raffle_entries';
    private $prizes_table = 'raffle_prizes';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->_create_tables();
    }

    // ── AUTO-CREATE / MIGRATE TABLES ───────────────────────────────────

    private function _create_tables()
    {
        // Prizes table
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->prizes_table}` (
            `id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name`        VARCHAR(255)     NOT NULL,
            `description` VARCHAR(500)     DEFAULT NULL,
            `quantity`    INT(11)          NOT NULL DEFAULT 1,
            `is_active`   TINYINT(1)       NOT NULL DEFAULT 0,
            `sort_order`  INT(11)          NOT NULL DEFAULT 0,
            `created_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        // Migrate: add is_active if missing
        $prize_cols = array_column($this->db->field_data($this->prizes_table), 'name');
        if ( ! in_array('is_active', $prize_cols)) {
            $this->db->query("ALTER TABLE `{$this->prizes_table}` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 0 AFTER `quantity`");
        }

        // Entries table
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name`       VARCHAR(255)     NOT NULL,
            `extra_info` VARCHAR(255)     DEFAULT NULL,
            `has_won`    TINYINT(1)       NOT NULL DEFAULT 0,
            `prize_id`   INT(11) UNSIGNED DEFAULT NULL,
            `prize_name` VARCHAR(255)     DEFAULT NULL,
            `won_at`     TIMESTAMP        NULL DEFAULT NULL,
            `created_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        // Migrate entry columns if table already existed
        $entry_cols = array_column($this->db->field_data($this->table), 'name');
        if ( ! in_array('prize_id',   $entry_cols)) $this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `prize_id`   INT(11) UNSIGNED DEFAULT NULL AFTER `has_won`");
        if ( ! in_array('prize_name', $entry_cols)) $this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `prize_name` VARCHAR(255)     DEFAULT NULL AFTER `prize_id`");
        if ( ! in_array('won_at',     $entry_cols)) $this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `won_at`     TIMESTAMP NULL   DEFAULT NULL AFTER `prize_name`");
    }

    // ── PRIZES ─────────────────────────────────────────────────────────

    public function get_all_prizes()
    {
        return $this->db->order_by('sort_order','ASC')->order_by('id','ASC')->get($this->prizes_table)->result_array();
    }

    public function get_prize($id)
    {
        return $this->db->where('id', $id)->get($this->prizes_table)->row_array();
    }

    /** Returns the single active prize, or NULL if none is active */
    public function get_active_prize()
    {
        return $this->db->where('is_active', 1)->get($this->prizes_table)->row_array() ?: NULL;
    }

    public function insert_prize(array $data)
    {
        $this->db->insert($this->prizes_table, $data);
        return $this->db->insert_id();
    }

    public function update_prize($id, array $data)
    {
        $this->db->where('id', $id)->update($this->prizes_table, $data);
    }

    public function delete_prize($id)
    {
        $this->db->where('id', $id)->delete($this->prizes_table);
    }

    /** Deactivate every prize, then optionally activate one */
    public function set_active_prize($id = NULL)
    {
        $this->db->update($this->prizes_table, ['is_active' => 0]);
        if ($id) {
            $this->db->where('id', $id)->update($this->prizes_table, ['is_active' => 1]);
        }
    }

    public function prize_won_count($prize_id)
    {
        return $this->db->where('prize_id', $prize_id)->where('has_won', 1)->count_all_results($this->table);
    }

    // ── ENTRIES ────────────────────────────────────────────────────────

    public function get_all_entries()
    {
        return $this->db->order_by('id','ASC')->get($this->table)->result_array();
    }

    public function get_entry($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function get_last_inserted()
    {
        return $this->db->order_by('id','DESC')->limit(1)->get($this->table)->row_array();
    }

    public function insert_entries(array $entries)
    {
        if ( ! empty($entries)) $this->db->insert_batch($this->table, $entries);
    }

    public function update_entry($id, array $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_entry($id)
    {
        $this->db->where('id', $id)->delete($this->table);
    }

    public function name_exists($name, $exclude_id = NULL)
    {
        $this->db->where('name', $name);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function search_entries($query = NULL, $status = NULL)
    {
        if ($query)            $this->db->like('name', $query);
        if ($status === 'eligible') $this->db->where('has_won', 0);
        elseif ($status === 'won')  $this->db->where('has_won', 1);
        return $this->db->order_by('id','ASC')->get($this->table)->result_array();
    }

    public function pick_random_winner()
    {
        return $this->db->where('has_won', 0)->order_by('RAND()')->limit(1)->get($this->table)->row_array() ?: NULL;
    }

    public function mark_as_won($id, $prize_id = NULL, $prize_name = NULL)
    {
        $this->db->where('id', $id)->update($this->table, [
            'has_won'    => 1,
            'prize_id'   => $prize_id  ?: NULL,
            'prize_name' => $prize_name ?: NULL,
            'won_at'     => date('Y-m-d H:i:s'),
        ]);
    }

    public function reset_winners()
    {
        $this->db->update($this->table, ['has_won' => 0, 'prize_id' => NULL, 'prize_name' => NULL, 'won_at' => NULL]);
    }

    public function clear_entries()
    {
        $this->db->truncate($this->table);
    }

    public function get_winners()
    {
        return $this->db->where('has_won', 1)->order_by('won_at','ASC')->get($this->table)->result_array();
    }

    // ── EXCEL / CSV PARSE ──────────────────────────────────────────────

    public function parse_excel($file_path)
    {
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $entries = [];

        if ($ext === 'csv') {
            if (($h = fopen($file_path, 'r')) !== FALSE) {
                $n = 0;
                while (($row = fgetcsv($h, 1000, ',')) !== FALSE) {
                    if (++$n === 1) continue;
                    $name = isset($row[0]) ? trim($row[0]) : '';
                    if ($name === '') continue;
                    $entries[] = ['name' => $name, 'extra_info' => isset($row[1]) ? trim($row[1]) : NULL];
                }
                fclose($h);
            }
        } else {
            if ( ! file_exists(APPPATH . '../vendor/autoload.php')) {
                log_message('error', 'PhpSpreadsheet not found. Run: composer require phpoffice/phpspreadsheet');
                return [];
            }
            require_once APPPATH . '../vendor/autoload.php';
            try {
                $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path)->getActiveSheet();
                for ($r = 2; $r <= $sheet->getHighestRow(); $r++) {
                    $name = trim((string) $sheet->getCell('A'.$r)->getValue());
                    if ($name === '') continue;
                    $extra = trim((string) $sheet->getCell('B'.$r)->getValue());
                    $entries[] = ['name' => $name, 'extra_info' => $extra !== '' ? $extra : NULL];
                }
            } catch (Exception $e) { log_message('error', 'Excel parse error: '.$e->getMessage()); }
        }

        return $entries;
    }
}
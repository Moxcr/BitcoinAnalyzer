<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitcoin_data_model extends CI_Model {
    public $open_value;
    public $record_date;
    public $created_at;

    public function get_last_pull_time()
    {
        $this->db->select('created_at');
        $this->db->from('bitcoin_data');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query->result_array()[0]['created_at'] : false;
    }

    public function is_stale() {
        $last_time = $this->get_last_pull_time();
        return (bool) (!$last_time || strtotime($last_time . ' +1 day') < time());
    }

    public function insert_record($bitcoin_record_data) {
        $this->open_value = $bitcoin_record_data['open_value'];
        $this->record_date = $bitcoin_record_data['date'];
        $this->created_at = date('Y-m-d H:i:s');

        if ($this->db->get_where('bitcoin_data', array('record_date' => $this->record_date))->num_rows() === 0) {
            return $this->db->insert('bitcoin_data', $this);
        }

        return false;
    }

    public function get_historical_data(int $start_time, int $end_time) {
        if ($end_time < $start_time) {
            log_message('error', 'Start time must come before end time.');
            return false;
        }

        $this->db->select(array('open_value', 'record_date'));
        $this->db->from('bitcoin_data');
        $this->db->order_by('record_date', 'DESC');
        $this->db->limit(30);

        $this->db->where('record_date >=', date('Y-m-d', $start_time));
        $this->db->where('record_date <=', date('Y-m-d', $end_time));

        $query = $this->db->get();

        if ($query === null || $query === false) {
            log_message('error', 'There was an error pulling the data.');
            return false;
        }

        return $query->result_array();
    }

}
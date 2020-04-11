<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitcoin_api
{
    private $ci;
    private $api_url;
    private $skip_first;
    private $date_col;
    private $open_val_col;

    public function __construct() {
        $this->ci = &get_instance();

        $this->ci->config->load('bitcoin_api');

        $config = $this->ci->config;

        $this->api_url = $config->item('bitcoin_api_url');
        $this->skip_first = $config->item('bitcoin_csv_skip_first_line');
        $this->date_col = $config->item('bitcoin_csv_date_col');
        $this->open_val_col = $config->item('bitcoin_csv_open_val_col');
    }


    public function refresh_data() {
        $csv_data = $this->download_bitcoin_data();
        $parsed_data = $this->process_bitcoin_csv($csv_data);

        $this->populate_csv_data($parsed_data);
    }

    protected function download_bitcoin_data() {
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        if (empty($data)) {
            log_message('error', 'Unable to download Bitcoin CSV.');

            return false;
        }

        return $data;
    }

    protected function process_bitcoin_csv($csv_data) {
        $rows = str_getcsv($csv_data, "\n");

        if ($this->skip_first) {
            array_shift($rows);
        }

        $result = array();
        foreach ($rows as $row) {
            $cols = str_getcsv($row, ",");

            $result []= array('date' => $cols[$this->date_col], 'open_value' => $cols[$this->open_val_col]);
        }

        return $result;
    }

    protected function populate_csv_data($parsed_data) {
        foreach ($parsed_data as $row) {
            $this->ci->Bitcoin_data_model->insert_record($row);
        }
    }
}
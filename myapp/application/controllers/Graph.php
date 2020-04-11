<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Graph extends CI_Controller {

    public function index() {
        $this->load->model('Bitcoin_data_model');
        if ($this->Bitcoin_data_model->is_stale()) {
            $this->load->library('Bitcoin_api');
            $this->bitcoin_api->refresh_data();
        }

        $view_vars = array();


        $this->load->view('graph', $view_vars);
    }

    public function graph_data() {
        $this->load->model('Bitcoin_data_model');

        $start_time = strtotime($_POST['start']);
        $end_time = strtotime($_POST['end']);

        $data = $this->Bitcoin_data_model->get_historical_data($start_time, $end_time);

        echo json_encode($data);
        exit;
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller
{

    public function index() {
        $this->load->library('migration');

        if ($this->migration->current() !== FALSE) {
            echo "Migration successful.";
            exit;
        } else {
            show_error($this->migration->error_string());
        }
    }

}
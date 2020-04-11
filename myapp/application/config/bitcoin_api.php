<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array();

$config['bitcoin_api_url'] = 'https://query1.finance.yahoo.com/v7/finance/download/BTC-USD?period1=' . strtotime('-30 days') . '&period2=' . time() . '&interval=1d&events=history';
$config['bitcoin_csv_skip_first_line'] = true;
$config['bitcoin_csv_date_col'] = 0;
$config['bitcoin_csv_open_val_col'] = 1;

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_bitcoin_data extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'open_value' => array(
                'type' => 'DECIMAL(10,6)'
            ),
            'record_date' => array(
                'type' => 'DATE'
            ),
            'created_at DATETIME default CURRENT_TIMESTAMP'
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('bitcoin_data');
    }

    public function down()
    {
        $this->dbforge->drop_table('bitcoin_data');
    }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Data_json extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        $data[] = array(
            'no'     => 1,
            'email'   => 'demo1@gmail.com',
            'password' => password_hash('demo', PASSWORD_BCRYPT),
            'birthdate' => '17',
        );

        $data[] = array(
            'no'     => 2,
            'email'   => 'demo2@gmail.com',
            'password' => password_hash('demo', PASSWORD_BCRYPT),
            'birthdate' => '18',
        );

        $jsonfile = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents('users.json', $jsonfile);

        return  $this->session->set_flashdata('message', 'Json Created');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user = json_decode(file_get_contents('users.json'));
    }

    public function index()
    {
        $data['title'] = 'Home';
        $data['user'] =  $this->user;
        $this->load->view('templates/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer');
    }
}

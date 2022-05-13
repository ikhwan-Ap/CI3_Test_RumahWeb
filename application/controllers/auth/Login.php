<?php

use phpDocumentor\Reflection\PseudoTypes\False_;
use PhpParser\Node\Stmt\Echo_;

defined('BASEPATH') or exit('No direct script access allowed');

class login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user = json_decode(file_get_contents('users.json'));
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index()
    {
        $data['title'] = 'Login';
        $this->load->view('auth/index', $data);
    }

    public function login()
    {

        $users = json_decode(file_get_contents('users.json'));

        if ($this->input->post('email') != null  && $this->input->post('password') != null) {
            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i]->email === $this->input->post('email')) {
                    if ($users[$i]->password === $this->input->post('password')) {
                        $session_data = array(
                            'email' => $this->input->post('email'),
                            'status' => 'Login'
                        );
                        $this->session->set_userdata($session_data);
                        $data = ['success' => 'Login Successfully'];
                        echo json_encode($data);
                        break;
                    }
                }
            }
        } else {
            $data = ['error' => 'Wrong Password / Email'];
            echo json_encode($data);
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->set_flashdata('message', 'Logout Berhasil');
        $data = [
            'success' => 'Andar Berhasil Logout'
        ];
        echo json_encode($data);
    }
}

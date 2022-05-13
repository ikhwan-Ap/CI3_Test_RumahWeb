<?php
defined('BASEPATH') or exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class User extends RestController
{

    protected $users;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    public function index_get()
    {
        $users = json_decode(file_get_contents('users.json'));
        if ($users) {
            $response = [
                'status' => 200,
                'total' => count($users),
                'error' => false,
                'message' => 'Successfully Get Data',
                'users' => $users,
            ];
            return $this->response($response);
        }
    }

    public function show_get($id = null)
    {
        $users = json_decode(file_get_contents('users.json'));

        if ($id) {
            foreach ($users as $key => $value) {
                $data = $value->id;
                if ($id == $data) {
                    $user = [
                        'id' =>  $users[$key]->id,
                        'email' =>  $users[$key]->email,
                        'password' =>  $users[$key]->password,
                        'birthdate' =>  $users[$key]->birthdate,
                    ];
                    $response = [
                        'status' => 200,
                        'error' => false,
                        'message' => 'Successfully Get Data',
                        'users' => $user,
                    ];
                    return $this->response($response);
                }
            }
        }
        return $this->response(['message' => 'Not Data Find With Id ' . $id], 404);
    }

    public function delete_delete($id = null)
    {
        $users = json_decode(file_get_contents('users.json'));
        if ($id) {
            foreach ($users as $key => $value) {
                $data = $value->id;
                if ($id == $data) {
                    array_splice($users, $key, $id);
                    $jsonfile = json_encode($users, JSON_PRETTY_PRINT);
                    file_put_contents('users.json', $jsonfile);
                    return $this->response(['success' => 'Deleted Successfully']);
                }
            }
        }
        return $this->response(['message' => 'Not Data Find With Id ' . $id], 404);
    }

    public function create_post()
    {
        $users = json_decode(file_get_contents('users.json'));

        if ($this->input->post()) {

            $rules = array(
                [
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'min_length[12]|callback_valid_password',
                ],

                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'callback_valid_email|required'
                ],

                'email' => [
                    'field' => 'birthdate',
                    'label' => 'Birthdate',
                    'rules' => 'callback_valid_birthDate',
                ],

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run()) {
                $users[] = [
                    'id' => count($users) + 1,
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password'),
                    'birthdate' => $this->input->post('birthdate'),
                ];
                $jsonfile = json_encode($users, JSON_PRETTY_PRINT);
                file_put_contents('users.json', $jsonfile);
                $response = [
                    'status' => 201,
                    'success' => 'Created Successfully',
                ];
                return $this->response($response);
            } else {
                $response = [
                    'error' => $this->validation_errors(),
                    'status' => 400,
                ];

                return $this->response($response);
            }
        }
    }

    public function edit_put($id = null)
    {
        $users = json_decode(file_get_contents('users.json'));
        if ($id) {
            if ($this->put('password')) {
                $this->form_validation->set_data($this->put());
                $rules = array(
                    [
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'min_length[12]|callback_valid_password',
                    ],
                    [
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'valid_email|required|callback_valid_email'
                    ],
                    [
                        'field' => 'birthdate',
                        'label' => 'Birthdate',
                        'rules' => 'callback_valid_birthDate',
                    ],
                );
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run()) {
                    foreach ($users as $key => $value) {
                        if ($value->id == $id) {
                            $users[$key]->id = $id;
                            $users[$key]->email = $this->put('email');
                            $users[$key]->password = $this->input->post('password');
                            $users[$key]->birthdate = $this->put('birthdate');
                        }
                    }
                    $jsonfile = json_encode($users, JSON_PRETTY_PRINT);

                    file_put_contents('users.json', $jsonfile);
                    $response = [
                        'status' => 200,
                        'success' => 'Updated Successfully'
                    ];
                    return $this->response($response);
                } else {
                    $response = [
                        'error' => $this->validation_errors(),
                        'status' => 400,
                    ];
                    return $this->response($response);
                }
            } else {
                $this->form_validation->set_data($this->put());
                $rules = array(
                    [
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'callback_valid_email|required'
                    ],
                    [
                        'field' => 'birthdate',
                        'label' => 'Birthdate',
                        'rules' => 'callback_valid_birthDate',
                    ],
                );
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run()) {
                    foreach ($users as $key => $value) {
                        if ($value->id == $id) {
                            $users[$key]->id = $id;
                            $users[$key]->email = $this->put('email');
                            $users[$key]->birthdate = $this->put('birthdate');
                        }
                    }
                    $jsonfile = json_encode($users, JSON_PRETTY_PRINT);

                    file_put_contents('users.json', $jsonfile);
                    $response = [
                        'status' => 200,
                        'success' => 'Updated Successfully'
                    ];
                    return $this->response($response);
                } else {
                    $response = [
                        'error' => $this->validation_errors(),
                        'status' => 400,
                    ];
                    return $this->response($response);
                }
            }
        }
    }

    public function valid_password($password = '')
    {
        $password = trim($password);

        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';

        if (empty($password)) {
            $this->form_validation->set_message('valid_password', 'The {field} field is required.');
            return FALSE;
        }

        if (preg_match_all($regex_lowercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');
            return FALSE;
        }

        if (preg_match_all($regex_uppercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');

            return FALSE;
        }

        if (preg_match_all($regex_number, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');

            return FALSE;
        }

        if (preg_match_all($regex_special, $password) < 2) {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least two special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));

            return FALSE;
        }

        return TRUE;
    }

    public function valid_birthDate($birthdate = '')
    {
        $birthdate = trim($birthdate);
        if (empty($birthdate)) {
            $this->form_validation->set_message('valid_birthDate', 'The {field} field is required.');
            return FALSE;
        }
        if ($birthdate < 17) {
            $this->form_validation->set_message('valid_birthDate', 'The {field} Old Must Up To 17 Years Old.');

            return FALSE;
        }
        return TRUE;
    }
    public function valid_email($email = '')
    {
        $regex_email = '/@rumahweb.co.id/';
        $email = trim($email);
        if (empty($email)) {
            $this->form_validation->set_message('valid_email', 'The {field} field is required.');
            return FALSE;
        }


        if (preg_match_all($regex_email, $email) < 1) {
            $this->form_validation->set_message('valid_email', 'The {field} Email Must @rumahweb.co.id.');
            return FALSE;
        }
        return TRUE;
    }
}

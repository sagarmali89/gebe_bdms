<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Sagar Mali
 * @version : 1.1
 * @since : 12 Oct 2018
 */
class Login extends CI_Controller {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('user_model');
        error_reporting(E_ALL);
        //without index.php
        // $this->redirect_key = '';
        // $this->form_submit_redirect_key = 'user/';
        //with index.php
        $this->redirect_key_login = 'index.php/login/';
        $this->redirect_key = 'index.php/user/';
        $this->form_submit_redirect_key = '';
    }

    /**
     * Index Page for this controller.
     */
    public function index() {
        $this->isLoggedIn();
    }

    public function logout() {
        $this->session->userdata('isLoggedIn', FALSE);
        session_destroy();
        redirect($this->redirect_key_login);
    }

    public function breakdown() {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            
            
              $regions = array();
            $reasons = array();
            $technicians = array();
            $street_names = array();
            $users = array();


            // get all regions
            $table = 'ims_region';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $regions[$ro->id] = $ro->region;
                }
            }
            asort($regions);
            //get reasons
            $table = 'ims_reason';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $reasons[$ro->id] = $ro->reason;
                }
            }
            asort($reasons);
            //get technicians
            $table = 'ims_technicians';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $technicians[$ro->id] = $ro->name;
                }
            }
            asort($technicians);

            // get $street_names
            $table = 'ims_street_name';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $street_names[$ro->id] = $ro->street_name;
                }
            }

            asort($street_names);

            // get all users
            $table = 'tbl_users';
            $where = array('roleId !=', '1');
            $res = $this->user_model->getData($table, $where);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $users[$ro->userId] = $ro->name;
                }
            }
            asort($users);


            $data['technicians'] = $technicians;
            $data['reasons'] = $reasons;
            $data['regions'] = $regions;
            $data['street_names'] = $street_names;
            $data['users'] = $users;
            
            $data['invoice'] = $this->user_model->getBreakdowns($id)->row();
            $this->load->view('templates/breakdown', $data);
        } else {
            echo "<h1>Sorry ! Page you are searching is not available...<h1>";
        }
    }

    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn() {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            $data['redirect_key'] = $this->redirect_key;
            $data['form_submit_redirect_key'] = $this->form_submit_redirect_key;
            $data['redirect_key_login'] = $this->redirect_key_login;
            $this->load->view('login', $data);
        } else {
            redirect($this->redirect_key . 'dashboard');
        }
    }

    /**
     * This function used to logged in user
     */
    public function loginMe() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');

            $result = $this->login_model->loginMe($email, $password);

            if (!empty($result)) {
                $lastLogin = $this->login_model->lastLoginInfo($result->userId);
                if (!$lastLogin) {
                    $createdDtm = '';
                } else {
                    $createdDtm = $lastLogin->createdDtm;
                }
                $sessionArray = array('userId' => $result->userId,
                    'role' => $result->roleId,
                    'roleText' => $result->role,
                    'name' => $result->name,
                    'lastLogin' => $createdDtm,
                    'isLoggedIn' => TRUE
                );

                $this->session->set_userdata($sessionArray);

                unset($sessionArray['userId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);

                $loginInfo = array("userId" => $result->userId, "sessionData" => json_encode($sessionArray), "machineIp" => $_SERVER['REMOTE_ADDR'], "userAgent" => getBrowserAgent(), "agentString" => $this->agent->agent_string(), "platform" => $this->agent->platform());

                $this->login_model->lastLogin($loginInfo);
//                if ($result->roleId == '2') {
//                    //user
//                    redirect($this->redirect_key . 'user_dashboard');
//                } else if ($result->roleId == 1) {
                //admin
                redirect($this->redirect_key . 'dashboard');
//                }
            } else {
                $this->session->set_flashdata('error', 'Email or password mismatch');

                $this->index();
            }
        }
    }

    /**
     * This function used to load forgot password view
     */
    public function forgotPassword() {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            $data['redirect_key'] = $this->redirect_key;
            $data['form_submit_redirect_key'] = $this->form_submit_redirect_key;
            $data['redirect_key_login'] = $this->redirect_key_login;

            $this->load->view('forgotPassword', $data);
        } else {
            redirect($this->redirect_key . 'dashboard');
        }
    }

    /**
     * This function used to generate reset password request link
     */
    function resetPasswordUser() {

        $status = '';
//ini_set('sendmail_from', "support@itsea.in" ); 
//ini_set('SMTP', "mail.itsea.in");  
//ini_set('smtp_port', 25);
        $this->load->library('form_validation');
//        error_reporting(E_ALL);
        $this->form_validation->set_rules('login_email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->forgotPassword();
        } else {
            $email = strtolower($this->security->xss_clean($this->input->post('login_email')));

            if ($this->login_model->checkEmailExist($email)) {
                $encoded_email = urlencode($email);

                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum', 15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = getBrowserAgent();
                $data['client_ip'] = $this->input->ip_address();

                $save = $this->login_model->resetPasswordUser($data);

                if ($save) {
                    $data1['reset_link'] = base_url() . $this->redirect_key_login . "resetPasswordConfirmUser/" . $data['activation_id'] . "/" . $encoded_email;
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);

                    if (!empty($userInfo)) {
                        $data1["name"] = $userInfo->name;
                        $data1["email"] = $userInfo->email;
                        $data1["message"] = "Reset Your Password";
                    }

                    $sendStatus = resetPasswordEmail($data1);
                    // print_r($sendStatus);
                    if ($sendStatus) {
                        $status = "send";
                        setFlashData($status, "Reset password link sent successfully, please check mails.");
                    } else {
                        $status = "notsend";
                        setFlashData($status, "Email has been failed, try again.");
                        echo 'not sent';
                        die();
                    }
                } else {
                    $status = 'unable';
                    setFlashData($status, "It seems an error while sending your details, try again.");
                }
            } else {
                $status = 'invalid';
                setFlashData($status, "This email is not registered with us.");
            }

            redirect($this->redirect_key_login . 'forgotPassword');
        }
    }

    /**
     * This function used to reset the password 
     * @param string $activation_id : This is unique id
     * @param string $email : This is user email
     */
    function resetPasswordConfirmUser($activation_id, $email) {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);

        // Check activation id in database
        $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);

        $data['email'] = $email;
        $data['activation_code'] = $activation_id;

        if ($is_correct == 1) {
            $data['redirect_key'] = $this->redirect_key;
            $data['form_submit_redirect_key'] = $this->form_submit_redirect_key;
            $data['redirect_key_login'] = $this->redirect_key_login;

            $this->load->view('newPassword', $data);
        } else {
            redirect($this->redirect_key_login);
        }
    }

    /**
     * This function used to create new password for user
     */
    function createPasswordUser() {
        $status = '';
        $message = '';
        $email = strtolower($this->input->post("email"));
        $activation_id = $this->input->post("activation_code");

        $this->load->library('form_validation');

        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $this->resetPasswordConfirmUser($activation_id, urlencode($email));
        } else {
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');

            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);

            if ($is_correct == 1) {
                $this->login_model->createPasswordUser($email, $password);

                $status = 'success';
                $message = 'Password reset successfully';
            } else {
                $status = 'error';
                $message = 'Password reset failed';
            }
            die($this->db->last_query());
            setFlashData($status, $message);

            redirect($this->redirect_key_login);
        }
    }

}

?>
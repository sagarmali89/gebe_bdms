<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class : Front (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Sagar Mali
 * @version : 1.1
 * @since : 30 Oct 2018
 */
class Web extends CI_Controller {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index() {
        $this->load->model('user_model');
        $table = 'schedules';

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $where = array('scheduled_type' => 'Water', 'to_date>=' => $date);
        $res = $this->user_model->getData($table, $where);
        $data['water_schedules'] = $res->result();

        $where = array('scheduled_type' => 'Electricity', 'to_date>=' => $date);
        $res1 = $this->user_model->getData($table, $where);
        $data['electricity_schedules'] = $res1->result();


        $this->global['pageTitle'] = 'View Water & Electricity Scheduled';
        $data['redirect_key'] = $this->redirect_key;
        $data['time'] = $time;
        $data['date'] = $date;

        $this->load->view('map', $data);
    }

}

?>
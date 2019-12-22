<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Sagar Mali
 * @version : 1.1
 * @since : 16 Oct 2018
 * @last_updated 18 Dec
 */
class User extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();
        error_reporting(E_ERROR | E_PARSE);
        //without index.php
        // $this->redirect_key = '';
        // $this->form_submit_redirect_key = 'user/';
        //with index.php
        $this->redirect_key = 'index.php/user/';
        $this->form_submit_redirect_key = '';
        $this->report_types = array('Daily breakdown Report', 'Completed Jobs Report', 'Outstanding Report', 'Technician Report', 'Division Report', 'Region Report', 'Total calls by Region Report');
        $this->divisions = array('Water', 'Electrical', 'Both');
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index() {
        redirect($this->redirect_key . 'dashboard');
    }

    /* Only Views Start */

    function viewMap() {
        $table = 'schedules';

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $where = array('scheduled_type' => 'Water', 'to_date>=' => $date);
        $res = $this->user_model->getData($table, $where);
        $data['water_schedules'] = $res->result();

        $where = array('scheduled_type' => 'Electricity', 'to_date>=' => $date);
        $res1 = $this->user_model->getData($table, $where);
        $data['electricity_schedules'] = $res1->result();


        $this->global['pageTitle'] = 'View Water & Epectricity Scheduled';
        $data['redirect_key'] = $this->redirect_key;
        $data['time'] = $time;
        $data['date'] = $date;

        $this->loadViews("viewMap", $this->global, $data, NULL);
    }

    function breakDown($id = 0) {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');



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


            $this->global['pageTitle'] = 'BDMS : Breakdown Manager';

            if ($id > 0) {
                $data['breakdown_data'] = $this->user_model->getBreakdowns($id)->row();
                $data['edit_breakdown'] = 1;
                $data['edit_form'] = $id;
                $this->global['dashboardTitle'] = 'Edit Breakdown';
            } else {
                $this->global['dashboardTitle'] = 'Add Breakdown';
            }
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("manageBreakdown", $this->global, $data, NULL);
        }
    }

    function dashboard() {
        $data['redirect_key'] = $this->redirect_key;
        
        $data['total_breakdowns']['On Hold'] = $this->db->query("SELECT COUNT(id) as total_on_hold FROM ims_breakdowns WHERE status='On Hold'")->row()->total_on_hold;
        $data['total_breakdowns']['On-Going'] = $this->db->query("SELECT COUNT(id) as total_on_going FROM ims_breakdowns WHERE status='On-Going'")->row()->total_on_going;
        $data['total_breakdowns']['Completed'] = $this->db->query("SELECT COUNT(id) as total_completed FROM ims_breakdowns WHERE status='Completed'")->row()->total_completed;
        $data['total_breakdowns']['Reported'] = $this->db->query("SELECT COUNT(id) as total_reported FROM ims_breakdowns WHERE status='Reported'")->row()->total_reported;
       
        $data['months'] = $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $data['status_names'] = array(
            array(
                'name'=>'On Hold',
                'color_code' => '#f39c12'
                ),
            array(
                'name'=>'On-Going',
                'color_code' => '#00c0ef'
                ),
            array(
                'name'=>'Completed',
                'color_code' => '#00a65a'
                ),
            array(
                'name'=>'Reported',
                'color_code' => '#dd4b39'
                )
        );
       // print_r($data['status_names']);
      //  die();
      $total_on_hold = 0;
      $total_on_going = 0;
      $total_completed = 0;
      $total_reported = 0;
       
        foreach($months as $month){
        $data['total_breakdowns'][$month]['On Hold'] = $this->db->query("SELECT COUNT(id) as total_on_hold FROM ims_breakdowns WHERE status='On Hold' and  createdDtm BETWEEN '".date('Y-m-01', strtotime(date('Y').'-'.$month.'-'.'01'))."' AND '".date('Y-m-t', strtotime(date('Y').'-'.$month.'-'.'01'))."'")->row()->total_on_hold;
        $total_on_hold = $total_on_hold + $data['total_breakdowns'][$month]['On Hold'];
        $data['total_breakdowns'][$month]['On-Going'] = $this->db->query("SELECT COUNT(id) as total_on_going FROM ims_breakdowns WHERE status='On-Going' and createdDtm BETWEEN '".date('Y-m-01', strtotime(date('Y').'-'.$month.'-'.'01'))."' AND '".date('Y-m-t', strtotime(date('Y').'-'.$month.'-'.'01'))."'")->row()->total_on_going;
        $total_on_going =  $total_on_going + $data['total_breakdowns'][$month]['On-Going'];
        $data['total_breakdowns'][$month]['Completed'] = $this->db->query("SELECT COUNT(id) as total_completed FROM ims_breakdowns WHERE status='Completed' and createdDtm BETWEEN '".date('Y-m-01', strtotime(date('Y').'-'.$month.'-'.'01'))."' AND '".date('Y-m-t', strtotime(date('Y').'-'.$month.'-'.'01'))."'")->row()->total_completed;
        $total_completed = $total_completed +  $data['total_breakdowns'][$month]['Completed'];
        $data['total_breakdowns'][$month]['Reported'] = $this->db->query("SELECT COUNT(id) as total_reported FROM ims_breakdowns WHERE status='Reported' and createdDtm BETWEEN '".date('Y-m-01', strtotime(date('Y').'-'.$month.'-'.'01'))."' AND '".date('Y-m-t', strtotime(date('Y').'-'.$month.'-'.'01'))."'")->row()->total_reported;
        $total_reported =  $total_reported + $data['total_breakdowns'][$month]['Reported'];
    }
    $data['total_reported_breakdowns'] = $total_reported;
    $data['total_on_going_breakdowns'] = $total_on_going;
    $data['total_on_hold_breakdowns'] = $total_on_hold;
    $data['total_completed_breakdowns'] = $total_completed;

        //echo '<pre>';
       // print_r($data['total_breakdowns']);
      //  print_r($data['statuses']);
      ///  die();
        $this->loadViews("dashboard", $this->global, $data, NULL);
    }
        function breakdowns() {
            if (0) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');

            $regions = array();
            $reasons = array();
            $technicians = array();
            $street_names = array();


            // get all regions
            $table = 'ims_region';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $regions[$ro->id] = $ro->region;
                }
            }

            //get reasons
            $table = 'ims_reason';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $reasons[$ro->id] = $ro->reason;
                }
            }

            //get technicians
            $table = 'ims_technicians';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $technicians[$ro->id] = $ro->name;
                }
            }

            // get $street_names
            $table = 'ims_street_name';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $street_names[$ro->id] = $ro->street_name;
                }
            }

            $data['technicians'] = $technicians;
            $data['reasons'] = $reasons;
            $data['regions'] = $regions;
            $data['street_names'] = $street_names;

           // $data['breakdowns'] = $this->user_model->getBreakdowns();
            $this->global['pageTitle'] = 'BDMS :  Add/Upate/Delete Breakdowns';
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("breakdowns", $this->global, $data, NULL);
        }
    }

    function reports() {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');

            $regions = array();
            $reasons = array();
            $technicians = array();
            $street_names = array();
            $users = array();
            $dispatchers = array();
            $problems = array();


            // get all regions
            $table = 'ims_region';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $regions[$ro->id] = $ro->region;
                }
            }

			// get all regions
            $table = 'ims_reason';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $reasons[$ro->id] = $ro->reason;
                }
            }
			
            $table = 'tbl_users';
            // role type = 4 dispatcher
            $where1 = array('roleId' => 4);
            $res = $this->user_model->getData($table, $where1);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $dispatchers[$ro->userId] = $ro->name;
                }
            }



            //get problems
            $table = 'ims_breakdowns';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $problems[] = $ro->problem;
                }
            }
            $problems = array_unique($problems);

            //get technicians
            $table = 'ims_technicians';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $technicians[$ro->id] = $ro->name;
                }
            }

            // get $street_names
            $table = 'ims_street_name';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $street_names[$ro->id] = $ro->street_name;
                }
            }

            $table = 'tbl_users';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $users[$ro->userId] = $ro->name;
                }
            }

            $data['dispatchers'] = $dispatchers;
            $data['problems'] = $problems;
            $data['technicians'] = $technicians;
            $data['reasons'] = $reasons;
            $data['regions'] = $regions;
            $data['street_names'] = $street_names;
            $data['users'] = $users;


            $data['report_types'] = $this->report_types;
            $data['divisions'] = $this->divisions;

            $data['report_dispatcher'] = $report_dispatcher = $this->input->post('report_dispatcher');
            $data['report_problem'] = $report_problem = $this->input->post('report_problem');

            $report_type = $this->input->post('report_type');
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');

            $report_technician = $this->input->post('report_technician');
            $report_division = $this->input->post('report_division');
            $report_region = $this->input->post('report_region');


            if ($report_problem != '') {
                $where['problem'] = $report_problem;
            }
            if ($report_dispatcher > 0) {
                $where['createdBy'] = $report_dispatcher;
            }
            if ($report_type == '') {
                $report_type = 'Daily breakdown Report';
            }
            if ($from_date == '') {
                $from_date = date('Y-m-d');
            }
            if ($to_date == '') {
                $to_date = date('Y-m-d');
            }
            if ($report_type == 'Daily breakdown Report') {
                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
                $this->global['pageTitle'] = 'Daily breakdown Report : ' . $from_date . ' - ' . $to_date;
            }
            if ($report_type == 'Completed Jobs Report') {
                $where['status'] = 'Completed';
                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
                $this->global['pageTitle'] = 'Completed Jobs Report : ' . $from_date . ' - ' . $to_date;
            }
            if ($report_type == 'Outstanding Report') {
                $where['status !='] = 'Completed';
                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
                $this->global['pageTitle'] = 'Outstanding Report : ' . $from_date . ' - ' . $to_date;
            }
            if ($report_type == 'Technician Report') {
                $where['technician_id'] = $report_technician;
                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
                $this->global['pageTitle'] = 'Technician Report : ' . $from_date . ' - ' . $to_date;
            }
            if ($report_type == 'Division Report') {
                $where['division'] = $report_division;
                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
                $this->global['pageTitle'] = 'Division Report : ' . $from_date . ' - ' . $to_date;
            }
            if ($report_type == 'Region Report') {
                $where['region_id'] = $report_region;
                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
                $this->global['pageTitle'] = 'Region Report : ' . $from_date . ' - ' . $to_date;
            }
            if ($report_type == 'Total calls by Region Report') {
                $calls_by_region = array();
                foreach ($regions as $k => $region) {
                    foreach ($this->divisions as $divis) {
                        $res = $this->user_model->getRegionWiseBreakDowns($k, $divis)->row();
                        if (!empty($res)) {
                            $calls_by_region[$k][$divis] = $res->total;
                        }
                    }
                }
                $data['calls_by_region'] = $calls_by_region;
                $this->global['pageTitle'] = 'Total calls by Region Report : ' . $from_date . ' - ' . $to_date;
            }

            $data['report_type'] = $report_type;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['report_technician'] = $report_technician;
            $data['report_division'] = $report_division;
            $data['report_region'] = $report_region;

            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("reports", $this->global, $data, NULL);
        }
    }
    function technician_reports() {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');

            $regions = array();
            $reasons = array();
            $technicians = array();
            $street_names = array();
            $users = array();
            $dispatchers = array();
            $problems = array();


            // get all regions
            $table = 'ims_region';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $regions[$ro->id] = $ro->region;
                }
            }

            // get all regions
            $table = 'ims_reason';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $reasons[$ro->id] = $ro->reason;
                }
            }

            $table = 'tbl_users';
            // role type = 4 dispatcher
            $where1 = array('roleId' => 4);
            $res = $this->user_model->getData($table, $where1);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $dispatchers[$ro->userId] = $ro->name;
                }
            }



            //get problems
            $table = 'ims_breakdowns';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $problems[] = $ro->problem;
                }
            }
            $problems = array_unique($problems);

            //get technicians
            $table = 'ims_technicians';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $technicians[$ro->id] = $ro->name;
                }
            }

            // get $street_names
            $table = 'ims_street_name';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $street_names[$ro->id] = $ro->street_name;
                }
            }

            $table = 'tbl_users';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $users[$ro->userId] = $ro->name;
                }
            }

            $data['dispatchers'] = $dispatchers;
            $data['problems'] = $problems;
            $data['technicians'] = $technicians;
            $data['reasons'] = $reasons;
            $data['regions'] = $regions;
            $data['street_names'] = $street_names;
            $data['users'] = $users;


            $data['report_types'] = $this->report_types;
            $data['divisions'] = $this->divisions;

            $data['report_dispatcher'] = $report_dispatcher = $this->input->post('report_dispatcher');
            $data['report_problem'] = $report_problem = $this->input->post('report_problem');

            $report_type = $this->input->post('report_type');
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');

            $report_technician = $this->input->post('report_technician');
//            $report_division = $this->input->post('report_division');
//            $report_region = $this->input->post('report_region');


//            if ($report_problem != '') {
//                $where['problem'] = $report_problem;
//            }
//            if ($report_dispatcher > 0) {
//                $where['createdBy'] = $report_dispatcher;
//            }
//            if ($report_type == '') {
//                $report_type = 'Daily breakdown Report';
//            }
            if ($from_date == '') {
                $from_date = date('Y-m-d');
            }
            if ($to_date == '') {
                $to_date = date('Y-m-d');
            }
//            if ($report_type == 'Daily breakdown Report') {
//                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
//                $this->global['pageTitle'] = 'Daily breakdown Report : ' . $from_date . ' - ' . $to_date;
//            }
//            if ($report_type == 'Completed Jobs Report') {
//                $where['status'] = 'Completed';
//                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
//                $this->global['pageTitle'] = 'Completed Jobs Report : ' . $from_date . ' - ' . $to_date;
//            }
//            if ($report_type == 'Outstanding Report') {
//                $where['status !='] = 'Completed';
//                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
//                $this->global['pageTitle'] = 'Outstanding Report : ' . $from_date . ' - ' . $to_date;
//            }
          //  if ($report_type == 'Technician Report') {
                $where['technician_id'] = $report_technician;
                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
                $this->global['pageTitle'] = 'Technician Report : ' . $from_date . ' - ' . $to_date;
           // }
//            if ($report_type == 'Division Report') {
//                $where['division'] = $report_division;
//                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
//                $this->global['pageTitle'] = 'Division Report : ' . $from_date . ' - ' . $to_date;
//            }
//            if ($report_type == 'Region Report') {
//                $where['region_id'] = $report_region;
//                $data['reportData'] = $this->user_model->getBreakDownsRange($from_date, $to_date, $where);
//                $this->global['pageTitle'] = 'Region Report : ' . $from_date . ' - ' . $to_date;
//            }
//            if ($report_type == 'Total calls by Region Report') {
//                $calls_by_region = array();
//                foreach ($regions as $k => $region) {
//                    foreach ($this->divisions as $divis) {
//                        $res = $this->user_model->getRegionWiseBreakDowns($k, $divis)->row();
//                        if (!empty($res)) {
//                            $calls_by_region[$k][$divis] = $res->total;
//                        }
//                    }
//                }
//                $data['calls_by_region'] = $calls_by_region;
//                $this->global['pageTitle'] = 'Total calls by Region Report : ' . $from_date . ' - ' . $to_date;
//            }

            $data['report_type'] = $report_type;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['report_technician'] = $report_technician;
//            $data['report_division'] = $report_division;
//            $data['report_region'] = $report_region;

            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("technician_reports", $this->global, $data, NULL);
        }
    }
    function settings() {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');
            $this->global['pageTitle'] = 'BDMS :  Settings';
            $data['street_names'] = $this->user_model->getStreetNames();
            $data['regions'] = $this->user_model->getData('ims_region');
            $data['reasons'] = $this->user_model->getData('ims_reason');
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("settings", $this->global, $data, NULL);
        }
    }

    function userListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->user_model->userListingCount($searchText);

            $returns = $this->paginationCompress("userListing/", $count, 10);

            $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);


            $this->global['pageTitle'] = 'BDMS : User Listing';
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("users", $this->global, $data, NULL);
        }
    }

    function empListing() {
        if (0) {
            $this->loadThis();
        } else {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->user_model->empListingCount($searchText);

            $returns = $this->paginationCompress("empListing/", $count, 10);

            $data['empRecords'] = $this->user_model->empListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'BDMS : Employee Listing';
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("emps", $this->global, $data, NULL);
        }
    }

    function addNew() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            $this->global['pageTitle'] = 'BDMS : Add New User';
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }

    function addNewEmp() {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');
            // $data['roles'] = $this->user_model->getUserRoles();

            $this->global['pageTitle'] = 'BDMS : Add New Employee';
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("addNewEmp", $this->global, $data, NULL);
        }
    }

    function editOld($userId = NULL) {
        if ($this->isAdmin() == TRUE || $userId == 1) {
            $this->loadThis();
        } else {
            if ($userId == null) {
                redirect($this->redirect_key . 'userListing');
            }

            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);

            $this->global['pageTitle'] = 'BDMS : Edit User';
            $data['redirect_key'] = $this->redirect_key;
            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }

    function editOldEmployee($userId = NULL) {
        // if ($this->isAdmin() == TRUE || $userId == 1) {
        // if userd id is 1 i.e. going to edit admin details which is not allowed
        if (0) {
            $this->loadThis();
        } else {
            if ($userId == null) {
                redirect($this->redirect_key . 'empListing');
            }

//            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getEmployeeInfo($userId);
            $data['redirect_key'] = $this->redirect_key;
            $this->global['pageTitle'] = 'BDMS : Edit Employee';

            $this->loadViews("editEmployee", $this->global, $data, NULL);
        }
    }

    function pageNotFound() {
        $this->global['pageTitle'] = 'BDMS : 404 - Page Not Found';
        $data['redirect_key'] = $this->redirect_key;
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function profile($active = "details") {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;
        $data['redirect_key'] = $this->redirect_key;

        $this->global['pageTitle'] = $active == "details" ? 'BDMS : My Profile' : 'BDMS : Change Password';
        $this->loadViews("profile", $this->global, $data, NULL);
    }

    function loginHistory($userId = NULL) {
        if (0) {
            $this->loadThis();
        } else {
            $userId = ($userId == NULL ? 0 : $userId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;

            $this->load->library('pagination');

            // $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);
            // $returns = $this->paginationCompress("loginHistoy/" . $userId . "/", $count, 10, 3);

            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate);
            // die($this->db->last_query());
            $this->global['pageTitle'] = 'BDMS : User Login History';
            $data['redirect_key'] = $this->redirect_key;

            $this->loadViews("loginHistory", $this->global, $data, NULL);
        }
    }

    function technicians() {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');
            $data['redirect_key'] = $this->redirect_key;
            $this->global['pageTitle'] = 'Technicians || IMS';
            $data['technicians'] = $this->user_model->getTechnicians()->result();
            $this->loadViews("technicians", $this->global, $data, NULL);
        }
    }

    /* Only Views End */


    /* Ajax Calls start */

    function getRegionDp() {
        $this->db->select('*');
        $this->db->from('ims_region');
        $result = $this->db->get()->result();
        $region_dp = '<option>--Select Region--</option>';
        if (!empty($result)) {
            foreach ($result as $row) {
                $region_dp .= "<option value=" . $row->id . ">" . $row->region . "</option>";
            }
        }
        echo json_encode(array('region_dp' => $region_dp));
        exit();
    }

    function getReportsDropDown() {
        $key = $this->input->post('key');

        $drop_down = '';
        if ($key == 'Technician Report') {
            $drop_down .= '<select class="form-control" name="report_technician">';
            $table = 'ims_technicians';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $drop_down .= "<option value=" . $ro->id . ">" . $ro->name . "</option>";
                }
            }
            $drop_down .= '</option>';
        } else if ($key == 'Division Report') {
            $drop_down .= '<select class="form-control" name="report_division">';
            foreach ($this->divisions as $ro) {
                $drop_down .= "<option value=" . $ro . ">" . $ro . "</option>";
            }
        } else if ($key == 'Region Report') {
            $drop_down .= '<select class="form-control" name="report_region">';
            $table = 'ims_region';
            $res = $this->user_model->getData($table);
            if (!empty($res) && $res->num_rows() > 0) {
                foreach ($res->result() as $ro) {
                    $drop_down .= "<option value=" . $ro->id . ">" . $ro->region . "</option>";
                }
            }
        }
        echo json_encode(array('drop_down' => $drop_down));
        exit();
    }

    function getStreetNameDetails() {
        $street_name_id = $this->input->post('street_name_id');
        $this->db->select('*');
        $this->db->from('ims_street_name');
        $this->db->where('id', $street_name_id);
        $query = $this->db->get()->row();
        echo json_encode(array('id' => $street_name_id, 'street_name' => $query->street_name, 'region_id' => $query->region_id));
        exit();
    }

    function getRegionDetails() {
        $region_id = $this->input->post('region_id');
        $this->db->select('*');
        $this->db->from('ims_region');
        $this->db->where('id', $region_id);
        $query = $this->db->get()->row();
        echo json_encode(array('id' => $region_id, 'region' => $query->region));
        exit();
    }

    function getReasonDetails() {
        $reason_id = $this->input->post('reason_id');
        $this->db->select('*');
        $this->db->from('ims_reason');
        $this->db->where('id', $reason_id);
        $query = $this->db->get()->row();
        echo json_encode(array('id' => $reason_id, 'region' => $query->reason));
        exit();
    }

    function deleteRow() {
        if (0) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $del_id = $this->input->post('del_id');
            $del_tbl = $this->input->post('del_tbl');

            $result = $this->user_model->deleteRow($del_id, $del_tbl);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    function deleteUser() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

            $result = $this->user_model->deleteUser($userId, $userInfo);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    function checkEmailExists() {

        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if (empty($result)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
        exit();
    }

    function getTechnicianDetails() {
        $tech_id = $this->input->post('tech_id');
        $this->db->select('*');
        $this->db->from('ims_technicians');
        $this->db->where('id', $tech_id);
        $query = $this->db->get()->row();
        echo json_encode(array('id' => $tech_id, 'name' => $query->name, 'ability' => $query->ability));
        exit();
    }

    /* Ajax Calls end */

    /* form submit start */

    function doAddRegion() {
        if (0) {
            $this->loadThis();
        } else {
            $insertInfo['region'] = $this->input->post('region');
            $insertInfo['createdDtm'] = date('Y-m-d H:i:s');
            $result = $this->user_model->addNewRegion($insertInfo);
            if ($result > 0) {
                $this->session->set_flashdata('success', 'New Region created successfully');
            } else {
                $this->session->set_flashdata('error', 'Region creation failed');
            }
            redirect($this->redirect_key . 'settings');
        }
    }

    function doAddReason() {
        if (0) {
            $this->loadThis();
        } else {
            $insertInfo['reason'] = $this->input->post('reason');
            $insertInfo['createdDtm'] = date('Y-m-d H:i:s');
            $result = $this->user_model->addNewReason($insertInfo);
            if ($result > 0) {
                $this->session->set_flashdata('success', 'Reason created successfully');
            } else {
                $this->session->set_flashdata('error', 'Reason creation failed');
            }
            redirect($this->redirect_key . 'settings');
        }
    }

    function doAddStreetName() {
        if (0) {
            $this->loadThis();
        } else {
            $insertInfo['street_name'] = $this->input->post('street_name');
            $insertInfo['region_id'] = $this->input->post('region');
            $insertInfo['createdDtm'] = date('Y-m-d H:i:s');
            $result = $this->user_model->addNewStreetName($insertInfo);
            if ($result > 0) {
                $this->session->set_flashdata('success', 'StreetName created successfully');
            } else {
                $this->session->set_flashdata('error', 'StreetName creation failed');
            }
            redirect($this->redirect_key . 'settings');
        }
    }

    function doUpdateRegion() {
        if (0) {
            $this->loadThis();
        } else {
            $updateInfo['region'] = $this->input->post('region');
            $whereInfo['id'] = $this->input->post('region_id');
            $table = 'ims_region';
            $result = $this->user_model->updateTable($table, $updateInfo, $whereInfo);
            if ($result > 0) {
                $this->session->set_flashdata('success', 'Region updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Region updation failed');
            }
            redirect($this->redirect_key . 'settings');
        }
    }

    function doUpdateReason() {
        if (0) {
            $this->loadThis();
        } else {
            $updateInfo['reason'] = $this->input->post('reason');
            $whereInfo['id'] = $this->input->post('reason_id');
            $table = 'ims_reason';
            $result = $this->user_model->updateTable($table, $updateInfo, $whereInfo);
            if ($result > 0) {
                $this->session->set_flashdata('success', 'Reason updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Reason updation failed');
            }
            redirect($this->redirect_key . 'settings');
        }
    }

    function doUpdateStreetName() {
        if (0) {
            $this->loadThis();
        } else {
            $whereInfo['id'] = $this->input->post('street_name_id');
            $updateInfo['street_name'] = $this->input->post('street_name');
            $updateInfo['region_id'] = $this->input->post('region');
            $table = 'ims_street_name';
            $result = $this->user_model->updateTable($table, $updateInfo, $whereInfo);
            if ($result > 0) {
                $this->session->set_flashdata('success', 'StreetName updated successfully');
            } else {
                $this->session->set_flashdata('error', 'StreetName updation failed');
            }
            redirect($this->redirect_key . 'settings');
        }
    }

    function addNewUser() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

            if ($this->form_validation->run() == FALSE) {
                $this->addNew();
            } else {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));

                $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleId' => $roleId, 'name' => $name,
                    'mobile' => $mobile, 'createdBy' => $this->vendorId, 'createdDtm' => date('Y-m-d H:i:s'));

                $this->load->model('user_model');
                $result = $this->user_model->addNewUser($userInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'New User created successfully');
                } else {
                    $this->session->set_flashdata('error', 'User creation failed');
                }

                redirect($this->redirect_key . 'addNew');
            }
        }
    }

    function addNewEmployee() {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

            if ($this->form_validation->run() == FALSE) {
                $this->addNewEmp();
            } else {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $emp_id = $this->input->post('emp_id');

                $userInfo = array('email' => $email, 'name' => $name, 'emp_id' => $emp_id, 'mobile' => $mobile, 'createdBy' => $this->vendorId, 'createdDtm' => date('Y-m-d H:i:s'));

                $this->load->model('user_model');
                $result = $this->user_model->addNewEmployee($userInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'New Employee created successfully');
                } else {
                    $this->session->set_flashdata('error', 'Employee creation failed');
                }

                redirect($this->redirect_key . 'addNewEmp');
            }
        }
    }

    function editUser() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userId');

            $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|max_length[20]');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

            if ($this->form_validation->run() == FALSE) {
                $this->editOld($userId);
            } else {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));

                $userInfo = array();

                if (empty($password)) {
                    $userInfo = array('email' => $email, 'roleId' => $roleId, 'name' => $name,
                        'mobile' => $mobile, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));
                } else {
                    $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleId' => $roleId,
                        'name' => ucwords($name), 'mobile' => $mobile, 'updatedBy' => $this->vendorId,
                        'updatedDtm' => date('Y-m-d H:i:s'));
                }

                $result = $this->user_model->editUser($userInfo, $userId);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'User updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'User updation failed');
                }

                redirect($this->redirect_key . 'userListing');
            }
        }
    }

    function editEmployee() {
        if (0) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userId');

            $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

            if ($this->form_validation->run() == FALSE) {
                $this->editOld($userId);
            } else {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $emp_id = $this->input->post('emp_id');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));

                $userInfo = array();


                $userInfo = array('email' => $email, 'name' => $name, 'emp_id' => $emp_id,
                    'mobile' => $mobile, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));


                $result = $this->user_model->editEmployee($userInfo, $userId);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'Employee updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Employee updation failed');
                }

                redirect($this->redirect_key . 'empListing');
            }
        }
    }

    function doAddAbsenceType() {
        $absence_type = strtoupper($this->input->post('absence_type'));
        $color = $this->input->post('color');
        $type_desc = $this->input->post('type_desc');

        $typeInfo = array('color' => $color, 'type' => $absence_type, 'type_desc' => $type_desc, 'createdDtm' => date('Y-m-d H:i:s'));

        $this->load->model('user_model');
        $result = $this->user_model->addAbsenceType($typeInfo);
        redirect($this->redirect_key . 'absenceTypes');
    }

    function profileUpdate($active = "details") {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->profile($active);
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));

            $userInfo = array('name' => $name, 'mobile' => $mobile, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

            $result = $this->user_model->editUser($userInfo, $this->vendorId);

            if ($result == true) {
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'Profile updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Profile updation failed');
            }
            redirect($this->redirect_key . 'profile/' . $active);
        }
    }

    function changePassword($active = "changepass") {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('oldPassword', 'Old password', 'required|max_length[20]');
        $this->form_validation->set_rules('newPassword', 'New password', 'required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword', 'Confirm new password', 'required|matches[newPassword]|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $this->profile($active);
        } else {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');

            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);

            if (empty($resultPas)) {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect($this->redirect_key . 'profile/' . $active);
            } else {
                $usersData = array('password' => getHashedPassword($newPassword), 'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s'));

                $result = $this->user_model->changePassword($this->vendorId, $usersData);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Password updation successful');
                } else {
                    $this->session->set_flashdata('error', 'Password updation failed');
                }

                redirect($this->redirect_key . 'profile/' . $active);
            }
        }
    }

    function doAddTechnician() {
        $name = $this->input->post('name');
        $ability = $this->input->post('ability');
        $typeInfo = array('name' => $name, 'ability' => $ability, 'createdDtm' => date('Y-m-d H:i:s'));
        $this->load->model('user_model');
        $this->user_model->addTechnician($typeInfo);
        redirect($this->redirect_key . 'technicians');
    }

    function doAddBreakDown() {
//        $insertInfo['call_id'] = $this->input->post('call_id');
        $insertInfo['division'] = $this->input->post('division');
        $insertInfo['connection_type'] = $this->input->post('connection_type');
        $insertInfo['caller_name'] = $data1['name'] = $this->input->post('caller_name');
        $insertInfo['street_id'] = $this->input->post('street_id');
        $insertInfo['region_id'] = $this->input->post('region_id');
        $insertInfo['status'] = $this->input->post('status');
        $insertInfo['email_address'] = $data1['email'] = $this->input->post('email_address');

        $insertInfo['house_number'] = $this->input->post('house_number');
        $insertInfo['technician_id'] = $this->input->post('technician_id');
        $insertInfo['reason_id'] = $this->input->post('reason_id');
        $insertInfo['direction_note'] = $this->input->post('direction_note');
        $insertInfo['meter_no'] = $this->input->post('meter_no');
        $insertInfo['cellular'] = $this->input->post('cellular');
//        $insertInfo['telephone'] = $this->input->post('telephone');
        $insertInfo['createdBy'] = $this->session->userdata('userId');

        $insertInfo['reported_date_to_technician'] = date('Y-m-d', strtotime($this->input->post('reported_date_to_technician')));
        $insertInfo['reported_time_to_technician'] = date('H:i:s', strtotime($this->input->post('reported_time_to_technician')));

        $jb_dt = trim($this->input->post('job_completed_date'));
        if ($jb_dt != '' && $jb_dt > '0000-00-00') {
            $insertInfo['job_completed_date'] = date('Y-m-d', strtotime($jb_dt));
        }
        $jb_time = trim($this->input->post('job_completed_time'));
        if ($jb_time != '' && $jb_time > '00:00') {
            $insertInfo['job_completed_time'] = date('H:i:s', strtotime($jb_time));
        }
//        $insertInfo['rec_time'] = date('H:i:s', strtotime($this->input->post('rec_time')));
//        $insertInfo['breakdown_date'] = $this->input->post('breakdown_date');
        $insertInfo['problem'] = $this->input->post('problem');

        $table = 'ims_breakdowns';

        $edit_form = $this->input->post('edit_form');
        $sendStatus2 = '';
        if ($edit_form > 0) {
            $result = $this->user_model->updateData($table, $edit_form, $insertInfo);
            if ($result == true) {
                //check if job is completed then send completion email
                //  $sendStatus = resetPasswordEmail($data1);
                if ((isset($data1['email']) && $data1['email'] != '') || $insertInfo['status'] == 'Completed') {
                    if (isset($jb_dt) && $jb_dt > '0000-00-00') {
                        // job completed
                        $subject = "Problem Resolved Confirmation";
                        $template = 'problemResolved';
                        $sendStatus2 = sendEmail($data1, $subject, $template);
                        if ($sendStatus2) {
                            $sendStatus2 = '<br>Problem Registered Confirmation Mail Sent!';
                        } else {
                            $sendStatus2 = '<br>Problem Registered Confirmation Mail Failed to Send!';
                        }
                    }
                }

                $this->session->set_flashdata('success', 'Break Down updated successfully ' . $sendStatus2);
            } else {
                $this->session->set_flashdata('error', 'Break Down updation failed');
            }
        } else {
            $insertInfo['createdDtm'] = date('Y-m-d H:i:s');
            $result = $this->user_model->addData($table, $insertInfo);
            if ($result == true) {
                //check if caller has email id then send him email confirmation for problem registered
                if (isset($data1['email']) && $data1['email'] != '') {
                    //check if problem registered mail already sent or not
                    $subject = "Problem Registered Confirmation";
                    $template = 'problemRegistered';
                    //  $sendStatus1 = resetPasswordEmail($data1);
                    $sendStatus1 = sendEmail($data1, $subject, $template);
                    if ($sendStatus1) {
                        $sendStatus1 = '<br>Problem Registered Confirmation Mail Sent!';
                    } else {
                        $sendStatus1 = '<br>Problem Registered Confirmation Mail Failed to Send!';
                    }
                }
                $this->session->set_flashdata('success', 'Break Down created successfully ' . $sendStatus1 . ' ' . $sendStatus2);
            } else {
                $this->session->set_flashdata('error', 'Break Down craetion failed');
            }
        }





        redirect($this->redirect_key . 'dashboard');
    }

    function doUpdateTechnician() {
        $name = $this->input->post('name');
        $ability = $this->input->post('ability');
        $tech_id = $this->input->post('tech_id');
        $typeInfo = array('name' => $name, 'ability' => $ability);
        $where = array('id' => $tech_id);
        $this->load->model('user_model');
        $this->user_model->updateTechnician($typeInfo, $where);
        redirect($this->redirect_key . 'technicians');
    }


    public function getBreakdownRecordsForDataTable() {
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $searchValue = $_POST['search']['value']; // Search value




        $totalRecords = $this->db->get('ims_breakdowns')->num_rows();
        if ($totalRecords> 0) {
         for ($i=0;$i<2;$i++) {
             $this->db->select('ims_breakdowns.*, ims_street_name.street_name, ims_technicians.name as technician_name, ims_region.region as region_name, ims_reason.reason as reason_name');
             $this->db->from('ims_breakdowns');
             $this->db->join('ims_street_name', 'ims_street_name.id = ims_breakdowns.street_id');
             $this->db->join('ims_technicians', 'ims_technicians.id = ims_breakdowns.technician_id');
             $this->db->join('ims_region', 'ims_region.id = ims_breakdowns.region_id');
             $this->db->join('ims_reason', 'ims_reason.id = ims_breakdowns.reason_id');
             if (trim($searchValue) !='') {
                 $this->db->like('caller_name', $searchValue);
                 $this->db->or_like('direction_note', $searchValue);
                 $this->db->or_like('status', $searchValue);
                 $this->db->or_like('connection_type', $searchValue);
                 $this->db->or_like('division', $searchValue);
                 $this->db->or_like('ims_breakdowns.createdDtm', $searchValue);
                 $this->db->or_like('ims_street_name.street_name', $searchValue);
                 $this->db->or_like('ims_region.region', $searchValue);
                 $this->db->or_like('ims_reason.reason', $searchValue);
                 $this->db->or_like('ims_technicians.name', $searchValue);
             }
             if($i==0){
                $this->db->order_by($columnName, $columnSortOrder);
                $data = $this->db->get()->result_array();
                $totalRecordwithFilter = count($data);
             }else{
                 $this->db->limit($rowperpage, $row);
                 $this->db->order_by($columnName, $columnSortOrder);
                 $data = $this->db->get()->result_array();
                 
             }
             
         }
        
        } else {
            $totalRecords = 0;
            $totalRecordwithFilter = 0;
            $draw = 0;
            $data = NULL;
        }



        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" =>$totalRecords,
            "iTotalDisplayRecords" =>  $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);
    }



    /* form submit end */
}
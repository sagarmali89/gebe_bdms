<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {

    // start added by sm
    /**
     * This function is used to add new tree to system
     * @return number $insert_id : This is last inserted id
     */
    function getBreakdowns($id = 0) {
        $this->db->select('*');
        $this->db->from('ims_breakdowns');
        if ($id > 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    function getRegionWiseBreakDowns($reg_id, $division) {
//        $q = "SELECT ims_region.region, count(ims_breakdowns.id) from ims_breakdowns, ims_region WHERE ims_breakdowns.region_id = ims_region.id GROUP BY ims_region.region;";
        $this->db->select('count(ims_breakdowns.id) as total');
        $this->db->from('ims_breakdowns');
        $this->db->where(array('ims_breakdowns.division' => $division,'ims_breakdowns.region_id'=>$reg_id));
        $res = $this->db->get();
        return $res;
    }

    function getTechnicians($id = 0) {
        $this->db->select('*');
        $this->db->from('ims_technicians');
        if ($id > 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    function getRegions($id = 0) {
        $this->db->select('*');
        $this->db->from('ims_region');
        if ($id > 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    function getBreakDownsRange($from, $to, $where = '') {
        $this->db->select('*');
        $this->db->from('ims_breakdowns');
        if ($from == $to) {
            $this->db->where('createdDtm', $from);
        } else {
            $this->db->where('createdDtm >=', $from);
            $this->db->where('createdDtm <=', $to);
        }
        if ($where != '') {
            $this->db->where($where);
        }
		
		//die();
        $query = $this->db->get();
		//echo $this->db->last_query();
        return $query;
    }

    function getUserIds() {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
//        $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    function getEmployeeIds() {
        $this->db->select('*');
        $this->db->from('vac_emp');
        $query = $this->db->get();
        return $query->result();
    }

    // end added by sm
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '') {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();

        return $query->num_rows();
    }

    function empListingCount($searchText = '') {
        $this->db->select('*');
        $this->db->from('vac_emp');
        if (!empty($searchText)) {
            $likeCriteria = "(email  LIKE '%" . $searchText . "%'
                            OR  name  LIKE '%" . $searchText . "%'
                            OR  emp_id  LIKE '%" . $searchText . "%'
                            OR  mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userListing($searchText = '', $page, $segment) {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function empListing($searchText = '') {
        $this->db->select('*');
        $this->db->from('vac_emp');
        if (!empty($searchText)) {
            $likeCriteria = "(email  LIKE '%" . $searchText . "%'
                            OR  name  LIKE '%" . $searchText . "%'
                            OR  emp_id  LIKE '%" . $searchText . "%'
                            OR  mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles() {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0) {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);
        $this->db->where("isDeleted", 0);
        if ($userId != 0) {
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo) {
        $this->db->trans_start();
        $this->db->insert('tbl_users', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function addNewEmployee($userInfo) {
        $this->db->trans_start();
        $this->db->insert('vac_emp', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId) {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        return $query->row();
    }

    function getEmployeeInfo($userId) {
        $this->db->select('*');
        $this->db->from('vac_emp');
        $this->db->where('id', $userId);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId) {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        return TRUE;
    }

    function editEmployee($userInfo, $userId) {
        $this->db->where('id', $userId);
        $this->db->update('vac_emp', $userInfo);
        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo) {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }

    function deleteRow($del_id, $del_tbl) {
        $this->db->where('id', $del_id);
        $this->db->delete($del_tbl);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword) {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');

        $user = $query->result();

        if (!empty($user)) {
            if (verifyHashedPassword($oldPassword, $user[0]->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo) {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);
        return $this->db->affected_rows();
    }

    function addAbsenceType($typeInfo) {
        $this->db->trans_start();
        $this->db->insert('vac_absence_types', $typeInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    function updateAbsenceType($typeInfo, $where) {
        $this->db->where($where);
        $this->db->update('vac_absence_types', $typeInfo);
        //  echo $this->db->last_query(); die();
        return $this->db->affected_rows();
    }

    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     */
    function loginHistoryCount($userId, $searchText, $fromDate, $toDate) {
        $this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        if (!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '" . date('Y-m-d', strtotime($fromDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if (!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '" . date('Y-m-d', strtotime($toDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if ($userId >= 1) {
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->from('tbl_last_login as BaseTbl');
        $query = $this->db->get();

        return $query->num_rows();
    }

    function loginHistory($userId, $searchText, $fromDate, $toDate) {
        $this->db->select('userId, sessionData, machineIp, userAgent, agentString, platform, createdDtm');
        $this->db->from('tbl_last_login');
        if (!empty($searchText)) {
            $likeCriteria = "(sessionData  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        if (!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(createdDtm, '%Y-%m-%d' ) >= '" . date('Y-m-d', strtotime($fromDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if (!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(createdDtm, '%Y-%m-%d' ) <= '" . date('Y-m-d', strtotime($toDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if ($userId >= 1) {
            $this->db->where('userId', $userId);
        }
        $this->db->order_by('id', 'DESC');
        // $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getUserInfoById($userId) {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();

        return $query->row();
    }

    function getEmployeeInfoId($userId) {
        $this->db->select('*');
        $this->db->from('vac_emp');
        $this->db->where('id', $userId);
        $query = $this->db->get();

        return $query->row();
    }

    function getUserInfoWithRole($userId) {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.roleId, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles', 'Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();

        return $query->row();
    }

    function addTechnician($typeInfo) {
        $this->db->trans_start();
        $this->db->insert('ims_technicians', $typeInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    function updateTechnician($typeInfo, $where) {
        $this->db->where($where);
        $this->db->update('ims_technicians', $typeInfo);
        return $this->db->affected_rows();
    }

    function addNewRegion($newInfo) {
        $this->db->trans_start();
        $this->db->insert('ims_region', $newInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function addNewStreetName($newInfo) {
        $this->db->trans_start();
        $this->db->insert('ims_street_name', $newInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function addNewReason($newInfo) {
        $this->db->trans_start();
        $this->db->insert('ims_reason', $newInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function getData($table, $where = '') {
        $this->db->select('*');
        if (isset($where) && $where != '') {
            $this->db->where($where);
        }
        $this->db->from($table);
        $query = $this->db->get();
        return $query;
    }

    function addData($table, $insertInfo) {
        $this->db->trans_start();
        $this->db->insert($table, $insertInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function updateData($table, $id, $updateInfo) {
        $this->db->where('id', $id);
        $this->db->update($table, $updateInfo);
        return $this->db->affected_rows();
    }

    function getStreetNames() {
        $this->db->select('add.*,reg.region');
        $this->db->from('ims_street_name as add');
        $this->db->join('ims_region as reg', 'add.region_id = reg.id', 'left');
        $result = $this->db->get();
        return $result;
    }

    function updateTable($table, $updateInfo, $whereInfo) {
        $this->db->where($whereInfo);
        $this->db->update($table, $updateInfo);
        return $this->db->affected_rows();
    }

}

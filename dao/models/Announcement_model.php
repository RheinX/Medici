<?php
/**
 * Created by PhpStorm.
 * User: cq
 * Date: 2017/5/6
 * Time: 13:45
 */

class Announcement_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function Read_Specific_All($data){

        $aid = $data['Id'];

        $sql = "select * from TB_Announcement where Id = ?";
        $query = $this->db->query($sql,$aid);

        $res = $query->row_array();

        $nu = $this->db->affected_rows();   //影响多少条记录
        if($nu == 0)
            $res['Id'] = -1;
        return $res;
    }

}

?>
<?php

/**
 * Created by PhpStorm.
 * User: 徐炜杰
 * Date: 2017/5/6
 * Time: 11:41
 */
class Book_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function Get_Book_Info($data){

        $uw = $data['Id'];

        $sql = "select * from TB_Book where Id = ?";
        $query = $this->db->query($sql,$uw);

        return $query->row_array();
    }
}
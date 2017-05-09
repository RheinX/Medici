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

    public function Get_Hottest_Book($num){

        //依次获取最热图书
        $num=intval($num);
        $sql = "select * from TB_Book order by Heat DESC limit ?";
        $query1 = $this->db->query($sql,array($num));

        return $query1->result_array();
    }

    public function Search_Book($data){

        //按照热度依次获取可能搜索图书
        $skey = $data['key'];
        $svalue = $data['value'];

        $this->db->select('*');
        $this->db->like($skey,$svalue,'both');
        $this->db->order_by('id','DESC');

        $query = $this->db->get('TB_Book');

        return $query->result_array();

    }
}
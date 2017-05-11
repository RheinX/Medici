<?php
/**
 * Created by PhpStorm.
 * User: cq
 * Date: 2017/5/10
 * Time: 16:40
 */

class Author_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function Get_Author_Information($data){

        $aid = $data['Id'];

        $sql = "select * from TB_Author where Id = ?";

        $query1 = $this->db->query($sql,array($aid));

        $res = $query1->result_array();

        if(empty($res))
            return null;

        return $res;
    }

    public function Get_All_Books($data){

        //获取作者所有图书编号
        $aid = $data['Id'];
        $sql = "select BookId from TB_BookAuthor where AuthorId = ?";

        $query1 = $this->db->query($sql,array($aid));
        $aset = array();
        $m = 0;

        foreach($query1->result_array() as $row){
            $aset[$m] = $row['BookId'];
            $m++;
        }

        if(empty($aset))
            return null;

        //获取所有图书信息
        $sql_sec = "select * from TB_Book where Id in ?";

        $query = $this->db->query($sql_sec,array($aset));

        return $query->result_array();

    }

    public function Search_Author($data){

        $ak = $data['KeyWord'];

        $this->db->select('*');
        $this->db->like('Name',$ak,'both');

        $query = $this->db->get('TB_Author');

        return $query->result_array();
    }
}
?>
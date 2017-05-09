<?php
/**
 * Created by PhpStorm.
 * User: cq
 * Date: 2017/5/5
 * Time: 16:50
 */

class Label_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function Get_Hot_Label($num){
        $queue = 0;
        $m = 0;
        $s = array();
        while(1) {
            //依次获取最热图书
            $sql = "select Id,Heat from TB_Book order by Heat DESC limit ?,1";
            $query1 = $this->db->query($sql,array($queue));

            $row = $query1->row_array();

            if (!isset($row)) {
                break;
            }

            //获取最热图书标签id
            $temp = $row['Id'];
            $sql_second = "select LabelId from TB_BookLabel where BookId = ?";
            $query2 = $this->db->query($sql_second, array($temp));

            foreach ($query2->result_array() as $r) {
                $s[$m] = $r['LabelId'];
                $m++;
            }

            if($num <= $m-1)
                break;
            $queue++;
        }
        //获取标签名字
        $query_third = "select Id,Name from TB_BranchLabel where Id in ?";
        $query3 = $this->db->query($query_third,array($s));

        $data = $query3->result_array();

        return $data;
    }

    public function Get_Bigger_Label($data){

        $lid = $data['Id'];

        if($lid == -1){
            $sql1 = "select * from TB_MastLabel";
            $query1 = $this->db->query($sql1);

            return $query1->result_array();
        }

        $sql2 = "select * from TB_MastLabel where Id = $lid";
        $query2 = $this->db->query($sql2);

        return $query2->result_array();
    }

    public function Get_Smaller_Label($data){

        $lid = $data['Id'];

        if($lid == -1){
            $sql1 = "select * from TB_BranchLabel";
            $query1 = $this->db->query($sql1);

            return $query1->result_array();
        }

        $sql2 = "select Id,Name from TB_BranchLabel where MasterId = $lid";
        $query2 = $this->db->query($sql2);

        return $query2->result_array();
    }

    public function Get_Label_Of_Book($data){
        $lid = $data['Id'];
        $sql1 = "select BookId from TB_BookLabel where LabelId = $lid";

        $query1 = $this->db->query($sql1);

        //获取书籍信息
        $num = 0;
        $set = array();
        foreach($query1->result_array() as $row){
            $set[$num] = $row['BookId'];
            $num++;
        }

        $sql2 = "select * from TB_Book where Id in ?";
        $query2 = $this->db->query($sql2,array($set));

        if(empty($query2))
            return null;
        return $query2->result_array();
    }


}
?>
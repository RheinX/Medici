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

    public function Get_Author_Of_Book($data){

        //获取所有写过此书的作者编号
        $bid = $data['Id'];
        $sql = "select AuthorId from TB_BookAuthor where BookId = ?";

        $query1 = $this->db->query($sql,array($bid));
        $aset = array();
        $m = 0;

        foreach($query1->result_array() as $row){
            $aset[$m] = $row['AuthorId'];
            $m++;
        }

        if(empty($aset))
            return null;

        //获取所有图书信息
        $sql_sec = "select * from TB_Author where Id in ?";

        $query = $this->db->query($sql_sec,array($aset));

        return $query->result_array();
    }

    public function Get_Borrow_Times($data){

        $bid = $data['Id'];
        $sql = "select BookId,count(Id) times from TB_History group by BookId HAVING BookId = ?";
        $query = $this->db->query($sql,array($bid));

        $res = $query->row_array();
        if(empty($res))
            return '0';
        $times = $res['times'];

        return $times;
    }

    public function Get_Comments($data){

        $num = $data['num'];
        $bid = $data['Id'];

        $num = intval($num);

        if($num == -1){
            $sql = "select * from TB_Comments where BookId = ?";
            $query = $this->db->query($sql,array($bid));

            return $query->result_array();
        }

        $sql_sec = "select * from TB_Comments where BookId = ? limit ?";
        $query1 = $this->db->query($sql_sec,array($bid,$num));

        return $query1->result_array();
    }

    public function Get_Number_Comment_Book($data){

        $bid  = $data['Id'];

        $sql = "select BookId,count(Id) times from TB_Comments group by BookId HAVING BookId = ?";
        $query = $this->db->query($sql,array($bid));

        $res = $query->row_array();
        if(empty($res))
            return '0';
        $number = $res['times'];

        return $number;
    }

    public function Get_Labels($data){

        $bid  = $data['Id'];

        $sql = "select LabelId from TB_BookLabel where BookId = ?";
        $query = $this->db->query($sql,array($bid));

        $aset = array();
        $m = 0;
        foreach($query->result_array() as $row){
            $aset[$m] = $row['LabelId'];
            $m++;
        }
        if(empty($aset))
            return null;

        //获取所有标签信息
        $sql_sec = "select * from TB_BranchLabel where Id in ?";

        $query = $this->db->query($sql_sec,array($aset));

        return $query->result_array();
    }

    public function Mark_Book($data){

        $bid = $data['BookId'];
        $score = $data['score'];
        $score = intval($score);

        $sql = "select Evaluation,Critics from TB_Book where Id = ?";
        $query = $this->db->query($sql,array($bid));

        $row = $query->row_array();
        if(empty($row))
            return false;

        $eva = intval($row['Evaluation']);
        $score = $score+$eva;
        $cri = intval($row['Critics']);
        $critics = $cri+1;

        $mod = array('Evaluation'=> $score,
                      'Critics'=>$critics);

        $this->db->where('Id',$bid);
        $this->db->update('TB_Book',$mod);

        //判断是否更新成功
        $nu = $this->db->affected_rows();  //影响多少条记录

        if($nu == 0)
            return false;
        else
            return true;
    }
}
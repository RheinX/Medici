<?php
/**
 * Created by PhpStorm.
 * User: cq
 * Date: 2017/5/5
 * Time: 9:39
 */

class User_model extends CI_Model{

    public function  __construct(){

        parent::__construct();
        $this->load->database();
    }

    public function Is_New_User($data){

        $existUser = true;
         $w = $data['WechatId'];
        $sql = "select Id from TB_User where Wechat = $w";

        $result = $this->db->query($sql);

        $nu = $this->db->affected_rows();  //影响多少条记录

        if($nu<>0)
            $existUser = false;

        return $existUser;
    }

    public function Register_WechatId($data){

        $w = $data['WechatId'];
        $parament = array('Wechat'=> $w);

        $success = $this->db->insert('TB_User',$parament);

        if($success == 0)
            return false;

        return true;

    }

    public function Fill_Telephone($data){

        $wid = $data['WechatId'];
        $tel = $data['tel'];

        $cur = array('Tel'=> $tel);

        $this->db->where('Wechat',$wid);
        $this->db->update('tb_user',$cur);


        //check if the operation is successful
        $this->db->where('Wechat',$wid);
        $this->db->select('Tel');
        $query=$this->db->get('tb_user')->result_array();
        $query=$query[0];

        $result['result']=true;
        if($query['Tel']!=$tel){
            $result['result']=false;
            $result['ErrorMessage']="Update Fail!";
        }
        return $result;
    }

    public function Write_Label($data,$WechatId){

        $num = count($data);
        for($i=0;$i<$num;$i++){
            $parament = array('UserId'=>$WechatId,
                                'LabelId'=>$data[$i]);
            $success = $this->db->insert('TB_UserLabel',$parament);

            if($success == 0)
                return false;
        }

        return true;
    }

    public function Is_Tele_Exist($data){

        $sql = "select Wechat from TB_User where Tel = ?";
        $t = array($data['tel']);

        $query = $this->db->query($sql,$t);

        $nu = $this->db->affected_rows();  //影响多少条记录

        if($nu == 0)
            return false;
        else
            return true;
    }

    public function Get_User_Info($data){

        $d = $data['WechatId'];

        $sql = "select Id from TB_User where Wechat = ?";
        $query1 = $this->db->query($sql,$d);

        $uid = $query1->row_array();
        $sql_second = "select * from TB_UserIfo where Id = ?";

        $query2 = $this->db->query($sql_second,$uid['Id']);

        $ret = $query2->row_array();

        return $ret;
    }

    public function Get_User_Borrow($data){

        $uw = $data['WechatId'];

        $sql = "select Id from TB_User where Wechat = ?";
        $query1 = $this->db->query($sql,$uw);

        $uid = $query1->row_array();

        $sql_second = "select * from TB_PreBorrow where userid = ?";

        $query2 = $this->db->query($sql_second,$uid['Id']);

        return $query2->result_array();
    }

    public function Get_User_Order($data){


        $uw = $data['WechatId'];

        $sql = "select Id from TB_User where Wechat = ?";
        $query1 = $this->db->query($sql,$uw);

        $uid = $query1->row_array();

        $sql_second = "select * from TB_Order where userid = ?";

        $query2 = $this->db->query($sql_second,$uid['Id']);

        return $query2->result_array();

    }

    public function Fill_Information($data){

        //根据微信号获得用户编号

        $sql = "select Id from TB_User where Wechat = ?";

        $query1 = $this->db->query($sql,array($data['WechatId']));

        $result = $query1->row_array();

        if(!Isset($result))
            return false;

        $uid = $result['Id'];

        //输入将要插入的参数

        $parament = array('Id'=>$uid,
                           'Name'=>$data['Name'],
                           'ImgUrl'=>$data['ImgUrl'],
                           'Address'=>$data['Address'],
                           'Gender'=>$data['Gender'],
                           'Birthday'=>$data['Birthday']);

        //插入数据

        $ss = $this->db->insert('TB_UserIfo',$parament);

        //判断是否插入成功
        $nu = $this->db->affected_rows();  //影响多少条记录

        if($nu == 0)
            return false;
        else
            return true;

    }

    public function Get_User_Label($data){

        //根据微信号获得用户编号

        $sql = "select Id from TB_User where Wechat = ?";

        $query1 = $this->db->query($sql,array($data['WechatId']));

        $result = $query1->row_array();

        if(empty($result))
            return null;

        $uid = $result['Id'];

        //根据ID获取用户感兴趣的标签
        $sql2 = "select LabelId from TB_UserLabel where UserId = $uid";
        $query2 = $this->db->query($sql2);

        $num = 0;
        $set = array();
        foreach($query2->result_array() as $row){
            $set[$num] = $row['LabelId'];
            $num++;
        }

        $sql3 = "select Id,Name from TB_BranchLabel where Id in ?";
        $query3 = $this->db->query($sql3,array($set));

        return $query3->result_array();

    }

    public function Get_Search_History($data){

        //根据微信号获得用户编号

        $sql = "select Id from TB_User where Wechat = ?";

        $query1 = $this->db->query($sql,array($data['WechatId']));

        $result = $query1->row_array();

        if(empty($result))
            return null;

        $uid = $result['Id'];

        //获取搜索编号
        $sql2 = "select SearchId from TB_UserSearch where UserId = $uid";

        $query1 = $this->db->query($sql2);

        $num = 0;
        $set = array();
        foreach($query1->result_array() as $row){
            $set[$num] = $row['SearchId'];
            $num++;
        }

        $sql3 = "select Keyword from TB_SearchRecord where Id in ?";
        $query3 = $this->db->query($sql3,array($set));

        return $query3->result_array();

    }



}
?>
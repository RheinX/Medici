<?php
/**
 * Created by PhpStorm.
 * User: 徐炜杰
 * Date: 2017/5/5
 * Time: 11:43
 *
 */
class User_Controller extends CI_Controller  {
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->model('Book_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
    }

    //judge if a user is new
    //{
    //"result": true
    //}
    public function User_Login(){
        $data['WechatId']=$this->input->post('WechatId');
        
        $IsNewUser=$this->User_model->Is_New_User($data);

        $result['result']=$IsNewUser;
        echo json_encode($result);
    }

    //register a user
    public function User_Register(){
        //register
        $data['WechatId']=$this->input->post('WechatId');
        $data['Name']=$this->input->post('Name');
        $data['ImgUrl']=$this->input->post('ImgUrl');
        $data['Address']=$this->input->post('Address');

        $result['result']=$this->User_model->Register_WechatId($data);
        if(!$result['result']){
            $result['ErrorMessage']='Register Fail!';
            echo json_encode($result);
            return;
        }

        //fill the information
        $result['result']=$this->User_model->Fill_Information($data);
        if(!$result['result']){
            $result['ErrorMessage']='Fill Information Fail!';
        }
        echo json_encode($result);
    }

    //user fill in his telephone number
    //we ignore the CAPTCHA
    public function Fill_Phone(){
        $data['WechatId']=$this->input->post('WechatId');
        $data['tel']=$this->input->post('Telephone');
        $data['CAPTCHA']=$this->input->post('CAPTCHA');


        //judge the user is new
        $IsNewUser=$this->User_model->Is_New_User($data);
        if($IsNewUser){
            $result['result']=false;
            $result['ErrorMessage']="User Not Existed!";
            echo json_encode($result);
            return;
        }
        //handle the telephone number
        //is telephone number valid
        if(!preg_match("/^1[34578]{1}\d{9}$/",$data['tel'])){
            $result['result']=false;
            $result['ErrorMessage']="Invalid Telephone Number";
            echo json_encode($result);
            return;
        }

        //is telephone has been registered
        if($this->User_model->Is_Tele_Exist($data)){
            $result['result']=false;
            $result['ErrorMessage']="The Telephone Number has been used!";
            echo json_encode($result);
            return;
        }

        $result=$this->User_model->Fill_Telephone($data);
        echo json_encode($result);
    }

    //bind the label with user
    public function Bind_Label_With_User(){

    }

    // get the information of user
    public function Get_User_Info(){
        $data['WechatId']=$this->input->post('WechatId');

        $user_info=$this->User_model->Get_User_Info($data);

        echo json_encode($user_info);
    }

    //get the information of borrow
    public function Get_User_Borrow(){
        $data['WechatId']=$this->input->post('WechatId');

        $result=$this->User_model->Get_User_Borrow($data);

        //add some extra information
        foreach ($result as $key=>$value){
            //push the information of book
            $para['Id']=$value['BookId'];
            $book_info=$this->Book_model->Get_Book_Info($para);
            $result[$key]['book']=$book_info;

            //calculate the time you need
            //get the offset of two days
            $del=strtotime($value['Deadline'])-strtotime(explode(" ",$value['Time'])[0]);
            $result[$key]['RemaindTime']=ceil($del/3600/24);  //translate into value of day
        }

        echo json_encode($result);
    }

    //get the information of order
    public function Get_User_Order(){
        $data['WechatId']=$this->input->post('WechatId');

        $result=$this->User_model->Get_User_Order($data);

        //add some extra information
        foreach ($result as $key=>$value){
            //push the information of book
            $para['Id']=$value['BookId'];
            $book_info=$this->Book_model->Get_Book_Info($para);
            $result[$key]['book']=$book_info;

            //calculate the time you need
            //get the offset of two days
            $del=strtotime($value['Deadline'])-strtotime(explode(" ",$value['Time'])[0]);
            $result[$key]['RemaindTime']=ceil($del/3600/24);  //translate into value of day
        }

        echo json_encode($result);
    }
}




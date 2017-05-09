<?php

/**
 * Created by PhpStorm.
 * User: 徐炜杰
 * Date: 2017/5/6
 * Time: 11:25
 */
class Announ_Controller extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Announcement_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
    }

    //get the announcement of homepage
    public function Get_Index_Anno(){
        $data['WechatId']=$this->input->post('WechatId');

        $result=$this->Announcement_model->Read_Simply();

        echo json_encode($result);
    }

    //get a specify announcement
    public function Get_Annou($id){
        $para['Id']=$id;
        $data=$this->Announcement_model->Read_Specific_All($para);

        if($data['Id']==-1){
            $data['ErrorMessage']="The Announcement NOT exist!";
        }


        echo json_encode($data);
    }
}
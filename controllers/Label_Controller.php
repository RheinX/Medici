<?php

/**
 * Created by PhpStorm.
 * User: 徐炜杰
 * Date: 2017/5/5
 * Time: 22:12
 */
class Label_Controller extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Label_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
    }

    //get the $num hottest labels
    public function Get_Recommend_Label($num){

        $data=$this->Label_model->Get_Hot_Label($num);

        echo json_encode($data);
    }

}
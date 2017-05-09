<?php

/**
 * Created by PhpStorm.
 * User: 徐炜杰
 * Date: 2017/5/8
 * Time: 13:20
 */
class Book_Controller extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Book_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
    }

    public function Get_Hottest_Book($num){
        $book=$this->Book_model->Get_Hottest_Book($num);

        echo json_encode($book);
    }

    public function Search_Book(){
        $data['value']=$this->input->post('KeyWord');
        $data['WechatId']=$this->input->post('WechatId');

        //record the searching key word
        $this->User_model->Record_Search($data);

        $result=array();

        //title
        $data['key']="Title";
        $books=$this->Book_model->Search_Book($data);
        foreach ($books as $key=>$value)
            array_push($result,$value);


        echo json_encode($books);
    }

}
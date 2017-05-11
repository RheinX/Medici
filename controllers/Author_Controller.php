<?php

/**
 * Created by PhpStorm.
 * User: 徐炜杰
 * Date: 2017/5/10
 * Time: 15:05
 */
class Author_Controller extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Book_model');
        $this->load->model('Author_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
    }

    public function Get_Author_Information($id){
        $data['Id']=$id;

        $author=$this->Author_model->Get_Author_Information($data);

        echo json_encode($author);
    }

    public function Get_All_Books($id){
        $data['Id']=$id;

        $books=$this->Author_model->Get_All_Books($data);

        echo json_encode($books);
    }

}
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
        $this->load->model('User_model');
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

    //get the detail information of one book
    public function Get_Book_Information($BookId){
        //get the information about book
        $data['Id']=$BookId;
        $books=$this->Book_model->Get_Book_Info($data);
        //$books=$books[0];

        //get the times of borrow
        $books['BorrowTimes']=$this->Book_model->Get_Borrow_Times($data);

        //get the author of books
        $books['author']=$this->Book_model->Get_Author_Of_Book($data);

        //get the label of books
        $books['label']=$this->Book_model->Get_Labels($data);

        echo json_encode($books);
    }

    //get all the comments of one book
    public function Get_All_Comments($id){
        $data['Id']=$id;
        $data['num']=-1;

        $comments=$this->Book_model->Get_Comments($data);
        foreach ($comments as $key=>$value){
            //get the user information
            $useId['Id']=$value['UserId'];
            $useId['WechatId']=$this->User_model->Get_User_WechatId($useId);
            $user=$this->User_model->Get_User_Info($useId);
            $comments[$key]['UserName']=$user['Name'];
            $comments[$key]['ImgUrl']=$user['ImgUrl'];
        }

        echo json_encode($comments);
    }

    public function Get_Comments($id,$num){
        $data['Id']=$id;
        $data['num']=$num;
        $comments=$this->Book_model->Get_Comments($data);
        foreach ($comments as $key=>$value){
            //get the user information
            $useId['Id']=$value['UserId'];
            $useId['WechatId']=$this->User_model->Get_User_WechatId($useId);
            $user=$this->User_model->Get_User_Info($useId);
            $comments[$key]['UserName']=$user['Name'];
            $comments[$key]['ImgUrl']=$user['ImgUrl'];
        }

        echo json_encode($comments);
    }

}
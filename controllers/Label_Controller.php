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
        $this->load->model('Book_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
    }

    //get the $num hottest labels
    public function Get_Recommend_Label($num){
        $data=$this->Label_model->Get_Hot_Label($num);

        echo json_encode($data);
    }

    //get all the big label and the small label in it
    public function Get_All_Bigger_Label(){
        $data1['Id']=-1;

        //$result=$this->Label_model->Get_Smaller_Label(1);
        $big_label=$this->Label_model->Get_Bigger_Label($data1);
        foreach ($big_label as $key=>$value){
            $big_label[$key]['SmallLabel']=$this->Label_model->Get_Smaller_Label($value);
            //$value['SmallLabel']=1;
            //array_push($result,$big_label);
        }

        echo json_encode($big_label);
    }

    //get all the small label in one big label
    public function Get_Bigger_Label_Child($Id){
        $data1['Id']=$Id;

        //$result=$this->Label_model->Get_Smaller_Label(1);
        $big_label=$this->Label_model->Get_Bigger_Label($data1);
        $big_label[0]['SmallLabel']=$this->Label_model->Get_Smaller_Label($big_label[0]);

        echo json_encode($big_label);
    }

    //get all the books under one small label
    public function Get_Books_In_Label($Id){
        $data['Id']=$Id;

        $books=$this->Label_model->Get_Label_Of_Book($data);

        //get the numbe of  comments of book
        foreach ($books as $key=>$value){
            //number of comments
            $comment_number=$this->Book_model->Get_Number_Comment_Book($value);
            $books[$key]['numberComment']=$comment_number;

            //author
            $author=$this->Book_model->Get_Author_Of_Book($value);
            //$books[$key]['author']=$author;
            $books[$key]['author']=[];
            foreach ($author as $k=>$v){
                array_push($books[$key]['author'],$v['Name']);
                //$books[$key]['author']=$v['Name'];
            }

        }

        echo json_encode($books);
    }
}
<?php
include 'commonAPI/database.php';

class passbyMessage{
    public $pmid="";
    public $email="";
    public $message="";
    function __construct($pmid, $email, $message){
        $this->pmid = $pmid;
        $this->email = $email;
        $this->message = $message;
    }
}

function leave_message($email, $content){
    /*数据库的连接可以写一起*/
    $con = get_connect();

    $sql = 'insert into passby_message(email, message) values("'.$email.'","'.$content.'")';
    $result = mysqli_query($con, $sql);
    if($result){
        return true;
    }else{
        return false;
    }
}

function get_messages_by_email($email){
    $con = get_connect();

    $sql = 'select * from passby_message where email="'.$email.'"';
    $messages = array();
    if($result = mysqli_query($con, $sql)){
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $oneMessage = new passbyMessage($row['pmid'], $row['email'], $row['message']);
            $messages[$i] = $oneMessage;
            $i=$i+1;
        }
    }
    return $messages;
}

function get_all_messages(){
    $con = get_connect();

    $sql = 'select * from passby_message';
    $messages = array();
    if($result = mysqli_query($con, $sql)){
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $oneMessage = new passbyMessage($row['pmid'], $row['email'], $row['message']);
            $messages[$i] = $oneMessage;
            $i=$i+1;
        }
    }
    return $messages;
}

function delete_message_by_pmid($pmid){
    $con = get_connect();

    $sql = 'select * from passby_message where pmid='.$pmid;
    $messages = array();
    if($result = mysqli_query($con, $sql)){
        $i=0;
        if($row = mysqli_fetch_assoc($result)){
            $oneMessage = new passbyMessage($row['pmid'], $row['email'], $row['message']);
            $messages[$i] = $oneMessage;
            $i=$i+1;
        }
    }
    return $messages;
}


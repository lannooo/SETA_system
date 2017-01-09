<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$sid = $_SESSION['userID'];
$hid = $_GET['hid'];
//$sid = 10001;
//$hid = 1;

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection');
}
$mysqli->query('set names utf8');

$sql = "select * from homework where hid = $hid";

$result = $mysqli->query($sql);
if($result == false)
    die("fail_select_error");

$row = $result->fetch_array();

$hw = new student_homework();
$hw->type=$row[2];$hw->name=$row[3];
$hw->end_t=$row[4];$hw->hard_ddl=$row[5];$hw->begin_t=$row[6];
$hw->punish_weight=$row[7];$hw->score_face=$row[8];$hw->score_weight=$row[9];
$hw->url=$row[11];

$sql_result = "select * from hw_result where hid = $hid and sid = $sid";
$hwresult_result = $mysqli->query($sql_result);
if(0 == mysqli_num_rows($hwresult_result)){
    $hw->result = null;
}
else {
    $row_result = $hwresult_result->fetch_array();
    $hw_result = new student_homework_result();
    $hw_result->rid=$row_result[0];$hw_result->hid=$row_result[1];$hw_result->sid=$row_result[2];
    $hw_result->type=$row_result[3];$hw_result->ifcorrected=$row_result[5];$hw_result->score=$row_result[6];
    $hw_result->comment=$row_result[7];$hw_result->uploadtime=$row_result[4];
    $answer_url = $row_result[8];//$hw_result->url=$row_result[7];
    $hw->result = $hw_result;
}

if($hw->type == 'G'){
    $sql_group = "select * from study_group inner join attend on study_group.teamleader_id = attend.sid and study_group.clid = attend.clid where sid = $sid";
    $sql_group_result = $mysqli->query($sql_group);
    if(1 != mysqli_num_rows($sql_group_result)){
        $hw->ismember = 1;
    }
}

$doc = new DOMDocument();
if($doc->load("../upload/hw_t/".$row[11].".XML") == false){
    die('fail_no_file');
}

echo json_encode($hw);
echo "&&&";

if($hw->type == 'F' || $hw->type == 'G') {
    $question = $doc->getElementsByTagName("question");
    $question_offline = new homework_question();
    $question_offline->type = $hw->type;
    $question_offline->qid = $question->item(0)->getAttribute("qid");
    $question_offline->score = $question->item(0)->getAttribute("score");
    $question_offline->name = $question->item(0)->getElementsByTagName("name")->item(0)->nodeValue;
    $question_offline->describe = $question->item(0)->getElementsByTagName("describe")->item(0)->nodeValue;

    echo json_encode($question_offline);
}
else { 
    if($hw->result != null && $answer_url != null){
        $doc_answer = new DOMDocument();
        if($doc_answer->load("../upload/hw_stu/".$answer_url) == false){
            die('fail_no_file');
        }
        $answer_list = $doc_answer->getElementsByTagName("answer");
        $count = 0;
    }

    $question_list = $doc->getElementsByTagName("question");

    foreach($question_list as $question) {
        $type = $question->getAttribute("type");
        if ($type == "0") {
            $question_output = new homework_choice();
            $question_output->type = $type;
            $question_output->qid = $question->getAttribute("qid");
            $question_output->score = $question->getAttribute("score");
            $question_output->name = $question->getElementsByTagName("name")->item(0)->nodeValue;
            $question_output->describe = $question->getElementsByTagName("describe")->item(0)->nodeValue;
            $question_output->choice_A = $question->getElementsByTagName("choice")->item(0)->nodeValue;
            $question_output->choice_B = $question->getElementsByTagName("choice")->item(1)->nodeValue;
            $question_output->choice_C = $question->getElementsByTagName("choice")->item(2)->nodeValue;
            $question_output->choice_D = $question->getElementsByTagName("choice")->item(3)->nodeValue;
            if($hw->result != null && $answer_url != null){
                $question_output->answer = $answer_list->item($count)->getElementsByTagName("content")->item(0)->nodeValue;
                if($hw_result->ifcorrected == 1){
                    $question_output->ans_score = $answer_list->item($count)->getElementsByTagName("score")->item(0)->nodeValue;
                }
                $count++;
            }
            $question_arr[] = $question_output;
        } else {
            $question_output = new homework_question();
            $question_output->type = $type;
            $question_output->qid = $question->getAttribute("qid");
            $question_output->score = $question->getAttribute("score");
            $question_output->name = $question->getElementsByTagName("name")->item(0)->nodeValue;
            $question_output->describe = $question->getElementsByTagName("describe")->item(0)->nodeValue;

            if($hw->result != null && $answer_url != null){
                $question_output->answer = $answer_list->item($count)->getElementsByTagName("content")->item(0)->nodeValue;
                if($hw_result->ifcorrected == 1){
                    $question_output->ans_score = $answer_list->item($count)->getElementsByTagName("score")->item(0)->nodeValue;
                }
                $count++;
            }
            $question_arr[] = $question_output;
        }
    }
    echo json_encode($question_arr);
}
?>
<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$sid = $_SESSION['userID'];
$hid = $_POST['hid'];
$answer_arr = $_POST['answer'];
$answer_List = json_encode($answer_arr);

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection'). mysqli_connect_error();
}
$mysqli->query('set names utf8');

$sql = "select * from homework where hid = $hid";
$result = $mysqli->query($sql);
if($result == false)
    die("fail_select_error");
if(mysqli_num_rows($result) == 0)
    die("fail_no_homework");
$row = $result->fetch_array();
$hard_ddl=$row[5];

$curr_time=date("y-m-d h:i:s");
if(strtotime($curr_time)>=strtotime($hard_ddl)){
    die("fail_overtime");
}

$sql_select = "select * from hw_result where hid = $hid and sid = $sid";
$result_select = $mysqli->query($sql_select);
if($result_select == false)
    die("fail_select_error");
if(0 == mysqli_num_rows($result_select)){
    $flag = false;
    $answer_url = $sid."_".$hid.".XML";
}
else {
    $flag = true;
    $row_select = $result_select->fetch_array();
    if($row_select[4] == 1)
        die("fail_isCorrected");
    $answer_url = $row_select[8];
}

$doc = new DOMDocument('1.0', 'utf-8');
$doc->formatOutput = true;
$homework = $doc->createElement('homework');
$hid_att = $doc->createAttribute('hid');
$hid_num = $doc->createTextNode($hid);
$homework->appendChild($hid_att);

$count = count($answer_arr);

for($i = 0; $i < $count; $i++){
    $answer = $doc->createElement('answer');
    $qid = $doc->createAttribute('qid');
    $number = $doc->createTextNode($answer_arr[$i]['qid']);
    $qid->appendChild($number);
    $content = $doc->createElement('content', $answer_arr[$i]['answer']);
    $answer->appendChild($qid);
    $answer->appendChild($content);
    $homework->appendChild($answer);
}
$doc->appendChild($homework);

//echo json_encode($doc->saveXML());
$doc->save("../upload/hw_stu/".$answer_url);

if($flag == false) {
    $sql_insert = "insert into hw_result(hid, sid, type, uploadtime, url) values($hid, $sid, 'O', now(), '$answer_url')";
    $result_insert = $mysqli->query($sql_insert);
    if($result_insert == false)
        die("fail_insert_error");
}
else {
    $sql_update = "update hw_result set uploadtime =  now()";
    $result_update = $mysqli->query($sql_update);
    if($result_update == false)
        die("fail_update_error");
}

echo "success";
?>
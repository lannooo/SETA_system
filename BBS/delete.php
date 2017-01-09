<?php
session_start();

if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $identity = $_SESSION['identity'];
}else{
    echo "<script language='javascript' type='text/javascript'>alert('请先登录');";
    echo "window.location.href='../common/login.html'";
    echo "</script>";
}session_start();

if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $identity = $_SESSION['identity'];
}else{
    echo "<script language='javascript' type='text/javascript'>alert('请先登录');";
    echo "window.location.href='../common/login.html'";
    echo "</script>";
}
?>
<?php
require '../commonAPI/deleteAPI.php';
require "../commonAPI/database.php";

$con = get_connect();


//删除的是post帖子还是reply回复
$delete_type = $_GET['delType'];
//删除的是homework还是group
$entry_type = $_GET['entry'];

if($delete_type == 'post'){
    $post_id = $_GET['id'];
    if($entry_type=='group' || $entry_type=='homework'){
        if(delete_post($con, $entry_type, $post_id, $id, $identity)){
            echo "<script language='javascript' type='text/javascript'>alert('删除帖子成功');";
            echo "history.go(-1);";
            echo "</script>";
        }else{
            echo "<script language='javascript' type='text/javascript'>alert('删除帖子失败');";
            echo "history.go(-1);";
            echo "</script>";
        }
    }

}else if($delete_type == 'reply'){
    $floor_id = $_GET['id'];
    if($entry_type=='group' || $entry_type=='homework'){
        if(delete_reply($con, $entry_type, $floor_id, $id, $identity)){
            // echo 'success';
            echo "<script language='javascript' type='text/javascript'>alert('删除回复成功');";
            echo "history.go(-1);";
            echo "</script>";
        }else{
            // echo 'fail';
            echo "<script language='javascript' type='text/javascript'>alert('删除回复失败');";
            echo "history.go(-1);";
            echo "</script>";
        }
    }
}


?>

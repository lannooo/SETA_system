<?php
session_start();
?>

<?php
/*身份验证，没有则返回登陆界面*/
if(isset($_SESSION['id'])&&isset($_SESSION['name'])&&isset($_SESSION['identity'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $identity = $_SESSION['identity'];

}else{
    echo "<script language='javascript' type='text/javascript'>alert('请先登录');";
    echo "window.location.href='../common/login.html'";
    echo "</script>";
}

require "../commonAPI/database.php";

$con = get_connect();


// 获取post过来的文字内容
$reply_text = $_POST['reply_text'];
$reply_pid = $_POST['post_id'];
$reply_ptype = $_POST['post_type'];
// echo $_POST['post_id'];
// echo $_POST['reply_text'];
if($identity=='student'){
    $id_type='sid';
    $u_type = 'S';
}
else if($identity=='teacher'){
    $id_type = 'tid';
    $u_type = 'T';
}

if($reply_ptype=='group'){
    $table = 'group_post_floor';
    $table_update = 'group_post';
}
else if($reply_ptype=='homework'){
    $table = 'homework_post_floor';
    $table_update = 'homework_post';
}

$sql = 'insert into '.$table.'(post_id,utype,name,'.$id_type.',post_date,content,ref_fid) values('.$reply_pid.',"'.$u_type.'","'.$name.'",'.$id.',now(),"'.$reply_text.'",NULL)';

$result = mysqli_query($con, $sql);
if($result){
    $sql_update = 'update '.$table_update.' set hotness=hotness+1 where post_id='.$reply_pid;
    $result_update = mysqli_query($con, $sql_update);
    if($result_update){
        echo "<script language='javascript' type='text/javascript'>alert('回复成功~');";
        echo "history.go(-1);";
        echo "</script>";
    }else{
        echo "<script language='javascript' type='text/javascript'>alert('系统有一点小问题，但是回复成功了');";
        echo "history.go(-1);";
        echo "</script>";
    }
}
else{
    echo "<script language='javascript' type='text/javascript'>alert('回复失败！');";
    echo "history.go(-1);";
    echo "</script>";
}

?>

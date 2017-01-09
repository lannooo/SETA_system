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
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>软件工程课程论坛</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--添加 user-scalable=no 可以禁用其缩放（zooming）功能-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">

    <!-- 可选的Bootstrap主题文件（一般不使用） -->
    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap-theme.min.css"></script>
<!-- font-awesome-4.7.0 CDN 地址-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
    .mytable {
       table-layout: fixed;
    }

    .mytable tr td {
        text-overflow: ellipsis; /* for IE */
        -moz-text-overflow: ellipsis; /* for Firefox,mozilla */
        overflow: hidden;
        white-space: nowrap;
    }
</style>
</head>
<body>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                     <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                     <span class="sr-only">Toggle navigation</span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     </button>
                     <a class="navbar-brand" href="index.php">课程论坛</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="returnCenter.php">返回个人中心</a></li>
                        <li class="dropdown">
                             <a href="#" class="dropdown-toggle" data-toggle="dropdown">帖子管理<strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                                <li><a href="myposts.php">我的发表</a></li>
                                <li><a href="myreply.php">我的回复</a></li>
                                <!-- <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li> -->
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
<?php
require "../commonAPI/database.php";

$con = get_connect();

?>

<div class="container">

<?php
if($identity=='student'){
    $course_names = array();
    $group_names = array();
    $sql = 'select gname, gid, coname from study_group natural join class natural join course';
    if($result = mysqli_query($con, $sql)){
        while($row = mysqli_fetch_assoc($result)){
            $course_names[$row['gid']] = $row['coname'];
            $group_names[$row['gid']] = $row['gname'];
        }
    }
    $sql = 'select * from group_post where sid='.$id.' and frozon=0 order by post_date DESC';
    $post_date = 'null';
    $put_date = true;
    if($result = mysqli_query($con, $sql)){
        while($row = mysqli_fetch_assoc($result)){?>

        <?php
            $temp_date = substr($row['post_date'], 0, 10);
            if($post_date != $temp_date){
                $post_date = $temp_date;
                $put_date = true;
            }else{
                $put_date = false;
            }

            if($put_date){ ?>
                <div class="page-header col-md-12">
                    <h3><?php echo $post_date?></h3>
                </div>
            <?php }?>
             <div class="row clearfix">
                <div class="col-md-12 column">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                标题-<?php echo $row['title']?>
                                <a style="width: 50px" class="pull-right" <?php echo  'href="detail.php?type=group&post_id='.$row['post_id'].'"'?>>查看详情</a>
                                <a style="width: 50px" class="pull-right" <?php echo 'href="delete.php?delType=post&entry=group&id='.$row['post_id'].'"'?>>删除</a>
                            </h4>

                        </div>
                        <div class="panel-body">
                            内容：<?php echo $row['content']?>

                        </div>
                        <div class="panel-footer">
                            <span align="left" style="margin-right: 100px"><i class="fa fa-edit">所属课程/<?php echo $course_names[$row['gid']]?></i></span>
                            <span align="left" style="margin-right: 100px"><i class="fa fa-edit">所属队伍/<?php echo $group_names[$row['gid']]?></i></span>
                            <span align="left" style="margin-right: 100px"><i class="fa fa-clock-o">发表于<?php echo $row['post_date']?></i></span>
                            <span align="left" style="margin-right: 100px"><i class="fa fa-star">热度/<?php echo $row['hotness']?></i></span>
                        </div>
                    </div>
                </div>
            </div>

    <?php }
    }
}else if($identity=='teacher'){
    $course_names = array();
    $sql = 'select coid, coname from class natural join course where tid='.$id;
    if($result = mysqli_query($con, $sql)){
        while($row = mysqli_fetch_assoc($result)){
            $course_names[$row['coid']] = $row['coname'];
        }
    }
    $sql = 'select * from homework_post where tid='.$id.' and frozon=0 order by coid ASC, post_date DESC';
    $post_course = '-1';
    $put_course = true;
    if($result = mysqli_query($con, $sql)){
        while($row = mysqli_fetch_assoc($result)){ ?>

        <?php
            if($post_course!=$row['coid'])
            {
                $post_course = $row['coid'];
                $put_course = true;
            }
            else
            {
                $put_course = false;
            }
            if($put_course)
            {?>
                <div class="page-header col-md-12">
                    <h3><?php echo $course_names[$post_course]?></h3>
                </div>
            <?php }?>
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                标题-<?php echo $row['title']?>
                                <a style="width: 50px" class="pull-right" <?php echo 'href="delete.php?delType=post&entry=homework&id='.$row['post_id'].'"'?>>删除</a>
                                <a style="width: 100px" class="pull-right" <?php echo  'href="detail.php?type=homework&post_id='.$row['post_id'].'"'?>>查看详情</a>
                            </h4>

                        </div>
                        <div class="panel-body">
                            <?php echo $row['content']?>

                        </div>
                        <div class="panel-footer">
                            <span align="left" style="margin-right: 100px"><i class="fa fa-edit">所属课程/<?php echo $course_names[$row['coid']]?></i></span>
                            <span align="left" style="margin-right: 100px"><i class="fa fa-clock-o">发表于<?php echo $row['post_date']?></i></span>
                            <span align="left" style="margin-right: 100px"><i class="fa fa-star">热度/<?php echo $row['hotness']?></i></span>
                        </div>
                    </div>
                </div>
            </div>
<?php }
    }
}?>
</div>
<div class="footer">
    <div class="container">
        <div class="row footer-top">
            <div class="col-md-6">
                <h4>软件工程教学辅助系统</h4>
                <p>
                    开发完成于2016-12-31, 项目开源代码见<a href="https://github.com/lannooo/SETA_system">github</a>
                    <br/>
                    成员组成: 王俊皓/边炜康/卢旭辉/张苏/刘奇煚
                </p>
            </div>
            <div class="col-md-6">
                <div class="row about">
                    <div class="col-md-6">
                        <h4>课外学习</h4>
                        <ul class="list-unstyled">
                            <li><a href="http://www.softwareengineerinsider.com/">Software Engineer Insider</a></li>
                            <li><a href="http://www.tutorialspoint.com/cmmi/">SEI CMMI Tutorial</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4>友情链接</h4>
                        <ul class="list-unstyled">
                            <li><a href="http://10.202.78.13/">浙江大学现代教务管理系统</a></li>
                            <li><a href="http://cspo.zju.edu.cn/redir.php?catalog_id=2">计算机科学与技术学院办公网</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row footer-bottom">
            <ul class="list-inline text-center">
                <li>软件需求工程&软件工程管理[G01]</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>

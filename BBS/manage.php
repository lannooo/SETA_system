<?php

session_start();



if(isset($_SESSION['userType'])&&$_SESSION['userName']&&$_SESSION['userID']){

    $id = $_SESSION['userID'];

    $identity = $_SESSION['userType'];

}

else{

    echo "<script language='javascript' type='text/javascript'>alert('管理员请先登录');";

    echo "window.location.href='../admin/admin_login.php'";

    echo "</script>";

}



?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>软件工程教学辅助系统</title>

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

    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

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
                         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="#">软件工程教学辅助系统</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <li><a href="../admin/admin_course.php">课程管理</a></li>
                                <li><a href="../admin/admin_teacher.php">教师管理</a></li>
                                <li><a href="../admin/admin_student.php">学生管理</a></li>
                                <li><a href="../BBS/manage.php">论坛管理</a></li>
                                <li><a href="../ManagePassbyMsg.php">留言管理</a></li>
                                <li><a href="../admin/admin_logout.php">登出</a></li>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">个人信息管理<strong class="caret"></strong></a>
                                <ul class="dropdown-menu">
                                    <li>
                                         <a href="../admin/admin_info.php">查看个人信息</a>
                                    </li>
                                    <li>
                                         <a href="../admin/admin_info_password.php">修改密码</a>
                                    </li>
                                    <li>
                                         <a href="../admin/admin_info_modify.php">修改个人信息</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="jumbotron">
                    <h1>管理员，您好!</h1>
                    <p>软件工程教学辅助系统</p>
                </div>
            </div>
        </div>

</div>

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="panel-group" id="panel-694852">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a class="panel-title" data-toggle="collapse" data-parent="#panel-694852" href="#panel-element-526524">小组答疑板块</a>
                    </div>
                    <div id="panel-element-526524" class="panel-collapse collapse in">
                        <div class="panel-body">

<?php
require "../commonAPI/database.php";
$con = get_connect();

if($identity=='admin'){
    $sql_group = 'select * from group_post WHERE frozon=0';
    $sql_homework = 'select * from homework_post WHERE frozon=0';

    ?>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>帖子ID</th>
                                    <th>发帖人</th>
                                    <th>标题</th>
                                    <th>发布日期</th>
                                    <th>热度</th>
                                    <th>内容</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
    <?php
    if($result = mysqli_query($con, $sql_group)){
        while($row = mysqli_fetch_assoc($result)){ ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['post_id']?>
                                        </td>
                                        <td>
                                            <?php echo $row['name']?>
                                        </td>
                                        <td>
                                            <?php echo $row['title']?>
                                        </td>
                                        <td>
                                            <?php echo $row['post_date']?>
                                        </td>
                                        <td>
                                            <?php echo $row['hotness']?>
                                        </td>
                                        <td>
                                            <?php echo $row['content']?>
                                        </td>
                                        <td>
                                            <a <?php echo 'href="delete.php?delType=post&entry=group&id='.$row['post_id'].'"'?> >删除</a>
                                        </td>
                                    </tr>
    <?php }
    }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a class="panel-title" data-toggle="collapse" data-parent="#panel-694852" href="#panel-element-560727">课程作业板块</a>
                    </div>
                    <div id="panel-element-560727" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>帖子ID</th>
                                    <th>发帖人</th>
                                    <th>标题</th>
                                    <th>发布日期</th>
                                    <th>热度</th>
                                    <th>内容</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php
                            if($result = mysqli_query($con, $sql_homework)){

                                while($row = mysqli_fetch_assoc($result)){ ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['post_id']?>
                                        </td>
                                        <td>
                                            <?php echo $row['name']?>
                                        </td>
                                        <td>
                                            <?php echo $row['title']?>
                                        </td>
                                        <td>
                                            <?php echo $row['post_date']?>
                                        </td>
                                        <td>
                                            <?php echo $row['hotness']?>
                                        </td>
                                        <td>
                                            <?php echo $row['content']?>
                                        </td>
                                        <td>
                                            <a <?php echo 'href="delete.php?delType=post&entry=homework&id='.$row['post_id'].'"'?> >删除</a>
                                        </td>
                                    </tr>
                                <?php }

                            }?>

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}else{
    echo "<script language='javascript' type='text/javascript'>alert('管理员请先登录');";
    echo "window.location.href='../admin/admin_login.php'";
    echo "</script>";
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

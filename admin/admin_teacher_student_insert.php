<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>软件工程教学辅助系统</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">
</head>

<body>
<?php
    session_start();
        $username=$_SESSION["username"];
        if(!$username){
            header("location: index.php");
        }

        $_SESSION["tid"]=$_GET["id1"];
        $_SESSION["clid"]=$_GET["id2"];
    ?>

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
                                <li><a href="admin_course.php">课程管理</a></li>
                                <li><a href="admin_teacher.php">教师管理</a></li>
                                <li><a href="admin_student.php">学生管理</a></li>
                                <li><a href="../BBS/manage.php">论坛管理</a></li>
                                <li><a href="../ManagePassbyMsg.php">留言管理</a></li>
                                <li><a href="admin_logout.php">登出</a></li>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">个人信息管理<strong class="caret"></strong></a>
                                <ul class="dropdown-menu">
                                    <li>
                                         <a href="admin_info.php">查看个人信息</a>
                                    </li>
                                    <li>
                                         <a href="admin_info_password.php">修改密码</a>
                                    </li>
                                    <li>
                                         <a href="admin_info_modify.php">修改个人信息</a>
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
                <h3>导入选课名单</h3>
                <br>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-4 column">
                <form method="post" action="admin_teacher_student_insert1.php" class="form-inline" role="form" enctype="multipart/form-data">
                    <div class="form-group"><input type="file" class="form-control input-md" name="file"></div>
                    <div class="form-group"><input type="submit" class="form-control input-md" name="button" value="提交" ></div>
                </form>
            </div>
            <div class="col-md-4 column">
            </div>
            <div class="col-md-4 column">
            </div>
        </div>
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

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="intro-message">
                    
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

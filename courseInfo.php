<?php
    require 'commonAPI/courseAPI.php';

    $courses=get_course_info_by_name($_GET['name']);
    if(count($courses)==0){
        $course= new courseInfo("","","","","","","","",
                                "","","","","","","","");
    }
    else{
        $course=$courses[0];
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
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">
    <!-- 可选的Bootstrap主题文件（一般不使用） -->
    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap-theme.min.css"></script>
<!-- font-awesome-4.7.0 CDN 地址-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#sendMessage").click(function(){
                $('#leaveMessage').submit();
                return false;
            });
        });
    </script>
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
                    <ul class="nav navbar-nav">
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a id="modal-365839" href="#modal-container-365839" role="button" class="btn" data-toggle="modal">游客留言</a>
                        </li>
                        <li>
                             <a href="common/login/login.html">登陆系统</a>
                        </li>
                        <li class="dropdown">
                             <a href="#" class="dropdown-toggle" data-toggle="dropdown">系统相关<strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                                <li>
                                     <a href="#">系统帮助</a>
                                </li>
                                <li class="divider">
                                </li>
                                <li>
                                     <a href="#">反馈</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="modal fade" id="modal-container-365839" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">
                                请留下你的建议吧！
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form id="leaveMessage" role="form" method="POST" action="leaveMessage.php">
                                <div class="form-group">
                                <label for="message_text">留言内容</label>
                                <textarea name="message_text" id="message_text" class="form-control" rows="7" style="resize: none;overflow-y: visible;"></textarea>
                                </div>
                                <div class="form-group">
                                <label for="email">邮箱</label>
                                <input class="form-control" type="email" name="email" id="email" placeholder="留下你的邮箱，方便我们联系你~">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                             <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button> <button type="button" class="btn btn-primary" id="sendMessage">保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="row clearfix">
        <div class="page-header text-right"><h3>课程详细介绍</h3></div>
         <div class="col-md-12 column">
            <dl class="dl-horizontal text-default lead">
                <dt>
                    课程名
                </dt>
                <dd>
                    <?php echo $course->coname?>
                </dd>
                <dt>
                    课程英语名
                </dt>
                <dd>
                    <?php echo $course->coname_en?>
                </dd>
                <dt>
                    课程代码
                </dt>
                <dd>
                    <?php echo $course->cocode?>
                </dd>
                <dt>
                    课程类别
                </dt>
                <dd>
                    <?php echo $course->cotype?>
                </dd>
                <dt>
                    学期
                </dt>
                <dd>
                    <?php echo $course->semester?>
                </dd>
                <dt>
                    教材
                </dt>
                <dd>
                    <?php echo $course->textbook?>
                </dd>
                <dt>
                    开课学院
                </dt>
                <dd>
                    <?php echo $course->college?>
                </dd>
                <dt>
                    学分
                </dt>
                <dd>
                    <?php echo $course->credit?>
                </dd>
                <dt>
                    周学时
                </dt>
                <dd>
                    <?php echo $course->week_learn_time?>
                </dd>
                <dt>
                    权重系数
                </dt>
                <dd>
                    <?php echo $course->weight?>
                </dd>
                <dt>
                    预修要求
                </dt>
                <dd>
                    <?php echo $course->pre_learning?>
                </dd>
                <dt>
                    教学计划
                </dt>
                <dd>
                    <?php echo $course->plan?>
                </dd>
                <dt>
                    教学背景
                </dt>
                <dd>
                    <?php echo $course->background?>
                </dd>
                <dt>
                    作业情况
                </dt>
                <dd>
                    <?php echo $course->assessment?>
                </dd>
                <dt>
                    大作业信息
                </dt>
                <dd>
                    <?php echo $course->project_info?>
                </dd>
            </dl>
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
                    成员组成: xxx,xxx,xxx,xxx,xxx
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
                            <li><a href="http://127.0.0.1/">浙江大学现代教务管理系统</a></li>
                            <li><a href="http://127.0.0.1">计算机科学与技术学院办公网</a></li>
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

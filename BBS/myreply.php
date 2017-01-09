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
if($identity=='student')
    $idtype = 'sid';
else if($identity)//教师是'tid'
    $idtype = 'tid';

require "../commonAPI/database.php";

$con = get_connect();

//group_post_floor -> group_post
$sql_1 = ' select
            P.post_id as post_id,
            P.gid as post_gid,
            P.name as post_sname,
            P.title as post_title,
            P.post_date as post_date,
            P.hotness as post_hotness,
            P.content as post_content,
            F.floor_id as floor_id,
            F.post_date as floor_date,
            F.content as floor_content
            from group_post as P,(select * from group_post_floor where '.$idtype.'='.$id.')as F
            where F.post_id=P.post_id and P.frozon=0 order by F.post_date DESC';
//homework_post_floor -> homework_post
$sql_2 = 'select
            P.post_id as post_id,
            P.coid as post_coid,
            P.name as post_tname,
            P.title as post_title,
            P.post_date as post_date,
            P.hotness as post_hotness,
            P.content as post_content,
            F.floor_id as floor_id,
            F.post_date as floor_date,
            F.content as floor_content
            from homework_post as P,(select * from homework_post_floor where '.$idtype.'='.$id.')as F
            where F.post_id=P.post_id and P.frozon=0 order by F.post_date DESC';

// begin query

if($result = mysqli_query($con, $sql_1)){
    ?>
    <div class="container">
        <div class="row clearfix">
            <div class="page-header col-md-12"><h3>我参与的小组答疑</h3></div>
        </div>
    <?php
    while($row=mysqli_fetch_assoc($result)){
        ?>
        <div class="row clearfix">
            <div class="col-md-12 column">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php echo $row['post_title']?>
                            <div class="pull-right">
                                <?php echo $row['post_sname']?>发表于<?php echo $row['post_date']?>
                            </div>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            原帖内容：<?php echo $row['post_content']?>
                        </div>
                        <div class="col-md-6">
                            我的回复：<?php echo $row['floor_content']?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        回复于
                        <?php echo $row['floor_date']?>
                        <a <?php echo 'href="detail.php?type=group&post_id='.$row['post_id'].'"'?>>查看详情</a>
                        <a <?php echo 'href="delete.php?delType=reply&entry=group&id='.$row['floor_id'].'"'?>>删除</a>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
    <?php
}

if($result = mysqli_query($con, $sql_2)){
    ?>
    <div class="container">
        <div class="row clearfix">
            <div class="page-header col-md-12"><h3>我参与的作业问答</h3></div>
        </div>
    <?php
    while($row=mysqli_fetch_assoc($result)){
        ?>
        <div class="row clearfix">
            <div class="col-md-12 column">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php echo $row['post_title']?>
                            <div class="pull-right">
                                <?php echo $row['post_tname']?>发表于<?php echo $row['post_date']?>
                            </div>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            原帖内容：<?php echo $row['post_content']?>
                        </div>
                        <div class="col-md-6">
                            我的回复：<?php echo $row['floor_content']?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        回复于
                        <?php echo $row['floor_date']?>
                        <a <?php echo 'href="detail.php?type=homework&post_id='.$row['post_id'].'"'?>>查看详情</a>
                        <a <?php echo 'href="delete.php?delType=reply&entry=homework&id='.$row['floor_id'].'"'?>>删除</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        // echo $row['post_id'].' ';
        // echo $row['post_coid'].' ';
        // echo $row['post_tname'].' ';
        // echo $row['post_title'].' ';
        // echo $row['post_date'].' ';
        // echo $row['post_hotness'].' ';
        // echo $row['post_content'].' ';
        // echo $row['floor_id'].' ';
        // echo $row['floor_date'].' ';
        // echo $row['floor_content'].'<br>';
    }
    ?>
    </div>
    <?php
}

?>
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

<?php
/**
 * Created by PhpStorm.
 * User: 51499
 * Date: 2016/12/11 0011
 * Time: 20:55
 */
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
    <script type="text/javascript">
      $(document).ready(function(){
        $('#send_btn').click(function(){
                $('#send_post').submit();
                return false;
            });
      });

    </script>
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


/*全局变量，主要是处理数据*/
$rows_gposts=array();
$rows_hposts=array();
$groups = array();
$courses = array();

/*查找group_post, homework_post*/
/*student*/
if($identity=='student'){/*学生看到的帖子分为两类，按照小组分的讨论板块和按照课程类别分的作业板块*/
      /*如果是学生需要查找他所在的小组名单和课程id列表*/
    $rows_gposts=array();
    $rows_hposts=array();
    $groups = array();
    $courses = array();
    $sql="select gid, gname from study_group where gid in (select distinct gid from attend where sid=".$id.")";
    if($result_groups = mysqli_query($con, $sql)){
        while($row_groups = mysqli_fetch_row($result_groups)){
            $groups[$row_groups[1]]=$row_groups[0];
        }
    }
    /*根据小组gid gname分类展示小组板块的内容*/
    // echo "groups:".count($groups);
    $sql="select coid, coname from course where coid in (select distinct coid from class where clid in (select clid from attend where sid=".$id."))";
    if($result_courses = mysqli_query($con, $sql)){
        while($row_courses=mysqli_fetch_row($result_courses)){
            $courses[$row_courses[1]]=$row_courses[0];
        }
    }
    /*根据课程coid和coname展示作业板块的内容*/
    // echo "courses:".count($courses);
    $sql_for_group_posts = "select post_id,gname,group_post.gid as gid,title,post_date,hotness,content
                            from group_post, study_group
                            where sid=".$id."
                                  and group_post.gid=study_group.gid
                                  and frozon=0
                            order by post_date DESC ";    /*根据gid的不同再分到不同的数组中*/
    $sql_for_hw_posts = "select post_id,class.coid as coid,title,post_date,hotness,content
                         from attend,class,homework_post
                         where sid=".$id."
                               and class.clid=attend.clid
                               and class.coid=homework_post.coid
                               and frozon=0
                         order by post_date DESC";
    if($result_group_posts = mysqli_query($con, $sql_for_group_posts)){
        $rows_gposts = array();
        while($row_group_posts = mysqli_fetch_assoc($result_group_posts)){
            $rows_gposts[ ] = $row_group_posts;
        }
        mysqli_free_result($result_group_posts);
        //echo count($rows_gposts);
    }
    if($result_hw_posts = mysqli_query($con, $sql_for_hw_posts)){
        $rows_hposts = array();
        while($row_hw_posts = mysqli_fetch_assoc($result_hw_posts)){
            $rows_hposts[ ] = $row_hw_posts;
        }
        mysqli_free_result($result_hw_posts);
        //echo count($rows_gposts);
    }
}
else if($identity=='teacher'){/*教师按照不同 的课程来看帖子，一门课里有两种帖子，小组答疑帖和作业问答贴*/
      /*如果是老师需要查找他所上的不同的课程id列表*/
    $rows_gposts=array();
    $rows_hposts=array();
    $groups = array();
    $courses = array();
    $sql = "select coid,coname from course where coid in (select distinct coid from class where tid=".$id.")";
    if($result_courses = mysqli_query($con, $sql)){
        while($row_courses=mysqli_fetch_row($result_courses)){
            $courses[$row_courses[1]]=$row_courses[0];
        }
    }
    /*根据coid 和coname分类展示两个板块的内容*/
    // echo "courses:".count($courses);
    //echo count($courses);
    //foreach($courses as $k=>$v){
    //    echo $k . "=>" . $v;
    //}
    $sql_for_group_posts = "select post_id,group_post.gid as gid,class.coid as coid,title,post_date,hotness,content
                            from attend,class,group_post
                            where tid=".$id."
                                  and attend.gid=group_post.gid
                                  and attend.sid=group_post.sid
                                  and attend.clid=class.clid
                                  and frozon=0
                            order by post_date DESC";

    $sql_for_hw_posts = "select post_id,coid,title,post_date,hotness,content
                         from homework_post
                         where tid=".$id."
                               and frozon=0
                               and coid in (select distinct coid from class where tid=".$id.")
                         order by post_date DESC";
    if($result_group_posts = mysqli_query($con, $sql_for_group_posts)){
        while($row_group_posts = mysqli_fetch_assoc($result_group_posts)){
            $rows_gposts[ ] = $row_group_posts;
        }
        mysqli_free_result($result_group_posts);
        // echo $rows_gposts[0]['post_date'];
    }
    if($result_hw_posts = mysqli_query($con, $sql_for_hw_posts)){
        while($row_hw_posts = mysqli_fetch_assoc($result_hw_posts)){
            $rows_hposts[ ] = $row_hw_posts;
            // echo 'one ';
        }
        mysqli_free_result($result_hw_posts);
        // echo $rows_hposts[0]['post_id'];

    }
}
mysqli_close($con);

?>

<div class="container">
    <div class="jumbotron">
        <h1>你好, <?php echo $name ?>！</h1>
        <div class="row clearfix">
          <div class="col-md-10 column">
            <span class="lead">
                你可以在小组中讨论问题，也可以参与课程的作业讨论中！学习使我快乐！
            </span>
          </div>
          <div class="col-md-2 column">
              <a id="modal-213536" href="#modal-container-213536" role="button" class="btn" data-toggle="modal"><i class="fa fa-plus fa-2x text-success">发帖子</i></a>
              <div class="modal fade" id="modal-container-213536" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">
                        <?php
                          if($identity=='teacher') echo "在作业板块发帖";
                          else if($identity=='student') echo "在小组讨论板块发帖";
                        ?>
                        </h4>
                    </div>
                    <div class="modal-body">
                      <!-- 加一个表单 -->
                    <form role="form" id="send_post" action="post.php" method="POST">
                      <?php
                        echo '<input type="text" style="display:none;" name="id" id="id" value="'.$id.'"/>';
                        echo '<input type="text" style="display:none;" name="identity" id="identity" value="'.$identity.'"/>';
                      ?>

                      <?php
                        if($identity=='student'){
                          echo '<div class="form-group">';
                          echo '<label for="select_group">选择小组</label>';
                          echo '<select class="form-control" id="select_group" name="select_group">';
                          foreach ($groups as $key => $value) {
                            echo '<option>'.$key.'</option>';
                          }
                          echo '</select>';
                          echo '</div>';
                        }
                        else if($identity=='teacher'){
                          echo '<div class="form-group">';
                          echo '<label for="select_course">选择课程</label>';
                          echo '<select class="form-control" id="select_course" name="select_course">';
                          foreach ($courses as $key => $value) {
                            echo '<option>'.$key.'</option>';
                          }
                          echo '</select>';
                          echo '</div>';
                        }

                      ?>
                      <div class="form-group">
                        <label for="title">帖子标题</label>
                        <input type="text" name="title" id="title"/>
                      </div>
                      <div class="form-group">
                        <label for="content">帖子内容</label>
                        <textarea name="content" id="content" class="form-control" rows="7" style="resize: none;overflow-y: visible;"></textarea>
                      </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                       <button type="button" id="send_btn" class="btn btn-primary">发表</button>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>
<br>
<!--小组讨论的帖子
学生：post_id,gname,gid,title,post_date,hotness,content
教师：post_id,gid,coid,title,post_date,hotness,content-->
<div class="container" id="grp_posts">

  <?php if($identity=='student'){ ?>
  <?php    foreach($groups as $k=>$v){ ?>
  <div class="row clearfix">
      <div class="col-md-1 column"><i class="fa fa-genderless fa-5x text-info"></i></div>
      <div class="col-md-11 column"><h3>小组讨论/<?php echo $k ?></h3></div>
  </div>
  <div class="row clearfix">
      <div class="col-md-1 column" style="background-color: white;"></div>
      <div class="col-md-11 column">
      <table class="table table-striped mytable">
        <thead>
          <tr class="info">
            <th width="27%">标题</th>
            <th width="18%">发布日期</th>
            <th width="34%">内容摘要</th>
            <th width="9%">回复量</th>
            <th width="12%"></th>
          </tr>
        </thead>
        <tbody>
    <!-- 筛选符合分组条件的帖子 -->
    <?php for($i=0; $i<count($rows_gposts); $i++){
      if($groups[$k]==$rows_gposts[$i]['gid']){?>
        <tr>
          <td><?php echo $rows_gposts[$i]['title']?></td>
          <td><?php echo $rows_gposts[$i]['post_date']?></td>
          <td><?php echo $rows_gposts[$i]['content']?></td>
          <td><?php echo $rows_gposts[$i]['hotness']?></td>
          <td><?php echo '<a href="detail.php?type=group&post_id='.$rows_gposts[$i]['post_id'].'&gid='.$rows_gposts[$i]['gid'].'"'?><span class="glyphicon glyphicon-chevron-right"></span>查看细节</a></td>
        </tr><?php }}?>
        <!-- 结束筛选 -->
        </tbody>
      </table>
      </div>
  </div>
  <?php }?>
  <!-- 结束foreach -->
<!-- 结束学生判断 -->
  <?php } else if($identity=='teacher'){?>
  <?php foreach($courses as $k=>$v){?>
  <div class="row clearfix">
      <div class="col-md-1 column"><i class="fa fa-genderless fa-5x text-info"></i></div>
      <div class="col-md-11"><h3>小组答疑/<?php echo $k ?></h3></div>
  </div>
  <div class="row clearfix">
      <div class="col-md-1" style="background-color: white;"></div>
      <div class="col-md-11">
        <table class="table table-striped mytable">
          <thead>
            <tr class="info">
              <th width="27%">标题</th>
              <th width="18%">发布日期</th>
              <th width="34%">内容摘要</th>
              <th width="9%">回复量</th>
              <th width="12%"></th>
            </tr>
          </thead>
          <tbody>
          <?php for($i=0; $i<count($rows_gposts); $i++){
            if($courses[$k]==$rows_gposts[$i]['coid']){?>
            <tr>
              <td><?php echo $rows_gposts[$i]['title']?></td>
              <td><?php echo $rows_gposts[$i]['post_date']?></td>
              <td><?php echo $rows_gposts[$i]['content']?></td>
              <td><?php echo $rows_gposts[$i]['hotness']?></td>
              <td><?php echo '<a href="detail.php?type=group&post_id='.$rows_gposts[$i]['post_id'].'&coid='.$rows_gposts[$i]['coid'].'&gid='.$rows_gposts[$i]['gid'].'"'?><span class="glyphicon glyphicon-chevron-right"></span>查看细节</a></td>
            </tr><?php }}?>
          </tbody>
        </table>
      </div>
    </div>
  <?php }}else;?>
</div>

<!--作业问答的帖子
学生：post_id,class.coid,title,post_date,hotness,content
教师：post_id,class.coid,title,post_date,hotness,content-->
<div class="container" id="hwk_posts">

  <?php foreach($courses as $k=>$v){ ?>
  <div class="row clearfix">
      <div class="col-md-1 column"><i class="fa fa-genderless fa-5x text-success"></i></div>
      <div class="col-md-11"><h3>作业问答/<?php echo $k?></h3></div>
  </div>
  <div class="row clearfix">
    <div class="col-md-1" style="background-color: white;"></div>
    <div class="col-md-11">
      <table class="table table-striped mytable">
        <thead>
          <tr class="info">
              <th width="27%">标题</th>
              <th width="18%">发布日期</th>
              <th width="34%">内容摘要</th>
              <th width="9%">回复量</th>
              <th width="12%"></th>
          </tr>
        </thead>
        <tbody>
    <!-- 筛选 -->
        <?php for($i=0; $i<count($rows_hposts); $i++){
          if($courses[$k]==$rows_hposts[$i]['coid']){?>
          <tr>
            <td><?php echo $rows_hposts[$i]['title']?></td>
            <td><?php echo $rows_hposts[$i]['post_date']?></td>
            <td><?php echo $rows_hposts[$i]['content']?></td>
            <td><?php echo $rows_hposts[$i]['hotness']?></td>
            <td><?php echo '<a href="detail.php?type=homework&post_id='.$rows_hposts[$i]['post_id'].'&coid='.$rows_hposts[$i]['coid'].'"'?><span class="glyphicon glyphicon-chevron-right"></span>查看细节</a></td>
          </tr><?php }}?>
        </tbody>
      </table>
    </div>
  </div>
  <?php }?>
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

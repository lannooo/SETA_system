<?php
session_start();
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
    <!-- font-awesome-4.7.0 CDN 地址-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">
    <!-- 可选的Bootstrap主题文件（一般不使用） -->
    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap-theme.min.css"></script>

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#submit_button').click(function(){
                var text_val = $.trim($('#reply_text').val());
                var postid_val = $.trim($('#post_id').val());
                var posttype_val = $.trim($('#post_type').val());

                var form = $('<form></form>');
                form.attr('action', 'reply.php');
                form.attr('method', 'POST');

                var text_input = $('<input type="text" name="reply_text" />');
                var postid_input = $('<input type="text" name="post_id" />');
                var posttype_input = $('<input type="text" name="post_type" />')
                text_input.attr('value', text_val);
                postid_input.attr('value', postid_val);
                posttype_input.attr('value', posttype_val);
                form.append(text_input);
                form.append(postid_input);
                form.append(posttype_input);

                form.submit();
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
/*身份验证，没有则返回登陆界面*/
if(isset($_SESSION['id'])&&isset($_SESSION['name'])&&isset($_SESSION['identity'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $identity = $_SESSION['identity'];

}else{
    echo "<script language='javascript' type='text/javascript'>alert('请先登录');";
    echo "window.location.href='login.html'";
    echo "</script>";
}
require "../commonAPI/database.php";

$con = get_connect();


/*获取GET中的帖子的属性*/
$type = $_GET['type'];
$post_id = $_GET['post_id'];
$gid = '';
$coid = '';
$tid='';
$post_uname = '';
$title = '';
$post_date = '';
$hotness = '';
$content = '';
$gname = '';
$coname = '';

if($type=='group'){
    /*获得原帖的信息*/
    $sql = "select * from group_post where frozon=0 and post_id=".$post_id;
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_assoc($result)){
            $gid = $row['gid'];
            $post_uname = $row['name'];
            $title = $row['title'];
            $post_date = $row['post_date'];
            $hotness = $row['hotness'];
            $content = $row['content'];
        }
        mysqli_free_result($result);
    }
    /*获得小组的名字*/
    $sql = "select gname from study_group where gid=".$gid;
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_assoc($result)){
            $gname = $row['gname'];
        }
        mysqli_free_result($result);
    }
    echo '<input type="text" style="display:none;" id="post_id" value="'.$post_id.'"/>';
    echo '<input type="text" style="display:none;" id="post_type" value="'.$type.'"/>';
    ?>
    <div class="container">
        <div class="row clearfix">
            <div class="modal fade" id="modal-container-677194" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">
                                请输入回复内容
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" action="reply.php" method="POST">
                                <div class="form-group">
                                    <textarea id="reply_text" name="reply_text" class="form-control" rows="10" style="resize: none;overflow-y: visible;"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                             <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button> <button id="submit_button" type="button" class="btn btn-primary">回复</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row clearfix" style="margin-top: 20px;margin-bottom: 50px;">
        <div class="col-md-2 column text-right">
            <h4>热度</h4>
            <p><i class="fa fa-commenting"></i><?php echo '  '.$hotness?></p>
            <a id="modal-677194" href="#modal-container-677194" role="button" class="btn" data-toggle="modal"><h4><strong>回复帖子</strong></h4></a>
        </div>
        <div class="col-md-8 column text-left navbar" style="background-color: #FFFFCC;">
            <h2><strong><?php echo $title?></strong></h2>
            <p class="lead"><?php echo $content?></p>
        </div>
        <div class="col-md-2 column  text-left">
            <i class="fa fa-user-circle fa-5x text-success"></i><br>
            <strong><?php echo $gname?></strong>
            <em><?php echo $post_uname?></em>
            <p><?php echo $post_date?></p>
        </div>

    </div>

    <?php
    /*获得显示的楼层的信息*/
    $number_per_page = 5;
    $page = 1;
    $sql = "select count(*) from group_post_floor where post_id=".$post_id;
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($result);
    $total_number = $row[0];
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }
    $total_pages = ceil($total_number/$number_per_page);
    $start = ($page-1)*$number_per_page;
    $sql = "select * from group_post_floor where post_id=".$post_id." order by ref_count limit ".$start.",".$number_per_page;
    if($result = mysqli_query($con, $sql)){
        while($row = mysqli_fetch_assoc($result)){
            if($row['utype']=='T')
                $utype = '教师';
            else if($row['utype']=='S')
                $utype = '学生';
            $name = $row['name'];
            $post_date  = $row['post_date'];
            $content = $row['content'];
            // $ref_count = $row['ref_count'];
            ?>
            <div class="row clearfix" style="margin-top: 20px;margin-bottom: 50px">
                <div class="col-md-2 column  text-right">
                    <i class="fa fa-user-circle fa-5x text-info"></i><br>
                    <strong><?php echo $utype?></strong>
                    <em><?php echo $name?></em>
                    <p><?php echo $post_date?></p>
                </div>

                <div class="col-md-8 column text-left navbar" style="background-color: #CCFFCC;width: fit-content;width: -webkit-fit-content;width: -moz-fit-content;">
                    <p class="lead"><?php echo $content ?></p>
                </div>
                <div class="col-md-2 column">
                    <p></p>
                </div>
            </div>
    <?php }
    }


}
else if($type=='homework')
{
    /*获得原帖的信息*/
    $sql = "select * from homework_post where post_id=".$post_id;
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_assoc($result)){
            $coid = $row['coid'];
            $tid = $row['tid'];
            $post_uname = $row['name'];
            $title = $row['title'];
            $post_date = $row['post_date'];
            $hotness = $row['hotness'];
            $content = $row['content'];
        }
        mysqli_free_result($result);
    }
    /*获取课程名称*/
    $sql = "select coname from course where coid=".$coid;
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_assoc($result)){
            $coname = $row['coname'];
        }
        mysqli_free_result($result);
    }
    $sql = "select name from teacher where tid=".$tid;
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_assoc($result)){
            $tname = $row['name'];
        }
        mysqli_free_result($result);
    }
    echo '<input type="text" style="display:none;" id="post_id" value="'.$post_id.'"/>';
    echo '<input type="text" style="display:none;" id="post_type" value="'.$type.'"/>';
    ?>

    <div class="container">
        <div class="row clearfix">
            <div class="modal fade" id="modal-container-677194" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">
                                请输入回复内容
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" action="reply.php" method="POST">
                                <div class="form-group">
                                    <textarea id="reply_text" class="form-control" rows="10" style="resize: none;overflow-y: visible;"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                             <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button> <button id="submit_button" type="button" class="btn btn-primary">回复</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix" style="margin-top: 20px;margin-bottom: 50px;">
            <div class="col-md-2 column text-right">
                <h4>热度</h4>
                <p><i class="fa fa-commenting"></i><?php echo '  '.$hotness?></p>
                <a id="modal-677194" href="#modal-container-677194" role="button" class="btn" data-toggle="modal"><h4><strong>回复帖子</strong></h4></a>
            </div>
            <div class="col-md-8 column text-left navbar" style="background-color: #FFFFCC;">
                <h2><strong><?php echo $title?></strong></h2>
                <p class="lead"><?php echo $content?></p>
            </div>
            <div class="col-md-2 column  text-left">
                <i class="fa fa-user-circle fa-5x text-success"></i><br>
                <strong><?php echo $coname?></strong>
                <em><?php echo $post_uname?></em>
                <p><?php echo $post_date?></p>
            </div>
        </div>
    <?php
    $number_per_page = 5;
    $page = 1;
    $sql = "select count(*) from homework_post_floor where post_id=".$post_id;
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($result);
    $total_number = $row[0];
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }
    $total_pages = ceil($total_number/$number_per_page);
    $start = ($page-1)*$number_per_page;
    $sql = "select * from homework_post_floor where post_id=".$post_id." order by ref_count limit ".$start.",".$number_per_page;
    if($result=mysqli_query($con, $sql)){
        while($row=mysqli_fetch_assoc($result)){
            if($row['utype']=='T')
                $utype = '教师';
            else if($row['utype']=='S')
                $utype = '学生';
            $name = $row['name'];
            $post_date = $row['post_date'];
            $content = $row['content'];
            ?>
            <div class="row clearfix" style="margin-top: 20px;margin-bottom: 50px">
                <div class="col-md-2 column  text-right">
                    <i class="fa fa-user-circle fa-5x text-info"></i><br>
                    <strong><?php echo $utype?></strong>
                    <em><?php echo $name?></em>
                    <p><?php echo $post_date?></p>
                </div>

                <div class="col-md-8 column text-left navbar" style="background-color: #CCFFCC;width: fit-content;width: -webkit-fit-content;width: -moz-fit-content;">
                    <p class="lead"><?php echo $content ?></p>
                </div>
                <div class="col-md-2 column">
                    <p></p>
                </div>
            </div>
    <?php }
    }}?>

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

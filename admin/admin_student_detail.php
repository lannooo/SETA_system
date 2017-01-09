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

        $sid=$_GET["id1"];

        $dbhost="localhost";
        $dbuser="sem";
        $dbpassword="sem2016";
        $dbname="software_eng";
        
        $mysqli=new mysqli($dbhost,$dbuser,$dbpassword,$dbname);

        if(mysqli_connect_errno()){
            echo mysqli_connect_error();
            die;
        }

        $mysqli->query("set names UTF-8");
        
        $sql="select sid,clid,coname,name,cltime,place,student_num from attend natural join class natural join course join teacher where class.tid= teacher.tid and sid='{$sid}'";
        
        $result=$mysqli->query($sql);
        if(!$result){
            echo "<script language='javascript'>alert('Error!');</script>";
        }
        else{
            echo "<div class='container'>";
                echo "<div class='row clearfix'>";
                    echo "<div class='col-md-12 column'>";
                        echo "<nav class='navbar navbar-default navbar-fixed-top' role='navigation'>";
                            echo "<div class='navbar-header'>";
                                 echo "<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'> <span class='sr-only'>Toggle navigation</span><span class='icon-bar'></span><span class='icon-bar'></span><span class='icon-bar'></span></button> <a class='navbar-brand' href='#'>软件工程教学辅助系统</a>";
                            echo "</div>";
                            echo "<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>";                     
                                echo "<ul class='nav navbar-nav navbar-right'>";
                                    echo "<li>";
                                        echo "<li><a href='admin_course.php'>课程管理</a></li>";
                                        echo "<li><a href='admin_teacher.php'>教师管理</a></li>";
                                        echo "<li><a href='admin_student.php'>学生管理</a></li>";
                                        echo "<li><a href='../BBS/manage.php'>论坛管理</a></li>";
                                        echo "<li><a href='../ManagePassbyMsg.php'>留言管理</a></li>";
                                        echo "<li><a href='admin_logout.php'>登出</a></li>";
                                    echo "</li>";
                                    echo "<li class='dropdown'>";
                                        echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>个人信息管理<strong class='caret'></strong></a>";
                                        echo "<ul class='dropdown-menu'>";
                                            echo "<li><a href='admin_info.php'>查看个人信息</a></li>";
                                            echo "<li><a href='admin_info_password.php'>修改密码</a></li>";
                                            echo "<li><a href='admin_info_modify.php'>修改个人信息</a></li>";
                                        echo "</ul>";
                                    echo "</li>";
                                echo "</ul>";
                            echo "</div>";
                        echo "</nav>";
                        echo "<div class='jumbotron'>";
                            echo "<h1>管理员，您好!</h1>";
                            echo "<p>软件工程教学辅助系统</p>";
                        echo "</div>";
                        echo "<h3>个人信息管理</h3>";
                        echo "<br>";
                        if($result->num_rows>0){
                            echo "<table class='table table-hover'>";
                            echo "<tr><td>编号</td><td>课程名称</td><td>教师姓名</td><td>上课时间</td><td>上课地点</td><td>教室容量</td><td>操作</td></tr>";
                            $i=1;
                            while($row =$result->fetch_array()){
                                echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td>$row[2]</td>";
                                echo "<td>$row[3]</td>";
                                echo "<td>$row[4]</td>";
                                echo "<td>$row[5]</td>";
                                echo "<td>$row[6]</td>";
                                echo "<td><a href='admin_student_class_delete.php?id1=$row[0]&id2=$row[1]'>删除</a></td>";
                                echo "</tr>";
                                $i++;
                            }
                            echo "</table>";
                        }
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        }

        $result->free();
        $mysqli->close();
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

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

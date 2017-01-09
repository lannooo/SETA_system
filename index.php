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
    <script src="js/jquery.json.js"></script>

    <script type="text/javascript">

        function postJSON(url, jsonStr, successFunction, async=true, dataType="json", contentType="application/text") {
        $.ajax({
          url : url,
          type : 'POST',
          async : async,
          data : jsonStr,
          processData : false,
          dataType : dataType,
          contentType : contentType,
          success : function(response, status, xhr) {
            var response;
            if (dataType != "json")
              response = $.parseJSON(response);
            if (status != "success")
              alert("未知错误");
            else successFunction(response);
          },
          error : function(xhr, error, exception) {
              // handle the error.
              alert(exception.toString());
          }
        });
      }
      function lgn_btn() {
        var req = {
            username: $("#lgn-username").val(),
            password: $("#lgn-password").val()
        };
        if ($("#lgn-type-stu").prop("checked"))
            req.group = "student";
        else if ($("#lgn-type-tea").prop("checked"))
            req.group = "teacher";
        else
            req.group = "ta_assist";
        var jsonStr = $.toJSON(req);
        postJSON("common/login/login.php", jsonStr, function showResponse(response) {
            if (response.code == 200) {
                if (req.group == "student")
                    window.location.href="student/Course_list.html";
                else
                    window.location.href="teacher/teacher-center.html";
            } else if (response.code == 1) {
                $("#lgn-ret").text("账号或密码错误");
                $("#lgn-ret").css("color", "color:#FF0000;");
            } else {
                $("#lgn-ret").text("不存在的账号");
                $("#lgn-ret").css("color", "color:#FF0000;");
            }
        });
      }

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

            <div class="carousel slide" id="carousel-751185">

                <ol class="carousel-indicators">

                    <li class="active" data-slide-to="0" data-target="#carousel-751185">

                    </li>

                    <li data-slide-to="1" data-target="#carousel-751185">

                    </li>

                    <li data-slide-to="2" data-target="#carousel-751185">

                    </li>

                </ol>

                <div class="carousel-inner">

                    <div class="item active">

                        <img alt="" src="img/1.jpg" width="100%" />

                        <div class="carousel-caption">

                            <h2>

                                软件工程教学辅助系统上线啦！

                            </h2>

                            <p>

                                系统为学生提供了方便的在线作业、资料获取、在线学习交流功能；同时辅助教师完成日常教学，可以进行作业管理、课程通知、资料管理等工作；游客也能浏览课程相关信息，并可以留言。

                            </p>

                        </div>

                    </div>

                    <div class="item">

                        <img alt="" src="img/2.jpg"  width="100%"/>

                        <div class="carousel-caption">

                            <h2>

                                软件需求工程

                            </h2>

                            <p>

                                本学期开设了软件需求工程，旨在理论和实践相结合，帮助学生了解软件开发过程中的需求开发、分析、维护的过程。

                            </p>

                        </div>

                    </div>

                    <div class="item">

                        <img alt="" src="img/3.jpg" width="100%" />

                        <div class="carousel-caption">

                            <h2>

                                软件工程管理

                            </h2>

                            <p>

                                本学期开设了软件工程管理，旨在理论和实践相结合，帮助学生学习工程管理的相关知识，对CMMI有初步和整体的了解。

                            </p>

                        </div>

                    </div>

                </div> <a class="left carousel-control" href="#carousel-751185" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control" href="#carousel-751185" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>

            </div>

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

                             <a class="btn" data-toggle="modal" href="#modal-container-login">登录</a>

                        </li>


                        <li>

                             <a class="btn" data-toggle="modal" href="common/register/register.html">注册</a>

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

    <div class="page-header text-left"><h3>教师介绍</h3></div>

    <div class="row clearfix">

        <div class="col-md-3 column text-center">

            <img alt="teacher-img" src="img/ic_person_lightblue.png" class="img-circle"  width="140px" height="140px"/>

            <h2>

                邢卫

            </h2>

            <p>

                浙江大学计算机学院副教授。1992年3月毕业于浙江大学计算机系，获硕士学位。2000年12月晋升副教授。作为课题负责人、技术负责人或主要骨干承担或参与国家自然科学基金项目、国家863计划项目、国家科技支撑计划项目、浙江省重大科技攻关项目等多项。目前的主要研究领域包括计算机网络、多媒体编码、流传输技术等。

            </p>

            <p>

                 <a class="btn" href="teacherInfo.php?name=邢卫">查看详情 »</a>

            </p>

        </div>

        <div class="col-md-3 column text-center">

            <img alt="teacher-img" src="img/ic_person_yellow.png" class="img-circle"  width="140px" height="140px"/>

            <h2>

                刘玉生

            </h2>

            <p>

                计算机科学与技术学院教授。工作于CAD实验室。主要研究项目有：基于SysML的复杂产品级多域耦合自动建模技术，设计与分析机电一体化的系统级模型集成研究...

            </p>

            <p>

                 <a class="btn" href="teacherInfo.php?name=刘玉生">查看详情 »</a>

            </p>

        </div>

        <div class="col-md-3 column text-center">

            <img alt="teacher-img" src="img/ic_person_blue.png" class="img-circle" width="140px" height="140px" />

            <h2>

                林海

            </h2>

            <p>

                计算机科学与技术学院教授。工作研究项目有：GFJG，可伸缩的高分辨率投影显示技术，气象体数据可视化，基于网络的医学体数据可视化，基于web的体数据可视化研究及其应用...

            </p>

            <p>

                 <a class="btn" href="teacherInfo.php?name=林海">查看详情 »</a>

            </p>

        </div>

        <div class="col-md-3 column text-center">

            <img alt="teacher-img" src="img/ic_person_green.png" class="img-circle" width="140px" height="140px" />

            <h2>

                金波

            </h2>

            <p>

                主要从事软件开发技术、软件工程、计算机网络工程、建筑物智能化技术和应用、项目管理、过程改进、质量控制、项目实施规划、项目战略规划编制等的研究，曾长期从事嵌入式系统的研发。对软件产品项目管理、成本控制、质量认证、过程监理、价值评估等有长期深入的研究和丰富的实践经验，曾获得国家部级科技进步一等奖。最近，主持研制(数据流、视频流和音频流)“三流合一”的电信级多媒体通信平台获得成功。

            </p>

            <p>

                 <a class="btn" href="teacherInfo.php?name=金波">查看详情 »</a>

            </p>

        </div>

    </div>

    <div class="page-header text-right"><h3>课程介绍</h3></div>

    <div class="row clearfix">

        <div class="col-md-9 column text-right">

            <h2>

                软件需求工程

            </h2>

            <p>

                软件的需求分析是软件开发过程的第一阶段。随着软件系统规模的扩大，需求分析与定义在整个软件开发与维护过程中越来越重要，直接关系到软件的成功与否。软件需求工程包括需求开发（需求获取、需求分析、需求规格说明、需求验证），需求管理（需求维护、需求变更）等...

            </p>

            <p>

                 <a class="btn" href="courseInfo.php?name=软件需求工程">查看详情 »</a>

            </p>

        </div>

        <div class="col-md-3 column text-right">

            <img alt="140x140" src="img/sre.jpg" class="img-rounded" width="50%"  />

        </div>

    </div>

    <div class="row clearfix">

        <div class="col-md-3 column text-left">

            <img alt="140x140" src="img/rem.jpg" class="img-rounded" width="50%" />

        </div>

        <div class="col-md-9 column text-left">

            <h2>

                软件工程管理

            </h2>

            <p>

                没有良好的管理，软件的开发过程将难以进行。软件工程管理讲授了如何对软件开发的整个生命周期内的每个环节的过程、活动进行管理。课程讲授CMMI模型的理论知识，和软件需求工程课程一起，在实践中让学生了解如何进行过程管理。

            </p>

            <p>

                 <a class="btn" href="courseInfo.php?name=软件工程管理">查看详情 »</a>

            </p>

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


    <div class="modal fade" id="modal-container-login" role="dialog" aria-labelledby="modify-block" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="modify-block">用户登录</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" role="form">
              <div class="form-group">
                <label class="col-sm-2 control-label">账号</label>
                <div class="col-sm-8"><input type="text" class="form-control" id="lgn-username" /></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">密码</label>
                <div class="col-sm-8"><input type="password" class="form-control" id="lgn-password"/></div>
              </div> 
              <div class="form-group" id="lgn-type">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                  <label><input type="radio" name="hwtype" id="lgn-type-stu" checked/>学生</label>&nbsp;&nbsp;&nbsp;
                  <label><input type="radio" name="hwtype" id="lgn-type-tea"/>教师</label>&nbsp;&nbsp;&nbsp;
                  <label><input type="radio" name="hwtype" id="lgn-type-ta"/>助教</label>&nbsp;&nbsp;&nbsp;
                </div>
              </div>
              <div class="form-group" id="lgn-type">
                <label class="col-sm-10 pull-right" id="lgn-ret"></label>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <a type="button" class="btn btn-danger" href="common/passwordModify/find_pwd.html">忘记密码</a>
            <button type="button" class="btn btn-primary" id="delete_btn" onclick="lgn_btn()">登录</button>
          </div>
        </div>
      </div>
    </div>



</body>

</html>


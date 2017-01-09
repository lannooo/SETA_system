<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>教学辅助平台</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="container">
		<div class="row clearfix">
			<div class="col-md-12 column">
				<div class="jumbotron">
					<h1>
						管理员，请登录！
					</h1>
					<p>
						浙江大学软件工程课程教学辅助平台
					</p>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-4 column">
			</div>
			<div class="col-md-4 column">
				<form method="post" action="admin_login.php" role="form">
					<div class="form-group"><label>用户名</label><input type="text" class="form-control" name="username" /></div>
					<div class="form-group"><label>密码</label><input type="password" class="form-control" name="password" /></div>
					<div class="form-group"><button type="submit" class="btn btn-default">登陆</button></div>
				</form>
			</div>
			<div class="col-md-4 column">
			</div>
		</div>
	</div>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

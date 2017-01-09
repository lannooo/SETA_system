<?php
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");
	
	// need params
	$param = "mid";
	if (!isset($_GET["mid"]))
		response(1, "缺少参数：" . $param);
	$mid = $_GET["mid"];

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasMaterial($mysqli, $tid, $mid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		TAHasMaterial($mysqli, $taid, $mid);
	}

	try {
		$prepared_sql = "SELECT url, name, type FROM material WHERE mid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('i', $mid);
			$stmt->store_result();
			$stmt->bind_result($url, $name, $type);
			$stmt->execute();
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(549, "数据库查询错误");
	}

	if (strcasecmp($type, "FOLDER") == 0 || strcasecmp($type, "ROOT") == 0)
		response(311, "无法下载整个目录");

	$filename = "../../upload/material/".$url;
	if (!file_exists($filename))
		die("Error when creating file, maybe no file selected...");
	$file = fopen($filename, "r");
	Header("Content-type: application/octet-stream");
	Header("Accpet-Ranges: bytes");
	Header("Accept-Length: " . filesize($filename));
	Header("Content-Disposition: attachment; filename=".$name);
	$buffer = 2048;
	while (!feof($file)) {
		$file_data=fread($file, $buffer);
		echo $file_data;
	}
	fclose($file);
?>
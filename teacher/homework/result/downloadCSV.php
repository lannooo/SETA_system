<?php
	include "../../teacher.php";


	// need login and permission
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// // need params
	$param = "hid";
	if (!isset($_GET["hid"]))
		response(1, "缺少参数：" . $param);
	$hid = $_GET["hid"];
	
	// check privilege
	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasHomework($mysqli, $tid, $hid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		needPermission("p_hw_review");
		TAHasHomework($mysqli, $taid, $hid);
	}

	$prepared_sql = "SELECT DISTINCT sid FROM attend NATURAL JOIN homework WHERE hid = ?";
	if ($stmt = $mysqli->prepare($prepared_sql)) {
		$stmt->bind_param('i', $hid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sid);
	}
	else
		die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
	$prepared_sql_2 = "SELECT name FROM student_info WHERE sid = ?";
	if ($stmt_2 = $mysqli->prepare($prepared_sql_2)) {
		$stmt_2->bind_param('i', $sid);
		$stmt_2->store_result();
		$stmt_2->bind_result($name);
	}
	else
		die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);

	$filename = getRandomString(8).".csv";
	if (file_exists($filename)) {
		unlink($filename);
	}
	$fp = fopen($filename, "a");
	$str = implode(",", array("学号", "姓名", "成绩", "评语"))."\r\n";
	fwrite($fp, iconv("UTF-8", "GB2312", $str));
	while ($stmt->fetch()) {
		$stmt_2->execute();
		$stmt_2->fetch();
		$str = implode(",", array($sid, $name, "", ""))."\r\n";
		// echo $str;
		fwrite($fp, iconv("UTF-8", "GB2312", $str));
	}
	fclose($fp);
	// }

	if (!file_exists($filename))
		die("Error when creating file, maybe no file selected...");
	$file = fopen($filename, "r");
	Header("Content-type: application/octet-stream");
	Header("Accpet-Ranges: bytes");
	Header("Accept-Length: " . filesize($filename));
	Header("Content-Disposition: attachment; filename=template.csv");
	$buffer = 2048;
	while (!feof($file)) {
		$file_data=fread($file, $buffer);
		echo $file_data;
	}
	fclose($file);
	unlink($filename);
?>

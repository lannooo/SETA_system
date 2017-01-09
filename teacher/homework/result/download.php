<?php
	include "../../teacher.php";


	// need login and permission
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// // need params
	// $params = array("hid", "type", "rids");
	// if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
	// 	response(512, "need post data in json");
	// }
	// $obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	// requiredParams($params, $obj);
	// $hid = (int)($obj->hid);

	$obj = json_decode("{}");
	$param = "hid";
	if (!isset($_GET["hid"]))
		response(1, "缺少参数：" . $param);
	$param = 'type';
	if (!isset($_GET[$param]))
		response(1, "缺少参数：" . $param);
	$param = 'rids';
	if (!isset($_GET[$param]))
		response(1, "缺少参数：" . $param);
	$obj->hid = $_GET["hid"];
	$obj->type = $_GET["type"];
	$obj->rids = $_GET["rids"];
	$hid = (int)($obj->hid);

	// check privilege
	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasHomework($mysqli, $tid, $obj->hid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		needPermission("p_hw_review");
		TAHasHomework($mysqli, $taid, $hid);
	}

	// get hw type
	// A: all homework
	// U: unchecked homework
	// S: select from rids
	// O: one file
	try {
		$prepared_sql = "SELECT type FROM homework WHERE hid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $hid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($hw_type);
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	}
	catch (Exception $e) {
		response(524, "数据库查询错误");
	}

	if (strcasecmp($hw_type, "O") == 0) {
		response(302, "在线作业不提供下载");
	}
	
	
	// get urls
	$datalist = array();
	$filenames = array();
	if (strcasecmp($obj->type, "A") == 0)
		$prepared_sql = "SELECT url FROM hw_result WHERE hid = ?";
	else if (strcasecmp($obj->type, "U") == 0)
		$prepared_sql = "SELECT url FROM hw_result WHERE hid = ? AND ifcorrected = 0";
	else // S or O
		$prepared_sql = "SELECT url FROM hw_result WHERE hid = ? AND rid = ?";
	if (!($stmt = $mysqli->prepare($prepared_sql)))
		die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);

	$stmt->bind_result($url);
	if (strcasecmp($obj->type, "A") == 0 || strcasecmp($obj->type, "U") == 0) {
		$stmt->bind_param("i", $hid);
		$stmt->execute();

		$stmt->store_result();
		while ($stmt->fetch()) {
			array_push($datalist, "../../../upload/hw_stu/".$url);
			array_push($filenames, iconv('utf-8','gb2312',$url));
			// $url = new String();
		}
	}
	else {
		//S or O
		$stmt->bind_param("ii", $hid, $rid);
		foreach ($obj->rids as $rid) {
			$url = "";
			$stmt->execute();
			$stmt->fetch();
			if (strcasecmp($url, "") != 0) {
				array_push($datalist, "../../../upload/hw_stu/".$url);
				array_push($filenames, iconv('utf-8','gb2312',$url));
			}
		}
	}

	$filename = getRandomString();
	if (file_exists($filename)) {
		unlink($filename);
	}
	$zip = new ZipArchive();
	if ($zip->open($filename, ZIPARCHIVE::CREATE) != TRUE)
		die("Unable to create file");

	$count = 0;
	foreach ($datalist as $val) {
		// $val = iconv('utf-8','gb2312',$val);
		if (file_exists($val)) {
			$file_info_arr= pathinfo($val);
			$zip->addFile($val, $filenames[$count]);
		}
		$count++;
	}
	$zip->close();
	// }

	if (!file_exists($filename))
		die("Error when creating file, maybe no file selected...");
	$file = fopen($filename, "r");
	Header("Content-type: application/octet-stream");
	Header("Accpet-Ranges: bytes");
	Header("Accept-Length: " . filesize($filename));
	Header("Content-Disposition: attachment; filename=download.zip");
	$buffer = 2048;
	while (!feof($file)) {
		$file_data=fread($file, $buffer);
		echo $file_data;
	}
	fclose($file);
	unlink($filename);
?>

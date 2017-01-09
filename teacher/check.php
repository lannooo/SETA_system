<?php
	include "teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("hid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$hid = $obj->hid;

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasHomework($mysqli, $tid, $hid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		TAHasHomework($mysqli, $taid, $hid);
	}

	// get course name
	$roughData = json_decode("{}");
	try {
		$prepared_sql = "SELECT coid, clid FROM class NATURAL JOIN homework WHERE hid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $hid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($roughData->coid, $roughData->clid);
			$stmt->fetch();
			$stmt->close();
		} else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}

		$prepared_sql = "SELECT coname FROM course WHERE coid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $roughData->coid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($roughData->coname);
			$stmt->fetch();
			$stmt->close();
		} else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(550, "数据库查询错误");
	}

	response(0, "ok", $roughData);	
?>
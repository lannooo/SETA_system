<?php
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("father", "coid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$fa_mid = $obj->father;

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherInCourse($mysqli, $tid, $obj->coid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		TAInCourse($mysqli, $taid, $obj->coid);
	}

	// sql search get root
	try {
		$prepared_sql = "SELECT mid FROM material WHERE type = 'ROOT' AND coid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('i', $obj->coid);
			$stmt->bind_result($root_mid);
			$stmt->execute();
			$stmt->close();
			if ($fa_mid == -1 || is_null($fa_mid))
				$fa_mid = $root_mid;
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(535, "数据库查询错误");
	}

	// get list of fa_mid
	$roughData = json_decode("{}");
	$roughData->info = array();
	try {
		$o = json_decode("{}");
		$prepared_sql = "SELECT mid, type, size, download, name, uploadtime
		FROM material WHERE father = ? AND coid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('ii', $fa_mid, $obj->coid);
			$stmt->store_result();
			$stmt->bind_result($o->mid, $o->type, $o->size, $o->download, $o->name, $o->upload_time);
			$stmt->execute();
			while ($stmt->fetch()) {
				array_push($roughData->info, $o);
				$roughData = json_decode(json_encode($roughData, JSON_UNESCAPED_UNICODE));
			};
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(536, "数据库查询错误");
	}

	response(0, "ok", $roughData);
?>
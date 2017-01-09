<?php
	include "../../teacher/teacher.php";

	if (!hasLogin("teacher")) {
		response(412, "没有登录的教师");
	}
	$uid = getSession("userID");

	// need params
	$params = array("clid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	$tid = $uid;
	teacherInClass($mysqli, $tid, $obj->clid);

	$listData = json_decode("{}");
	$listData->info = array();
	try {
		$prepared_sql = "SELECT taid, username FROM ta_assist WHERE clid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('i', $obj->clid);
			$stmt->execute();
			$stmt->bind_result($o->taid, $o->username);
			$stmt->store_result();
			while ($stmt->fetch()) {
				array_push($listData->info, $o);
				$listData = json_decode(json_encode($listData, JSON_UNESCAPED_UNICODE));
			}
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(547, "数据库查询错误");
	}

	response(0, "ok", $listData);
?>
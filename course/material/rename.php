<?php
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");
	
	// need params
	$params = array("mid", "new_name");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$mid = $obj->mid;

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasMaterial($mysqli, $tid, $mid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		needPermission("p_ma_modify");
		TAHasMaterial($mysqli, $taid, $mid);
	}

	// sql update material name
	try {
		$prepared_sql = "UPDATE material SET name = ? WHERE mid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('si', $obj->new_name, $mid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(534, "数据库操作错误");
	}
	
	response();
?>
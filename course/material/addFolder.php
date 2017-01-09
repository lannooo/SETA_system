<?php
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");
	
	// need params
	$params = array("mid", "folder_name", "coid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$mid = $obj->mid;
	$coid = $obj->coid;

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasMaterial($mysqli, $tid, $mid);
		teacherInCourse($mysqli, $tid, $coid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		needPermission("p_ma_modify");
		TAInCourse($mysqli, $tid, $coid);
		TAHasMaterial($mysqli, $taid, $mid);
	}

	try {
		$prepared_sql = "INSERT INTO material(mid, father, type, size, url, coid, name, download, uploadtime, tid) VALUES(NULL, ?, ?, 0, NULL, ?, ?, 0, ?, ?)";
		$folder_str = "FOLDER";
		$uploadtime = date('Y-m-d H:i:s',time());
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('isissi', $mid, $folder_str, $coid, $obj->folder_name, $uploadtime, $tid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(532, "数据库操作错误");
	}
	
	response();
?>
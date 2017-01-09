<?php
	include "teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");
	
	if (hasLogin("teacher")) {
		$tid = $uid;
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
	}

	// need params
	// no need

	$detailData = json_decode("{}");
	$detailData->courses = array();
	// return name, teacher/TA, courses(course_id, course_name, course_info)), 
	if (hasLogin("teacher")) {
		$detailData->type = "teacher";
		try {
			$prepared_sql = "SELECT name FROM teacher WHERE tid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("i", $tid);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($detailData->name);
				$stmt->fetch();
				$stmt->close();
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		} catch (Exception $e) {
			response(515, "数据库查询错误");
		}
	} else {
		$detailData->type = "TA";
		try {
			$prepared_sql = "SELECT name FROM ta_info WHERE taid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("i", $taid);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($detailData->name);
				$stmt->fetch();
				$stmt->close();
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			} 
		} catch (Exception $e) {
			response(516, "数据库查询错误");
		}
	}

	if (hasLogin("teacher")) {
		$prepared_sql = "SELECT DISTINCT coid, coname, college, cotype, plan FROM course NATURAL JOIN class WHERE tid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $tid);
		} else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} else {
		$prepared_sql = "SELECT DISTINCT coid, coname, college, cotype, plan FROM course NATURAL JOIN class NATURAL JOIN ta_assist WHERE taid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $taid);
		} else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	}
	try {
		$obj = json_decode("{}");
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result(
			$obj->coid, $obj->coname, $obj->college, $obj->cotype, $obj->plan);
		while ($stmt->fetch()) {
			array_push($detailData->courses, $obj);
			$detailData = json_decode(json_encode($detailData, JSON_UNESCAPED_UNICODE));
		};
		$stmt->close();
	} catch (Exception $e) {
		response(517, "数据库查询错误");
	}

	response(0, "ok", $detailData);
?>

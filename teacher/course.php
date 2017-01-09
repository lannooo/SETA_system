<?php
// 	courseInfo，classes


	include "teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("coid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$coid = $obj->coid;

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherInCourse($mysqli, $tid, $obj->coid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		TAInCourse($mysqli, $taid, $obj->coid);
	}

	$roughData = json_decode("{}");
	$roughData->course_info = json_decode("{}");

	$prepared_sql = "SELECT 
		coid, coname, textbook, cocode, cotype, semster, coname_en, 
		college, credit, week_learn_time, weight, pre_learning, plan, 
		background, assessment, project_info 
		FROM course NATURAL JOIN class WHERE tid = ? AND coid = ?";
	if ($stmt = $mysqli->prepare($prepared_sql)) {
		$stmt->bind_param("ii", $tid, $obj->coid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result(
			$coid,
			$roughData->course_info->coname,
			$roughData->course_info->textbook,
			$roughData->course_info->cocode,
			$roughData->course_info->cotype,
			$roughData->course_info->semster,
			$roughData->course_info->coname_en,
			$roughData->course_info->college,
			$roughData->course_info->credit,
			$roughData->course_info->week_learn_time,
			$roughData->course_info->weight,
			$roughData->course_info->pre_learning,
			$roughData->course_info->plan,
			$roughData->course_info->background,
			$roughData->course_info->assessment,
			$roughData->course_info->project_info
		);
		$stmt->fetch();
		$stmt->close();
	}
	else {
		die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
	}

	// classInfo
	$roughData->classes = array();
	$o = json_decode("{}");
	if (hasLogin("teacher"))
		$prepared_sql = "SELECT clid, cltime, place, student_num FROM class WHERE tid = ? AND coid = ?";
	else
		$prepared_sql = "SELECT clid, cltime, place, student_num FROM class NATURAL JOIN ta_assist WHERE taid = ? AND coid = ?";
	if ($stmt = $mysqli->prepare($prepared_sql)) {
		if (hasLogin("teacher"))
			$stmt->bind_param("ii", $tid, $obj->coid);
		else
			$stmt->bind_param("ii", $taid, $obj->coid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($o->clid, $o->cltime, $o->place, $o->student_num);
		while ($stmt->fetch()) {
			array_push($roughData->classes, $o);
			$roughData = json_decode(json_encode($roughData, JSON_UNESCAPED_UNICODE));
		}
		$stmt->close();
	}
	else {
		die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
	}

	// material statics
	// $roughData->material_statics = json_decode("{}");
	// $prepared_sql = "SELECT total, t_word_usage, t_video_usage, t_audio_usage, t_other_usage
	// 	FROM material_statics WHERE tid = ? AND coid = ?";
	// if ($stmt = $mysqli->prepare($prepared_sql)) {
	// 	$stmt->bind_param("ii", $_SESSION["current"]["tid"], $obj->coid);
	// 	$stmt->execute();
	// 	$stmt->store_result();
	// 	$stmt->bind_result(
	// 		$roughData->material_statics->total,
	// 		$roughData->material_statics->t_word_usage,
	// 		$roughData->material_statics->t_video_usage,
	// 		$roughData->material_statics->t_audio_usage,
	// 		$roughData->material_statics->t_other_usage
	// 	);
	// 	$stmt->fetch();
	// 	$stmt->close();
	// }
	// else {
	// 	die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
	// }

	function getMid($mysqli, $tid, $obj) {
		$root_str = "ROOT";
		$prepared_sql = "SELECT mid FROM material WHERE tid = ? AND coid = ? AND type = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("iis", $tid, $obj->coid, $root_str);
			$stmt->execute();
			$stmt->store_result();
			$num = $stmt->num_rows;
			$stmt->bind_result($root_mid_tmp);
			$stmt->fetch();
			$stmt->close();
			if ($num == 0)
				return -1;
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
		return $root_mid_tmp;
	}

	$root_mid = getMid($mysqli, $tid, $obj);
	if ($root_mid == -1) {
		$root_str = "ROOT";
		$root_name = "根目录";
		$uploadtime = date('Y-m-d H:i:s',time());
		$prepared_sql = "INSERT INTO material(mid, father, type, name, size, uploadtime, url, tid, coid, download) VALUES(NULL, NULL, ?, ?, 0, ?, NULL, ?, ?, 0)";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("sssii", $root_str, $root_name, $uploadtime, $tid, $obj->coid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
		$root_mid = getMid($mysqli, $tid, $obj);
		if ($root_mid == -1)
			response(200, "无法获取课程资料的根目录");
	}
	$roughData->root_mid = $root_mid;
	response(0, "ok", $roughData);
?>

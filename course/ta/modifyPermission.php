<?php
	include "../../teacher/teacher.php";
	
	if (!hasLogin("teacher")) {
		response(412, "没有登录的教师");
	}
	$uid = getSession("userID");

	// need params
	$params = array(
		"p_ma_upload", "p_ma_modify", "p_ma_delete", "p_hw_deliver", 
		"p_hw_modify", "p_hw_review", "p_BBS_reply", "p_nt_deliver",
		"p_nt_modify", "p_nt_delete", "p_ta_info", "clid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	$tid = $uid;
	teacherInClass($mysqli, $tid, $obj->clid);

	// modify database
	try {
		$prepared_sql = "UPDATE ta_permission SET 
			p_ma_upload = ?, p_ma_modify = ?, p_ma_delete = ?, p_hw_deliver = ?, p_hw_modify = ?, p_hw_review = ?, 
			p_BBS_reply = ?, p_nt_deliver = ?, p_nt_modify = ?, p_nt_delete = ?, p_ta_info = ? WHERE clid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('iiiiiiiiiiii', 
				$obj->p_ma_upload, $obj->p_ma_modify, $obj->p_ma_delete, $obj->p_hw_deliver, $obj->p_hw_modify, $obj->p_hw_review,
				$obj->p_BBS_reply, $obj->p_nt_deliver, $obj->p_nt_modify, $obj->p_nt_delete, $obj->p_ta_info, $obj->clid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(545, "数据库操作错误");
	}

	response();
?>
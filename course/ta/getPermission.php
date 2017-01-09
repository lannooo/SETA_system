<?php
	include "../../teacher/teacher.php";
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("clid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherInClass($mysqli, $tid, $obj->clid);
	} else {
		$taid = $uid;
		TAInClass($mysqli, $taid, $obj->clid);
	}

	// test or add to database
	$GLOBALS["detailData"] = json_decode("{}");
	function get_permission($mysqli, $clid) {
		try {
			$detailData = $GLOBALS["detailData"];
			$prepared_sql = "SELECT p_ma_upload, p_ma_modify, p_ma_delete, p_hw_deliver, p_hw_modify, 
						p_hw_review, p_BBS_reply, p_nt_deliver, p_nt_modify, p_nt_delete, p_ta_info FROM ta_permission WHERE clid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param('i', $clid);
				$stmt->execute();
				$stmt->bind_result(
					$detailData->p_ma_upload, $detailData->p_ma_modify, $detailData->p_ma_delete,
					$detailData->p_hw_deliver, $detailData->p_hw_modify, $detailData->p_hw_review,
					$detailData->p_BBS_reply, $detailData->p_nt_deliver, $detailData->p_nt_modify,
					$detailData->p_nt_delete, $detailData->p_ta_info
				);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				return $num;
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		} catch (Exception $e) {
			response(542, "数据库查询错误");
		}
	}

	$ret = get_permission($mysqli, $obj->clid);
	if ($ret == 0) {
		$prepared_sql = "INSERT INTO ta_permission VALUES(?, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0)";
		$stmt = $mysqli->prepare($prepared_sql);
		$stmt->bind_param('i', $obj->clid);
		$stmt->execute();
		$stmt->close();
		$ret = get_permission($mysqli, $obj->clid);
		if ($ret == 0)
			response(309, "获取助教权限失败");
	}

	response(0, "ok", $GLOBALS["detailData"]);
?>
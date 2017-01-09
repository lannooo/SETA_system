<?php
	include "../../teacher/teacher.php";
	if (!hasLogin("TA")) {
		response(413, "没有登录的助教");
	}
	$uid = getSession("userID");
	$taid = $uid;

	// test or add to database
	$GLOBALS["detailData"] = json_decode("{}");
	try {
		$detailData = $GLOBALS["detailData"];
		$prepared_sql = "SELECT p_ma_upload, p_ma_modify, p_ma_delete, p_hw_deliver, p_hw_modify, 
					p_hw_review, p_BBS_reply, p_nt_deliver, p_nt_modify, p_nt_delete, p_ta_info 
					FROM ta_permission NATURAL JOIN ta_assist WHERE taid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('i', $taid);
			$stmt->execute();
			$stmt->bind_result(
				$detailData->p_ma_upload, $detailData->p_ma_modify, $detailData->p_ma_delete,
				$detailData->p_hw_deliver, $detailData->p_hw_modify, $detailData->p_hw_review,
				$detailData->p_BBS_reply, $detailData->p_nt_deliver, $detailData->p_nt_modify,
				$detailData->p_nt_delete, $detailData->p_ta_info
			);
			$stmt->store_result();
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(548, "数据库查询错误");
	}

	response(0, "ok", $GLOBALS["detailData"]);
?>
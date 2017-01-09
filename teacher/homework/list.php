<?php
//  显示课程内的作业列表 

	include "../teacher.php";

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
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		TAInClass($mysqli, $taid, $obj->clid);
	}

	$nowtime = date('Y-m-d H:i:s',time());

	// get rough data from database
	$roughData = json_decode("{}");
	$roughData->info = array();
	try {
		$o = json_decode("{}");
		$prepared_sql = "SELECT hid, type, name, end_t, hard_ddl, begin_t, score_face, score_weight, finish_number FROM homework WHERE clid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $obj->clid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result(
				$o->hid, $o->type, $o->name, $o->end_t, $o->hard_ddl, $o->begin_t, 
				$o->score_face, $o->score_weight, $o->finish_number);
			while ($stmt->fetch()) {
				if (strcmp($nowtime, $o->begin_t) < 0)
					$o->timetype = 0;
				else if (strcmp($nowtime, $o->end_t) < 0)
					$o->timetype = 1;
				else if (strcmp($nowtime, $o->hard_ddl) < 0)
					$o->timetype = 2;
				else
					$o->timetype = 3;
				array_push($roughData->info, $o);
				$roughData = json_decode(json_encode($roughData, JSON_UNESCAPED_UNICODE));
			};
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(522, "数据库查询错误");
	}

	response(0, "ok", $roughData);
?>

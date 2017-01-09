<?php
	include "../../teacher/teacher.php";
	
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");


	// need params
	$params = array("anid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasAnnouncement($mysqli, $tid, $obj->anid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		TAHasAnnouncement($mysqli, $taid, $obj->anid);
	}

	try {
		$prepared_sql = "SELECT anid, adate, title, content FROM anouncement WHERE anid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('i', $obj->anid);
			$stmt->bind_result($anid, $adate, $title, $content);
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
		response(539, "数据库查询错误");
	}

	$detailData = array('anid' => $anid, 'adate' => $adate, 'title' => $title, 'content' => $content);
	response(0, "ok", $detailData);
?>
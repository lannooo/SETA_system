<?php

	include "../../teacher.php";


	// need login and permission
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("hid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$hid = (int)($obj->hid);


	// check privilege
	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasHomework($mysqli, $tid, $obj->hid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		needPermission("p_hw_review");
		TAHasHomework($mysqli, $taid, $hid);
	}


	// get rough data from database
	$roughData = json_decode("{}");
	$roughData->info = array();
	// get hw type and group info
	try {
		$prepared_sql = "SELECT type FROM homework WHERE hid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $hid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($hw_type);
			$stmt->fetch();
			$stmt->close();
			if (strcasecmp($hw_type, "G") == 0) {
				$search_group = "SELECT gid FROM attend WHERE sid = ? AND clid = ?";
				if ($stmt_group = $mysqli->prepare($search_group)) {
					$stmt_group->bind_param("ii", $sid, $obj->clid);
					$stmt_group->bind_result($gid);
				}
				else {
					die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
				}
				$group_detail = "SELECT gname, teamleader_id FROM study_group WHERE gid = ?";
				if ($stmt_detail = $mysqli->prepare($group_detail)) {
					$stmt_detail->bind_param("i", $gid);
					$stmt_detail->bind_result($gname, $teamleader);
				}
				else {
					die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
				}
			}

			$stu_detail = "SELECT name FROM student_info WHERE sid = ?";
			if ($stmt_stu = $mysqli->prepare($stu_detail)) {
				$stmt_stu->bind_param("i", $sid);
				$stmt_stu->bind_result($stu_name);
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(529, "数据库查询错误");
	}
	// get result
	try {
		$prepared_sql = "SELECT rid, sid, ifcorrected, type, uploadtime, score, comment FROM hw_result WHERE hid = ?";
		$o = json_decode("{}");
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $hid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($o->rid, $o->sid, $o->checked, $o->type, $o->uploadtime, $o->score, $o->comment);
			while ($stmt->fetch()) {
				// echo "" . $o->rid . "\t" . $o->sid . "\t" . $o->checked . "\t" . $o->type . "\n";
				$sid = $o->sid;
				$stmt_stu->execute();
				$stmt_stu->fetch();
				$o->name = $stu_name;
				// echo $stu_name . "\n";
				if (strcasecmp($o->type, "G") == 0) {
					// group homework
					if (!isset($stmt_group))
						response(305, "不存在的课程小组");
					else {
						$stmt_group->execute();
						$stmt_group->fetch();
						$stmt_detail->execute();
						$stmt_detail->fetch();
						if ($teamleader != $sid)
							response(306, "获取课程小组失败");
						$o->gname = $gname;
						$o->gid = $gid;
					}
				}
				array_push($roughData->info, $o);
				$roughData = json_decode(json_encode($roughData, JSON_UNESCAPED_UNICODE));
				// $o = json_decode("{}");
			};
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(530, "数据库查询错误");
	}

	response(0, "ok", $roughData);
?>
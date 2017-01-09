<?php

	include "../../teacher.php";


	// need login and permission
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");


	// need params
	$params = array("rid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$rid = (int)($obj->rid);
	
	// get hid, hurl, rurl from database
	try {
		$prepared_sql = "SELECT hid, url, score, ifcorrected, comment, type 
		FROM hw_result WHERE rid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $rid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($hid, $rurl, $total_score, $ifchecked, $comment, $rtype);
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
		$prepared_sql = "SELECT url FROM homework WHERE hid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $hid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($hurl);
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(528, "数据库查询错误");
	}
	
	// check privilege
	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasHomework($mysqli, $tid, $hid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		needPermission("p_hw_review");
		TAHasHomework($mysqli, $taid, $hid);
	}

	// get homework detail from XML
	$detailData = json_decode("{}");
	$detailData->score = $total_score;
	$detailData->ifchecked = $ifchecked;
	$detailData->comment = $comment;
	try {
		$homeworkXML = simplexml_load_file("../../../upload/hw_t/" . $hurl . ".XML");
		$attr = $homeworkXML->attributes();
		$detailData->homework = array();
		foreach ($homeworkXML->question as $question) {
			$obj = json_decode("{}");
			$attr = $question->attributes();
			$obj->qid = (string)$attr["qid"];
			$obj->score = (string)$attr["score"];
			$obj->type = (string)$attr["type"];
			$obj->name = (string)$question->name;
			$obj->describe = (string)$question->describe;
			if ($obj->type == 0) {
				foreach ($question->choice as $choice) {
					$obj->{"choice".(string)($choice->attributes()["cid"])} = (string)$choice;
				}
			}
			if ($obj->type == 0 || $obj->type == 1) {
				$obj->answer = (string)$question->answer;
			}
			array_push($detailData->homework, $obj);
		}
	} catch (Exception $e) {
		response(604, "XML读取错误");
	}

	if ($rtype != "O")
		response(0, "ok", $detailData);

	// get answer detail from XML
	try {
		$answerXML = simplexml_load_file("../../../upload/hw_stu/" . $rurl);
		foreach ($answerXML->answer as $answer) {
			$attr = $answer->attributes();
			$qid = (string)$attr["qid"];
			if (isset($answer->score))
				$score = (int)$answer->score;
			else
				$score = -1;
			$content = (string)$answer->content;
			for ($i = 0; $i < count($detailData->homework); $i++) {
				if ($detailData->homework[$i]->qid == $qid) {
					$detailData->homework[$i]->content = $content;
					$detailData->homework[$i]->re_score = $score;
					break;
				}
			}
		}
	} catch (Exception $e) {
		response(605, "XML读取错误");
	}

	response(0, "ok", $detailData);
?>

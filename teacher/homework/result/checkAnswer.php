<?php

	include "../../teacher.php";


	// need login and permission
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");


	// need params
	$params = array("rid", "scores", "comment");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(512, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$rid = $obj->rid;

	// get hid, hurl, type, rurl from database
	try {
		$prepared_sql = "SELECT hid, type, url FROM hw_result WHERE rid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $rid);
			$stmt->bind_result($hid, $hw_type, $rurl);
			$stmt->execute();
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
		$prepared_sql = "SELECT url FROM homework WHERE hid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $hid);
			$stmt->bind_result($hurl);
			$stmt->execute();
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(525, "数据库查询错误");
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

	// check scores
	if (strcasecmp($hw_type, "O") !=  0) {
		if (count($obj->scores) != 1 || $obj->scores[0]->score > 100)
			response(303, "离线作业的分数不能大于100分");
		try {
			$prepared_sql = "UPDATE hw_result 
			SET ifcorrected = 1, score = ?, comment = ? WHERE rid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("isi", $obj->scores[0]->score, $obj->comment, $rid);
				$stmt->execute();
				$stmt->close();
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		} catch (Exception $e) {
			response(526, "数据库操作错误");
		}
		response();
	}

	// get homework detail from XML
	$total_score = 0;
	for ($i = 0; $i < count($obj->scores); $i++)
		$total_score += $obj->scores[$i]->score;

	$detailData = json_decode("{}");
	try {
		$homeworkXML = simplexml_load_file("../../../upload/hw_t/" . $hurl . ".XML");
		$attr = $homeworkXML->attributes();
		$detailData->homework = array();
		foreach ($homeworkXML->question as $question) {
			$o = json_decode("{}");
			$attr = $question->attributes();
			$o->qid = (string)$attr["qid"];
			$o->score = (string)$attr["score"];
			$o->type = (string)$attr["type"];
			if ($o->type == 0 || $o->type == 1) {
				$o->answer = (string)$question->answer;
			}
			array_push($detailData->homework, $o);
			// echo json_encode($detailData). "!!!";
		}
	} catch (Exception $e) {
		response(602, "XML读取错误");
	}

	try {
		$answerXML = simplexml_load_file("../../../upload/hw_stu/" . $rurl);
		foreach ($answerXML->answer as $answer) {
			// echo ">>> ";
			$attr = $answer->attributes();
			$qid = (string)$attr["qid"];
			$content = (string)$answer->content;
			if (isset($answer->score))
				$score = (int)$answer->score;
			else
				$score = 0;
			for ($i = 0; $i < count($obj->scores); $i++)
				if ($obj->scores[$i]->qid == $qid) {
					$score = $obj->scores[$i]->score;
					break;
				}
			for ($i = 0; $i < count($detailData->homework); $i++)
				if ($detailData->homework[$i]->qid == $qid) {
					if ($detailData->homework[$i]->score < $score)
						response(304, "批改分数高于设定的题目分数", array('qid' => $qid));
				}
			$answer->score = $score;
			// echo $answer->score;
		}
		$answerXML->saveXML("../../../upload/hw_stu/" . $rurl);
	} catch (Exception $e) {
		response(603, "XML读取错误");
	}

	// update database
	try {
		$prepared_sql = "UPDATE hw_result 
		SET ifcorrected = 1, score = ?, comment = ? WHERE rid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("isi", $total_score, $obj->comment, $rid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(527, "数据库操作错误");
	}

	response();
?>
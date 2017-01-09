<?php

// 读取编号15的作业的详细信息
// {
// 	"hid": 15
// }

	include "../teacher.php";

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

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasHomework($mysqli, $tid, $obj->hid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		TAHasHomework($mysqli, $taid, $obj->hid);
	}

	// get homework from database
	try {
		$detailData = json_decode("{}");
		$prepared_sql = "SELECT type, name, end_t, hard_ddl, begin_t, score_face, score_weight, finish_number, url FROM homework WHERE hid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $obj->hid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result(
				$detailData->type, $detailData->name, $detailData->end_t, 
				$detailData->hard_ddl, $detailData->begin_t, $detailData->score_face, 
				$detailData->score_weight, $detailData->finish_number, $url);
			$stmt->fetch();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(519, "数据库查询错误");
	}


	// get detail from XML
	try {
		$homeworkXML = simplexml_load_file("../../upload/hw_t/" . $url . ".XML");
		$attr = $homeworkXML->attributes();
		$detailData->homework = array();
		foreach ($homeworkXML->question as $question) {
			$o = json_decode("{}");
			$attr = $question->attributes();
			$o->qid = (string)$attr["qid"];
			$o->score = (string)$attr["score"];
			$o->type = (string)$attr["type"];
			$o->name = (string)$question->name;
			$o->describe = (string)$question->describe;
			if ($o->type == 0) {
				foreach ($question->choice as $choice) {
					$o->{"choice".(string)($choice->attributes()["cid"])} = (string)$choice;
				}
			}
			if ($o->type == 0 || $o->type == 1) {
				$o->answer = (string)$question->answer;
			}
			array_push($detailData->homework, $o);
		}
	} catch (Exception $e) {
		response(601, "XML读取错误");
	}

	response(0, "ok", $detailData);
?>

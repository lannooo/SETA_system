<?php

// 把编号15的homework进行修改
// {
// 	"hid": 15,
// 	"type": "F",
// 	"name": "第8周（小组）作业",
// 	"end_t": "2016-11-11 23:59:59",
// 	"begin_t": "2016-11-10 00:00:00",
// 	"hard_ddl": "2016-12-01 23:59:59",
// 	"score_face": 10,
// 	"score_weight": 5,
// 	"homework": [
// 		{"qid": 1, "type": 4, "score": 100, "name": "这是一道离线题嘻嘻嘻", "describe": "阅读课程资源里的week5.docx，写心得体会。"}
// 	]
// }

	include "../teacher.php";


	// need login and permission
	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");


	// need params
	$params = array("type", "name", "end_t", "hard_ddl", "begin_t", "score_face", "score_weight", "homework", "hid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$obj->score_weight = (double)$obj->score_weight;
	$obj->url = getRandomString();

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasHomework($mysqli, $tid, $obj->hid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		needPermission("p_hw_modify");
		TAHasHomework($mysqli, $taid, $obj->hid);
	}

	// generate homework
	$homeworkXML = new SimpleXMLElement(
		"<?xml version=\"1.0\" encoding=\"utf-8\"?><homework></homework>");
	$homeworkXML->addAttribute("version", "1.0");

	if (strcasecmp($obj->type, "F") == 0)
		if (count($obj->homework) != 1 || $obj->homework[0]->type != 4)
			response(301, "离线作业数据格式错误");
	for ($i = 0; $i < count($obj->homework); $i++) {
		$questionType = $obj->homework[$i]->type;
		$subChild = $homeworkXML->addChild("question", "");
		$subChild->addAttribute("qid", "".$obj->homework[$i]->qid);
		$subChild->addAttribute("score", "".$obj->homework[$i]->score);
		$subChild->addAttribute("type", "".$questionType);
		$subChild->addChild("name", $obj->homework[$i]->name);
		$subChild->addChild("describe", $obj->homework[$i]->describe);
		if ($questionType == 0) {
			$choice = $subChild->addChild("choice", $obj->homework[$i]->choiceA);
			$choice->addAttribute("cid", "A");
			$choice = $subChild->addChild("choice", $obj->homework[$i]->choiceB);
			$choice->addAttribute("cid", "B");
			$choice = $subChild->addChild("choice", $obj->homework[$i]->choiceC);
			$choice->addAttribute("cid", "C");
			$choice = $subChild->addChild("choice", $obj->homework[$i]->choiceD);
			$choice->addAttribute("cid", "D");
		}
		if ($questionType == 0 || $questionType == 1) {
			$subChild->addChild("answer", $obj->homework[$i]->answer);
		}
	}
	$homeworkXML->saveXML("../../upload/hw_t/" . $obj->url . ".XML");


	// add to database
	try {
		$prepared_sql = "UPDATE homework SET type = ?, name = ?, end_t = ?, hard_ddl = ?, begin_t = ?, punish_weight = ?,
			score_face = ?, score_weight = ?, finish_number = ?, url = ? WHERE hid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('sssssdidisi', 
				$obj->type, $obj->name, $obj->end_t, $obj->hard_ddl, $obj->begin_t, 
				$obj->punish_weight, $obj->score_face, $obj->score_weight, 
				$obj->finish_number, $obj->url, $obj->hid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(523, "数据库操作错误");
	}

	response();
?>

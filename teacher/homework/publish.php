<?php

// 发布homework
// {
// 	"type": "O",
// 	"name": "第7周作业",
// 	"end_t": "2016-11-11 23:59:59",
// 	"begin_t": "2016-11-10 00:00:00",
// 	"hard_ddl": "2016-12-01 23:59:59",
// 	"score_face": 10,
// 	"score_weight": 5,
// 	"homework": [
// 		{"qid": 1, "type": 2, "score": 20, "name": "填空题1", "describe": "天苍苍，野茫茫，{block}。"},
// 		{"qid": 2, "type": 0, "score": 20, "name": "选择题1", "describe": "下列说法正确的是：", "choiceA": "1+1=2", "choiceB": "1+1=3", "choiceC": "1+1=4", "choiceD": "1+1=5", "answer": "A"},
// 		{"qid": 3, "type": 1, "score": 20, "name": "判断题1", "describe": "Java是男生", "answer": "F"},
// 		{"qid": 4, "type": 3, "score": 20, "name": "问答题1", "describe": "你的理想是什么？"},
// 		{"qid": 5, "type": 0, "score": 20, "name": "选择题2", "describe": "国庆节放几天？", "choiceA": "2 Days", "choiceB": "3 Days", "choiceC": "5 Days", "choiceD": "7 Days", "answer": "C"}
// 	]
// }

	include "../teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");


	// need params
	$params = array("clid", "type", "name", "end_t", "hard_ddl", "begin_t", "score_face", "score_weight", "homework");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);
	$obj->finish_number = 0;
	$obj->url = getRandomString();
	$obj->score_weight = (double)$obj->score_weight;
	$obj->hid = 0;

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherInClass($mysqli, $tid, $obj->clid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $taid);
		needPermission("p_hw_deliver");
		TAInClass($mysqli, $taid, $obj->clid);
	}

	// generate homework
	$homeworkXML = new SimpleXMLElement(
		"<?xml version=\"1.0\" encoding=\"utf-8\"?><homework></homework>");
	$homeworkXML->addAttribute("version", "1.0");

	// 'F': offline; 'O': online; 'G': group
	if (strcasecmp($obj->type, "F") == 0 || strcasecmp($obj->type, "G") == 0)
		if (count($obj->homework) != 1 || $obj->homework[0]->type != 4)
			response(300, "离线作业数据格式错误");
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
		$prepared_sql = "INSERT INTO homework(clid, type, name, end_t, hard_ddl, begin_t, punish_weight, score_face, score_weight, finish_number, url, hid) VALUES(?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, NULL)";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('isssssidis', 
				$obj->clid, $obj->type, $obj->name, $obj->end_t, 
				$obj->hard_ddl, $obj->begin_t, $obj->score_face,
				$obj->score_weight, $obj->finish_number, $obj->url);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(521, "数据库操作错误");
	}

	response();
?>

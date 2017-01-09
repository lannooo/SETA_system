<?php
	include "base.php";
	// header('Content-type: text/json');
	function TAHasHomework($mysqli, $taid, $hid) {
		try {			
			$prepared_sql = "SELECT hid FROM homework NATURAL JOIN class NATURAL JOIN ta_assist WHERE taid = ? AND hid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $taid, $hid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(14, "课程作业不属于此助教");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(514, "数据库查询错误");
		}
	}
	function teacherHasHomework($mysqli, $tid, $hid) {
		try {			
			$prepared_sql = "SELECT hid FROM homework NATURAL JOIN class WHERE tid = ? AND hid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $tid, $hid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(13, "课程作业不属于此教师");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(513, "数据库查询错误");
		}
	}
	function TAHasAnnouncement($mysqli, $tid, $anid) {
		try {			
			$prepared_sql = "SELECT anid FROM anouncement NATURAL JOIN course NATURAL JOIN class NATURAL JOIN ta_assist WHERE tid = ? AND anid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $tid, $anid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(12, "课程通知不属于此助教");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(512, "数据库查询错误");
		}
	}
	function teacherHasAnnouncement($mysqli, $tid, $anid) {
		try {			
			$prepared_sql = "SELECT anid FROM anouncement WHERE tid = ? AND anid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $tid, $anid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(11, "课程通知不属于此教师");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(511, "数据库查询错误");
		}
	}
	function TAHasMaterial($mysqli, $taid, $mid) {
		try {			
			$prepared_sql = "SELECT mid FROM ta_assist NATURAL JOIN class NATURAL JOIN course NATURAL JOIN material WHERE taid = ? AND mid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $taid, $mid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(10, "课程资料不属于此助教");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(510, "数据库查询错误");
		}
	}
	function teacherHasMaterial($mysqli, $tid, $mid) {
		try {			
			$prepared_sql = "SELECT mid FROM material NATURAL JOIN course NATURAL JOIN class WHERE tid = ? AND mid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $tid, $mid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(9, "课程资料不属于此教师");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(509, "数据库查询错误");
		}
	}
	function TAInCourse($mysqli, $taid, $coid) {
		try {
			$prepared_sql = "SELECT taid FROM ta_assist NATURAL JOIN class WHERE taid = ? AND coid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $taid, $coid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(8, "助教不属于此课程");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(508, "数据库查询错误");
		}
	}
	function TAInClass($mysqli, $taid, $clid) {
		try {			
			$prepared_sql = "SELECT taid FROM ta_assist WHERE taid = ? AND clid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $taid, $clid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($tmp == false)
					response(7, "助教不属于此班级");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(507, "数据库查询错误");
		}
	}
	function teacherHasTA($mysqli, $tid, $taid) {
		try {			
			$prepared_sql = "SELECT tid FROM ta_assist NATURAL JOIN class WHERE tid = ? AND taid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $tid, $taid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(6, "教师没有这个助教");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(506, "数据库查询错误");
		}
	}
	function teacherInCourse($mysqli, $tid, $coid) {
		try {
			$prepared_sql = "SELECT coid FROM class WHERE tid = ? AND coid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $tid, $coid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(5, "教师不属于此课程");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(505, "数据库查询错误");
		}
	}
	function teacherInClass($mysqli, $tid, $clid) {
		try {
			$prepared_sql = "SELECT clid FROM class WHERE tid = ? AND clid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param("ii", $tid, $clid);
				$stmt->execute();
				$stmt->bind_result($tmp);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(4, "教师不属于此班级");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		}
		catch (Exception $e) {
			response(504, "数据库查询错误");
		}
	}
	function getTidFromTA($mysqli, $taid) {
		try {
			$prepared_sql = "SELECT tid FROM class JOIN ta_assist WHERE taid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param('i', $taid);
				$stmt->execute();
				$stmt->bind_result($tmptid);
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->fetch();
				$stmt->close();
				if ($num == 0)
					response(3, "没有绑定教师的野生助教");
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		} catch (Exception $e) {
			response(503, "数据库查询错误");
		}
		return $tmptid;
	}
	function response($code=0, $msg="ok", $body="") {
		exit(json_encode(array("code" => $code, "msg" => $msg, "body" => $body), JSON_UNESCAPED_UNICODE));
	}
	function requiredParams($params, $obj) {
		foreach ($params as $key => $param) {
			if (!isset($obj->{$param})) {
				response(1, "缺少参数：" . $param);
			}
		}
	}
	function getPermission($taid, $param) {
		try {
			$mysqli = $GLOBALS["mysqli"];
			$prepared_sql = "SELECT ".$param." FROM ta_assist NATURAL JOIN ta_permission WHERE taid = ?";
			if ($stmt = $mysqli->prepare($prepared_sql)) {
				$stmt->bind_param('i', $taid);
				$stmt->bind_result($permission);
				$stmt->execute();
				$stmt->fetch();
				$stmt->close();
				return $permission;
			}
			else {
				die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			}
		} catch (Exception $e) {
			response(501, "数据库查询错误");
		}
		return 0;
	}
	function needPermission($param) {
		$taid = getSession("userID");
		if (getPermission($taid, $param) == 0)
			response(2, "助教没有此操作的权限");
	}
	function getRandomString($length=32) {
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$str = null;
		$max = strlen($strPol)-1;
		for ($i = 0; $i < $length; $i++) {
			$str .= $strPol[rand(0,$max)];
		}
		return $str;
	}
?>
<?php
	class FileUpload {
		public $path = "../../upload/material";
		public $allowtype = array('jpg', 'gif', 'png');
		public $maxsize = 102400000;
		public $israndname = true;

		public $originName; //源文件名
		public $tmpFileName; //临时文件名
		public $fileType; //文件类型(文件后缀)
		public $fileSize; //文件大小
		public $newFileName; //新文件名
		public $errorNum = 0; //错误号
		public $errorMess = ""; //错误报告消息
		public $type = "";

		/**
		 * 用于设置成员属性（$path, $allowtype,$maxsize, $israndname）
		 * 可以通过连贯操作一次设置多个属性值
		 *@param  string $key  成员属性名(不区分大小写)
		 *@param  mixed  $val  为成员属性设置的值
		 *@return  object     返回自己对象$this，可以用于连贯操作
		 */
		function set($key, $val) {
			$key = strtolower($key);
			if (array_key_exists($key, get_class_vars(get_class($this)))) {
				$this->setOption($key, $val);
			}
			return $this;
		}

		/**
		 * 调用该方法上传文件
		 * @param  string $fileFile  上传文件的表单名称
		 * @return bool        如果上传成功返回数true
		 */

		function upload($fileField) {
			$return = true;
			/* 检查文件路径是滞合法 */
			if (!$this->checkFilePath()) {
				$this->errorMess = $this->getError();
				return false;
			}
			/* 将文件上传的信息取出赋给变量 */
			$name = $_FILES[$fileField]['name'];
			$tmp_name = $_FILES[$fileField]['tmp_name'];
			$size = $_FILES[$fileField]['size'];
			$error = $_FILES[$fileField]['error'];
			if (is_Array($name)) {
				$errors = array();
				for ($i = 0; $i < count($name); $i++) {
					if ($this->setFiles($name[$i], $tmp_name[$i], $size[$i], $error[$i])) {
						if (!$this->checkFileSize() || !$this->checkFileType()) {
							$errors[] = $this->getError();
							$return = false;
						}
					} else {
						$errors[] = $this->getError();
						$return = false;
					}
					if (!$return)
						$this->setFiles();
				}

				if ($return) {
					$fileNames = array();
					for ($i = 0; $i < count($name); $i++) {
						if ($this->setFiles($name[$i], $tmp_name[$i], $size[$i], $error[$i])) {
							$this->setNewFileName();
							if (!$this->copyFile()) {
								$errors[] = $this->getError();
								$return = false;
							}
							$fileNames[] = $this->newFileName;
						}
					}
					$this->newFileName = $fileNames;
				}
				$this->errorMess = $errors;
				return $return;
			} else {
				if ($this->setFiles($name, $tmp_name, $size, $error))
					if ($this->checkFileSize() && $this->checkFileType()) {
						$this->setNewFileName();
						if ($this->copyFile())
							return true;
					}
				$this->errorMess = $this->getError();
				return false;
			}
		}

		public function getFileName() {
			return $this->newFileName;
		}

		/**
		 * 上传失败后，调用该方法则返回，上传出错信息
		 * @param  void   没有参数
		 * @return string  返回上传文件出错的信息报告，如果是多文件上传返回数组
		 */
		public function getErrorMsg() {
			return $this->errorMess;
		}

		/* 设置上传出错信息 */
		private function getError() {
			$str = "上传文件<font color='red'>{$this->originName}</font>时出错 : ";
			switch ($this->errorNum) {
			case 4:
				$str .= "没有文件被上传";
				break;
			case 3:
				$str .= "文件只有部分被上传";
				break;
			case 2:
				$str .= "上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值";
				break;
			case 1:
				$str .= "上传的文件超过了php.ini中upload_max_filesize选项限制的值";
				break;
			case -1:
				$str .= "未允许类型";
				break;
			case -2:
				$str .= "文件过大,上传的文件不能超过{$this->maxsize}个字节";
				break;
			case -3:
				$str .= "上传失败";
				break;
			case -4:
				$str .= "建立存放上传文件目录失败，请重新指定上传目录";
				break;
			case -5:
				$str .= "必须指定上传文件的路径";
				break;
			default:
				$str .= "未知错误";
			}
			return $str.'<br>';
		}

		/* 设置和$_FILES有关的内容 */
		private function setFiles($name = "", $tmp_name = "", $size = 0, $error = 0) {
			$this->setOption('errorNum', $error);
			if ($error)
				return false;
			$this->setOption('originName', $name);
			$this->setOption('tmpFileName', $tmp_name);
			$aryStr = explode(".", $name);
			$this->setOption('fileType', strtolower($aryStr[count($aryStr) - 1]));
			$this->setOption('type', 'OTHER');
			$this->setOption('fileSize', $size);
			return true;
		}

		/* 为单个成员属性设置值 */
		private function setOption($key, $val) {
			$this->$key = $val;
		}

		/* 设置上传后的文件名称 */
		private function setNewFileName() {
			if ($this->israndname) {
				$this->setOption('newFileName', $this->proRandName());
			} else {
				$this->setOption('newFileName', $this->originName);
			}
		}

		/* 检查上传的W文件是否是合法的类型 */
		private function checkFileType() {
			return true;
		}

		/* 检查上传的文件是否是允许的大小 */
		private function checkFileSize() {
			if ($this->fileSize > $this->maxsize) {
				$this->setOption('errorNum', -2);
				return false;
			} else {
				return true;
			}
		}

		/* 检查是否有存放上传文件的目录 */
		private function checkFilePath() {
			if (empty($this->path)) {
				$this->setOption('errorNum', -5);
				return false;
			}
			if (!file_exists($this->path) || !is_writable($this->path)) {
				if (! @ mkdir($this->path, 0755)) {
					$this->setOption('errorNum', -4);
					return false;
				}
			}
			return true;
		}

		/* 设置随机文件名 */
		private function proRandName() {
			$fileName = getRandomString(32);
			return $fileName;
		}

		/* 复制上传文件到指定的位置 */
		private function copyFile() {
			if (!$this->errorNum) {
				$path = rtrim($this->path, '/').'/';
				$path .= $this->newFileName;
				$path = iconv('utf-8','gb2312',$path);
				if (@move_uploaded_file($this->tmpFileName, $path)) {
					return true;
				} else {
					$this->setOption('errorNum', -3);
					return false;
				}
			} else {
				return false;
			}
		}
	}

?>

<?php
	include "../../teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// exportCSV 也 比较特殊
	$param = 'hid';
	if (!isset($_POST[$param]))
		response(1, "缺少参数：" . $param);
	$hid = $_POST["hid"];

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

	$up = new fileupload;
	$up -> set("path", "../../..//upload/csv/");
	$up -> set("maxsize", 102400000);
	$up -> set("israndname", true);
	if($up -> upload("file"))
		$final_filename = $up->getFileName()[0];
	else 
		response(307, "上传CSV文件失败");

	$type = "O";
	$prepared_sql = "UPDATE hw_result SET score = ?, comment = ?, ifcorrected = 1 WHERE hid = ? AND sid = ? AND type <> ?";
	if ($stmt = $mysqli->prepare($prepared_sql))
		$stmt->bind_param('isiis', $score, $comment, $hid, $sid, $type);
	else
		die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);

	$csvurl = "../../../upload/csv/".$final_filename;
	$handle = fopen($csvurl, "r");
	fgetcsv($handle);
	while ($data = fgetcsv($handle)) {
		$sid = iconv('gb2312','utf-8', $data[0]);
		$score = iconv('gb2312','utf-8', $data[2]);
		$comment = iconv('gb2312','utf-8', $data[3]);
		if ($score == null)
			continue;
	    $stmt->execute();
    }
	fclose($handle);
	unlink($csvurl);

	response();
?>

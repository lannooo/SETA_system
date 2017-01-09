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
			if (strcasecmp($this->fileType, "wav") == 0 ||
				strcasecmp($this->fileType, "ape") == 0 ||
				strcasecmp($this->fileType, "mac") == 0 ||
				strcasecmp($this->fileType, "flac") == 0 ||
				strcasecmp($this->fileType, "mp3") == 0 ||
				strcasecmp($this->fileType, "raw") == 0 ||
				strcasecmp($this->fileType, "m4a") == 0 ||
				strcasecmp($this->fileType, "ogg") == 0 ||
				strcasecmp($this->fileType, "mid") == 0 ||
				strcasecmp($this->fileType, "amr") == 0 ||
				strcasecmp($this->fileType, "wma") == 0 ||
				strcasecmp($this->fileType, "voc") == 0 ||
				strcasecmp($this->fileType, "aif") == 0)
				$this->setOption('type', 'AUDIO');
			else if (strcasecmp($this->fileType, "mpg") == 0 ||
				strcasecmp($this->fileType, "mpeg") == 0 ||
				strcasecmp($this->fileType, "mp4") == 0 ||
				strcasecmp($this->fileType, "avi") == 0 ||
				strcasecmp($this->fileType, "rm") == 0 ||
				strcasecmp($this->fileType, "rmvb") == 0 ||
				strcasecmp($this->fileType, "wmv") == 0 ||
				strcasecmp($this->fileType, "wav") == 0 ||
				strcasecmp($this->fileType, "mov") == 0 ||
				strcasecmp($this->fileType, "swf") == 0 ||
				strcasecmp($this->fileType, "flv") == 0 ||
				strcasecmp($this->fileType, "3gp") == 0)
				$this->setOption('type', 'VIDEO');
			else if (strcasecmp($this->fileType, "exe") == 0 ||
				strcasecmp($this->fileType, "bat") == 0 ||
				strcasecmp($this->fileType, "com") == 0 ||
				strcasecmp($this->fileType, "apk") == 0 ||
				strcasecmp($this->fileType, "dll") == 0 ||
				strcasecmp($this->fileType, "sys") == 0 ||
				strcasecmp($this->fileType, "jar") == 0 ||
				strcasecmp($this->fileType, "ipa") == 0 ||
				strcasecmp($this->fileType, "sh") == 0 ||
				strcasecmp($this->fileType, "msi") == 0)
				$this->setOption('type', 'EXE');
			else if (strcasecmp($this->fileType, "rar") == 0 ||
				strcasecmp($this->fileType, "zip") == 0 ||
				strcasecmp($this->fileType, "tar") == 0 ||
				strcasecmp($this->fileType, "gz") == 0 ||
				strcasecmp($this->fileType, "7z") == 0 ||
				strcasecmp($this->fileType, "tgz") == 0 ||
				strcasecmp($this->fileType, "cab") == 0 ||
				strcasecmp($this->fileType, "iso") == 0 ||
				strcasecmp($this->fileType, "arc") == 0 ||
				strcasecmp($this->fileType, "gzip") == 0 ||
				strcasecmp($this->fileType, "bz2") == 0 ||
				strcasecmp($this->fileType, "ace") == 0 ||
				strcasecmp($this->fileType, "gzip") == 0)
				$this->setOption('type', 'RAR');
			else if (strcasecmp($this->fileType, "jpg") == 0 ||
				strcasecmp($this->fileType, "jpeg") == 0 ||
				strcasecmp($this->fileType, "bmp") == 0 ||
				strcasecmp($this->fileType, "gif") == 0 ||
				strcasecmp($this->fileType, "png") == 0 ||
				strcasecmp($this->fileType, "jpe") == 0 ||
				strcasecmp($this->fileType, "jfif") == 0 ||
				strcasecmp($this->fileType, "tif") == 0 ||
				strcasecmp($this->fileType, "tiff") == 0 ||
				strcasecmp($this->fileType, "ico") == 0 ||
				strcasecmp($this->fileType, "icon") == 0 ||
				strcasecmp($this->fileType, "svg") == 0 ||
				strcasecmp($this->fileType, "eps") == 0 ||
				strcasecmp($this->fileType, "pns") == 0 ||
				strcasecmp($this->fileType, "psd") == 0)
				$this->setOption('type', 'PICTURE');
			else if (strcasecmp($this->fileType, "doc") == 0 ||
				strcasecmp($this->fileType, "docx") == 0 ||
				strcasecmp($this->fileType, "ppt") == 0 ||
				strcasecmp($this->fileType, "pptx") == 0 ||
				strcasecmp($this->fileType, "pps") == 0 ||
				strcasecmp($this->fileType, "xls") == 0 ||
				strcasecmp($this->fileType, "xlsx") == 0 ||
				strcasecmp($this->fileType, "csv") == 0 ||
				strcasecmp($this->fileType, "txt") == 0 ||
				strcasecmp($this->fileType, "rtf") == 0 ||
				strcasecmp($this->fileType, "pdf") == 0 ||
				strcasecmp($this->fileType, "caj") == 0 ||
				strcasecmp($this->fileType, "md") == 0)
				$this->setOption('type', 'DOCUMENT');
			else
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
			if (in_array(strtolower($this->fileType), $this->allowtype)) {
				return true;
			} else {
				$this->setOption('errorNum', -1);
				return false;
			}
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
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// upload 比较特殊
	// echo "111   ";
	// echo $_POST["coid"];
	$param = 'mid';
	if (!isset($_POST[$param]))
		response(1, "缺少参数：" . $param);
	$param = 'coid';
	if (!isset($_POST[$param]))
		response(1, "缺少参数：" . $param);
	$fa_mid = $_POST["mid"];
	$coid = $_POST["coid"];

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasMaterial($mysqli, $tid, $fa_mid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		needPermission("p_ma_upload");
		TAHasMaterial($mysqli, $taid, $fa_mid);
	}

	$up = new fileupload;
	$up -> set("path", "../../upload/material/");
	$up -> set("maxsize", 102400000);
	$up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
	$up -> set("israndname", true);
	if($up -> upload("file"))
		$final_filename = $up->getFileName()[0];
	else 
		response(308, "上传课程资料失败");

	// sql insert
	try {
		$prepared_sql = "INSERT INTO material(mid, father, type, size, url, tid, coid, name, download, uploadtime) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, 0, ?)";
		$uploadtime = date('Y-m-d H:i:s',time());
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('isisiiss',
				$fa_mid,
				$up->type, 
				$up->fileSize,
				$final_filename,
				$tid,
				$coid,
				$up->originName,
				$uploadtime);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(533, "数据库操作错误");
	}

	response();
?>

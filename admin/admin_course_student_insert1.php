<meta charset="utf-8">
<?php
    session_start();
    $coid=$_SESSION["coid"];
    $clid=$_SESSION["clid"];
    
    $dbhost="localhost";
    $dbuser="sem";
    $dbpassword="sem2016";
    $dbname="software_eng";

	if ($_FILES["file"]["error"]>0)
	{
		echo "Error: ".$_FILES["file"]["error"]."<br/>;window.location.href='admin_course_detail.php?id1=$coid';</script>";
	}

    $mysqli=new mysqli($dbhost,$dbuser,$dbpassword,$dbname);

    if(mysqli_connect_errno()){
        echo mysqli_connect_error();
        die;
    }

    $excelpath=$_FILES["file"]["tmp_name"];
    if(!$excelpath){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_course_detail.php?id1=$coid';</script>";
    }

    $mysqli->query("set names UTF-8");

    require_once './PHPExcel_1.8.0_doc/Classes/PHPExcel.php';
    require_once './PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php';
    require_once './PHPExcel_1.8.0_doc/Classes/PHPExcel/Reader/Excel2007.php';

    $objReader = PHPExcel_IOFactory::createReader('excel2007');
    
    $objPHPExcel = $objReader->load($excelpath); 
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();

    for($j=2;$j<=$highestRow;$j++){ 
        $str="";
        for($k='A';$k<=$highestColumn;$k++){ 
            $str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'|*|';
        } 
        $str=mb_convert_encoding($str,'UTF-8','auto');
        $strs = explode("|*|",$str);
        
        $sql="select sid from student where username='{$strs[0]}'";

        $result=$mysqli->query($sql);

        if(!$result){
            echo "<script language='javascript'>alert('Error!');window.location.href='admin_course_detail.php?id1=$coid';</script>";
        }
        else{
            if($result->num_rows>0){
                while($row=$result->fetch_array()){
                    $sql="select * from attend where sid='{$row[0]}' and clid='{$clid}'";
                    $result1=$mysqli->query($sql);

                    if(!$result1){
                        echo "<script language='javascript'>alert('Error!');window.location.href='admin_course_detail.php?id1=$coid';</script>";
                    }
                    else{
                        if($result1->num_rows>0){
                            break;
                        }
                    }
                    
                    $sql="insert into attend values ('{$row[0]}','{$clid}',null)";

                    $result1=$mysqli->query($sql);

                    if(!$result1){
                        echo "<script language='javascript'>alert('Error!');window.location.href='admin_course_detail.php?id1=$coid';</script>";
                    }
                }
            }
            else{
                continue;
            }
        }
    }
    $result->free();
    $mysqli->close();

    echo "<script language='javascript'>alert('添加学生成功');window.location.href='admin_course_detail.php?id1=$coid';</script>";
?>
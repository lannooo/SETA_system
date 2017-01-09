<meta charset="utf-8">
<?php
    $dbhost="localhost";
    $dbuser="sem";
    $dbpassword="sem2016";
    $dbname="software_eng";

    if ($_FILES["file"]["error"]>0)
    {
        echo "Error: ".$_FILES["file"]["error"]."<br/>;window.location.href='adminstudent.php?id1=$coid';</script>";
    }

    $excelpath=$_FILES["file"]["tmp_name"];
    if(!$excelpath){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php?id1=$coid';</script>";
    }

    $mysqli=new mysqli($dbhost,$dbuser,$dbpassword,$dbname);

    if(mysqli_connect_errno()){
        echo mysqli_connect_error();
        die;
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
        
        $sql="select * from student where username='{$strs[0]}'";

        $result=$mysqli->query($sql);

        if(!$result){
            echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
        }
        else{
            if($result->num_rows>0){
                continue;
            }
        }

        $sql="insert into student (sid,username,password,verified_email,verified_phone) values (null,'{$strs[0]}','{$strs[0]}',0,0)";

        $result=$mysqli->query($sql);

        if(!$result){
            echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
        }
        else{
            $sql="select sid from student where username='{$strs[0]}'";

            $result=$mysqli->query($sql);

            if(!$result){
                echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
            }
            else{
                if($result->num_rows>0){
                    while($row=$result->fetch_array()){
                        $sql="insert into student_info (sid,name,gender,college,major) values ({$row[0]},'{$strs[1]}','X','未填写','未填写')";

                        $result1=$mysqli->query($sql);

                        if(!$result1){
                            echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
                        }
                    }
                }
            }
        }
    }
    $result->free();
    $mysqli->close();

    echo "<script language='javascript'>alert('添加学生成功');window.location.href='admin_student.php';</script>";
?>
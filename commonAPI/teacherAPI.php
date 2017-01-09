<?php
include 'database.php';

class teacherInfo{
    public $tid="";
    public $name="";
    public $gender="";
    public $phone="";
    public $email="";
    public $college="";
    public $department="";
    public $position="";
    public $education="";
    public $direction="";
    public $past_evaluation="";
    public $desc_achive="";
    public $desc_teach_type="";
    public $desc_publish="";
    public $desc_honor="";
    public $desc_more="";

    function __construct($tid,$name,$gender,$phone,$email,$college,$department,
                        $position,$education,$direction,$past_evaluation,$desc_achive,
                        $desc_teach_type,$desc_publish,$desc_honor,$desc_more){
        $this->tid=$tid;
        $this->name=$name;
        $this->gender=$gender;
        $this->phone=$phone;
        $this->email=$email;
        $this->college=$college;
        $this->department=$department;
        $this->position=$position;
        $this->education=$education;
        $this->direction=$direction;
        $this->past_evaluation=$past_evaluation;
        $this->desc_achive;
        $this->desc_teach_type=$desc_teach_type;
        $this->desc_publish=$desc_publish;
        $this->desc_honor=$desc_honor;
        $this->desc_more=$desc_more;
    }
}


function get_teacher_info_by($entry,$entry_name){
    $con = get_connect();

    $sql='select * from teacher where '.$entry_name.'="'.$entry.'"';
    $teachers=array();
    if($result=mysqli_query($con, $sql)){
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            if($row['gender']=='M')
                $row['gender']="男";
            else
                $row['gender']="女";

            $oneTeacher = new teacherInfo($row['tid'],$row['name'],$row['gender'],$row['phone'],$row['email'],
                                          $row['college'],$row['department'],$row['position'],$row['education'],
                                          $row['direction'],$row['past_evaluation'],$row['desc_achive'],
                                          $row['desc_teach_type'],$row['desc_publish'],$row['desc_honor'],$row['desc_more']);
            $teachers[$i] = $oneTeacher;
            $i=$i+1;
        }
    }
    return $teachers;
}

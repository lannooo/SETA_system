<?php
include 'database.php';

class courseInfo{
    public $coid="";
    public $coname="";
    public $textbook="";
    public $cocode="";
    public $cotype="";
    public $semester="";
    public $coname_en="";
    public $college="";
    public $credit="";
    public $week_learn_time="";
    public $weight="";
    public $pre_learning="";
    public $plan="";
    public $background="";
    public $assessment="";
    public $project_info="";

    function __construct($coid,$coname,$textbook,$cocode,$cotype,$semester,
                        $coname_en,$college,$credit,$week_learn_time,$weight,
                        $pre_learning,$plan,$background,$assessment,$project_info){
        $this->coid=$coid;
        $this->coname=$coname;
        $this->textbook=$textbook;
        $this->cocode=$cocode;
        $this->cotype=$cotype;
        $this->semester=$semester;
        $this->coname_en=$coname_en;
        $this->college=$college;
        $this->credit=$credit;
        $this->week_learn_time=$week_learn_time;
        $this->weight=$weight;
        $this->pre_learning=$pre_learning;
        $this->plan=$plan;
        $this->background=$background;
        $this->assessment=$assessment;
        $this->project_info=$project_info;
    }
}

function get_course_info_by_name($coname){
    $con = get_connect();

    $sql='select * from course where coname like "%'.$coname.'%"';
    $courses=array();
    if($result=mysqli_query($con, $sql)){
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $oneCourse = new courseInfo($row['coid'],$row['coname'],$row['textbook'],$row['cocode'],
                                        $row['cotype'],$row['semster'],$row['coname_en'],$row['college'],
                                        $row['credit'],$row['week_learn_time'],$row['weight'],$row['pre_learning'],
                                        $row['plan'],$row['background'],$row['assessment'],$row['project_info']);
            $courses[$i] = $oneCourse;
            $i=$i+1;
        }
    }
    return $courses;
}

function get_course_info_by($entry, $entry_name){
    $con = get_connect();

    $sql='select * from course where '.$entry_namer.'="'.$entry.'"';
    $courses=array();
    if($result=mysqli_query($con, $sql)){
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $oneCourse = new courseInfo($row['coid'],$row['coname'],$row['textbook'],$row['cocode'],
                                        $row['cotype'],$row['semester'],$row['coname_en'],$row['college'],
                                        $row['credit'],$row['week_learn_time'],$row['weight'],$row['pre_learning'],
                                        $row['plan'],$row['background'],$row['assessment'],$row['project_info']);
            $courses[$i] = $oneCourse;
            $i=$i+1;
        }
    }
    return $courses;
}

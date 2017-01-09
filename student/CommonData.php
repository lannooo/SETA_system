<?php
$servername = "localhost";
$username = "sem";
$password = "sem2016";
$dbname = "software_eng";

class course{
    public $coid;
    public $coname;
    public $textbook;
    public $cocode;
    public $cotype;
    public $semster;
    public $coname_en;
    public $college;
    public $credit;
    public $week_learn_time;
    public $weight;
    public $pre_learning;
    public $plan;
    public $background;
    public $assessment;
    public $project_info;
}

class course_class extends course{
    public $clid;
    public $tid;
    public $cltime;
    public $place;
    public $student_num;
    public $tname;
}

class teacher_info{
    public $tid;
    public $name;
    public $gender;
    public $phone;
    public $email;
    public $college;
    public $department;
    public $position;
    public $education;
    public $direction;
    public $past_evaluation;
    public $desc_achieve;
    public $desc_teach_type;
    public $desc_publish;
    public $desc_honor;
    public $desc_more;
}

class announcement{
    public $anid;
    public $tid;
    public $coid;
    public $adate;
    public $title;
    public $content;
    public $type;
    public $read_count;
}
class student_homework{
    public $hid;
    public $clid;
    public $type;
    public $name;
    public $end_t;
    public $hard_ddl;
    public $begin_t;
    public $punish_weight;
    public $score_face;
    public $score_weight;
    public $finish_number;
    public $url;
    public $result;
    public $ismember;
    function __construct() {
        $this->ismember = 0;
    }
}

class student_homework_result{
    public $rid;
    public $hid;
    public $sid;
    public $type;
    public $uploadtime;
    public $ifcorrected;
    public $score;
    public $comment;
    public $url;
}

function get_utf8_string($content) {    //  将一些字符转化成utf8格式
    $encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
    return  mb_convert_encoding($content, 'GBK', $encoding);
}

class homework_question{
    public $qid;
    public $score;
    public $name;
    public $describe;
    public $type;  //'O' -> offline 'F' -> online 'G' -> group
    //'0' -> choice '1' -> judge '2' -> fill '3' -> essay
    public $answer;
    public $ans_score;
    function __construct() {
        $this->answer = null;
        $this->ans_score = null;
    }
}

class homework_online{
    public $number_choice;
    public $number_judge;
    public $number_completion;
    public $number_essay;
    function __construct() {
        $this->number_choice = 0;
        $this->number_judge = 0;
        $this->number_completion = 0;
        $this->number_essay = 0;
    }
}

class homework_choice extends homework_question {
    public $choice_A;
    public $choice_B;
    public $choice_C;
    public $choice_D;
}

class homework_number{
    public $coname;
    public $clid;
    public $total;
    public $unfinished;
    public $notcorrected;
    public $corrected;
    function __construct() {
        $this->corrected = 0;
        $this->notcorrected = 0;
        $this->unfinished = 0;
        $this->total = 0;
    }
}

class group{
    public $gid;
    public $clid;
    public $gname;
    public $teamleader_id;
    public $leader_username;
    public $leader_name;
    public $count;
}
class group_member{
    public $sid;
    public $username;
    public $type;  //leader | member
    public $name;
    public $gender;
    public $phone;
    public $email;
    public $college;
    public $major;
}
class class_group{
    public $clid;
    public $coname;
    public $group;
    function __construct() {
        $this->group = null;
    }
}

class material{
    public $mid;
    public $father;
    public $type;
    public $name;
    public $size;
    public $uploadtime;
    public $url;
    public $tid;
    public $coid;
    public $download;
}

class path{
    public $name;
    public $mid;
}

?>
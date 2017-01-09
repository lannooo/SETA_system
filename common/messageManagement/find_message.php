 <?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/23
 * Time: 下午3:48
 */
require_once "connect.php";

session_start();

 $conn_2 = new mysqli($severname, $usrname, $password, $dbname);

 $raw=file_get_contents('php://input');
 $json=json_decode($raw);

 $id=$_SESSION['userID'];
 $userType=$_SESSION['userType'];
 $type=$json->type;

 if($type=="send"){
     $sql="SELECT mid, toid, totype, mdate, title, ifread FROM message WHERE fromid=?";
 }
 else if($type=="receive"){
     $sql="SELECT mid, fromid, fromtype, mdate, title, ifread FROM message WHERE toid=?";
 }

 if ($stmt = $conn->prepare($sql)) {
 $stmt->bind_param('i',$id);
 $stmt->bind_result($mid,$tfid,$tftype,$mdate,$title,$ifread);
 $stmt->execute();
} else {echo "Error!!!";}

 $num=1;
 $returnResult='[';

 if($stmt->fetch()){
     $nameQuery="";
     if($tftype==0){
         $nameQuery="SELECT name FROM student_info WHERE sid=".$tfid;
     }
     else if($tftype==1){
         $nameQuery="SELECT name FROM teacher WHERE tid=".$tfid;
     }
     else{
         $nameQuery="SELECT name FROM ta_info WHERE taid=".$tfid;
     }
     $result=$conn_2->query($nameQuery);
     $row=$result->fetch_assoc();
     $name=$row['name'];
     $returnResult=$returnResult.'{"mid":'.$mid.',"number":'.$num.', "name":"'.$name.'", "title":"'.$title.'", "date":"'.$mdate.'", "ifread":'.$ifread.'}';
     $num+=1;
 }
 while($stmt->fetch()){
     $nameQuery="";
     if($tftype==0){
         $nameQuery="SELECT name FROM student_info WHERE sid=".$tfid;
     }
     else if($tftype==1){
         $nameQuery="SELECT name FROM teacher WHERE tid=".$tfid;
     }
     else{
         $nameQuery="SELECT name FROM ta_info WHERE taid=".$tfid;
     }

     $result=$conn_2->query($nameQuery);
     $row=$result->fetch_assoc();
     $name=$row['name'];
     $returnResult=$returnResult.',{"mid":'.$mid.',"number":'.$num.', "name":"'.$name.'", "title":"'.$title.'", "date":"'.$mdate.'", "ifread":'.$ifread.'}';
     $num+=1;
 }


 $returnResult=$returnResult.']';

 echo($returnResult);

 $conn->close();
 $conn_2->close();
 ?>
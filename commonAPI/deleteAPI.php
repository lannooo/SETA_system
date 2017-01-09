<?php

    function delete_post($connect, $post_type, $post_id, $uid, $utype){
        if($post_type=='group' && $utype=='student'){
            $sql_query = 'select sid from group_post where post_id='.$post_id;
            $sql_del = 'update group_post set frozon=1 where post_id='.$post_id;
            if($result = mysqli_query($connect, $sql_query)){
                if($row = mysqli_fetch_row($result)){
                    if($row[0] == $uid){
                        mysqli_query($connect, $sql_del);
                        return 1;
                    }
                }
            }
        }else if($post_type=='homework' && $utype=='teacher'){
            $sql_query = 'select tid from homework_post where post_id='.$post_id;
            $sql_del = 'update homework_post set frozon=1 where post_id='.$post_id;
            if($result = mysqli_query($connect, $sql_query)){
                if($row = mysqli_fetch_row($result)){
                    if($row[0] == $uid){
                        mysqli_query($connect, $sql_del);
                        return 1;
                    }
                }
            }
        }else if($utype=='admin'){
            $sql='select * from admin where aid='.$uid;
            if($result = mysqli_query($connect, $sql)){
                $sql_del='';
                if($post_type=='group'){
                    $sql_del = 'update group_post set frozon=1 where post_id='.$post_id;
                }else if($post_type=='homework'){
                    $sql_del = 'update homework_post set frozon=1 where post_id='.$post_id;
                }else{
                    return 0;
                }
                if($result_2 = mysqli_query($connect, $sql_del)){
                    return 1;
                }
            }
        }
        return 0;
    }

    function delete_reply($connect, $floor_type, $floor_id, $uid, $utype){
        $table = '';
        //确定表名
        if($floor_type=='group')
            $table = 'group_post_floor';
        else if($floor_type=='homework')
            $table = 'homework_post_floor';
        else
            return 0;
        $sql_del='delete from '.$table.' where floor_id='.$floor_id;
        //确定用户类型
        if($utype=='student'){
            $sql = 'select sid from '.$table.' where floor_id='.$floor_id;
            if($result = mysqli_query($connect, $sql)){
                if($row = mysqli_fetch_row($result)){
                    if($row[0]==$uid){
                        mysqli_query($connect, $sql_del);
                        return 1;
                    }
                }
            }
        }else if($utype=='teacher'){
            $sql = 'select tid from '.$table.' where floor_id='.$floor_id;
            if($result = mysqli_query($connect, $sql)){
                if($row = mysqli_fetch_row($result)){
                    if($row[0]==$uid){
                        mysqli_query($connect, $sql_del);
                        return 1;
                    }
                }
            }
        }else if($utype=='admin'){
            $sql='select * from admin where aid='.$uid;
            if($result = mysqli_query($connect, $sql)){
                 mysqli_query($connect, $sql_del);
                return 1;
            }
        }
        return 0;
    }


?>

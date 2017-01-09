/**
 * Created by luxuhui on 16/12/28.
 */
$(document).ready(function(){
    $("#logout").on("click",function(){
        var data={};
        data.action="logout";
        var url="logout.php"
        $.ajax({
            type: 'post',
            dataType: "json",
            data: JSON.stringify(data),
            url: url,
            contentType : "application/text",
            success: function (data) {
                if (data.code == 200) {
                    alert("退出成功！");
                    window.location.href="./common/login/login.html";
                } else if(data.code == 1){
                    alert("退出失败！");
                }
            },
            error: function (data) {
                alert("ajax传输失败！");
            }
        });
    })
})
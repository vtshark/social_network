var indnews=0;
var headnews='';
var idinterval=0;
var idinterval1=0;
var maxscroll=0;
//your-class
$(document).ready(function(){
$('.your-class').slick({
  dots: true,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear'
});
});

function showeditpanel(ind)
{
  var str;
  var el=$("#news"+ind);
 
    if ((indnews!=0)&&(indnews!=ind))  {
        hideeditpanel(indnews);
    }   
        //<button class='b3' title='Редактировать'><img src='/static/edit.png'></button>\
        var id=$("#idUserWall").val();
        str="<div id='news"+ind+"' class='hidden1 divleft'>\
        <form style='float:left;' action='/user/"+id+"/' method ='POST'>\
        <input name='inddelnews' class='hidden' value='"+ind+"'>\
        <button style='float:left;' type='submit' class='b3' title='Удалить'><img src='/static/delete.png'></button>\
        </form>\
        </div>";
        el.html(str);
        indnews=ind;
}
function hideeditpanel(ind)
{
    var el=$("#news"+ind);
    el.html("<div id='news"+ind+"' class='hidden'></div>");
}
function form_add_news()
{   var str='';
    var el=$("#headnews");
    
    if (!headnews) {
        headnews=el.html();
        var id=$("#idUserWall").val();
        str=str+headnews+"<div class='addnews'>\
                            <form action='/user/"+id+"/' method ='POST'>\
                                <textarea class='textnews' name='textnews' placeholder='текст сообщения'></textarea>\
                                <br/><input class='b1' type='submit' value='Сохранить'>\
                                &nbsp;<input id='closeAddNews' class='b2' type='button' value='Закрыть'>\
                            </form>\
                        </div>";
        el.html(str);
    }
}
function close_add_news() {
    $("#headnews").html(headnews);
    //document.getElementById('headnews').innerHTML=headnews;
    headnews='';
}
function getEvent(e) {
    if (!e) e = window.event;
    if (e.target.id=='addNews') form_add_news();
    if (e.target.id=='closeAddNews') close_add_news();
    if (e.target.className=='trwall clearleft') {
        showeditpanel(e.target.id);
    }
    if (e.target.id=='sendMsg') sendMsg();
    if (e.target.id=='exit') {
        clearInterval(idinterval);
        clearInterval(idinterval1);
        location="/exit/";
    }
}
function loadf() {
    //$("#dialog")
    if (document.getElementById("dialog"))  {
        refreshDialog(0);
        scrollToNewMsg();
        idinterval = setInterval('refreshDialog(1)', 5000);
    } else {
        console.log(idinterval);
        clearInterval(idinterval);
    }
    if (document.getElementById("menu"))  {
        checkNews();
        idinterval1=setInterval('checkNews()', 5000);
    }
    //clearInterval(idinterval);
}
function checkNews() {
	var url = "/checkNews/";
	$.get(url, function(data) {
	    //console.log(data);
	    //if (data!="") {
	       //$("#menuMsg").append(data);
	       //$("#menuMsg").html(data); 
	       document.getElementById("menuMsg").innerHTML=data;
	    //}
	});
}
function refreshDialog(prizn) {

	var url = "/newmsg/"+$("#idfriend").val()+"/"+prizn+"/";
	$.get(url, function(data) {
	    console.log("newmsg");
	    var dialog = $("#dialog");
	    var msgArr = JSON.parse(data);
	    if (msgArr.length>0) {
	        var i = 0;
	        var str = "";
	        var align = "";
            while (i < msgArr.length) {
                if (msgArr[i]['id_user'] == msgArr[i]['id_autor']) {
                    align = "align=left";
                } else {
                    align = "align=right";
                }
                str = str + "<div " +  align + " ><p><b>" + 
                        msgArr[i]['autor'] + "</b>&nbsp" + 
                        msgArr[i]['date'] + "</p></div>" +
                        "<div class='left'>" + msgArr[i]['text'] + "</div><br/>";
                i++;
            }
            dialog.append(str);
            scrollToNewMsg();
	    }
	    /*dialog.html=msgArr.length;
	    var newData = $(data).filter(".newMsg");
	    if(newData.length > 0) {
	        newData.appendTo(dialog);
            scrollToNewMsg();
	    }*/
	});
}

function scrollToNewMsg() {
    var dialog = $("#dialog");
    var top = dialog.scrollTop();
    if(top > maxscroll - 40) {
            dialog.scrollTop(9999);
            maxscroll = dialog.scrollTop();
        }
}

function sendMsg() {
    var idfriend=$("#idfriend").val();
    var newMsg=$("#newMsg").val();
    var url = "/saveMsg/";
    if (newMsg.length<1000) {
        $.post(url, { idfriend: idfriend, newMsg: newMsg },
            function(data) {
                $("#newMsg").val('');
                refreshDialog(1);
            });
    } else {
        alert("Сообщение не должно превышать 1000 символов!");
    }
    
}
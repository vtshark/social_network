var indnews=0;
var headnews='';
var idinterval;
var maxscroll=0;

function createXmlHttp() {
    var xmlHttp = false;
    try {
    xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
    try {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (e2) {
        xmlHttp = false;
    }
    }
    if (!xmlHttp && typeof XMLHttpRequest != 'undefined') {
        xmlHttp = new XMLHttpRequest();
    }
    return xmlHttp;
}    
////////////////////////////////////////////

function showeditpanel(ind)
{
 var str;
 var el=document.getElementById('news'+ind);
    if ((indnews!=0)&&(indnews!=ind))  {
        hideeditpanel(indnews);
    }   
        //<button class='b3' title='Редактировать'><img src='/static/edit.png'></button>\
        var id=document.getElementById('idUserWall').value;
        str="<div id='news"+ind+"' class='hidden1 divleft'>\
        <form style='float:left;' action='/user/?id="+id+"' method ='POST'>\
        <input name='inddelnews' class='hidden' value='"+ind+"'>\
        <button style='float:left;' type='submit' class='b3' title='Удалить'><img src='/static/delete.png'></button>\
        </form>\
        </div>";
        el.outerHTML =str;
        indnews=ind;
}
function hideeditpanel(ind)
{
    var el=document.getElementById('news'+ind);
    el.outerHTML ="<div id='news"+ind+"' class='hidden'></div>";
}
function form_add_news()
{   var str='';
    var el=document.getElementById('headnews');
    
    if (!headnews) {
        headnews=el.innerHTML;
        var id=document.getElementById('idUserWall').value;
        str=str+headnews+"<div class='addnews'><form action='/user/?id="+id+"' method ='POST'>\
        <textarea class='textnews' name='textnews' placeholder='текст сообщения'></textarea>\
        <br/><input class='b1' type='submit' value='Сохранить'>\
        &nbsp;<input id='closeAddNews' class='b2' type='button' value='Закрыть'>\
        </form></div>";
        el.innerHTML=str;
    }
}
function close_add_news() {
    document.getElementById('headnews').innerHTML=headnews;
    headnews='';
}
function getEvent(e) {
    //alert(e.target.id);
    if (!e) e = window.event;
    if (e.target.id=='addNews') form_add_news();
    if (e.target.id=='closeAddNews') close_add_news();
    if (e.target.className=='trwall clearleft') {
        showeditpanel(e.target.id);
    }
    if (e.target.id=='sendMsg') sendMsg();
    
}
function loadf() {
    if (document.getElementById("dialog"))  {
        refreshDialog(0);
        document.getElementById("dialog").scrollTop = 9999;
        maxscroll=document.getElementById("dialog").scrollTop;
        setInterval('refreshDialog(1)', 5000);
    }
    //clearInterval(idinterval);
}
function refreshDialog(prizn) {
	var url = "/template/newmsg.phtml?idf="+document.getElementById('idfriend').value+"&prizn="+prizn;
	xmlHttp=createXmlHttp();
	xmlHttp.open("GET", url, false);
	xmlHttp.onreadystatechange = function() {
	    if (xmlHttp.readyState == 4)   {
            var response = xmlHttp.responseText;
       	    var el=document.getElementById('dialog');
            el.innerHTML=el.innerHTML+response;
    	    if (document.getElementById("dialog").scrollTop >= (maxscroll-40)) {
	            document.getElementById("dialog").scrollTop = 9999;
                maxscroll=document.getElementById("dialog").scrollTop;
	        }
        }
	}
	xmlHttp.send(null);
}
function sendMsg() {
    var idfriend=document.getElementById("idfriend").value;
    var newMsg=document.getElementById("newMsg").value;
    var url = "/core/saveMsg.php?idfriend="+idfriend+"&newMsg="+newMsg;
    xmlHttp=createXmlHttp();
	xmlHttp.open("GET", url, false);
	xmlHttp.onreadystatechange = function() {
	
	        if (xmlHttp.readyState == 4)   {
            var response = xmlHttp.responseText; 
            document.getElementById("newMsg").value='';
            refreshDialog(1);
	    }
    }
    xmlHttp.send(null);
}

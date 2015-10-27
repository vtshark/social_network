var indnews=0;
var headnews='';
///* Создание нового объекта XMLHttpRequest*/
//var xmlHttp = false;
//try {
//  xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
//} catch (e) {
//  try {
//    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
//  } catch (e2) {
//    xmlHttp = false;
//  }
//}
//if (!xmlHttp && typeof XMLHttpRequest != 'undefined') {
//  xmlHttp = new XMLHttpRequest();
//}
////////////////////////////////////////////

function setmode(m)
{
    location='?m='+m;
}
function showeditpanel(ind)
{
 var str;
 var el=document.getElementById('news'+ind);
    if ((indnews!=0)&&(indnews!=ind))  {
        hideeditpanel(indnews);
    }   
        //<img src='/xxi/static/edit.png' title='Комментировать'>&nbsp;&nbsp;&nbsp;
        str="<div id='news"+ind+"' class='hidden1'><form action='?m=news&del' method ='POST'>";
        str=str+"<input name='inddelnews' class='hidden' value='"+ind+"'>";
        str=str+"<button type='submit' class='b3' title='Удалить'><img type='submit' src='/xxi/static/delete.png'></button></form></div>";
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
        str=str+headnews+"<div class='addnews'><form action='?m=news' method ='POST'>";
        str=str+"<textarea name='textnews' placeholder='текст сообщения' style='width:350px;height:200px;'></textarea>";
        str=str+"<br/><input class='b1' type='submit' value='Сохранить'>";
        str=str+"&nbsp;<input onclick=close_news_news() class='b2' type='button' value='Закрыть'>";
        str=str+"</form></div>";
        el.innerHTML=str;
    }
}
function close_news_news() {
    document.getElementById('headnews').innerHTML=headnews;
    headnews='';
}
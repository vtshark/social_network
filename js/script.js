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

function showeditpanel(ind)
{
 var str;
 var el=document.getElementById('news'+ind);
    if ((indnews!=0)&&(indnews!=ind))  {
        hideeditpanel(indnews);
    }   
        str="<div id='news"+ind+"' class='hidden1'>\
        <form action='/news/' method ='POST'>\
        <input name='inddelnews' class='hidden' value='"+ind+"'>\
        <button class='b3' title='Редактировать'><img src='../static/edit.png'></button>\
        <button type='submit' class='b3' title='Удалить'><img src='../static/delete.png'></button>\
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
        str=str+headnews+"<div class='addnews'><form action='/news/' method ='POST'>";
        str=str+"<textarea class='textnews' name='textnews' placeholder='текст сообщения'></textarea>";
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
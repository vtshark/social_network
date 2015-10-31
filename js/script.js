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
        <button class='b3' title='Редактировать'><img src='/static/edit.png'></button>\
        <button type='submit' class='b3' title='Удалить'><img src='/static/delete.png'></button>\
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
        str=str+headnews+"<div class='addnews'><form action='/news/' method ='POST'>\
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
    if (!e) e = window.event;
    //var t = e.target || e.srcElement;
    //alert(e.target.id);
    if (e.target.id=='addNews') form_add_news();
    if (e.target.id=='closeAddNews') close_add_news();
    if (e.target.className=='trnews') {
        showeditpanel(e.target.id);
    }
    //if (e.target.className=='tdDelFriend') {
        //showeditpanel(e.target.id);
//        alert(e.target.id);
    //}
    
}
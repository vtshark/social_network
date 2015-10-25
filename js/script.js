var indnews=0;
function setmode(m)
    {
        location='?m='+m;
    }
function showeditpanel(ind)
{//alert(indnews);
 var str;
 var el=document.getElementById('news'+ind);
    if ((indnews!=0)&&(indnews!=ind))  {
        hideeditpanel(indnews);
    }   
        str="<div id='news"+ind+"' class='hidden1'><img src='/xxi/static/edit.png' title='Комментировать'>&nbsp;&nbsp;&nbsp;";
        str=str+"<img src='/xxi/static/delete.png' title='Удалить'></div>";
        el.outerHTML =str;
        indnews=ind;
}
function hideeditpanel(ind)
{
 var el=document.getElementById('news'+ind);
    el.outerHTML ="<div id='news"+ind+"' class='hidden'></div>";
}
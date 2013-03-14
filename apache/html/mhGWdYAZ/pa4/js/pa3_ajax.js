function updatePermission(albumid,adduserid,deleteuserid)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    console.log(document.getElementById(albumid));
    document.getElementById(albumid).innerHTML=xmlhttp.responseText; 

    }
   }

var parameters = "albumid="+encodeURIComponent(albumid)+"&adduserid="+encodeURIComponent(adduserid)+"&deleteuserid="+encodeURIComponent(deleteuserid);

console.log('update');
console.log(parameters);

xmlhttp.open("POST","updatepermission.php",true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(parameters);
}









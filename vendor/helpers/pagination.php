<?php 
namespace vendor\helpers;

class pagination {
function getPag($pag_actual=1,$pag_number,$links_number,$link,$class_normal='',$class_active=''){
$pag = $pag_actual;
$pagt = $pag_number;
$np = $links_number;
$nps= ceil($links_number  / 2); //7
$paglink=$link;        
$finaly_link = '<ul>';  
for ( $i = 1 ; $i<=$np ; $i++ ) {
     if($pagt <= $np || $pag <  $nps ){
              $i == $pag ?$class=$class_active:$class = $class_normal; 
             $finaly_link .= "<li><a href='".$paglink ."/".$i."' class='".$class."' >". $i. "</a></li>";
          
     
     }else if(($pag + ($nps - 1 ) ) > $pagt  ) {
          (($pagt - $np ) + $i ) == $pag ?$class=$class_active:$class = $class_normal; 
            $finaly_link .= "<li><a href='".$paglink ."/".(($pagt - $np ) + $i )."' class='".$class."' >". (($pagt - $np ) + $i ). "</a></li>";
          
     } else   if($i  < $nps){
        // fase 1
            ($pag - ( $nps - $i)) == $pag ?$class=$class_active:$class = $class_normal; 
               $finaly_link .= "<li><a href='".$paglink ."/".($pag - ( $nps - $i)) ."' class='".$class."' >". ($pag - ( $nps - $i)) . "</a></li>";
        
   
    } else if ($i < ($np + 1) ){
    // fase 2
             ($pag - ( $nps - $i)) == $pag ?$class=$class_active:$class = $class_normal; 
                  $finaly_link .= "<li><a href='".$paglink ."/".($pag - ( $nps - $i)) ."' class='".$class."' >".($pag - ( $nps - $i)) . "</a></li>";
 
        
    }
}
        $finaly_link .= '</ul>';
        return $finaly_link;
    }
}
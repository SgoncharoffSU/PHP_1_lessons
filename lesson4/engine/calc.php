<?php
$int1='238';
$int2='24';
$operation='+';
if (is_numeric($int1) && is_numeric($int2) && in_array($operation,['+','-','*','/'])) {
  if ($operation === '/' && $int2=='0' ) { $resultat= 'Деление на ноль!'; }
  else { eval("\$resultat = ".$int1 . $operation . $int2 . ";"); }
}
else { $resultat= 'Недопустимый ввод!'; }


echo '<input type="text" value="'.$resultat.'">';
?>
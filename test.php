<?php $str = '7h 999j 696m';
preg_match_all('!\d+!', $str, $matches);
echo $matches[0][0];

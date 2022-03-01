<?php 
/*
 * replcae laladock vals
 */
$data = explode("\n", file_get_contents('.env') );
$relace_ary1 = array();
$relace_ary2 = array();
foreach ($data as $key => $value) {
    if (!preg_match('/^([^=]+)=(.+)$/', $value, $matches)) {continue;}
    $relace_ary1[] = '${' . $matches[1] . '}';
    $relace_ary2[] = $matches[2];
}

$tmp = file_get_contents('docker-compose.yml');
$tmp = str_replace($relace_ary1, $relace_ary2, $tmp);

file_put_contents('docker-compose.yml.develop', $tmp)

?>
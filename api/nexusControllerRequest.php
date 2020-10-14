<?php

date_default_timezone_set('Asia/Jakarta');
// $nDateFrom = DateTime::createFromFormat('Y-m-d H:i', $dateFrom)->getTimestamp();
$json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
echo date("Y-m-d H:i:s ");

<?php
// $date = "2020-12-31";
// $date = explode("-", $date);
// if (!empty($date[0]) && !empty($date[1]) && !empty($date[2]) && is_numeric($date[0]) && is_numeric($date[1]) && is_numeric($date[2]) && $date[0] > 1970 && $date[0] < 2100 && $date[1] > 0 && $date[1] < 13 && $date[2] > 0 && $date[2] < 33) {
//   echo "correct date";
// } else {
//   echo "invalid date";
// }
require "api/DatabaseController.php";
$dbHandler = new DatabaseController;

$fetchResult = $dbHandler->runQuery("SELECT thercoInfo,heaterInfo,coolerInfo FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => "6f278959a8"]);
$fetchResult["thercoInfo"] = explode("%", $fetchResult["thercoInfo"]);
print_r($fetchResult["thercoInfo"]);
// $o = 0;
// $fetchResult = $dbHandler->runQuery("SELECT table_name AS 'Table',
// ROUND(((data_length + index_length) / 1024), 2) AS 'Size (KB)'
// FROM information_schema.TABLES
// WHERE table_schema = 'somecooldb'
// ORDER BY (data_length + index_length) DESC;", [], "ALL");
// foreach ($fetchResult as $i) {
//   print_r($i);
//   echo "<br>";
// }
// $dbHandler->runQuery("INSERT INTO nexusplot (bondKey, date, timestamp, deviceType, data1, data2, data3, data4, data5, data6, data7, data8) VALUES (:bondKey, :now, :timej, 'nexus', :d1, :d2, :d3, :d4, :d5, :d6, :d7, :d8);", ["bondKey" => "'`'\"`'`'`'`", "now" => "2020-09-25", "timej" => ":00", "d1" => rand(30, 40), "d2" => rand(60, 90), "d3" => rand(20, 40), "d4" => rand(20, 40), "d5" => rand(20, 40), "d6" => rand(20, 40), "d7" => rand(20, 40), "d8" => rand(20, 40)]);

// for ($i = 0; $i < 24; $i++) {
//   for ($x = 0; $x < 20; $x++) {
//     $dbHandler->runQuery("INSERT INTO nexusplot (bondKey, date, timestamp, deviceType, data1, data2, data3, data4, data5, data6, data7, data8) VALUES (:bondKey, :now, :timej, 'nexus', :d1, :d2, :d3, :d4, :d5, :d6, :d7, :d8);", ["bondKey" => "NQWU8s88pE", "now" => "2020-09-26", "timej" => $i . ":" . ($x * 3) . ":00", "d1" => rand(30, 40), "d2" => rand(60, 90), "d3" => rand(20, 40), "d4" => rand(20, 40), "d5" => rand(20, 40), "d6" => rand(20, 40), "d7" => rand(20, 40), "d8" => rand(20, 40)]);
//   }
// }

<?php
require "api/DatabaseController.php";
$dbHandler = new DatabaseController;
for ($i = 0; $i < 24; $i++) {
  for ($x = 0; $x < 2; $x++) {
    $dbHandler->runQuery("INSERT INTO nexusplot (bondKey, date, timestamp, deviceType, data1, data2, data3, data4, data5, data6, data7, data8) VALUES (:bondKey, :now, :timej, 'nexus', :d1, :d2, :d3, :d4, :d5, :d6, :d7, :d8);", ["bondKey" => "NQWU8s88pE", "now" => "2020-09-14", "timej" => $i . ":" . ($x * 30) . ":00", "d1" => rand(20, 40), "d2" => rand(20, 40), "d3" => rand(20, 40), "d4" => rand(20, 40), "d5" => rand(20, 40), "d6" => rand(20, 40), "d7" => rand(20, 40), "d8" => rand(20, 40)]);
  }
}

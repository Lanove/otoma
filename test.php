<?php

require "api/DatabaseController.php";
$dbHandler = new DatabaseController(); // Open database

$fetchResult["plot"]["newest"] = $dbHandler->runQuery("SELECT MAX(date) AS newestPlot FROM dailyplot WHERE bondKey = :bondkey;", ["bondkey" => "6f278959a8"]);

print_r(json_encode($fetchResult));
echo "<br>";
echo date("Y-m-d");
if ($fetchResult["plot"]["newest"]["newestPlot"] !== date("Y-m-d")) {
    echo "rapodo";
} else
    echo "podo";

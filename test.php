<!-- ~e~e;.H]o&H4 -->
<?php
require "api/DatabaseController.php";
$dbHandler = new DatabaseController();
$prog = $dbHandler->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey;", ["bondKey" => "6f278959a8"], "ALL");
$array = array("0" => [1, 2, 3]);
echo json_encode((object)$array);

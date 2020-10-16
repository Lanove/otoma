<!-- ~e~e;.H]o&H4 -->
<?php
require "api/DatabaseController.php";
$dbHandler = new DatabaseController();
$lolo = "koko,lolo,asdf,";
$lolo = rtrim($lolo, ", ");
echo $lolo;
print_r(explode(',', $lolo));

<?php
class DatabaseController
{
    private $errorId = array(
        "nxqsdO2IUqTevovYP1ab" => "Execute Error",
        "vqRZtoPE5Onglgm7VRxi" => "Prepare Error",
        "xgmkYBTVw4jdRd0zJ3um",
        "Mp9dtheVnEglGLxUNOEW",
        "SnUUGQ06aoI3znLvWH7b",
        "E5lnXXVea7wPE8Jbv8Nf",
        "ich0t89jfZb3FEbnJqri",
        "0yxoYl97nGj3AhK5m6an",
        "RDBsnztyd00WzYeOzMst",
        "jG4UbPDhLXh23Lev8vZN"
    );

    private $host = "localhost:3306";
    private $name   = "otomamyi_otomadatabase";
    private $user = "otomamyi";
    private $pass = "H5TQ2G83CFR";
    private $row;
    private $errorCode = "";

    private $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    private $database;
    private $errorDb;

    function __construct()
    {
        try {
            $this->database = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->pass, $this->options);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            print_r($e);
        }
    }
    function runQuery($query, $param_array, $fetchMode = "SINGLE")
    {
        $stmt = $this->database->prepare($query);
        if (!$stmt) {
            $this->errorCode = "vqRZtoPE5Onglgm7VRxi";
            return;
        }
        if ($stmt->execute($param_array)) {
            $this->row = $stmt->rowCount();
            if ($fetchMode == "SINGLE")
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            else
                $result = $stmt->fetchAll();
            if (!empty($result)) {
                return $result;
            }
        } else {
            $this->errorCode = "nxqsdO2IUqTevovYP1ab";
            return;
        }
    }

    function getRow()
    {
        return $this->row;
    }

    function close()
    {
        if (!empty($this->errorCode)) {
            for ($i = 0; $i < 5; $i++) {
                $stmt = $this->database->prepare("INSERT INTO error(code) VALUES(?)");
                if ($stmt->execute([$this->errorCode]))
                    break;
            }
        }
        unset($this->stmt);
        unset($this->database);
    }

    function getErrorCode()
    {
        return $this->errorCode;
    }

    function errorHandler()
    {
        echo htmlspecialchars("Oops, something is going wrong. Please try again later", ENT_QUOTES, 'UTF-8');
        $this->close();
    }
}

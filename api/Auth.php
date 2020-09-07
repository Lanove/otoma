<?php
require "DatabaseController.php";

class Auth
{
    function getMemberByUsername($username)
    {
        $dbHandler = new DatabaseController();
        $fetchResult = $dbHandler->runQuery("SELECT * FROM users WHERE username = :name", ["name" => $username]);
        return $fetchResult;
    }

    function getTokenByUsername($username, $expired)
    {
        $dbHandler = new DatabaseController();
        $fetchResult = $dbHandler->runQuery("SELECT * FROM tbl_token_auth WHERE username = :name AND is_expired = :expired", ["name" => $username, "expired" => $expired]);
        return $fetchResult;
    }

    function markAsExpired($tokenId)
    {
        $dbHandler = new DatabaseController();

        $expired = 1;
        $fetchResult = $dbHandler->runQuery("UPDATE tbl_token_auth SET is_expired = :expired WHERE id = :tokenId", ["expired" => $expired, "tokenId" => $tokenId]);

        return $fetchResult;
    }

    function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date)
    {
        $dbHandler = new DatabaseController();

        $fetchResult = $dbHandler->runQuery("INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?,?)", [$username, $random_password_hash, $random_selector_hash, $expiry_date]);

        return $fetchResult;
    }
}

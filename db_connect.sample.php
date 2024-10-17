<?php 
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DSN', 'mysql:host=ホスト名; dbname=データベース名; charset=utf8');

function db_connect(){
    $dbh = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
    return $dbh;
}
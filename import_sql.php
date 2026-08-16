<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
$pdo->exec("CREATE DATABASE IF NOT EXISTS db_yesi");
$pdo->exec("USE db_yesi");
$sql = file_get_contents(__DIR__ . '/db_yesi.sql');
$pdo->exec($sql);
echo "SQL Imported successfully.\n";

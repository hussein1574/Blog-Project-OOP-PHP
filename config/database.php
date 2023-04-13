<?php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_project');

//database connection

try{
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

}catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}

?>
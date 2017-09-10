<?php

require __DIR__. '/core/DbManager.php';

$dbManager = new core\DbManager();

$persist = [];
// Put the key the current timestamp and the query you wan't to execute
$persist[20170609132929] = "Create Table IF NOT EXISTS `user` (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(100), profile_pic VARCHAR(255), token VARCHAR(255), is_active boolean default 1, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, user_id VARCHAR(255));";

foreach ($persist as $query) {
    echo $dbManager->executeQuery($query). " <br />";
}
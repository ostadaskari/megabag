<?php
session_start();
require_once("../db/db.php");


// (ACL) Restrict access to admins/managers only  (access level )
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

try {
    //pagination 
    //set page number and limit items per page 
    $limit= 3; //number of rows per page
    $page= isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
            //COUNT TOTAL ROWS FOR LOGINS LOG
    $result = $conn->query("SELECT COUNT(*) AS total FROM login_logs");
    $totalRows = $result->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);

    $stmt = $conn->prepare("SELECT * FROM login_logs ORDER BY created_at DESC LIMIT ? OFFSET ?"); //select all logs in ordr of date and set limitation and offset for each page
    $stmt->bind_param('ii', $limit, $offset); 
    $stmt->execute();
    $result = $stmt->get_result();
    $logs = $result->fetch_all(MYSQLI_ASSOC); //the desired format for fetching data from a result set 'associative array'. This means that the array keys will be the names of the columns in your database table, making it easier to access data by column name rather than by numerical index


    
} catch (Exception $e) {
    $error = "Failed to fetch logs.";
    $logs = [];
}

include("../../design/views/logs/login_logs_view.php");

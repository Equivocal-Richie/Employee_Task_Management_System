<?php 

ob_start();
session_start(); // Add closing parenthesis here
require 'classes/admin_class.php';


$host_name = 'localhost';
$user_name = 'root';
$password = '';
$db_name = 'employee_task_management_system';

try {
    $db = new PDO("mysql:host={$host_name}; dbname={$db_name}", $user_name, $password);
    $obj_admin = new Admin_Class($db);
} catch (PDOException $e) {
    echo $e->getMessage();
}

if(isset($_GET['logout'])){
    $obj_admin->admin_logout();
}
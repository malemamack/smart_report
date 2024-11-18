<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['parent_id'])) {

  if ($_SESSION['role'] == 'Admin') {
     include "../DB_connection.php";
     include "data/parent.php";

     $id = $_GET['parent_id'];
     if (removeRUser($id, $conn)) {
     	$sm = "Successfully deleted!";
        header("Location: parent.php?success=$sm");
        exit;
     }else {
        $em = "Unknown error occurred";
        header("Location: parent.php?error=$em");
        exit;
     }


  }else {
    header("Location: parent.php");
    exit;
  } 
}else {
	header("Location: parent.php");
	exit;
} 
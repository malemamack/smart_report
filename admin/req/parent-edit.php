<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
    	

if (isset($_POST['fname'])      &&
    isset($_POST['lname'])      &&
    isset($_POST['username'])   &&
    isset($_POST['parent_id']) &&
    isset($_POST['address'])  &&

    isset($_POST['phone_number'])  &&
    
    isset($_POST['email_address']) &&
    isset($_POST['gender'])        &&
    isset($_POST['date_of_birth'])) {
    
    include '../../DB_connection.php';
    include "../data/parent.php";

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $uname = $_POST['username'];

    $address = $_POST['address'];
    
    $phone_number = $_POST['phone_number'];

    $email_address = $_POST['email_address'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $id_number = $_POST['id_number'];
    $parent_id = $_POST['parent_id'];
    

    $data = 'parent_id='.$parent_id;

    if (empty($fname)) {
		$em  = "First name is required";
		header("Location: ../parent-edit.php?error=$em&$data");
		exit;
	}else if (empty($lname)) {
		$em  = "Last name is required";
		header("Location: ../parent-edit.php?error=$em&$data");
		exit;
	}else if (empty($uname)) {
		$em  = "Username is required";
		header("Location: ../parent-edit.php?error=$em&$data");
		exit;
	}else if (!unameIsUnique($uname, $conn, $r_user_id)) {
		$em  = "Username is taken! try another";
		header("Location: ../parent-edit.php?error=$em&$data");
		exit;
	}else if (empty($address)) {
        $em  = "Address is required";
        header("Location: ../parent-edit.php?error=$em&$data");
        exit;

    }else if (empty($id_number)) {
		$em  = "ID Number is required";
		header("Location: ../parent-edit.php?error=$em&$data");
		exit;
    }else if (empty($phone_number)) {
        $em  = "Phone number is required";
        header("Location: ../parent-edit.php?error=$em&$data");
        exit;
    }else if (empty($email_address)) {
        $em  = "Email address is required";
        header("Location: ../parent-edit.php?error=$em&$data");
        exit;
    }else if (empty($gender)) {
        $em  = "Gender address is required";
        header("Location: ../parent-edit.php?error=$em&$data");
        exit;
    }else if (empty($date_of_birth)) {
        $em  = "Date of birth address is required";
        header("Location: ../parent-edit.php?error=$em&$data");
        exit;
    }else {
        $sql = "UPDATE parent SET
                username = ?, fname=?, lname=?,
                address = ?, date_of_birth = ?, phone_number = ?,gender=?, email_address = ?, id_number= ?
                WHERE parent_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$uname, $fname, $lname, $address, $date_of_birth, $id_number, $phone_number, $gender, $email_address, $parent_id]);
        $sm = "successfully updated!";
        header("Location: ../parent.php?success=$sm&$data");
        exit;
	}
    
  }else {
  	$em = "An error occurred";
    header("Location: ../parent.php?error=$em");
    exit;
  }

  }else {
    header("Location: ../../logout.php");
    exit;
  } 
}else {
	header("Location: ../../logout.php");
	exit;
} 

<?php 

// All Students 
function getAllStudents($conn){
   $sql = "SELECT * FROM students";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $students = $stmt->fetchAll();
     return $students;
   }else {
   	return 0;
   }
}

// DELETE
function removeStudent($id, $conn){
   $sql  = "DELETE FROM students
           WHERE student_id=?";
   $stmt = $conn->prepare($sql);
   $re   = $stmt->execute([$id]);
   if ($re) {
     return 1;
   }else {
    return 0;
   }
}

// Get Student By Id 
function getStudentById($id, $conn){
   $sql = "SELECT * FROM students
           WHERE student_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() == 1) {
     $student = $stmt->fetch();
     return $student;
   }else {
    return 0;
   }
}



// Search 
function searchStudents($key, $conn){
   $key = preg_replace('/(?<!\\\)([%_])/', '\\\$1',$key);
   $sql = "SELECT * FROM students
           WHERE student_id LIKE ? 
           OR fname LIKE ?
           OR address LIKE ?
           OR email_address LIKE ?
           OR date_of_birth LIKE ?
           OR contact LIKE ?
           OR id_number LIKE ?
           OR lname LIKE ?
           OR preffered_name LIKE ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$key, $key, $key, $key, $key, $key, $key, $key, $key]);

   if ($stmt->rowCount() == 1) {
     $students = $stmt->fetchAll();
     return $students;
   }else {
    return 0;
   }
}

// All Parents
function getAllParents($conn){
  $sql = "SELECT parent_id, CONCAT(fname, ' ', lname) AS parent_name FROM parent";
  $stmt = $conn->prepare($sql);
  
  $stmt->execute();

  if ($stmt->rowCount() >= 1) {
      $parents = $stmt->fetchAll();
      return $parents;
  } else {
      return 0; // Return 0 if no parents found
  }
}


<?php 
session_start();
if (isset($_SESSION['teacher_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {
       include "../DB_connection.php";
       include "data/student.php";
       include "data/grade.php";
       include "data/class.php";
       include "data/section.php";
       include "data/setting.php";
       include "data/subject.php";
       include "data/teacher.php";
       include "data/student_score.php";

       if (!isset($_GET['student_id'])) {
           header("Location: students.php");
           exit;
       }
       $student_id = $_GET['student_id'];
       $student = getStudentById($student_id, $conn);
       $setting = getSetting($conn);
       $subjects = getSubjectByGrade($student['grade'], $conn);

       $teacher_id = $_SESSION['teacher_id'];
       $teacher = getTeacherById($teacher_id, $conn);

       $teacher_subjects = str_split(trim($teacher['subjects']));

       $ssubject_id = 0;
       if (isset($_POST['ssubject_id'])) {
           $ssubject_id = $_POST['ssubject_id'];

           $student_score = getScoreById($student_id, $teacher_id, $ssubject_id, $setting['current_semester'], $setting['current_year'], $conn); 
       }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Teacher - Students Grade</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../1.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php 
    include "inc/navbar.php";
    if ($student != 0 && $setting != 0 && $subjects != 0 && $teacher_subjects != 0) {
    ?>

    <div class="d-flex align-items-center flex-column"><br><br>
        <div class="login shadow p-3">
        <form method="post" action="">
            <div class="mb-3">
                <ul class="list-group">
                    <li class="list-group-item"><b>ID: </b> <?php echo $student['student_id'] ?></li>
                    <li class="list-group-item"><b>First Name: </b> <?php echo $student['fname'] ?></li>
                    <li class="list-group-item"><b>Last Name: </b> <?php echo $student['lname'] ?></li>
                    <li class="list-group-item"><b>Grade: </b> 
                        <?php  
                            $g = getGradeById($student['grade'], $conn); 
                            echo $g['grade_code'].'-'.$g['grade'];
                        ?>
                    </li>
                    <li class="list-group-item"><b>Section: </b> 
                        <?php  
                            $s = getSectioById($student['section'], $conn); 
                            echo $s['section'];
                        ?>
                    </li>
                    <li class="list-group-item text-center"><b>Year: </b> <?php echo $setting['current_year']; ?> &nbsp;&nbsp;&nbsp;<b>Term</b> <?php echo $setting['current_semester']; ?></li>
                </ul>
            </div>
            <h5 class="text-center">Add Grade</h5>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?=$_GET['error']?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?=$_GET['success']?>
                </div>
            <?php } ?>
           
            <label class="form-label">Subject</label>
            <select class="form-control" name="ssubject_id">
                <?php 
                foreach($subjects as $subject){ 
                    foreach($teacher_subjects as $teacher_subject){
                        if($subject['subject_id'] == $teacher_subject){ ?>
                            <option <?php if($ssubject_id == $subject['subject_id']){echo "selected";} ?> value="<?php echo $subject['subject_id'] ?>">
                                <?php echo $subject['subject_code'] ?>
                            </option>
                        <?php }   
                    }
                } ?>
            </select><br>

            <button type="submit" class="btn btn-primary">Select</button><br><br>
        </form>
        
        <form method="POST" action="req\save-score.php">
    <label for="score-1">Test 1 Score:</label>
    <input type="number" name="score-1" id="score-1" min="0" max="100" required>
    <label for="aoutof-1">Out of:</label>
    <input type="number" name="aoutof-1" id="aoutof-1" min="0" max="100" required><br>

    <label for="score-2">Test 2 Score:</label>
    <input type="number" name="score-2" id="score-2" min="0" max="100" required>
    <label for="aoutof-2">Out of:</label>
    <input type="number" name="aoutof-2" id="aoutof-2" min="0" max="100" required><br>

    <label for="score-3">Test 3 Score:</label>
    <input type="number" name="score-3" id="score-3" min="0" max="100" required>
    <label for="aoutof-3">Out of:</label>
    <input type="number" name="aoutof-3" id="aoutof-3" min="0" max="100" required><br>

    <label for="exam">Exam Score:</label>
    <input type="number" name="exam" id="exam" min="0" max="100" required><br>

    <label for="final_mark">Final Mark:</label>
    <input type="number" name="final_mark" id="final_mark" min="0" max="100" required><br>

    <label for="attendance">Attendance:</label>
    <input type="text" name="attendance" id="attendance"><br>

    <label for="comments">Comments:</label>
    <textarea name="comments" id="comments"></textarea><br>

    <input type="submit" value="Submit">
</form>
 
        
        </div>
    </div>

    <?php 
        } else {
            header("Location: student-grade.php");
            exit;
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(4) a").addClass('active');
        });
    </script>

</body>
</html>
<?php 

  } else {
    header("Location: ../login.php");
    exit;
  } 
} else {
    header("Location: ../login.php");
    exit;
} 

?>

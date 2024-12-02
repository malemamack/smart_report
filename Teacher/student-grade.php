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
    <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="body-login" style="background-image: url(../2.jpg);">
    <?php 
    include "inc/navbar.php";
        if ($student != 0 && $setting !=0 && $subjects !=0 && $teacher_subjects != 0) {
     ?>

<a href="index.php" class="btn btn-light">Go Back</a>

     <div class="d-flex align-items-center flex-column"><br><br>
        <div class="login shadow p-3">
        <form method="post"
              action="">
            <div class="mb-3">
                <ul class="list-group">
                    <li class="list-group-item"><b>ID: </b> <?php echo $student['student_id'] ?></li>
                  <li class="list-group-item"><b>First Name: </b> <?php echo $student['fname'] ?></li>
                  <li class="list-group-item"><b>Last Name: </b> <?php echo $student['lname'] ?></li>
                  <li class="list-group-item"><b>Garde: </b> 
                    <?php  $g = getGradeById($student['grade'], $conn); 
                        echo $g['grade_code'].'-'.$g['grade'];
                    ?>
                  </li>
                  <li class="list-group-item"><b>Section: </b> 
                    <?php  $s = getSectioById($student['section'], $conn); 
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
            <select class="form-control"
                    name="ssubject_id">
                    <?php foreach($subjects as $subject){ 
                        foreach($teacher_subjects as $teacher_subject){
                            if($subject['subject_id'] == $teacher_subject){ ?>
                    
                       <option <?php if($ssubject_id == $subject['subject_id']){echo "selected";} ?> 
                           value="<?php echo $subject['subject_id'] ?>">
                        <?php echo $subject['subject_code'] ?></option>
                    <?php }   }
                        } ?>
            </select><br>

            
            <button type="submit" class="btn btn-primary">Select</button><br><br>
        </form>
        <form method="post" action="req/save-score.php">
    <div class="input-group mb-3">
        <input type="number" min="0" max="300" class="form-control" placeholder="Test 1 Score" name="score-1" id="score1" oninput="calculateFinalMark()">
        <span class="input-group-text">/</span>
        <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-1" id="outof1" oninput="calculateFinalMark()">
    </div>
    <div class="input-group mb-3">
        <input type="number" min="0" max="300" class="form-control" placeholder="Test 2 Score" name="score-2" id="score2" oninput="calculateFinalMark()">
        <span class="input-group-text">/</span>
        <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-2" id="outof2" oninput="calculateFinalMark()">
    </div>
    <div class="input-group mb-3">
        <input type="number" min="0" max="300" class="form-control" placeholder="Test 3 Score" name="score-3" id="score3" oninput="calculateFinalMark()">
        <span class="input-group-text">/</span>
        <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-3" id="outof3" oninput="calculateFinalMark()">
    </div>
    <div class="input-group mb-3">
        <input type="number" min="0" max="300" class="form-control" placeholder="Exam Score" name="score-4" id="exam" oninput="calculateFinalMark()">
        <span class="input-group-text">/</span>
        <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-4" id="outof4" oninput="calculateFinalMark()">
    </div>
    <div class="input-group mb-3">
        <input type="number" class="form-control" placeholder="Final Mark (Calculated)" name="score-5" id="finalMark" readonly>
        <span class="input-group-text">%</span>
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Attendance" name="attendance">
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Comment" name="comment">
    </div>

    <input type="text" name="student_id" value="<?=$student_id?>" hidden>
    <input type="text" name="subject_id" value="<?=$ssubject_id?>" hidden>
    <input type="text" name="current_semester" value="<?=$setting['current_semester']?>" hidden>
    <input type="text" name="current_year" value="<?=$setting['current_year']?>" hidden>

    <button type="submit" class="btn btn-primary">Save</button>
</form>

 <?php  ?> 
        </div>
        </div>
     <?php 
         }else{
            header("Location: students.php");
            exit;
         }
     ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(4) a").addClass('active');
        });
    </script>
    <script>
    function calculateFinalMark() {
        const score1 = parseFloat(document.getElementById('score1').value) || 0;
        const score2 = parseFloat(document.getElementById('score2').value) || 0;
        const score3 = parseFloat(document.getElementById('score3').value) || 0;
        const exam = parseFloat(document.getElementById('exam').value) || 0;

        const outof1 = parseFloat(document.getElementById('outof1').value) || 0;
        const outof2 = parseFloat(document.getElementById('outof2').value) || 0;
        const outof3 = parseFloat(document.getElementById('outof3').value) || 0;
        const outof4 = parseFloat(document.getElementById('outof4').value) || 0;

        const totalScore = score1 + score2 + score3 + exam;
        const totalOutOf = outof1 + outof2 + outof3 + outof4;

        if (totalOutOf > 0) {
            const finalMark = (totalScore / totalOutOf) * 100;
            document.getElementById('finalMark').value = finalMark.toFixed(2); // Round to 2 decimals
        } else {
            document.getElementById('finalMark').value = ''; // Clear if "out of" is invalid
        }
    }
</script>

</body>
</html>
<?php 

  }else {
    header("Location: ../login.php");
    exit;
  } 
}else {
    header("Location: ../login.php");
    exit;
} 
?>

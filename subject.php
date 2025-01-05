<?php
ob_start();
session_start();
require_once 'dbconnect.php'; // Assuming dbconnect.php connects using mysqli

// Only proceed if the user is logged in
if( !isset($_SESSION['user']) ) {
    header("Location: index.php");
    exit;
}

// Get the current user details
$res = mysqli_query($conn, "SELECT * FROM users WHERE userId=" . $_SESSION['user']);
$userRow = mysqli_fetch_array($res);

// Handle subject deletion
if(isset($_GET['delete_id'])) {
    mysqli_query($conn, "DELETE FROM subject WHERE subjectId=" . $_GET['delete_id']);
    header("Location: subject.php");
}

// Handle subject insertion or update
if(isset($_POST['btn-save'])) {
    
    $semester_semesterId = $_POST['semester_semesterId'];
    $subjectCode = $_POST['subjectCode'];
    $subjectName = $_POST['subjectName'];
    $subjectCredits = $_POST['subjectCredits'];
    $Grade = $_POST['Grade'];
    $isGpa = $_POST['isGpa'];

    // Update subject if subjectId is set
    if($_POST['subjectId']) {
        $sql_query = "UPDATE subject SET subjectCode='$subjectCode', subjectName='$subjectName', subjectCredits='$subjectCredits', Grade='$Grade', isGpa='$isGpa' WHERE subjectId=" . $_POST['subjectId'];
        
        if(mysqli_query($conn, $sql_query)) {
            echo "<script>alert('Data updated successfully'); window.location.href='subject.php';</script>";
        } else {
            echo "<script>alert('Error occurred while updating data');</script>";
        }
    } else {
        // Insert new subject if no subjectId
        $sql_query = "INSERT INTO subject (subjectCode, subjectName, subjectCredits, isGpa, semester_semesterId, Grade) 
                      VALUES ('$subjectCode', '$subjectName', '$subjectCredits', '$isGpa', '$semester_semesterId', '$Grade')";
        
        if(mysqli_query($conn, $sql_query)) {
            echo "<script>alert('Data inserted successfully'); window.location.href='subject.php';</script>";
        } else {
            echo "<script>alert('Error occurred while inserting data');</script>";
        }
    }

    // Calculate SGPA and update semester
    $sgpa = 0.0;
    $tot_credits = 0;
    $result_set_sub = mysqli_query($conn, "SELECT * FROM subject WHERE semester_semesterId=" . $semester_semesterId);
    
    if(mysqli_num_rows($result_set_sub) > 0) {
        while($row = mysqli_fetch_row($result_set_sub)) {
            if($row[4] == 1) { // If it's a GPA subject
                $grde = $row[6];
                $sql = "SELECT * FROM scheme WHERE grade='$grde' LIMIT 1";
                $result = mysqli_query($conn, $sql);
                $value = mysqli_fetch_row($result);
                $tot_credits += $row[3];
                $sgpa += ($value[2] * $row[3]);
            }
        }
    }
    
    $sgpa = $sgpa / $tot_credits;
    $sql_query = "UPDATE semester SET totCredits='$tot_credits', semesterGPA='$sgpa' WHERE semesterId=" . $semester_semesterId;
    mysqli_query($conn, $sql_query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome - <?php echo $userRow['userEmail']; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript">
        function edt_id(subjectId, subjectCode, subjectName, subjectCredits, isGpa, semester, Grade) {
            if(confirm('Sure to edit ?')) {
                $("#" + semester).modal("show");
                $(".modal-body #subjectId").val(subjectId);
                $(".modal-body #subjectCode").val(subjectCode);
                $(".modal-body #subjectName").val(subjectName);
                $(".modal-body #subjectCredits").val(subjectCredits);
                $(".modal-body #isGpa").val(isGpa);
                $(".modal-body #Grade").val(Grade);
            }
        }

        function delete_id(id) {
            if(confirm('Sure to Delete ?')) {
                window.location.href = 'subject.php?delete_id=' + id;
            }
        }
    </script>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://www.codingcage.com">GPA Calculator</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="home.php">My Profile</a></li>
                <li><a href="semester.php">My Semesters</a></li>
                <li class="active"><a href="subject.php">My Results</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $userRow['userEmail']; ?>&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav> 

<div id="wrapper">
    <div class="container">
        <h1 class="text-primary">My Subjects</h1>
        <hr>

        <div class="col-md-12">
            <?php
            $result_set = mysqli_query($conn, "SELECT * FROM semester WHERE users_userId=" . $_SESSION['user']);
            if(mysqli_num_rows($result_set) > 0) {
                while($row = mysqli_fetch_row($result_set)) {
                    $semester = $row[0];
                    ?>  
                    <h3 class="text-info"> Subjects and Results of <?php echo $row[1]; ?>
                        <span class="label label-default">SGPA : <?php echo $row[3]; ?></span>
                        <span class="label label-default">TOT. GPA CREDITS : <?php echo $row[2]; ?></span>
                        <button type="button" name="btn-add" class="btn btn-info pull-right" data-toggle="modal" data-target="#<?php echo $row[0]; ?>">
                            <i class="glyphicon glyphicon-plus"></i> Add subjects</button>
                    </h3>

                    <div id="<?php echo $row[0]; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Subjects for <?php echo $row[1]; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <input type="hidden" name="subjectId" id="subjectId">
                                            <input type="hidden" class="form-control" name="semester_semesterId" value="<?php echo $row[0]; ?>">

                                            <label for="subjectCode">Subject Code</label>
                                            <input type="text" class="form-control" name="subjectCode" id="subjectCode" required >

                                            <label for="subjectName">Subject Name</label>
                                            <input type="text" class="form-control" name="subjectName" id="subjectName" required>

                                            <label for="subjectCredits">Subject Credits</label>
                                            <input type="text" class="form-control" name="subjectCredits" id="subjectCredits" required >

                                            <label for="Grade">Subject Grade</label>
                                            <select class="form-control" name="Grade" id="Grade">
                                               <!-- <option>A+</option> -->
                                                <option>A</option>
                                                <option>A-</option>
                                                <option>B+</option>
                                                <option>B</option>
                                                <option>B-</option>
                                                <option>C+</option>
                                                <option>C</option>
                                                <option>C-</option>
                                                <option>D</option>
                                                <option>I-we</option>
                                                <option>I-ca</option>
                                                <option>F</option>
                                            </select>

                                            <label class="radio-inline"><input type="radio" name="isGpa" id="isGpa" value="1" checked>GPA</label>
                                            <label class="radio-inline"><input type="radio" name="isGpa" id="isGpa" value="0">Non-GPA</label>
                                        </div>
                                        <button type="submit" name="btn-save" class="btn btn-success pull-right">SUBMIT</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><center>Subject Code</center></th>
                                <th><center>Subject Name</center></th>
                                <th><center>Subject Credits</center></th>
                                <th><center>Subject Grade</center></th>
                                <th><center>GPA/NGPA</center></th>
                                <th><center>Edit</center></th>
                                <th><center>Delete</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result_set_sub = mysqli_query($conn, "SELECT * FROM subject WHERE semester_semesterId=" . $semester);
                            if(mysqli_num_rows($result_set_sub) > 0) {
                                while($row = mysqli_fetch_row($result_set_sub)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row[1]; ?></td>
                                        <td><?php echo $row[2]; ?></td>
                                        <td><?php echo $row[3]; ?></td>
                                        <td><?php echo $row[6]; ?></td>
                                        <td><?php echo ($row[4] == 1 ? "GPA" : "NGPA"); ?></td>
                                        <td align="center"><a href="javascript:edt_id('<?php echo $row[0]; ?>', '<?php echo $row[1]; ?>', '<?php echo $row[2]; ?>', '<?php echo $row[3]; ?>', '<?php echo $row[4]; ?>', '<?php echo $row[5]; ?>', '<?php echo $row[6]; ?>')"><i class="fa fa-edit"></i></a></td>
                                        <td align="center"><a href="javascript:delete_id('<?php echo $row[0]; ?>')"><i class="glyphicon glyphicon-remove"></i></a></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5">No Data Found!</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <br><hr><br>
                    <?php
                }
            } else {
                ?>
                <h3> No semesters found</h3>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script src="assets/jquery-1.11.3-jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>

<?php ob_end_flush(); ?>

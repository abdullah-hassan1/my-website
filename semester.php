<?php
ob_start();
session_start();
require_once 'dbconnect.php';

if (isset($_GET['delete_id'])) {
    mysqli_query($conn, "DELETE FROM semester WHERE semesterId=" . $_GET['delete_id']);
    header("Location: semester.php");
}

if (isset($_GET['edit_id'])) {
    $result_set = mysqli_query($conn, "SELECT * FROM semester WHERE semesterId=" . $_GET['edit_id']);
    $fetched_row = mysqli_fetch_array($result_set);
}

if (isset($_POST['btn-update'])) {
    $semesterName = $_POST['semesterName'];
    $sql_query = "UPDATE semester SET semesterName='$semesterName' WHERE semesterId=" . $_GET['edit_id'];

    if (mysqli_query($conn, $sql_query)) {
        ?>
        <script type="text/javascript">
            alert('Data Are Updated Successfully');
            window.location.href = 'semester.php';
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            alert('error occurred while updating data');
        </script>
        <?php
    }
}

if (isset($_POST['btn-cancel'])) {
    header("Location: semester.php");
}

if (isset($_POST['btn-save'])) {
    $semesterName = $_POST['semesterName'];
    $user = $_SESSION['user'];

    $sql_query = "INSERT INTO semester(semesterName, users_userId) VALUES('$semesterName', '$user')";

    if (mysqli_query($conn, $sql_query)) {
        ?>
        <script type="text/javascript">
            alert('Data Are Inserted Successfully');
            window.location.href = 'semester.php';
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            alert('error occurred while inserting your data');
        </script>
        <?php
    }
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$res = mysqli_query($conn, "SELECT * FROM users WHERE userId=" . $_SESSION['user']);
$userRow = mysqli_fetch_array($res);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - <?php echo $userRow['userEmail']; ?></title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript">
function edt_id(id) {
    if(confirm('Sure to edit ?')) {
        console.log(id);
        window.location.href = 'semester.php?edit_id=' + id;
    }
}

function delete_id(id) {
    if(confirm('Sure to Delete ?')) {
        window.location.href = 'semester.php?delete_id=' + id;
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
                    <li class="active"><a href="semester.php">My Semesters</a></li>
                    <li><a href="subject.php">My Results</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $userRow['userEmail']; ?>&nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div id="wrapper">

        <div class="container">

            <h1 class="text-primary">My Semesters</h1>
            <hr>
            <form class="form-inline" method="post">
                <?php
                if (isset($_GET['edit_id'])) {
                ?>
                    <div class="form-group">
                        <label for="semesterName">Semester Name</label>
                        <input type="text" class="form-control" name="semesterName" value="<?php echo $fetched_row['semesterName']; ?>" required>
                    </div>
                    <button type="submit" name="btn-update" class="btn btn-success"><strong>UPDATE</strong></button>
                    <button type="submit" name="btn-cancel" class="btn btn-default"><strong>Cancel</strong></button>
                <?php
                } else {
                ?>
                    <div class="form-group">
                        <label for="semesterName">Semester Name</label>
                        <input type="text" class="form-control" name="semesterName">
                    </div>
                    <button type="submit" name="btn-save" class="btn btn-success">SUBMIT</button>
                <?php
                }
                ?>
            </form>

            <div class="col-md-6">
                <br><br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><center>Semester Name</center></th>
                            <th><center>Total Credits</center></th>
                            <th><center>Semester GPA</center></th>
                            <th><center>Edit</center></th>
                            <th><center>Delete</center></th>
                            <th><center>View Subjects</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result_set = mysqli_query($conn, "SELECT * FROM semester WHERE users_userId=" . $_SESSION['user']);
                        if (mysqli_num_rows($result_set) > 0) {
                            while ($row = mysqli_fetch_row($result_set)) {
                        ?>
                                <tr>
                                    <td><?php echo $row[1]; ?></td>
                                    <td><?php echo $row[2]; ?></td>
                                    <td><?php echo $row[3]; ?></td>
                                    <td align="center"><a href="javascript:edt_id('<?php echo $row[0]; ?>')"><i class="fa fa-edit"></i></a></td>
                                    <td align="center"><a href="javascript:delete_id('<?php echo $row[0]; ?>')"><i class="glyphicon glyphicon-remove"></i></a></td>
                                    <td align="center"><a href="subject.php"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                                <tr>
                                    <td colspan="5">No Data Found !</td>
                                </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

</body>
</html>
<?php ob_end_flush(); ?>

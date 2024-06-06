<?php
session_start(); // Start the session

// Check if the user is logged in as a teacher
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
} else {
    // Redirect to the login page if not logged in as a teacher
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <style>
    body{
    font-family: 'Roboto', sans-serif;
    font-size: 19px;
    background-color:lightblue;
}
.portion_1{
    float:left;
    width:14%;
    background-color: rgb(75, 75, 204);
    font-weight: bold;
    display: inline;
    padding-left: 4%;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    bottom: 0;
    overflow-x: hidden;
}
a{
    text-decoration: none;
    color: white;
    display: block;
    height: 25px;
    padding-top: 14%;
    margin-top: 25px;
}
.dropdown{
    padding-top: 10%;
}
.portion_2{
    margin-left: 14%
}
img{
    width: 82%;
    height: 100%;
    position: fixed;
    z-index: 1;
    right: 0;
    bottom: 0;
}
.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown_content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    z-index: 1;
}
.dropdown_content a {
    color: rgb(75, 75, 204);
    padding-left:16px;
    padding-right:16px;
    padding-bottom:14%;
    text-decoration: none;
    display: block;
}
.dropdown:hover .dropdown_content {
    display: block;
}
</style>
</head>
<body>
    <div class="portion_1">
        <a href="admin_homepage.php" target="_self">Home</a>
        <div class="dropdown">
            <a>Students &#9662;</a>
            <div class="dropdown_content">
                <a href="student_registration.php">Register</a>
                <a href="update_student_info.php">Update Personal Info</a>
            </div>
        </div>
        <br/>
        <div class="dropdown">
            <a>Teachers &#9662;</a>
            <div class="dropdown_content">
                <a href="instructor_registration.php">Register</a>
                <a href="update_instructor_info.php">Update Personal Info</a>
                <a href="teacher_names_mark_list.php?role=<?= $role ?>">Mark Attendance</a>
            </div>
        </div>
        <a href="subject_entry.php" target="_self">Add Course</a>
        <a href="drop_course.php" target="_self">Drop Course</a>
        <div class="dropdown">
            <a>Teacher Course Info &#9662;</a>
            <div class="dropdown_content">
                <a href="teacher_info.php">Individual Teacher Info</a>
                <a href="combined_teacher_info.php">Combined Teacher Info</a>
            </div>
        </div>
        <div class="dropdown">
            <a href="teacher_names_view_list.php?role=<?= $role ?>">Attendance Report</a>
        </div>
        <a href="login.php" target="_self">Log Out</a>
    </div>
    <div class="portion_2">
        <img src="Admin Homepage.png"/>
    </div>
</body>
</html>
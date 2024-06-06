<?php
session_start(); // Start the session

// Check if the user is logged in as a teacher
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Student') {
    // Get first name, last name, and role from the session
    $program = isset($_SESSION['program']) ? $_SESSION['program'] : '';
    $session = isset($_SESSION['session']) ? $_SESSION['session'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
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
    <title>Student Homepage</title>
    <style>
        body{
    font-family: 'Roboto', sans-serif;
    font-size: 19px;
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
    height: 50px;
    padding-top: 43%;
}
.dropdown{
    padding-top: 10%;
}
.portion_2{
    margin-left: 14%;
}
img{
    width: 82%;
    height: 100%;
    position: fixed;
    z-index: 1;
    top: 0;
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
    <div class="portion_1"><br/><br/>
        <a href="student_homepage.php" target="_self">Home</a>
        <div class="dropdown">
            <a href="student_course_list.php?program=<?= $program ?>&session=<?= $session ?>&email=<?= $email ?>&role=<?= $role ?>">Attendance Report</a>
        </div>
        <a href="notification.php?program=<?= $program ?>&session=<?= $session?>&email=<?= $email ?>">Notifications</a>
        <a href="login.php" target="_self">Log Out</a>
    </div>
    <div class="portion_2">
        <img src="Student Homepage.png"/>
    </div>
</body>
</html>
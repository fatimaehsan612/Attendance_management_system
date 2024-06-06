<?php
session_start(); // Start the session

// Check if the user is logged in as a teacher
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Teacher') {
    // Get first name, last name, and role from the session
    $firstName = isset($_SESSION['firstName']) ? $_SESSION['firstName'] : '';
    $lastName = isset($_SESSION['lastName']) ? $_SESSION['lastName'] : '';
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
} else {
    // Redirect to the login page if not logged in as a teacher
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Homepage</title>
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
    padding-top: 14%;
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
    </style>
</head>
<body>
    <div class="portion_1"><br/><br/><br/><br/><br/>
        <a href="teacher_homepage.php" target="_self">Home</a><br/><br/><br/>
        <a href="teacher_course_mark_list.php?firstName=<?= $firstName ?>&lastName=<?= $lastName ?>&role=<?= $role ?>">Mark Attendance</a><br/><br/><br/>
        <a href="teacher_course_view_list.php?firstName=<?= $firstName ?>&lastName=<?= $lastName ?>&role=<?= $role ?>">Attendance Report</a><br/><br/><br/>
        <a href="login.php" target="_self">Log Out</a>
    </div>
    <div class="portion_2">
        <img src="Teacher Homepage.png"/>
    </div>
</body>
</html>


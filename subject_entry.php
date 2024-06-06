<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: lightblue;
    margin: 0;
    padding: 0;
    margin-top: 10%;
}

.container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
}

h1 {
    text-align: center;
    color: rgb(75, 75, 204);
}

.form-group {
    margin: 5px 0;
}

.form-group.inline {
    display: inline-block;
    width: 45%; 
    margin-right: 5%;
}

.form-row {
    display: flex;
    justify-content: space-between;
}

label {
    display: block;
    font-weight: bold;
    color: rgb(75, 75, 204);
}

input[type="text"],
select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    background-color: #f2f2f2;
}

button {
    background-color: rgb(75, 75, 204);
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: rgb(75, 75, 204);
}
    </style>
    <title>Subject Entry Form</title>
</head>
<body>
    <div class="container">
        <h1>Subject Entry Form</h1>
        <form method="post">
            <div class="form-row">
                <div class="form-group inline">
                    <label for="course">Subject's Name:</label>
                    <input type="text" id="course" name="course" required>
                </div>
                <div class="form-group inline">
                    <label for="session">Session:</label>
                    <input type="text" id="session" name="session" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group inline">
                    <label for="program">Program:</label>
                    <input type="text" id="program" name="program" required>
                </div>
                <div class="form-group inline">
                    <label for="teacher">Teacher's Name:</label>
                    <input type="text" id="teacher" name="teacher" required>
                </div>
            </div>

            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbms_project";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $course = $_POST['course'];
    $session = $_POST['session'];
    $program = $_POST['program'];
    $teacher = $_POST['teacher'];

    // SQL query to check if the entry already exists in the teacher_course table
    $checkIfExistsQuery = "SELECT * FROM teacher_course WHERE `teacher`='$teacher' AND `subject`='$course' AND `program`='$program'";
    $result = $conn->query($checkIfExistsQuery);

    if ($result->num_rows > 0) {
        // Information already exists
        echo "<script>alert('Information already exists.');</script>";
        $conn->close();
        exit();
    }

    // SQL query to check if the entry already exists in the teacher_course table
    $checkIfExistsQuery = "SELECT * FROM teacher_course WHERE `subject`='$course' AND `session`='$session' AND `program`='$program'";
    $result = $conn->query($checkIfExistsQuery);

    if ($result->num_rows > 0) {
        // Information already exists
        echo "<script>alert('Course already assigned to a teacher');</script>";
        $conn->close();
        exit();
    }

    // SQL query to check if the teacher exists
    $checkTeacherQuery = "SELECT * FROM `teacher` WHERE CONCAT(`firstName`, ' ', `lastname`) = '$teacher'";
    $result = $conn->query($checkTeacherQuery);

    if ($result->num_rows > 0) {
        // Teacher exists, proceed with inserting the course
        $insertQuery = "INSERT INTO `teacher_course` (`subject`, `session`, `program`, `teacher`)
                        VALUES ('$course', '$session', '$program', '$teacher')";

        if ($conn->query($insertQuery) === TRUE) {
            // Insertion successful
            echo "<script>alert('Course Added successfully');</script>";
            echo "<script>window.location.href='admin_homepage.php';</script>";
            exit();
        } else {
            // Insertion failed
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    } else {
        // Teacher does not exist
        echo "<script>alert('Teacher does not exist. Course can\'t be added.');</script>";
    }

    // Close the database connection
    $conn->close();
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a teacher is selected
    if (isset($_POST['course_program_session'])) {
        // Get the selected teacher's first name and last name
        list($selectedCourse, $selectedProgram,$selectedSession,$role) = explode(' ', $_POST['course_program_session']);

        // Redirect to teacher_course_list.php with the selected teacher's first name and last name as parameters
        header("Location: view_attendance.php?course=$selectedCourse&program=$selectedProgram&session=$selectedSession&role=$role");
        exit();
    } else {
        echo "<script>alert('Please select a teacher.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Course Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightblue;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: rgb(75, 75, 204);
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: rgb(75, 75, 204);
            color: white;
        }
        .button-group {
            text-align: center;
            margin-top: 20px; 
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
            background-color: rgb(60, 60, 200);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selected Teacher's Course Information:</h2>
            <form method="post">
                <?php
                    // Retrieve parameters from the URL
                    $selectedFirstName = $_GET['firstName'];
                    $selectedLastName = $_GET['lastName'];
                    $role=$_GET['role'];

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

                    // Fetch the teacher's course information from the teacher_course table
                    $query = "SELECT * FROM teacher_course WHERE `teacher` = '$selectedFirstName $selectedLastName'";
                    echo '<b><i>Courses of '. $selectedFirstName . ' ' . $selectedLastName.'</b></i>';
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        echo '<table>';
                        echo '<tr><th>Course</th><th>Program</th><th>Session</th><th>Select</th></tr>';
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['subject'] . '</td>';
                            echo '<td>' . $row['program'] . '</td>';
                            echo '<td>' . $row['session'] . '</td>';
                            echo '<td><input type="radio" name="course_program_session" value="' . $row['subject'] . ' ' . $row['program'] . ' ' .$row['session'] . ' ' . $role . '"></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo "No courses found for the selected teacher.";
                    }

                    // Close the database connection
                    $conn->close();
                ?>
            <div class="button-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>

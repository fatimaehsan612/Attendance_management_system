<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Course</title>
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
        <h2>Drop Course Information</h2>
        <form method="post">
            <?php
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

                // Fetch all entries from the teacher_course table
                $query = "SELECT * FROM teacher_course";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    echo '<table>';
                    echo '<tr><th>Course</th><th>Session</th><th>Program</th><th>Teacher</th><th>Select</th></tr>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['subject'] . '</td>';
                        echo '<td>' . $row['session'] . '</td>';
                        echo '<td>' . $row['program'] . '</td>';
                        echo '<td>' . $row['teacher'] . '</td>';
                        echo '<td><input type="radio" name="course_program_session_teacher" value="' . $row['subject'] . ' ' . $row['session'] . ' ' .$row['program'] . ' ' . $row['teacher'] . '"></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo "No courses found in the table.";
                }

                // Close the database connection
                $conn->close();
            ?>
            <div class="form-group">
                <button type="submit" name="submit">Drop Course</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['submit'])) { // Check if the form is submitted
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

    // Get selected values from the form
    if (isset($_POST['course_program_session_teacher'])) {
        $selectedValues = explode(' ', $_POST['course_program_session_teacher']);
        $selectedCourse = $selectedValues[0];
        $selectedSession = $selectedValues[1];
        $selectedProgram = $selectedValues[2];
        $selectedTeacher = $selectedValues[3].' '.$selectedValues[4];
        // SQL query to delete the selected entry
        $deleteQuery = "DELETE FROM `teacher_course` WHERE `subject`='$selectedCourse' AND `session`='$selectedSession' AND `program`='$selectedProgram' AND `teacher`='$selectedTeacher'";

        if ($conn->query($deleteQuery) === TRUE) {
            // Deletion successful
            echo "<script>alert('Course Dropped successfully');</script>";
            echo "<script>window.location.href='admin_homepage.php';</script>";
            exit();
        } else {
            // Deletion failed
            echo "Error: " . $deleteQuery . "<br>" . $conn->error;
        }
    } else {
        // No radio button selected
        echo "<script>alert('Please select a course to drop.');</script>";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightblue;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            padding: 20px;
            margin: 20px;
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
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: rgb(75, 75, 204);
            color: white;
        }

        .notification {
            background-color: #ffcccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
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
    <div class = "container">
        <h2>Notifications</h2>
        <table>
        <?php
                // Retrieve parameters from the URL
                $selectedProgram = $_GET['program'];
                $Session = $_GET['session'];
                $selectedSession="20".$Session;
                $email = $_GET['email']; // Assuming $email is "ce21001@itu.edu.pk"
                $rollno = explode('@', $email); // $rollno is now an array ["ce21001", "itu.edu.pk"]
                $rollNumber = $rollno[0]; // Extract the roll number, which is "ce21001"

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
                $query = "SELECT * FROM teacher_course WHERE `program` = '$selectedProgram' AND `session`='$selectedSession'";
                $result = $conn->query($query);

                // Check if there are courses for the student
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Build the attendance table name for the current course
                        $studentsTableName = strtolower($row['subject']) . strtolower($selectedProgram) . substr($selectedSession, -2) . "attendance";

                        // Check if the attendance table exists
                        $checkStudentsTableQuery = "SHOW TABLES LIKE '$studentsTableName'";
                        $checkStudentsTableResult = $conn->query($checkStudentsTableQuery);

                        if ($checkStudentsTableResult->num_rows > 0) {
                            // Retrieve the percentage for the student from the attendance table
                            $getAttendanceQuery = "SELECT `Percentage` FROM `$studentsTableName` WHERE `Roll no.` = '$rollNumber'";
                            $attendanceResult = $conn->query($getAttendanceQuery);

                            if ($attendanceResult->num_rows > 0) {
                                $attendanceData = $attendanceResult->fetch_assoc();
                                $percentage = $attendanceData['Percentage'];

                                // Check if the attendance percentage is below 75%
                                if ($percentage < 75) {
                                    echo '<div class="notification">';
                                    echo "In $row[subject], your attendance is below 75%.";
                                    echo '</div>';
                                }
                            }
                        }
                    }
                }

                // Close the database connection
                $conn->close();
            ?>
        </table>
        <div class="button-group">
            <button onclick="window.location.href='student_homepage.php'">Back to Homepage</button>
        </div>
    </div>
</body>
</html>
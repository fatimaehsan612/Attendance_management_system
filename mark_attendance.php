<?php
// Assuming you have already validated and sanitized the input parameters
$selectedCourse = $_GET['course'];
$selectedProgram = $_GET['program'];
$selectedSession = $_GET['session'];
$role =$_GET['role'];
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

// Assuming you have a form submission to mark attendance
if ($_SERVER["REQUEST_METHOD"] == "POST") {
       
        // Create or update attendance table
        $attendanceTableName = strtolower($selectedCourse . $selectedProgram . substr($selectedSession, -2) . "attendance");
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `$attendanceTableName` (
            `Name` varchar(255) NOT NULL,
            `Roll no.` varchar(191) PRIMARY KEY NOT NULL,
            `Percentage` DECIMAL(5,2) DEFAULT 0.00
        )";

        // Execute the query to create the table if not exists
        $conn->query($createTableQuery);

        // Determine the last lecture number
        $getLastLectureQuery = "SHOW COLUMNS FROM `$attendanceTableName` LIKE 'Lecture%'";
        $lastLectureResult = $conn->query($getLastLectureQuery);

        $lectureColumns = $lastLectureResult->fetch_all(MYSQLI_ASSOC);

        if (empty($lectureColumns)) {
            // If no lecture columns exist, create the first lecture column
            $newLecture = 1;
            $alterTableQuery = "ALTER TABLE `$attendanceTableName` ADD COLUMN `Lecture $newLecture` VARCHAR(1)";
            $conn->query($alterTableQuery);

            // Fetch student data from the respective program and session table
            $studentsQuery = "SELECT `Name`, `Roll no.` FROM `" . strtolower($selectedProgram) . substr($selectedSession, -2) . "info`";
            $studentsResult = $conn->query($studentsQuery);

            while ($student = $studentsResult->fetch_assoc()) {
                $insertDataQuery = "INSERT INTO `$attendanceTableName` (`Name`, `Roll no.`, `Lecture $newLecture`, `Percentage`) VALUES ('{$student['Name']}', '{$student['Roll no.']}', '', 0.00)";
                $conn->query($insertDataQuery);
            }
        } else {
            // Use the last lecture column for the new lecture
            $lastLectureData = end($lectureColumns);
            preg_match('/\d+/', $lastLectureData['Field'], $matches);
            $newLecture = intval($matches[0]) + 1;
            if ($newLecture == 31) {
                // Display an alert and redirect
                echo "<script>alert('You have completed all lectures. There is no more remaining attendance to mark.'); window.location.href = 'admin_homepage.html';</script>";
                exit();
            }

            // Alter table to add a new lecture column
            $alterTableQuery = "ALTER TABLE `$attendanceTableName` ADD COLUMN `Lecture $newLecture` VARCHAR(1)";
            $conn->query($alterTableQuery);
        }

        // Loop through the submitted data to mark attendance and calculate percentage
        foreach ($_POST['attendance'] as $rollNo => $status) {
            // Update the attendance table for each student
            $updateAttendanceQuery = "UPDATE `$attendanceTableName` SET `Lecture $newLecture` = '$status' WHERE `Roll no.` = '$rollNo'";
            $conn->query($updateAttendanceQuery);

            // Initialize Count_P to 0
            $countP = 0;

            // Calculate total count of 'P' for each student
            for ($i = 1; $i <= $newLecture; $i++) {
                $countQuery = "SELECT COUNT(`Lecture $i`) AS count FROM `$attendanceTableName` WHERE `Lecture $i` = 'P' AND `Roll no.` = '$rollNo'";
                $countResult = $conn->query($countQuery);

                if ($countResult) {
                    $countRow = $countResult->fetch_assoc();
                    $countP += $countRow['count'];
                }
            }

            // Calculate percentage and update the Percentage column
            $percentage = ($countP / $newLecture) * 100;
            $calculatePercentageQuery = "UPDATE `$attendanceTableName` SET `Percentage` = '$percentage' WHERE `Roll no.` = '$rollNo'";
            $conn->query($calculatePercentageQuery);
        }

        // Close the database connection
        $conn->close();

        // Display success message and redirect
        echo "<script>alert('Attendance marked successfully');</script>";
        if ($role === 'Teacher') {
            // Redirect to teacher homepage
            echo "<script>window.location.href='teacher_homepage.php';</script>";
            exit();
        } elseif ($role === 'Admin') {
            // Redirect to admin homepage
            echo "<script>window.location.href='admin_homepage.php';</script>";
            exit();
        }
        exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
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
        <h2>Mark Attendance</h2>
        <form method="post">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Roll no.</th>
                    <th>Present</th>
                    <th>Absent</th>
                </tr>
                <?php
                // Fetch student data from the respective program and session table
                $studentsQuery = "SELECT `Name`, `Roll no.` FROM `" . strtolower($selectedProgram) . substr($selectedSession, -2) . "info`";
                $studentsResult = $conn->query($studentsQuery);
                $studentsTableName = strtolower($selectedProgram) . substr($selectedSession, -2) . "info";
                $checkStudentsTableQuery = "SHOW TABLES LIKE '$studentsTableName'";
                $checkStudentsTableResult = $conn->query($checkStudentsTableQuery);

                if ($checkStudentsTableResult->num_rows === 0) {
                    // If the table doesn't exist, display an alert and redirect
                    echo "<script>alert('No students are enrolled in this course.'); window.location.href = 'admin_homepage.html';</script>";
                    $conn->close();
                    exit();
                }
                while ($student = $studentsResult->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $student['Name'] . '</td>';
                    echo '<td>' . $student['Roll no.'] . '</td>';
                    echo '<td><input type="radio" name="attendance[' . $student['Roll no.'] . ']" value="P"></td>';
                    echo '<td><input type="radio" name="attendance[' . $student['Roll no.'] . ']" value="A"></td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <div class="button-group">
                <button type="submit">Submit Attendance</button>
            </div>
        </form>
    </div>
</body>
</html>

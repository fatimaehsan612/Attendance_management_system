
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
    height: 100vh;
}

.container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    max-width: 1500px;
    padding: 20px;
    margin: 20px;
    overflow-x: auto; /* Added overflow-x property */
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
    border: 1px solid black;
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
    <div class = "container">
        <h2>View Attendance</h2>
        <table>
            <?php
            // Assuming you have already validated and sanitized the input parameters
            $selectedCourse = $_GET['course'];
            $selectedProgram = $_GET['program'];
            $selectedSession = $_GET['session'];
            $role=$_GET['role'];
            if($role==='Student'){
                $email = $_GET['email']; // Assuming $email is "ce21001@itu.edu.pk"
                $rollno = explode('@', $email); // $rollno is now an array ["ce21001", "itu.edu.pk"]
                $rollNumber = $rollno[0]; // Extract the roll number, which is "ce21001"
            }
            
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
            $studentsTableName = strtolower($selectedCourse) . strtolower($selectedProgram) . substr($selectedSession, -2) . "attendance";
            $checkStudentsTableQuery = "SHOW TABLES LIKE '$studentsTableName'";
            $checkStudentsTableResult = $conn->query($checkStudentsTableQuery);

            if ($checkStudentsTableResult->num_rows === 0) {
                // If the table doesn't exist, display an alert and redirect
                echo "<script>alert('No Attendance Record for this course');</script>";
                if($role==='Admin'){
                    echo "<script>window.location.href='admin_homepage.php';</script>";
                }
                else if($role==='Student'){
                    echo "<script>window.location.href='student_homepage.php';</script>";
                }
                else{
                    echo "<script>window.location.href='teacher_homepage.php';</script>";
                }
                $conn->close();
                exit();
            }
            // Fetch columns from the database
            $columnsQuery = "SHOW COLUMNS FROM `" . strtolower($selectedCourse) . strtolower($selectedProgram) . substr($selectedSession, -2) . "attendance`";
            $columnsResult = $conn->query($columnsQuery);
            $columns = [];
            while ($column = $columnsResult->fetch_assoc()) {
                $columns[] = $column['Field'];
            }

            // Display table headers
            echo '<tr>';
            foreach ($columns as $column) {
                echo '<th>' . $column . '</th>';
            }
            echo '</tr>';
            if($role==='Student'){
                $studentsQuery = "SELECT * FROM `" . strtolower($selectedCourse) . strtolower($selectedProgram) . substr($selectedSession, -2) . "attendance` WHERE `Roll no.`='$rollNumber'";
                $studentsResult = $conn->query($studentsQuery);
            }
            else{
                $studentsQuery = "SELECT * FROM `" . strtolower($selectedCourse) . strtolower($selectedProgram) . substr($selectedSession, -2) . "attendance`";
                $studentsResult = $conn->query($studentsQuery);
            }

            while ($student = $studentsResult->fetch_assoc()) {
                echo '<tr>';
                foreach ($columns as $column) {
                    echo '<td>' . $student[$column] . '</td>';
                }
                echo '</tr>';
            }
            
            ?>
        </table>
        <div class="button-group">
            <button id="backToHomepage">Back to Homepage</button>
        </div>
    </div>

    <!-- JavaScript for redirection -->
    <script>
        // Add an event listener to the Back to Homepage button
        document.getElementById("backToHomepage").addEventListener("click", function() {
            // Redirect based on the user's role
            <?php
            if ($role === 'Admin') {
                echo "window.location.href = 'admin_homepage.php';";
            } elseif ($role === 'Student') {
                echo "window.location.href = 'student_homepage.php';";
            } else {
                echo "window.location.href = 'teacher_homepage.php';";
            }
            ?>
        });
    </script>
</body>
</html>


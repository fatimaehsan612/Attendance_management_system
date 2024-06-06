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
    margin-top: 5%;
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
input[type="email"],
input[type="tel"],
input[type="number"],
select,
textarea {
    width: calc(100% - 20px);
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
    <title>Student Registration Form</title>
</head>
<body>
    <div class="container">
        <h1>Student Registration Form</h1>
        <form method="post"> <!-- Make sure to update the action attribute -->
            <div class="form-row">
                <div class="form-group inline">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group inline">
                    <label for="phone">Phone No:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>  
            </div>

            <div class="form-row">
                
                <div class="form-group inline">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group inline">
                    <label for="dob">Date of Birth:</label>
                    <input type="text" id="dob" name="dob" placeholder="YYYY-MM-DD" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group inline">
                    <label for="semester">Semester:</label>
                    <input type="number" id="semester" name="semester" required min="1" max="8">
                </div>
                <div class="form-group inline">
                    <label for="program">Program:</label>
                    <input type="text" id="program" name="program" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group inline">
                    <label for="session">Session:</label>
                    <input type="text" id="session" name="session" required>
                </div>
                <div class="form-group inline">
                    <label for="address">Home Address:</label>
                    <textarea id="address" name="address" required></textarea>
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
// Check if the form is submitted
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
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $semester = $_POST['semester'];
    $program = $_POST['program'];
    $session = $_POST['session'];
    $phone = $_POST['phone'];

    // Formulate the table name based on program and session
    $tableName = strtolower($program) . substr($session, -2) . "info";

    // Check if the table exists, if not, create it
    $createTableQuery = "CREATE TABLE IF NOT EXISTS `$tableName` (
                            `Name` varchar(255) NOT NULL,
                            `Roll no.` varchar(191) PRIMARY KEY NOT NULL,
                            `Email` varchar(255) NOT NULL,
                            `DOB` varchar(255) NOT NULL,
                            `Gender` varchar(255) NOT NULL,
                            `Address` varchar(255) NOT NULL,
                            `Semester` varchar(255) NOT NULL,
                            `Program` varchar(255) NOT NULL,
                            `Session` varchar(255) NOT NULL,
                            `Phone Number` varchar(255) NOT NULL
                        )";

    if ($conn->query($createTableQuery) === TRUE) {
        // Check if the information already exists in the table
        $checkIfExistsQuery = "SELECT * FROM `$tableName` WHERE `Name`='$name' AND `Address`='$address'";
        $result = $conn->query($checkIfExistsQuery);

        if ($result->num_rows > 0) {
            // Information already exists
            echo "<script>alert('Student is already enrolled.'); window.location.href='admin_homepage.php';</script>";
            $conn->close();
            exit();
        }

        // Count the number of rows in the table
        $countRowsQuery = "SELECT COUNT(*) AS row_count FROM `$tableName`";
        $countResult = $conn->query($countRowsQuery);

        if ($countResult && $countRow = $countResult->fetch_assoc()) {
            $rowCount = $countRow['row_count'];
            $rollPrefix = strtolower($program) . substr($session, -2);
            $rollno = $rollPrefix . sprintf('%03d', $rowCount + 1);
        } else {
            // Error in counting rows
            echo "Error in counting rows: " . $conn->error;
            exit();
        }

        $email = $rollno. "@itu.edu.pk";
        
        // SQL query to insert data into the dynamically created table
        $insertQuery = "INSERT INTO `$tableName` (`Name`, `Roll no.`, `Email`, `DOB`, `Gender`, `Address`, `Semester`, `Program`, `Session`, `Phone Number`)
                        VALUES ('$name', '$rollno', '$email', '$dob', '$gender', '$address', '$semester', '$program', '$session', '$phone')";

        if ($conn->query($insertQuery) === TRUE) {
            // Registration successful
            echo "<script>alert('Registered successfully');</script>";
            echo "<script>window.location.href='admin_homepage.php';</script>";
            exit();
        } else {
            // Registration failed
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    } else {
        // Registration failed - table creation error
        echo "Error: " . $createTableQuery . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>





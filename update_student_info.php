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
    <title>Update Student Info</title>
</head>
<body>
    <div class="container">
        <h1>Student Update Form</h1>
        <form method="post" action="update_student_info.php">
            <div class="form-row">
                <div class="form-group inline">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group inline">
                    <label for="rollno">Roll No:</label>
                    <input type="text" id="rollno" name="rollno" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group inline">
                    <label for="phone">Phone No:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group inline">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group inline">
                    <label for="dob">Date of Birth:</label>
                    <input type="text" id="dob" name="dob" placeholder="YYYY-MM-DD" required>
                </div>
                <div class="form-group inline">
                    <label for="semester">Semester:</label>
                    <input type="number" id="semester" name="semester" required>
                </div>

            </div>

            <div class="form-row">
              
                <div class="form-group inline">
                    <label for="program">Program:</label>
                    <input type="text" id="program" name="program" required>
                </div>
                <div class="form-group inline">
                    <label for="session">Session:</label>
                    <input type="text" id="session" name="session" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group inline">
                    <label for="address">Home Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

            
            </div>

            <div class="form-group">
                <button type="submit">Update</button>
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
    $rollno = $_POST['rollno'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $semester = $_POST['semester'];
    $program = $_POST['program'];
    $session = $_POST['session'];
    $phone = $_POST['phone'];

    // Formulate the table name based on program and session
    $tableName = strtolower($program) . substr($session, -2) . "info";

    // Check if the given roll number exists in the table
    $checkRollNoQuery = "SELECT * FROM `$tableName` WHERE `Roll no.` = '$rollno'";
    $checkRollNoResult = $conn->query($checkRollNoQuery);

    if ($checkRollNoResult->num_rows === 0) {
        // Roll number not found, display an alert and exit
        echo "<script>alert('Student with Roll No. $rollno is not enrolled.'); window.location.href='admin_homepage.php';</script>";
        $conn->close();
        exit();
    }

    // SQL query to update data in the 'student' table based on roll number
    $sql = "UPDATE `$tableName` SET 
        Name = '$name',
        Email = '$email',
        DOB = '$dob',
        Gender = '$gender',
        Address = '$address',
        Semester = '$semester',
        Program = '$program',
        Session = '$session',
        `Phone Number` = '$phone'
        WHERE `Roll no.` = '$rollno'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Updated successfully');</script>";
        echo "<script>window.location.href='admin_homepage.php';</script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

?>

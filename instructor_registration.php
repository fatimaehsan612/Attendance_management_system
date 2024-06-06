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
    color:rgb(75, 75, 204)
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
select,
textarea {
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
    <title>Teacher Registration Form</title>
</head>
<body>
    <div class="container">
        <h1>Instructor Registration Form</h1>
        <form method="post"> <!-- Update the action attribute -->
            <div class="form-row">
                <div class="form-group inline">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="form-group inline">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
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
                    <label for="degree">Degree:</label>
                    <input type="text" id="degree" name="degree" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group inline">
                    <label for="address">Address:</label>
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
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $degree = $_POST['degree'];
    $address = $_POST['address'];
    // Check if the information already exists in the table
    $email = $firstName . "." . $lastName . "@itu.edu.pk";
    $checkIfExistsQuery = "SELECT * FROM `teacher` WHERE `email`='$email' AND `Address`='$address'";
    $result = $conn->query($checkIfExistsQuery);

    if ($result->num_rows > 0) {
        // Information already exists
        echo "<script>alert('Teacher already exists.');</script>";
        $conn->close();
        exit();
    }
    // SQL query to insert data into the 'teacher' table
    $sql = "INSERT INTO teacher (`firstName`, `lastname`, `email`, `phone`, `gender`, `dob`, `degree`, `address`)
        VALUES ('$firstName', '$lastName', '$email', '$phone', '$gender', '$dob', '$degree', '$address')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful
        echo "<script>alert('Registered successfully');</script>";
        echo "<script>window.location.href='admin_homepage.php';</script>";
        exit();
    } else {
        // Registration failed
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>


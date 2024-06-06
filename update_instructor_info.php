    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="instructor_registration.css">
    <title>Update Teacher info</title>
</head>
<body>
    <div class="container">
        <h1>Update Instructor info</h1>
        <form method="post">
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
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
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
                    <label for="degree">Degree:</label>
                    <input type="text" id="degree" name="degree" required>
                </div>

                <div class="form-group inline">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
            </div>

            
                <!-- Your form fields -->
                <button type="submit">Update</button>
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
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $degree = $_POST['degree'];
    $address = $_POST['address'];

    // Check if the given email exists in the table
    $checkEmailQuery = "SELECT * FROM teacher WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows === 0) {
        // Email not found, display an alert and exit
        echo "<script>alert('Instructor with Email $email is not registered.'); window.location.href='admin_homepage.php';</script>";
        $conn->close();
        exit();
    }

    // SQL query to update data in the 'teacher' table based on email
    $sql = "UPDATE teacher SET
            firstName = '$firstName',
            lastname = '$lastName',
            phone = '$phone',
            gender = '$gender',
            dob = '$dob',
            degree = '$degree',
            address = '$address'
            WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        // Update successful
        echo "<script>alert('Updated successfully');</script>";
        echo "<script>window.location.href='admin_homepage.php';</script>";
        exit();
    } else {
        // Update failed
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>


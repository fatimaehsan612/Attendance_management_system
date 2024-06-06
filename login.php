<?php
session_start(); // Start session to store user data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
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

    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate user input (you should do more validation/sanitization)
    if (empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('Please fill in all fields');</script>";
    } else {
        // Check user credentials based on the selected role
        if ($role == 'Admin') {
            $table = 'admin';

            // Validate if the email is enrolled in the admin table
            $emailCheckQuery = "SELECT * FROM `$table` WHERE `email` = '$email'";
            $emailCheckResult = $conn->query($emailCheckQuery);

            if ($emailCheckResult === false) {
                // Display an error message
                echo "<script>alert('Admin not enrolled');</script>";
            } else {
                if ($emailCheckResult->num_rows > 0) {
                    // Email exists in the admin table, now check the password
                    $query = "SELECT * FROM `$table` WHERE `email` = '$email' AND `dob` = '$password'";
                    $result = $conn->query($query);

                    if ($result === false) {
                        // Display an error message
                        echo "<script>alert('Invalid password');</script>";
                    } else {
                        if ($result->num_rows > 0) {
                            // Valid admin, store user data in session
                            $_SESSION['email'] = $email;
                            $_SESSION['role'] = $role;
                            header("Location: admin_homepage.php");
                            exit();
                        } else {
                            // Password does not match
                            echo "<script>alert('Invalid password');</script>";
                        }
                    }
                } else {
                    // Email not enrolled in the admin table
                    echo "<script>alert('Admin not enrolled');</script>";
                }
            }

        } elseif ($role == 'Teacher') {
            $table = 'teacher';

            // Validate if the email is enrolled in the teacher table
            $emailCheckQuery = "SELECT * FROM `$table` WHERE `Email` = '$email'";
            $emailCheckResult = $conn->query($emailCheckQuery);

            if ($emailCheckResult === false) {
                // Display an error message
                echo "<script>alert('Teacher not enrolled');</script>";
            } else {
                if ($emailCheckResult->num_rows > 0) {
                    // Email exists in the teacher table, now check the password
                    $query = "SELECT * FROM `$table` WHERE `Email` = '$email' AND `DOB` = '$password'";
                    $result = $conn->query($query);

                    if ($result === false) {
                        echo "<script>alert('Invalid password');</script>";
                    } else {
                        if ($result->num_rows > 0) {
                            // Valid teacher, store user data in session
                            $_SESSION['email'] = $email;
                            $_SESSION['role'] = $role;

                            // Get first name and last name from the email
                            list($firstName, $lastName) = explode('.', strstr($email, '@', true));

                            // Store first name and last name in session
                            $_SESSION['firstName'] = $firstName;
                            $_SESSION['lastName'] = $lastName;

                            // Redirect to teacher_homepage.php
                            header("Location: teacher_homepage.php");
                            exit();

                        } else {
                            echo "<script>alert('Invalid password');</script>";
                        }
                    }
                } else {
                    // Email not enrolled in the teacher table
                    echo "<script>alert('Teacher not enrolled');</script>";
                }
            }
        } elseif ($role == 'Student') {
            // Dynamically create the table name based on the first 4 letters of the email
            $program = substr($email, 0, 2); // First 2 digits for program
            $session = substr($email, 2, 2); // Next 2 digits for session
            $table = $program . $session . "info";

            // Check if the table exists
            $checkTableQuery = "SHOW TABLES LIKE '$table'";
            $tableExists = $conn->query($checkTableQuery);

            if ($tableExists->num_rows > 0) {
                // Validate if the email is enrolled in the student table
                $emailCheckQuery = "SELECT * FROM `$table` WHERE `Email` = '$email'";
                $emailCheckResult = $conn->query($emailCheckQuery);

                if ($emailCheckResult === false) {
                    // Display an error message
                    echo "<script>alert('Student not enrolled');</script>";
                } else {
                    if ($emailCheckResult->num_rows > 0) {
                        // Email exists in the student table, now check the password
                        $query = "SELECT * FROM `$table` WHERE `Email` = '$email' AND `DOB` = '$password'";
                        $result = $conn->query($query);

                        if ($result === false) {
                            echo "<script>alert('Invalid password');</script>";
                        } else {
                            if ($result->num_rows > 0) {
                                // Valid student, store user data in session
                                $_SESSION['email'] = $email;
                                $_SESSION['role'] = $role;
                                $_SESSION['program'] = $program;
                                $_SESSION['session'] = $session;
                                header("Location: student_homepage.php");
                                exit();
                            } else {
                                echo "<script>alert('Invalid password');</script>";
                            }
                        }
                    } else {
                        // Email not enrolled in the student table
                        echo "<script>alert('Student not enrolled');</script>";
                    }
                }
            } else {
                // Table for the given program and session does not exist
                echo "<script>alert('Student not enrolled');</script>";
            }
        } else {
            // Invalid role
            echo "<script>alert('Invalid role');</script>";
        }

        $conn->close(); // Close the database connection
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    body {
    background-color: lightblue;
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    margin: 0;
}

.box {
    margin: 5% 14%;
}

.portion_1 {
    float: left;
    width: 45%;
    background-color: rgb(75, 75, 204);
    color: white;
    font-weight: bold;
    font-size: 75px;
    display: inline;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    padding-left: 5%;
    padding-top: 14%;
    padding-bottom: 14%;
}

.portion_2 {
    margin-left: 50%;
    background-color: white;
    color: black;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    padding: 7% 9%;
}

.hello {
    font-weight: bold;
    font-size: 21px;
}

p {
    display: inline;
    margin: 0;
}

input {
    padding-bottom: 5%;
    margin-bottom: 5%;
    border-radius: 14px;
    width: 75%;
    padding-top: 3.5%;
    padding-left: 9%;
    border: 1px solid lightgrey;
    transition: border-color 0.3s;
    margin-top: 10px;
}

input[type="email"],
input[type="password"] {
    border: 2px solid rgb(75, 75, 204);
}

.login {
    background-color: rgb(75, 75, 204);
    color: white;
    width: 87%;
    padding-left: 0%;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.password {
    padding-left: 100px;
}

input[type="radio"] {
    width: 19px;
    height: 19px;
    border: 1px solid rgb(75, 75, 204);
    border-radius: 50%;
    margin-right: 10px;
    position: relative;
    top: 6px;
}

input[type="radio"]:checked::before {
    content: "";
    width: 12px;
    height: 12px;
    background-color: lightblue;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.box form {
    margin: 0;
}

/* Style for the submit button on hover */
.login:hover {
    background-color: rgb(60, 60, 200);
}

</style>
</head>
<body>
    <div class="box">
        <div class="portion_1">
            <p>Attendance Management System</p>
        </div>
        <div class="portion_2">
            <form method="post" action="">
                <p class="hello">Hello!<br/></p>
                <p>Welcome<br/><br/></br>Please select your role :</p><br/></p>
                <input type="radio" name="role" value="Admin"/>Admin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="role" value="Teacher"/>Teacher&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="role" value="Student"/>Student<br/><br/><br/>
                <input type="email" name="email" placeholder="Email Address"/>
                <input type="password" name="password" placeholder="DOB (YYYY-MM-DD)"/>
                <input type="submit" value="Login" class="login"/><br/><br/>
            </form>
        </div>
    </div>
</body>
</html>

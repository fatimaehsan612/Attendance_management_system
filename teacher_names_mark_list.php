<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a teacher is selected
    if (isset($_POST['teacher'])) {
        // Get the selected teacher's first name and last name
        list($selectedFirstName, $selectedLastName, $selectedRole) = explode(' ', $_POST['teacher']);

        // Redirect to teacher_course_list.php with the selected teacher's first name and last name as parameters
        header("Location: teacher_course_mark_list.php?firstName=$selectedFirstName&lastName=$selectedLastName&role=$selectedRole");
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
    <title>Teacher Selection</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Select a Teacher:</h2>
        <form method="post">
            <?php
                // Database connection parameters
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "dbms_project";

                $selectedRole=$_GET['role'];

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch all teachers from the database
                $query = "SELECT * FROM teacher";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    echo '<table>';
                    echo '<tr><th>Name</th><th>Select</th></tr>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['firstName'] . ' ' . $row['lastname'] . '</td>';
                        echo '<td><input type="radio" name="teacher" value="' . $row['firstName'] . ' ' . $row['lastname'] . ' ' . $selectedRole . '"></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo "No teachers found in the database.";
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

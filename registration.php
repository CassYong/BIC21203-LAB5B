<!DOCTYPE html>
<html>

<head>
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 40%;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }

        .success {
            color: green;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Registration</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="matric">Matric No:</label>
            <input type="text" id="matric" name="matric" required><br><br>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Please select</option>
                <option value="lecturer">Lecturer</option>
                <option value="student">Student</option>
            </select><br><br>

            <input type="submit" value="Submit">

            <?php
            // Process the form data if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Connect to the database
                $servername = "localhost";
                $username = "root";  // Default username for local server
                $password = "";      // Default password for local server
                $dbname = "lab_5b";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare and bind the SQL statement
                $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $matric, $name, $hashed_password, $role);

                // Get data from the form
                $matric = $_POST['matric'];
                $name = $_POST['name'];
                $password = $_POST['password'];
                $role = $_POST['role'];

                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Execute the statement
                if ($stmt->execute()) {
                    echo "<div class='success'>Registration successful!</div>";
                } else {
                    echo "<div class='error'>Error: " . $stmt->error . "</div>";
                }

                // Close the statement and connection
                $stmt->close();
                $conn->close();
            }
            ?>
        </form>
    </div>
</body>

</html>

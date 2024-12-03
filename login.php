<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
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
        input[type="password"] {
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

        .register {
            margin-top: 15px;
        }

        .register a {
            color: #4CAF50;
            text-decoration: none;
        }

        .register a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="matric">Matric No:</label>
            <input type="text" id="matric" name="matric" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Login">
        </form>

        <div class="register">
            <p><a href="registration.php">Register</a> here if you have not.</p>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "lab_5b";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get form data
            $matric = $_POST['matric'];
            $password = $_POST['password'];

            // Check user
            $sql = "SELECT * FROM users WHERE matric = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $matric);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                echo "<div class='error'>No user found with that matric number. Please <a href='registration.php'>register</a>.</div>";
            } else {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    header("Location: users.php");
                    exit();
                } else {
                    echo "<div class='error'>Invalid password, please try again.</div>";
                }
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>

</html>

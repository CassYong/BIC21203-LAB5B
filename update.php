<!DOCTYPE html>
<html>

<head>
    <title>Update User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        form {
            width: 40%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], select {
            width: 95%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <h2>Update User Information</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $matric = $_POST['matric'];
        $name = $_POST['name'];
        $role = $_POST['role'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lab_5b";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE users SET name = ?, role = ? WHERE matric = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $name, $role, $matric);

            if ($stmt->execute()) {
                header("Location: users.php");
                exit();
            } else {
                echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='error-message'>Error preparing statement: " . $conn->error . "</p>";
        }

        $conn->close();
    }

    if (isset($_GET['matric'])) {
        $matric = $_GET['matric'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lab_5b";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT matric, name, role FROM users WHERE matric = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $name = $user['name'];
            $role = $user['role'];
        } else {
            echo "<p class='error-message'>User not found.</p>";
            exit();
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<p class='error-message'>No matric number provided.</p>";
        exit();
    }
    ?>

    <form action="" method="post">
        <label for="matric">Matric</label>
        <input type="text" name="matric" value="<?php echo htmlspecialchars($matric); ?>" readonly><br>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>
        <label for="role">Role</label>
        <select name="role" id="role" required>
            <option value="lecturer" <?php if ($role == 'lecturer') echo 'selected'; ?>>Lecturer</option>
            <option value="student" <?php if ($role == 'student') echo 'selected'; ?>>Student</option>
        </select><br><br>
        <input type="submit" value="Update">
    </form>
</body>

</html>

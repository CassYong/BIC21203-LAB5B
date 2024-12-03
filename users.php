<!DOCTYPE html>
<html>

<head>
    <title>Users List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        td a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        td a:hover {
            color: #0056b3;
        }

        .no-users {
            text-align: center;
            font-size: 16px;
            color: #777;
        }

        .delete-link {
            text-decoration: none;
            color: #FF0000;
            font-weight: bold;
            cursor: pointer;
        }

        .delete-link:hover {
            color: #b30000;
        }
    </style>
</head>

<body>
    <h1>Users List</h1>

    <?php
    // Handle deletion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_matric'])) {
        $matricToDelete = $_POST['delete_matric'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lab_5b";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "DELETE FROM users WHERE matric = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $matricToDelete);

        if ($stmt->execute()) {
            echo "<p style='text-align:center;color:green;'>User with Matric No: $matricToDelete deleted successfully.</p>";
        } else {
            echo "<p style='text-align:center;color:red;'>Error deleting user: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <table>
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Role</th>
            <th colspan="2">Action</th>
        </tr>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lab_5b";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT matric, name, role FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["matric"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                echo "<td><a href='update.php?matric=" . urlencode($row["matric"]) . "'>Update</a></td>";
                echo "<td>
                        <form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                            <input type='hidden' name='delete_matric' value='" . htmlspecialchars($row["matric"]) . "'>
                            <a class='delete-link' onclick='this.closest(\"form\").submit(); return false;'>Delete</a>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='no-users'>No users found</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>

</html>

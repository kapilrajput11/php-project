<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Create table if not exists
$query = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$db->exec($query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    $insert = "INSERT INTO users (name, email) VALUES (:name, :email)";
    $stmt = $db->prepare($insert);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        $message = "User added successfully!";
    } else {
        $message = "Error adding user.";
    }
}

// Fetch all users
$query = "SELECT * FROM users ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        form {
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            color: #155724;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>User Management System</h1>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Enter Name" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <button type="submit">Add User</button>
        </form>

        <h2>Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
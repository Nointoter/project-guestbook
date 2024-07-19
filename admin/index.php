<?php
include '../config.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
}

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Guestbook</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
    <h1>Admin - Guestbook</h1>
    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>E-mail</th>
                <th>Text</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['text']}</td>
                <td>{$row['ip_address']}</td>
                <td>{$row['user_agent']}</td>
                <td><a href='index.php?delete={$row['id']}'>Delete</a></td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>

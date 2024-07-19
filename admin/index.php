<?php
include '../config.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

// Pagination settings
$limit = 25; // Number of messages per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Get total number of messages
$result = $conn->query("SELECT COUNT(*) AS total FROM messages");
$total_messages = $result->fetch_assoc()['total'];
$total_pages = ceil($total_messages / $limit);

// Fetch messages for the current page
$stmt = $conn->prepare("SELECT * FROM messages ORDER BY created_at DESC LIMIT ?, ?");
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();
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
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>

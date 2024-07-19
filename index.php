<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $text = $conn->real_escape_string($_POST['text']);
    $captcha = $conn->real_escape_string($_POST['captcha']);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    if ($captcha != $_SESSION['captcha_code']) {
        echo "Incorrect CAPTCHA.";
    } else {
        $sql = "INSERT INTO messages (username, email, text, ip_address, user_agent) VALUES ('$user_name', '$email', '$text', '$ip_address', '$user_agent')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Пагинация
$records_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$order = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';
$next_order = $order == 'ASC' ? 'desc' : 'asc';

$result = $conn->query("SELECT * FROM messages ORDER BY $sort $order LIMIT $offset, $records_per_page");

$total_records_result = $conn->query("SELECT COUNT(*) AS total FROM messages");
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guestbook</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <h1>Guestbook</h1>
    <form action="index.php" method="post">
        <label for="username">User Name:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="captcha">CAPTCHA:</label>
        <div class="captcha-container">
            <img src="captcha/captcha.php" id="captchaImage" alt="CAPTCHA Image">
            <button type="button" id="refreshCaptcha">Refresh CAPTCHA</button>
        </div>
        <input type="text" id="captcha" name="captcha" required>
        
        <label for="text">Text:</label>
        <textarea id="text" name="text" required></textarea>
        
        <input type="submit" value="Submit">
    </form>
    <h2>Messages</h2>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=username&order=<?= $next_order ?>">User Name</a></th>
                <th><a href="?sort=email&order=<?= $next_order ?>">E-mail</a></th>
                <th><a href="?sort=created_at&order=<?= $next_order ?>">Date</a></th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['created_at']}</td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="<?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>

<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guestbook";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
        <img src="captcha/captcha.php" alt="CAPTCHA Image">
        <input type="text" id="captcha" name="captcha" required>
        
        <label for="text">Text:</label>
        <textarea id="text" name="text" required></textarea>
        
        <input type="submit" value="Submit">
    </form>
    <h2>Messages</h2>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=username">User Name</a></th>
                <th><a href="?sort=email">E-mail</a></th>
                <th><a href="?sort=created_at">Date</a></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
        $order = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';
        $next_order = $order == 'ASC' ? 'desc' : 'asc';

        $result = $conn->query("SELECT * FROM messages ORDER BY $sort $order LIMIT 5");
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
</body>
</html>

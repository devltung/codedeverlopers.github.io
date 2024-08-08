<?php
session_start();

include 'db.php'; // Đảm bảo bạn đã kết nối cơ sở dữ liệu

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT banned FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if ($user['banned']) {
        header('Location: banned.php'); // Chuyển hướng đến trang thông báo bị cấm
        exit();
    }
}

$posts = []; // Khởi tạo biến với một giá trị mặc định là mảng rỗng

// Thực hiện truy vấn
$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

if ($result) {
    // Lấy tất cả dữ liệu từ truy vấn
    $posts = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Lỗi truy vấn cơ sở dữ liệu: " . $conn->error;
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link href="images/logo.png" rel="icon" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f0f0f0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
        }

        header .container {
            width: 80%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        nav {
            display: flex;
            align-items: center;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .welcome-message {
            font-size: 1.2em;
            font-weight: bold;
            padding: 5px 10px;
            border: 2px solid #ffffff;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
        }

        .username {
            color: pink;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            margin-left: 20px;
        }

        .button:hover {
            background-color: #45a049;
        }

        section {
            padding: 40px 0;
            border-bottom: 1px solid #ddd;
        }

        section .container {
            width: 80%;
            margin: 0 auto;
        }

        footer {
            background-color: #f1f1f1;
            color: #333;
            padding: 10px 0;
            text-align: center;
        }

        footer .container {
            width: 80%;
            margin: 0 auto;
        }

        .post {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .post h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .post h5 {
            font-size: 0.9em;
            color: #555;
            margin: 10px 0;
        }

        .post-content {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Số dòng hiển thị */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 10px;
        }

        .post-full {
            display: none;
            margin-top: 10px;
        }

        .post-read-more {
            display: block;
            font-weight: bold;
            color: #007BFF;
            cursor: pointer;
        }

        .post-read-more:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>WELCOME TO CODEPORTAL</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Trang Chủ</a></li>
                    <li><a href="about.php">Giới Thiệu</a></li>
                    <li><a href="exercise.php">Exercises</a></li>
                    <li><a href="ide.php">IDE</a></li>
                    <li>
                        <span class="welcome-message">
                            <span>Welcome, </span>
                            <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </span>
                    </li>
                    <a href="logout.php" class="button">Logout</a>
                </ul>
            </nav>
        </div>
    </header>

    <?php if (empty($posts)): ?>
        <section>
            <div class="container">
                <p>Không có bài viết để hiển thị.</p>
            </div>
        </section>
    <?php else: ?>
        <section>
            <div class="container">
                <h2>Posts:</h2>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                        <h5><em><?php echo isset($post['username']) ? htmlspecialchars($post['username']) : 'Admin'; ?> - <?php echo htmlspecialchars($post['created_at']); ?></em></h5>
                        <div class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                        <div class="post-full"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                        <a class="post-read-more" href="#">Xem thêm</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <footer>
        <div class="container">
            <p>&copy; 2024 CodePortal. Tất cả các quyền được bảo lưu.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.post-read-more').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var post = this.parentNode;
                var fullContent = post.querySelector('.post-full');
                var shortContent = post.querySelector('.post-content');
                
                if (fullContent.style.display === 'none') {
                    fullContent.style.display = 'block';
                    shortContent.style.display = 'none';
                    this.textContent = 'Rút gọn';
                } else {
                    fullContent.style.display = 'none';
                    this.textContent = 'Xem thêm';
                }
            });
        });
    </script>
</body>
</html>

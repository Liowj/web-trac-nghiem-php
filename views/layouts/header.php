<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Thi Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Thi Online</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-warning fw-bold">
                                👋 Chào, <?php echo $_SESSION['fullname']; ?>!
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white bg-danger rounded px-3 ms-2" href="index.php?page=logout">Đăng xuất</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=login">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=register">Đăng ký</a></li>
                    <?php endif; ?>
                
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
<?php
// Force PHP to show exact errors on your screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// If admin session is active, go straight to console
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

require_once 'koneksidb.php';
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksidb, trim($_POST['username']));
    $password = mysqli_real_escape_string($koneksidb, trim($_POST['password']));

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM tb_admins WHERE username = '$username' AND password = '$password' LIMIT 1";
        $result = mysqli_query($koneksidb, $query);

        if (mysqli_num_rows($result) == 1) {
            $user_data = mysqli_fetch_assoc($result);
            
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user_data['username'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error_msg = "TERMINAL ACCESS DENIED // INVALID ACCESS CODE";
        }
    } else {
        $error_msg = "TERMINAL PARAMETER MISMATCH // COMPLETE ALL FIELDS";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Access | PORTO SPACE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; overflow: hidden; position: relative;">
    <img src="assets/bg/about-bg.webp" alt="" class="sub-hero-bg" aria-hidden="true">
    <div class="hero-overlay"></div>
    <div class="scanline-overlay" aria-hidden="true"></div>

    <div class="container" style="max-width: 440px; position: relative; z-index: 10;">
        <div class="glass-panel text-center" style="border-radius: 4px; box-shadow: 0 20px 40px rgba(0,0,0,0.65);">
            <h1 class="logo mb-4" style="font-size: 1.8rem; display: block; letter-spacing: 6px;">PORTO SPACE</h1>
            <span class="label-text d-block mb-4" style="font-size: 0.85rem; opacity: 0.6; letter-spacing: 2px;">DISPATCH TERMINAL GATEWAY</span>

            <?php if (!empty($error_msg)): ?>
                <div class="text-start mb-4" style="background: rgba(196, 154, 108, 0.15); border: 1px solid var(--accent-status-amber); padding: 12px; font-size: 0.75rem; letter-spacing: 1px; color: var(--accent-status-amber); font-family: 'Jost', sans-serif;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>SEC_ALERT:</strong> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <div class="mb-3 text-start">
                    <input type="text" name="username" class="glass-input" placeholder="OFFICER USERNAME" required autocomplete="off" style="font-family: 'Jost', sans-serif; letter-spacing: 1.5px; font-size: 0.9rem;">
                </div>
                <div class="mb-4 text-start">
                    <input type="password" name="password" class="glass-input" placeholder="TERMINAL PASSKEY" required style="font-family: 'Jost', sans-serif; letter-spacing: 1.5px; font-size: 0.9rem;">
                </div>
                <button type="submit" class="btn-sleek w-100" style="padding: 12px 0; font-size: 0.8rem; letter-spacing: 3px; border-radius: 2px;">ACCESS CONSOLE</button>
            </form>

            <div class="mt-4 pt-3 border-top border-secondary">
                <a href="index.php" class="muted-text text-decoration-none" style="font-size: 0.75rem; letter-spacing: 2px;">&larr; PUBLIC GATEWAY</a>
            </div>
        </div>
    </div>
</body>
</html>
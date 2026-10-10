
<?php
session_start();
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!preg_match('/\A[^\x00-\x1F\x7F]{5,20}\z/us', $username)) {
        $errors[] = 'Username must be between 5 and 20 characters.';
    }
    if (!preg_match('/\A[^\x00-\x1F\x7F]{7,}\z/us', $password)) {
        $errors[] = 'Password must be at least 7 characters long.';
    }

    if ($errors === []) {
        try {
            $user = findUserByUsername($username);
            if ($user !== null && isset($user['password']) && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'fullname' => $user['fullname'],
                ];
                header('Location: index.php');
                exit;
            }

            $errors[] = 'Incorrect username or password.';
        } catch (JsonException | RuntimeException $exception) {
            error_log($exception->getMessage());
            $errors[] = 'Unable to check your login right now. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AIM Smile Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/stylelogin.css">
</head>

<body class="login-page">

    <main class="container">

        <section class="form-card">

            <div class="logo">
                <img src="logo.jpg" alt="AIM Smile Dental Clinic Logo">
            </div>

            <h1>AIM Smile</h1>

            <p class="clinic-name">
                DENTAL CLINIC
            </p>

            <h2>
                Welcome Back!
            </h2>

            <p class="subtitle">
                Login to your account
            </p>


            <form id="loginForm" method="post">

                <?php foreach ($errors as $error): ?>
                    <label class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></label>
                <?php endforeach; ?>

                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                        value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="login-password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="login-password"
                        name="password"
                        placeholder="Enter your password"
                    >

                </div>


                <div class="options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        Remember me

                    </label>

                    <a href="#">
                        Forgot Password?
                    </a>

                </div>


                <button type="submit">
                    Login
                </button>

            </form>


            <p class="account-text">

                Don't have an account?

                <a href="register.php">
                    Register
                </a>

            </p>

        </section>

    </main>

<?php include 'includes/login_val.php'; ?>


</body>
</html>
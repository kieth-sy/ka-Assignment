<?php
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirm-password'] ?? '';

    if (!preg_match('/\A[\p{L}\p{M} .\'’-]{5,50}\z/u', $fullname)) {
        $errors[] = 'Name must be between 5 and 50 characters.';
    }
    if (
        !preg_match('/\A[^\s@]+@[^\s@]+\.[^\s@]{2,}\z/u', $email)
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (!preg_match('/\A[^\x00-\x1F\x7F]{5,20}\z/us', $username)) {
        $errors[] = 'Username must be between 5 and 20 characters.';
    }
    if (!preg_match('/\A[^\x00-\x1F\x7F]{7,}\z/us', $password)) {
        $errors[] = 'Password must be at least 7 characters long.';
    }
    if ($confirmation === '' || $confirmation !== $password) {
        $errors[] = 'Confirm password must be the same as your password.';
    }
    if (!isset($_POST['terms'])) {
        $errors[] = 'You must agree to the Terms and Conditions.';
    }

    if ($errors === []) {
        try {
            $success = createUser([
                'fullname' => $fullname,
                'email' => $email,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            if (!$success) {
                $errors[] = 'That username or email is already registered.';
            }
        } catch (JsonException | RuntimeException $exception) {
            error_log($exception->getMessage());
            $errors[] = 'Unable to save your account right now. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | AIM Smile Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/stylelogin.css">
</head>

<body class="register-page">

    <main class="container">

        <section class="form-card register-card">

            <div class="logo">
                <img src="logo.jpg" alt="AIM Smile Dental Clinic Logo">
            </div>

            <h1>AIM Smile</h1>

            <p class="clinic-name">
                DENTAL CLINIC
            </p>

            <h2>
                Create Account
            </h2>

            <p class="subtitle">
                Register for your dental care account
            </p>

            <form id="registerForm" method="post">

                <?php foreach ($errors as $error): ?>
                    <label class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></label>
                <?php endforeach; ?>

                <?php if ($success): ?>
                    <label class="success">Account created. You can now log in.</label>
                <?php endif; ?>


                <div class="form-group">

                    <label for="fullname">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        placeholder="Enter your full name"
                    >

                </div>


                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                    >

                </div>


                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                    >

                </div>


                <div class="form-group">

                    <label for="register-password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="register-password"
                        name="password"
                        placeholder="Create a password"
                    >

                </div>


                <div class="form-group">

                    <label for="confirm-password">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="confirm-password"
                        name="confirm-password"
                        placeholder="Confirm your password"
                    >

                </div>


                <div class="terms">

                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                    >

                    <label for="terms">
                        I agree to the Terms and Conditions.
                    </label>

                </div>


                <button type="submit">
                    Create Account
                </button>

            </form>


            <p class="account-text">

                Already have an account?

                <a href="login.php">
                    Login
                </a>

            </p>

        </section>

    </main>

<?php include 'includes/register_val.php'; ?>
    

</body>
</html>
<?php
session_start();
require_once 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare and execute query to get user details
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($user_id, $name, $hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Set multiple session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user'] = $name;  // Store name instead of email
            $_SESSION['email'] = $email;  // Store email separately

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid login credentials!";
        }
    } else {
        $error = "Invalid login credentials!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | RevvIt</title>
    <link href="dist/output.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-orange-50 to-teal-50 min-h-screen">
    <!-- Header -->
    <header class="bg-primary text-white shadow-sm p-4 fixed w-full top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-white hover:text-white/80 transition-colors">
                <a href="index.php">RevvIt!</a>
            </div>
            <nav>
                <ul class="flex space-x-8">
                    <li><a href="index.php" class="text-white/70 hover:text-white transition-colors duration-300">Home</a></li>
                    <li><a href="signup.php" class="text-white/70 hover:text-white transition-colors duration-300">Sign Up</a></li>
                    <li><a href="login.php" class="text-white font-medium">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Login Form -->
    <main class="flex justify-center items-center min-h-screen pt-16 px-4">
        <div class="bg-white/70 backdrop-blur-lg p-8 rounded-2xl shadow-xl w-full max-w-md transform hover:scale-[1.01] transition-all duration-300">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h1>
                <p class="text-gray-600">Please sign in to continue</p>
            </div>
            
            <form method="POST" action="" class="space-y-6">
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-gray-700 block">Email Address</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all duration-300 bg-white/50"
                            placeholder="Enter your email">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-gray-700 block">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all duration-300 bg-white/50"
                            placeholder="Enter your password">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="bg-red-50 text-red-500 p-4 rounded-lg text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <button type="submit" 
                    class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/50 transform hover:-translate-y-0.5 transition-all duration-300">
                    Sign In
                </button>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-gray-600">Don't have an account? 
                    <a href="signup.php" class="text-primary hover:text-primary/80 font-medium transition-colors duration-300">
                        Create Account
                    </a>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white/80 backdrop-blur-md text-gray-600 p-6 text-center mt-auto">
        <div class="container mx-auto">
            <p>&copy; <?php echo date("Y"); ?> RevvIt! All rights reserved.</p>
            <p class="mt-2">
                <a href="mailto:support@revvit.com" class="text-primary hover:text-primary/80 transition-colors duration-300">
                    support@revvit.com
                </a>
            </p>
        </div>
    </footer>
</body>
</html>

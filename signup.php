<?php
session_start();
require_once 'db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: login.php?signup=success");
                exit();
            } else {
                $error = "An error occurred. Please try again later.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | RevvIt</title>
    <link href="dist/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
    <style>
        .password-strength-meter {
            height: 4px;
            background-color: #ddd;
            border-radius: 2px;
            margin-top: 4px;
        }
        .password-strength-meter div {
            height: 100%;
            border-radius: 2px;
            transition: width 0.3s ease;
        }
        .strength-weak { background-color: #ef4444; }
        .strength-medium { background-color: #f97316; }
        .strength-strong { background-color: #22c55e; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8" x-data="{ 
    showPassword: false,
    showConfirmPassword: false,
    password: '',
    confirmPassword: '',
    strengthLevel: 0,
    getStrengthLevel() {
        let score = 0;
        if (this.password.length >= 8) score++;
        if (/[A-Z]/.test(this.password)) score++;
        if (/[0-9]/.test(this.password)) score++;
        if (/[^A-Za-z0-9]/.test(this.password)) score++;
        this.strengthLevel = score;
    }
}">
    <!-- Logo -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
        <h1 class="text-4xl font-bold text-primary">RevvIt!</h1>
        <h2 class="mt-2 text-2xl font-semibold text-gray-900">Create your account</h2>
        <p class="mt-2 text-gray-600">Start your learning journey today</p>
    </div>

    <!-- Sign Up Form -->
    <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10 w-full max-w-md">
        <?php if (!empty($error)): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="signup.php" class="space-y-6">
            <!-- Name Input -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <div class="mt-1">
                    <input id="name" name="name" type="text" required 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative">
                    <input id="password" name="password" 
                        :type="showPassword ? 'text' : 'password'" 
                        required
                        x-model="password"
                        @input="getStrengthLevel"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    <button type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <!-- Password Strength Meter -->
                <div class="password-strength-meter mt-2">
                    <div :class="{
                        'strength-weak': strengthLevel === 1,
                        'strength-medium': strengthLevel === 2 || strengthLevel === 3,
                        'strength-strong': strengthLevel === 4
                    }" :style="'width: ' + (strengthLevel * 25) + '%'"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Password must be at least 8 characters long and include uppercase, numbers, and special characters
                </p>
            </div>

            <!-- Confirm Password Input -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div class="mt-1 relative">
                    <input id="confirm_password" name="confirm_password" 
                        :type="showConfirmPassword ? 'text' : 'password'" 
                        required
                        x-model="confirmPassword"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    <button type="button" 
                        @click="showConfirmPassword = !showConfirmPassword" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500" x-show="password && confirmPassword && password !== confirmPassword">
                    Passwords do not match
                </p>
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    I agree to the <a href="#" class="text-primary hover:text-primary/80">Terms of Service</a> and 
                    <a href="#" class="text-primary hover:text-primary/80">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Create Account
                </button>
            </div>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="login.php" class="font-medium text-primary hover:text-primary/80">
                    Log in here
                </a>
            </p>
        </div>
    </div>
</body>
</html>

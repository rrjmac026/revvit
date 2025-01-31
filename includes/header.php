<?php
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RevvIt! - Learning Platform</title>
    <link href="dist/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-primary text-white shadow-sm p-4 fixed w-full top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-white hover:text-white/80 transition-colors">
                <a href="dashboard.php">RevvIt!</a>
            </div>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="dashboard.php" class="hover:text-white/80 transition-colors">Dashboard</a></li>
                    <li><a href="profile.php" class="hover:text-white/80 transition-colors">Profile</a></li>
                    <li><a href="quiz.php" class="hover:text-white/80 transition-colors">Quiz</a></li>
                    <li><a href="logout.php" class="hover:text-white/80 transition-colors">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="pt-20 flex-grow container mx-auto px-4 py-8">

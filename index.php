<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RevvIt! - Login</title>
    <link href="dist/output.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <header class="bg-primary text-white shadow-sm p-4 fixed w-full top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-white hover:text-white/80 transition-colors">
                <a href="index.php">Revvlt!</a>
            </div>
            <nav>
                <ul class="flex space-x-6">
                    <!-- <li><a href="index.php" class="text-white font-medium">Home</a></li> -->
                    <!-- <li><a href="signup.php" class="text-white/70 hover:text-white transition-colors duration-300">Sign Up</a></li>
                    <li><a href="login.php" class="text-white/70 hover:text-white transition-colors duration-300">Login</a></li> -->
                    <!-- <li><a href="profile.php" class="text-white/70 hover:text-white transition-colors duration-300">Profile</a></li>
                    <li><a href="quiz.php" class="text-white/70 hover:text-white transition-colors duration-300">Quizzes</a></li> -->
                    <!-- <li><a href="community.php" class="text-white/70 hover:text-white transition-colors duration-300">Community</a></li> -->
                </ul>
            </nav>
        </div>
    </header>

    <main class="pt-20 pb-16">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-primary to-orange-500 text-white py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Revvlt!</h1>
                    <p class="text-xl md:text-2xl text-white/90">Your Ultimate Learning and Review Companion</p>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:-translate-y-1 transition-all duration-300">
                    <h2 class="text-xl font-bold text-primary mb-4">Interactive Quizzes</h2>
                    <p class="text-gray-600">Challenge yourself with our diverse collection of quizzes across various subjects. Track your progress and improve your knowledge.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:-translate-y-1 transition-all duration-300">
                    <h2 class="text-xl font-bold text-primary mb-4">Community Learning</h2>
                    <p class="text-gray-600">Connect with fellow learners, share study materials, and participate in group discussions to enhance your understanding.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:-translate-y-1 transition-all duration-300">
                    <h2 class="text-xl font-bold text-primary mb-4">Personalized Experience</h2>
                    <p class="text-gray-600">Create your own study profile, save favorite topics, and get personalized recommendations based on your interests.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:-translate-y-1 transition-all duration-300">
                    <h2 class="text-xl font-bold text-primary mb-4">Progress Tracking</h2>
                    <p class="text-gray-600">Monitor your learning journey with detailed analytics and performance insights to help you stay motivated.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gray-50 py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Start Learning?</h2>
                    <p class="text-xl text-gray-600 mb-8">Join our community today and take your learning experience to the next level!</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="signup.php" class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transform hover:-translate-y-0.5 transition-all duration-300">Get Started</a>
                        <a href="login.php" class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-semibold border-2 border-primary hover:bg-gray-50 transform hover:-translate-y-0.5 transition-all duration-300">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-primary text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2">&copy; 2022 Reviewer App. All rights reserved.</p>
            <p>Contact Us: <a href="mailto:support@reviewerapp.com" class="underline hover:text-white/80">support@reviewerapp.com</a></p>
        </div>
    </footer>
</body>
</html>
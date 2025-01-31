<?php
session_start();
require_once 'db.php';
require_once 'includes/course_functions.php';
require_once 'includes/achievement_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user'];
$email = $_SESSION['email'] ?? '';

// Fetch user details from database
$stmt = $conn->prepare("SELECT email, name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $username = $row['name'];
    $profile_picture = $row['profile_picture'];
}
$stmt->close();

// Get course statistics
$course_stats = getCourseStatistics($conn, $user_id);
$enrolled_courses = getUserCourses($conn, $user_id);

// Get user achievements
$user_achievements = getUserAchievements($conn, $user_id);

// Get user quiz attempts
$quiz_attempts = getQuizAttempts($conn, $user_id);

// Calculate profile completion
$profile_completion = calculateProfileCompletion($conn, $user_id);

// Time-based greeting
$hour = date('H');
if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | RevvIt</title>
    <link href="dist/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <?php include 'includes/header.php'; ?>

    <main class="flex-grow pt-20 pb-16 container mx-auto px-4">
        <div class="max-w-4xl mx-auto grid md:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="md:col-span-1 bg-white rounded-xl shadow-lg p-6 h-fit">
                <div class="flex flex-col items-center">
                    <?php 
                    $default_profile_picture = 'images/default-profile.png';
                    $display_picture = !empty($profile_picture) && file_exists('uploads/profile_pictures/' . $profile_picture) 
                        ? 'uploads/profile_pictures/' . $profile_picture 
                        : $default_profile_picture;
                    ?>
                    <div class="relative mb-4 w-48 h-48 mx-auto">
                        <img 
                            src="<?php echo htmlspecialchars($display_picture); ?>" 
                            alt="Profile Picture" 
                            class="w-full h-full rounded-full object-cover shadow-lg border-4 border-primary/20"
                        >
                        <a href="update-profile.php?tab=profile-picture" class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full shadow-md hover:bg-primary/90 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </a>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($username); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($email); ?></p>
                    
                    <div class="w-full space-y-3">
                        <a href="update-profile.php?tab=profile-picture" class="w-full block text-center bg-primary/10 text-primary py-2 rounded-md hover:bg-primary/20 transition-colors">
                            <i class="fas fa-user-edit mr-2"></i>Edit Profile
                        </a>
                        <a href="files.php" class="w-full block text-center bg-primary/10 text-primary py-2 rounded-md hover:bg-primary/20 transition-colors">
                            <i class="fas fa-folder mr-2"></i>My Files
                        </a>
                        <a href="quiz.php" class="w-full block text-center bg-primary/10 text-primary py-2 rounded-md hover:bg-primary/20 transition-colors">
                            <i class="fas fa-question-circle mr-2"></i>Take a Quiz
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dashboard Insights -->
            <div class="md:col-span-2 space-y-6">
                <!-- Learning Progress -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Learning Progress</h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-blue-800 mb-2">Courses Enrolled</h4>
                            <p class="text-3xl font-bold text-blue-600"><?php echo $course_stats['enrolled_courses'] ?? 0; ?></p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-green-800 mb-2">Perfect Scores</h4>
                            <p class="text-3xl font-bold text-green-600"><?php echo $course_stats['perfect_scores'] ?? 0; ?></p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-orange-800 mb-2">In Progress</h4>
                            <p class="text-3xl font-bold text-orange-600"><?php echo $course_stats['in_progress_courses'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Recent Quiz Attempts -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Recent Quiz Attempts</h3>
                    <div class="space-y-4">
                        <?php if (!empty($quiz_attempts)): ?>
                            <?php foreach ($quiz_attempts as $attempt): ?>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex-grow">
                                        <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($attempt['subject_name']); ?> Quiz</h4>
                                        <p class="text-sm text-gray-600">
                                            Score: <?php echo $attempt['correct_answers']; ?>/<?php echo $attempt['total_questions']; ?> 
                                            (<?php echo number_format($attempt['score_percentage'], 1); ?>%)
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <?php echo date('F j, Y g:i A', strtotime($attempt['attempted_at'])); ?>
                                        </p>
                                    </div>
                                    <div class="ml-4">
                                        <?php if ($attempt['score_percentage'] >= 80): ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Excellent</span>
                                        <?php elseif ($attempt['score_percentage'] >= 60): ?>
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Good</span>
                                        <?php else: ?>
                                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">Keep Practicing</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500">No quiz attempts yet. Start learning!</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Achievements -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Achievements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if (!empty($user_achievements)): ?>
                            <?php foreach ($user_achievements as $achievement): ?>
                                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-primary">
                                    <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($achievement['title']); ?></h4>
                                    <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($achievement['description']); ?></p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Earned: <?php echo date('F j, Y', strtotime($achievement['earned_at'])); ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-2">
                                <p class="text-center text-gray-500">No achievements yet. Keep learning to earn them!</p>
                                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                                    <li>Get perfect scores (100%) in 5 subjects to earn Perfect Score Novice</li>
                                    <li>Get perfect scores in 10 subjects to earn Perfect Score Expert</li>
                                    <li>Get perfect scores in all 15 subjects to earn Perfect Score Master</li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Courses -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Recent Courses</h3>
                    <div class="space-y-4">
                        <?php if (!empty($enrolled_courses)): ?>
                            <?php 
                            $recent_courses = array_slice($enrolled_courses, 0, 3); // Show only the 3 most recent courses
                            foreach ($recent_courses as $course): 
                                $progress = isset($course['progress']) ? number_format($course['progress'], 2) : '0.00';
                            ?>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex-grow">
                                        <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($course['subject_name']); ?></h4>
                                        <p class="text-sm text-gray-600">
                                            Status: <?php echo htmlspecialchars($course['status']); ?>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div 
                                                class="bg-primary h-2 rounded-full" 
                                                style="width: <?php echo $progress; ?>%"
                                            ></div>
                                        </div>
                                        <span class="text-sm text-gray-600"><?php echo $progress; ?>%</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($enrolled_courses) > 3): ?>
                                
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500">No courses enrolled yet. Start learning!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>

<?php
$conn->close();
?>

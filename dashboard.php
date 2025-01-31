<?php
require_once 'includes/auth_check.php';

// Use the authentication check function
checkAuthentication(true, true);

require_once 'db.php';
require_once 'includes/course_functions.php';
require_once 'includes/achievement_functions.php';

$user_id = $_SESSION['user_id'];

// Get user's name from database
$name_query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($name_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$name_result = $stmt->get_result();
$user_name = $name_result->fetch_assoc()['name'];
$display_name = $user_name ?: $_SESSION['user']; // Fallback to username if name is not set

// Get user's achievements
$user_achievements = getUserAchievements($conn, $user_id);

// Get courses enrolled with additional details
$courses_query = "SELECT 
    c.name as course_name,
    s.name as subject_name,
    uc.progress_percentage,
    uc.enrolled_at,
    uc.status
    FROM user_courses uc
    INNER JOIN courses c ON uc.course_id = c.id
    LEFT JOIN subjects s ON c.subject_id = s.id
    WHERE uc.user_id = ?
    ORDER BY uc.enrolled_at DESC
    LIMIT 3";

try {
    $stmt = $conn->prepare($courses_query);
    if (!$stmt) {
        error_log("Failed to prepare courses query: " . $conn->error);
        $enrolled_courses = [];
        $total_enrolled = 0;
    } else {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $courses_result = $stmt->get_result();
        $enrolled_courses = $courses_result->fetch_all(MYSQLI_ASSOC);
        
        // Get total enrolled courses count
        $total_query = "SELECT COUNT(*) as total FROM user_courses WHERE user_id = ?";
        $total_stmt = $conn->prepare($total_query);
        $total_stmt->bind_param("i", $user_id);
        $total_stmt->execute();
        $total_result = $total_stmt->get_result();
        $total_enrolled = $total_result->fetch_assoc()['total'];
    }
} catch (Exception $e) {
    error_log("Error fetching enrolled courses: " . $e->getMessage());
    $enrolled_courses = [];
    $total_enrolled = 0;
}

// Get recent quiz attempts (last 30 days)
$recent_quiz_query = "SELECT 
    qa.id,
    qa.score_percentage,
    qa.attempted_at,
    COALESCE(s.name, 'Unknown Subject') as subject_name
    FROM quiz_attempts qa
    LEFT JOIN subjects s ON qa.subject_id = s.id
    WHERE qa.user_id = ? 
    AND qa.attempted_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ORDER BY qa.attempted_at DESC
    LIMIT 3";

try {
    $stmt = $conn->prepare($recent_quiz_query);
    if (!$stmt) {
        error_log("Failed to prepare recent quiz query: " . $conn->error);
    } else {
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            error_log("Failed to execute recent quiz query: " . $conn->error);
        }
        $quiz_result = $stmt->get_result();
        $recent_quizzes = $quiz_result->fetch_all(MYSQLI_ASSOC);
        $total_attempted = count($recent_quizzes);
        
        // Debug output
        error_log("Recent Quizzes Query Results: " . print_r($recent_quizzes, true));
    }
} catch (Exception $e) {
    error_log("Error fetching recent quizzes: " . $e->getMessage());
    $recent_quizzes = [];
    $total_attempted = 0;
}

// Get recent achievements with details
$achievements_query = "SELECT 
    ua.*,
    a.name as achievement_name,
    a.description as achievement_description
    FROM user_achievements ua
    JOIN achievements a ON ua.achievement_id = a.id
    WHERE ua.user_id = ? 
    AND ua.earned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ORDER BY ua.earned_at DESC";
$stmt = $conn->prepare($achievements_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$achievements_result = $stmt->get_result();
$recent_achievements = $achievements_result->fetch_all(MYSQLI_ASSOC);
$newAchievements = count($recent_achievements);

// Get profile completion percentage
$profile_query = "SELECT name, email, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($profile_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile_result = $stmt->get_result();
$profile_data = $profile_result->fetch_assoc();

$total_fields = 3; // Total number of profile fields
$filled_fields = 0;

// Count filled fields
if ($profile_data['name']) $filled_fields++;
if ($profile_data['email']) $filled_fields++;
if ($profile_data['profile_picture'] && $profile_data['profile_picture'] != 'default_profile.png') $filled_fields++;

$profileCompletion = round(($filled_fields / $total_fields) * 100);

// Get personalized recommendations
$recommendations = [];

// 1. Check if profile is incomplete
if ($profileCompletion < 100) {
    $recommendations[] = [
        'text' => 'Complete your profile to unlock more features',
        'link' => 'profile.php',
        'priority' => 1
    ];
}

// 2. Check recent quiz performance
if ($total_attempted < 3) {
    $recommendations[] = [
        'text' => 'Take more quizzes to improve your knowledge',
        'link' => 'quiz.php',
        'priority' => 2
    ];
}

// Sort recommendations by priority
usort($recommendations, function($a, $b) {
    return $a['priority'] - $b['priority'];
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | RevvIt!</title>
    <link href="dist/output.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-primary text-white shadow-sm p-4 fixed w-full top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-white hover:text-white/80 transition-colors">
                <a href="dashboard.php">RevvIt!</a>
            </div>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="dashboard.php" class="text-white font-medium">Dashboard</a></li>
                    <li><a href="profile.php" class="text-white/70 hover:text-white transition-colors duration-300">Profile</a></li>
                    <li><a href="quiz.php" class="text-white/70 hover:text-white transition-colors duration-300">Quizzes</a></li>
                    <li><a href="community.php" class="text-white/70 hover:text-white transition-colors duration-300">Community</a></li>
                    <li><a href="logout.php" class="text-white/70 hover:text-white transition-colors duration-300">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 pb-16">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-primary to-orange-500 text-white py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome back, <?php echo htmlspecialchars($display_name); ?>!</h1>
                    <p class="text-xl md:text-2xl text-white/90">Your Ultimate Learning and Review Companion</p>
                </div>
            </div>
        </div>

        <!-- User Stats Section -->
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Courses Enrolled Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Courses Enrolled</h3>
                        <span class="text-3xl font-bold text-primary"><?php echo $total_enrolled; ?></span>
                    </div>
                    <div class="space-y-2">
                        <?php foreach(array_slice($enrolled_courses, 0, 3) as $course): ?>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium"><?php echo htmlspecialchars($course['course_name']); ?></p>
                            <p class="text-xs text-gray-500">
                                <?php echo htmlspecialchars($course['subject_name']); ?> - 
                                <?php echo $course['progress_percentage']; ?>% Complete
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Quizzes Attempted Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Recent Quizzes</h3>
                        <span class="text-3xl font-bold text-primary"><?php echo $total_attempted; ?></span>
                    </div>
                    <div class="space-y-2">
                        <?php foreach(array_slice($recent_quizzes, 0, 3) as $quiz): ?>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium"><?php echo htmlspecialchars($quiz['subject_name']); ?></p>
                            <p class="text-xs text-gray-500">
                                Score: <?php echo $quiz['score_percentage']; ?>% - 
                                <?php echo date('M d, Y', strtotime($quiz['attempted_at'])); ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- New Achievements Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">New Achievements</h3>
                        <span class="text-3xl font-bold text-primary"><?php echo $newAchievements; ?></span>
                    </div>
                    <div class="space-y-2">
                        <?php foreach(array_slice($recent_achievements, 0, 3) as $achievement): ?>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium"><?php echo htmlspecialchars($achievement['achievement_name']); ?></p>
                            <p class="text-xs text-gray-500">
                                <?php echo date('M d, Y', strtotime($achievement['earned_at'])); ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="container mx-auto px-4 py-16">
            <div class="max-w-4xl mx-auto mb-16">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Your Achievements</h2>
                    <p class="text-xl text-gray-600">Track your learning milestones!</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php if (!empty($user_achievements)): ?>
                        <?php foreach ($user_achievements as $achievement): ?>
                            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center transform transition hover:scale-105">
                                <!-- <div class="text-4xl mb-3"><?php echo htmlspecialchars($achievement['icon']); ?></div> -->
                                <h3 class="font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($achievement['title']); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($achievement['description']); ?></p>
                                <p class="text-xs text-gray-500 mt-3">
                                    Earned: <?php echo date('F j, Y', strtotime($achievement['earned_at'])); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-3 bg-white rounded-xl shadow-lg p-8 text-center">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">No Achievements Yet</h3>
                            <p class="text-gray-600 mb-6">Keep learning to unlock achievements!</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl mb-2">🌟</div>
                                    <h4 class="font-medium">Perfect Score Novice</h4>
                                    <p class="text-sm text-gray-600">Get 100% in 5 subjects</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl mb-2">🏆</div>
                                    <h4 class="font-medium">Perfect Score Expert</h4>
                                    <p class="text-sm text-gray-600">Get 100% in 10 subjects</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl mb-2">👑</div>
                                    <h4 class="font-medium">Perfect Score Master</h4>
                                    <p class="text-sm text-gray-600">Get 100% in all subjects</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Recommended for You</h2>
                <p class="text-xl text-gray-600 mb-8">Based on your activity, we suggest the following:</p>
                <ul class="space-y-3 text-gray-700">
                    <?php foreach ($recommendations as $rec): ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($rec['link']); ?>" 
                               class="text-orange-600 hover:underline">
                                <?php echo htmlspecialchars($rec['text']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (empty($recommendations)): ?>
                        <li class="text-gray-600">Great job! Keep up the good work!</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-gray-50 py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="quiz.php" class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transform hover:-translate-y-0.5 transition-all duration-300">Take a Quiz</a>
                        <a href="profile.php" class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-semibold border-2 border-primary hover:bg-gray-50 transform hover:-translate-y-0.5 transition-all duration-300">Update Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2">&copy; <?php echo date("Y"); ?> RevvIt!. All rights reserved.</p>
            <p>Contact Us: <a href="mailto:support@revvit.com" class="underline hover:text-white/80">support@revvit.com</a></p>
        </div>
    </footer>
</body>
</html>

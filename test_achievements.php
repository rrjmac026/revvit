<?php
require_once 'db.php';
require_once 'includes/achievement_functions.php';

// Get user ID from session or use a test user ID
session_start();
$user_id = $_SESSION['user_id'] ?? 1; // Replace 1 with your test user ID

echo "<h1>Achievement System Test</h1>";

// Test 1: Count Perfect Scores
$perfect_scores_query = "
    SELECT COUNT(DISTINCT qa.subject_id) as perfect_score_count, GROUP_CONCAT(DISTINCT qa.subject_id) as subject_ids
    FROM quiz_attempts qa
    WHERE qa.user_id = ? 
    AND qa.score_percentage = 100
    AND qa.subject_id IS NOT NULL
";
$stmt = $conn->prepare($perfect_scores_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo "<h2>Perfect Scores Test</h2>";
echo "User ID: " . $user_id . "<br>";
echo "Perfect Score Count: " . $data['perfect_score_count'] . "<br>";
echo "Subject IDs with Perfect Scores: " . $data['subject_ids'] . "<br>";

// Test 2: Check Achievement Tables
echo "<h2>Achievement Tables Test</h2>";

$achievements_query = "SELECT * FROM achievements";
$result = $conn->query($achievements_query);
echo "<h3>Available Achievements:</h3>";
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>ID: {$row['id']} - {$row['name']} ({$row['description']}) {$row['icon']}</li>";
    }
    echo "</ul>";
} else {
    echo "No achievements defined in the table.<br>";
}

// Test 3: Check User Achievements
$user_achievements_query = "
    SELECT a.name, a.description, a.icon, ua.earned_at
    FROM user_achievements ua
    JOIN achievements a ON ua.achievement_id = a.id
    WHERE ua.user_id = ?
";
$stmt = $conn->prepare($user_achievements_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>User's Current Achievements:</h3>";
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['icon']} {$row['name']} - Earned: {$row['earned_at']}</li>";
    }
    echo "</ul>";
} else {
    echo "User has no achievements yet.<br>";
}

// Test 4: Try to Award Achievements
echo "<h2>Attempting to Award Achievements</h2>";
$awarded = checkAndAwardAchievements($conn, $user_id);
if (!empty($awarded)) {
    echo "<h3>Newly Awarded Achievements:</h3>";
    echo "<ul>";
    foreach ($awarded as $achievement) {
        echo "<li>{$achievement['icon']} {$achievement['title']}</li>";
    }
    echo "</ul>";
} else {
    echo "No new achievements awarded.<br>";
}

$conn->close();
?>

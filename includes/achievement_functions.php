<?php
function recordQuizAttemptForAchievements($conn, $user_id, $subject_id, $total_questions, $correct_answers, $score_percentage) {
    $insert_query = "INSERT INTO quiz_attempts 
        (user_id, subject_id, total_questions, correct_answers, score_percentage) 
        VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iiiid", $user_id, $subject_id, $total_questions, $correct_answers, $score_percentage);
    $stmt->execute();
    
    return $stmt->insert_id;
}

function checkAndAwardAchievements($conn, $user_id) {
    initializeAchievements($conn); // Ensure achievements exist
    $awarded_achievements = [];

    // Count perfect scores (100%) in distinct subjects
    $perfect_scores_query = "
        SELECT COUNT(DISTINCT qa.subject_id) as perfect_score_count
        FROM quiz_attempts qa
        WHERE qa.user_id = ? 
        AND qa.score_percentage = 100
        AND qa.subject_id IS NOT NULL
    ";
    
    $stmt = $conn->prepare($perfect_scores_query);
    if (!$stmt) {
        error_log("Error preparing perfect scores query: " . $conn->error);
        return $awarded_achievements;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $perfect_scores = $result->fetch_assoc()['perfect_score_count'];
    
    error_log("User $user_id has $perfect_scores perfect scores"); // Debug log
    
    // Check Perfect Score Achievements
    if ($perfect_scores >= 5) {
        $achievement = awardAchievement($conn, $user_id, 5); // Perfect Score Novice
        if ($achievement) $awarded_achievements[] = $achievement;
    }
    if ($perfect_scores >= 10) {
        $achievement = awardAchievement($conn, $user_id, 6); // Perfect Score Expert
        if ($achievement) $awarded_achievements[] = $achievement;
    }
    if ($perfect_scores >= 15) {
        $achievement = awardAchievement($conn, $user_id, 7); // Perfect Score Master
        if ($achievement) $awarded_achievements[] = $achievement;
    }

    // Quiz Achievements
    $quiz_count_query = "SELECT COUNT(DISTINCT subject_id) as total_quizzes FROM quiz_attempts WHERE user_id = ?";
    $stmt = $conn->prepare($quiz_count_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $quiz_count = $result->fetch_assoc()['total_quizzes'];

    // Check Quiz Starter Achievement
    if ($quiz_count >= 1) {
        $achievement = awardAchievement($conn, $user_id, 1); // First quiz achievement
        if ($achievement) $awarded_achievements[] = $achievement;
    }

    // Check Quiz Master Achievement
    if ($quiz_count >= 5) {
        $achievement = awardAchievement($conn, $user_id, 2); // Quiz master achievement
        if ($achievement) $awarded_achievements[] = $achievement;
    }

    // Profile Completion Achievement
    $profile_completion = calculateProfileCompletion($conn, $user_id);
    if ($profile_completion >= 100) {
        $achievement = awardAchievement($conn, $user_id, 3); // Profile perfectionist
        if ($achievement) $awarded_achievements[] = $achievement;
    }

    // Course Enrollment Achievement
    $course_count_query = "SELECT COUNT(*) as total_courses FROM user_courses WHERE user_id = ?";
    $stmt = $conn->prepare($course_count_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course_count = $result->fetch_assoc()['total_courses'];

    if ($course_count >= 3) {
        $achievement = awardAchievement($conn, $user_id, 4); // Learning enthusiast
        if ($achievement) $awarded_achievements[] = $achievement;
    }

    return $awarded_achievements;
}

function awardAchievement($conn, $user_id, $achievement_id) {
    // Check if user already has this achievement
    $check_query = "SELECT id FROM user_achievements WHERE user_id = ? AND achievement_id = ?";
    $check_stmt = $conn->prepare($check_query);
    if (!$check_stmt) {
        error_log("Error preparing check achievement query: " . $conn->error);
        return null;
    }
    
    $check_stmt->bind_param("ii", $user_id, $achievement_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    // If user doesn't have the achievement yet, award it
    if ($result->num_rows === 0) {
        $award_query = "INSERT INTO user_achievements (user_id, achievement_id) VALUES (?, ?)";
        $award_stmt = $conn->prepare($award_query);
        if (!$award_stmt) {
            error_log("Error preparing award achievement query: " . $conn->error);
            return null;
        }
        
        $award_stmt->bind_param("ii", $user_id, $achievement_id);
        if (!$award_stmt->execute()) {
            error_log("Error awarding achievement: " . $award_stmt->error);
            return null;
        }
        
        // Get achievement details
        $ach_query = "SELECT id, name as title, description, icon FROM achievements WHERE id = ?";
        $ach_stmt = $conn->prepare($ach_query);
        if (!$ach_stmt) {
            error_log("Error preparing get achievement query: " . $conn->error);
            return null;
        }
        
        $ach_stmt->bind_param("i", $achievement_id);
        $ach_stmt->execute();
        $result = $ach_stmt->get_result();
        return $result->fetch_assoc();
    }
    
    return null;
}

function calculateProfileCompletion($conn, $user_id) {
    // Fetch user profile details
    $query = "
        SELECT 
            (CASE WHEN email IS NOT NULL AND email != '' THEN 1 ELSE 0 END) +
            (CASE WHEN name IS NOT NULL AND name != '' THEN 1 ELSE 0 END) +
            (CASE WHEN profile_picture IS NOT NULL AND profile_picture != '' THEN 1 ELSE 0 END) AS completed_fields,
            3 AS total_fields
        FROM users 
        WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $completion = $result->fetch_assoc();
    
    return ($completion['completed_fields'] / $completion['total_fields']) * 100;
}

function getUserAchievements($conn, $user_id) {
    initializeAchievements($conn); // Ensure achievements exist
    
    // Check and award any new achievements first
    checkAndAwardAchievements($conn, $user_id);
    
    $query = "
        SELECT 
            a.id,
            a.name as title,
            a.description,
            a.icon,
            ua.earned_at
        FROM user_achievements ua
        JOIN achievements a ON ua.achievement_id = a.id
        WHERE ua.user_id = ?
        ORDER BY ua.earned_at DESC
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getQuizAttempts($conn, $user_id) {
    $query = "
        SELECT 
            qa.id,
            s.name as subject_name,
            qa.total_questions,
            qa.correct_answers,
            qa.score_percentage,
            qa.attempted_at
        FROM 
            quiz_attempts qa
        JOIN 
            subjects s ON qa.subject_id = s.id
        WHERE 
            qa.user_id = ?
        ORDER BY 
            qa.attempted_at DESC
        LIMIT 5";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function initializeAchievements($conn) {
    // Create achievements table if it doesn't exist
    $create_achievements = "
        CREATE TABLE IF NOT EXISTS achievements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            icon VARCHAR(100) DEFAULT NULL
        )
    ";
    $conn->query($create_achievements);

    // Create user_achievements table if it doesn't exist
    $create_user_achievements = "
        CREATE TABLE IF NOT EXISTS user_achievements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            achievement_id INT NOT NULL,
            earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_achievement (user_id, achievement_id)
        )
    ";
    $conn->query($create_user_achievements);

    // Insert or update achievements
    $achievements = [
        [1, 'Quiz Starter', 'Completed first quiz', '🎉'],
        [2, 'Quiz Master', 'Completed 5 quizzes', '👑'],
        [3, 'Profile Perfectionist', 'Completed profile', '📈'],
        [4, 'Learning Enthusiast', 'Enrolled in 3 courses', '📚'],
        [5, 'Perfect Score Novice', 'Achieved perfect scores in 5 different subjects', '🌟'],
        [6, 'Perfect Score Expert', 'Achieved perfect scores in 10 different subjects', '🏆'],
        [7, 'Perfect Score Master', 'Achieved perfect scores in all 15 subjects', '👑']
    ];

    $insert_achievement = "INSERT IGNORE INTO achievements (id, name, description, icon) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_achievement);

    foreach ($achievements as $achievement) {
        $stmt->bind_param("isss", $achievement[0], $achievement[1], $achievement[2], $achievement[3]);
        $stmt->execute();
    }
}

?>

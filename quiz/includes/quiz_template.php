<?php
/**
 * Common quiz initialization code to ensure consistent course enrollment
 * 
 * @param object $conn Database connection
 * @param int $user_id User ID
 * @param string $quiz_file Current quiz file name
 * @return array Returns [course_id, subject_name] or dies with error
 */
function initializeQuiz($conn, $user_id, $quiz_file) {
    // Initialize tables and get course ID
    initializeTables($conn);
    $course_id = getCourseIdFromQuiz($conn, $quiz_file);

    if (!$course_id) {
        die("Error: Course not found. Please contact the administrator.");
    }

    // Get subject name for display
    $query = "
        SELECT s.name 
        FROM courses c 
        JOIN subjects s ON c.subject_id = s.id 
        WHERE c.id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $subject_name = $row ? $row['name'] : '';

    return [$course_id, $subject_name];
}

/**
 * Process quiz submission
 * 
 * @param object $conn Database connection
 * @param int $user_id User ID
 * @param int $course_id Course ID
 * @param array $quiz_questions Quiz questions array
 * @param array $post_data POST data from form submission
 * @return array Returns [score, total_questions, percentage]
 */
function processQuizSubmission($conn, $user_id, $course_id, $quiz_questions, $post_data) {
    $score = 0;
    $total_questions = count($quiz_questions);
    
    foreach ($quiz_questions as $index => $question) {
        if (isset($post_data["answer_$index"]) && $post_data["answer_$index"] === $question['correct_answer']) {
            $score++;
        }
    }
    
    // Calculate score as a decimal (0.0 to 1.0)
    $score_decimal = $score / $total_questions;
    $percentage = $score_decimal * 100;
    
    // Record the quiz attempt with the decimal score
    recordQuizAttempt($conn, $user_id, $course_id, $score_decimal);
    
    // Check and award any achievements
    checkAndAwardAchievements($conn, $user_id);
    
    return [$score, $total_questions, $percentage];
}
?>

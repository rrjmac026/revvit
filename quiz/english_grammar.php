<?php
require_once 'includes/auth_template.php';

// Initialize quiz and get course information
list($course_id, $subject_name) = initializeQuiz($conn, $user_id, basename(__FILE__));

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$quiz_questions = [
    [
        'question' => 'Which is the correct plural form of "child"?',
        'options' => [
            'A' => 'Childs',
            'B' => 'Childes',
            'C' => 'Children',
            'D' => 'Child\'s'
        ],
        'correct_answer' => 'C',
        'explanation' => 'The correct plural of "child" is "children", which is an irregular plural form'
    ],
    [
        'question' => 'What is the past participle of "go"?',
        'options' => [
            'A' => 'Went',
            'B' => 'Gone',
            'C' => 'Goed',
            'D' => 'Going'
        ],
        'correct_answer' => 'B',
        'explanation' => 'The past participle of "go" is "gone", used in perfect tenses'
    ],
    [
        'question' => 'Which sentence uses the correct verb tense?',
        'options' => [
            'A' => 'I have went to the store',
            'B' => 'I has gone to the store',
            'C' => 'I have gone to the store',
            'D' => 'I goes to the store'
        ],
        'correct_answer' => 'C',
        'explanation' => '"I have gone to the store" is grammatically correct, using present perfect tense'
    ]
];

// Handle quiz submission
$quiz_result = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    $total_questions = count($quiz_questions);
    
    foreach ($quiz_questions as $index => $question) {
        $answer_key = 'answer_' . $index;
        if (isset($_POST[$answer_key]) && $_POST[$answer_key] === $question['correct_answer']) {
            $score++;
        }
    }
    
    $percentage = round(($score / $total_questions) * 100, 2);
    
    try {
        // Find the subject_id and corresponding course_id for this quiz
        $subject_query = $conn->prepare("
            SELECT s.id as subject_id, s.name as subject_name, c.id as course_id 
            FROM subjects s
            JOIN courses c ON s.id = c.subject_id
            WHERE s.name LIKE '%English%'
        ");
        $subject_query->execute();
        $subject_result = $subject_query->get_result();
        $subject_row = $subject_result->fetch_assoc();
        $subject_id = $subject_row ? $subject_row['subject_id'] : null;
        $course_id = $subject_row ? $subject_row['course_id'] : null;

        // Debug output
        error_log("English Quiz - Subject/Course Query Results: " . print_r($subject_row, true));

        if ($subject_id && $course_id) {
            // First, check if user is already enrolled in this course
            $check_enrollment = $conn->prepare("
                SELECT id FROM user_courses 
                WHERE user_id = ? AND course_id = ?
            ");
            $check_enrollment->bind_param("ii", $user_id, $course_id);
            $check_enrollment->execute();
            $existing_enrollment = $check_enrollment->get_result()->fetch_assoc();

            // If not enrolled, add to user_courses
            if (!$existing_enrollment) {
                $enroll_query = "INSERT INTO user_courses (
                    user_id,
                    course_id,
                    enrolled_at,
                    progress_percentage,
                    status
                ) VALUES (?, ?, NOW(), ?, 'in_progress')";
                
                $stmt = $conn->prepare($enroll_query);
                $initial_progress = $percentage; // Use quiz score as initial progress
                $stmt->bind_param("iid", $user_id, $course_id, $initial_progress);
                
                if (!$stmt->execute()) {
                    error_log("Failed to enroll in course: " . $conn->error);
                } else {
                    error_log("Successfully enrolled in English course. User: $user_id");
                }
            }

            // Record quiz attempt
            $insert_query = "INSERT INTO quiz_attempts (
                user_id, 
                subject_id, 
                total_questions, 
                correct_answers, 
                score_percentage, 
                attempted_at
            ) VALUES (?, ?, ?, ?, ?, NOW())";
            
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iiiid", 
                $user_id, 
                $subject_id, 
                $total_questions, 
                $score, 
                $percentage
            );
            
            if (!$stmt->execute()) {
                error_log("Failed to record quiz attempt: " . $conn->error);
            } else {
                error_log("Successfully recorded English quiz attempt. User: $user_id, Score: $percentage%");
            }
        } else {
            error_log("Subject/Course ID not found for English quiz - Please check subjects and courses tables");
        }
    } catch (Exception $e) {
        error_log("Quiz attempt/enrollment error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Grammar Quiz | RevvIt</title>
    <link href="../dist/output.css" rel="stylesheet">
</head>
<body class="bg-orange-50 min-h-screen flex flex-col">
    
    
    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center mb-6">
                <a href="../quiz.php" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Quizzes
                </a>
            </div>
            <h1 class="text-3xl font-bold text-center mb-8">English Grammar Quiz</h1>
            
            <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
                <form method="POST" class="space-y-6">
                    <?php foreach ($quiz_questions as $index => $question): ?>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="font-semibold mb-4"><?php echo ($index + 1) . '. ' . htmlspecialchars($question['question']); ?></p>
                            <div class="space-y-2">
                                <?php foreach ($question['options'] as $key => $option): ?>
                                    <label class="block">
                                        <input type="radio" 
                                               name="answer_<?php echo $index; ?>" 
                                               value="<?php echo $key; ?>" 
                                               class="mr-2" required>
                                        <?php echo htmlspecialchars($key . ') ' . $option); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition-colors">
                        Submit Quiz
                    </button>
                </form>
            <?php else: ?>
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-4">Quiz Results</h2>
                    <p class="text-xl mb-2">Score: <?php echo $score; ?> / <?php echo $total_questions; ?></p>
                    <p class="text-lg mb-4">Percentage: <?php echo $percentage; ?>%</p>
                    
                    <div class="mt-6">
                        <a href="../quiz.php" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                            Back to Courses
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>

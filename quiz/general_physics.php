<?php
session_start();
require_once '../includes/auth_check.php';
require_once '../db.php';

// Use the authentication check function
checkAuthentication(true, true);

// Initialize variables
$user_id = $_SESSION['user_id'];
$submitted = false;
$score = 0;
$total_questions = 10;
$percentage = 0;

// Handle quiz submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    
    // Calculate score based on answers
    if (isset($_POST['q1']) && $_POST['q1'] == "c") { $score++; } // F = ma
    if (isset($_POST['q2']) && $_POST['q2'] == "b") { $score++; } // 9.81 m/s²
    if (isset($_POST['q3']) && $_POST['q3'] == "a") { $score++; } // Newton
    if (isset($_POST['q4']) && $_POST['q4'] == "d") { $score++; } // Joule
    if (isset($_POST['q5']) && $_POST['q5'] == "b") { $score++; } // Kinetic Energy
    if (isset($_POST['q6']) && $_POST['q6'] == "c") { $score++; } // Watt
    if (isset($_POST['q7']) && $_POST['q7'] == "a") { $score++; } // Speed of light
    if (isset($_POST['q8']) && $_POST['q8'] == "d") { $score++; } // Ohm's Law
    if (isset($_POST['q9']) && $_POST['q9'] == "b") { $score++; } // Tesla
    if (isset($_POST['q10']) && $_POST['q10'] == "c") { $score++; } // Pascal
    
    $percentage = round(($score / $total_questions) * 100, 2);
    $submitted = true;
    
    try {
        // Find the subject_id and corresponding course_id for this quiz
        $subject_query = $conn->prepare("
            SELECT s.id as subject_id, s.name as subject_name, c.id as course_id 
            FROM subjects s
            JOIN courses c ON s.id = c.subject_id
            WHERE s.name LIKE '%Physics%'
        ");
        $subject_query->execute();
        $subject_result = $subject_query->get_result();
        $subject_row = $subject_result->fetch_assoc();
        $subject_id = $subject_row ? $subject_row['subject_id'] : null;
        $course_id = $subject_row ? $subject_row['course_id'] : null;

        // Debug output
        error_log("Physics Quiz - Subject/Course Query Results: " . print_r($subject_row, true));

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
                    error_log("Successfully enrolled in Physics course. User: $user_id");
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
                error_log("Successfully recorded Physics quiz attempt. User: $user_id, Score: $percentage%");
            }
        } else {
            error_log("Subject/Course ID not found for Physics quiz - Please check subjects and courses tables");
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
    <title>General Physics Quiz | RevvIt</title>
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

            <h1 class="text-3xl font-bold text-center mb-8">General Physics Quiz</h1>
            
            <?php if ($submitted): ?>
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
            <?php else: ?>
                <form method="POST" class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">1. What is Newton's Second Law of Motion?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q1" value="a" class="mr-2" required>
                            Every action has an equal and opposite reaction
                        </label>
                        <label class="block">
                            <input type="radio" name="q1" value="b" class="mr-2">
                            An object at rest stays at rest
                        </label>
                        <label class="block">
                            <input type="radio" name="q1" value="c" class="mr-2">
                            Force equals mass times acceleration (F = ma)
                        </label>
                        <label class="block">
                            <input type="radio" name="q1" value="d" class="mr-2">
                            Energy cannot be created or destroyed
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">2. What is the approximate acceleration due to gravity on Earth?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q2" value="a" class="mr-2" required>
                            5.0 m/s²
                        </label>
                        <label class="block">
                            <input type="radio" name="q2" value="b" class="mr-2">
                            9.81 m/s²
                        </label>
                        <label class="block">
                            <input type="radio" name="q2" value="c" class="mr-2">
                            15.0 m/s²
                        </label>
                        <label class="block">
                            <input type="radio" name="q2" value="d" class="mr-2">
                            20.0 m/s²
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">3. What is the SI unit of force?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q3" value="a" class="mr-2" required>
                            Newton
                        </label>
                        <label class="block">
                            <input type="radio" name="q3" value="b" class="mr-2">
                            Joule
                        </label>
                        <label class="block">
                            <input type="radio" name="q3" value="c" class="mr-2">
                            Pascal
                        </label>
                        <label class="block">
                            <input type="radio" name="q3" value="d" class="mr-2">
                            Watt
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">4. What is the SI unit of energy?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q4" value="a" class="mr-2" required>
                            Newton
                        </label>
                        <label class="block">
                            <input type="radio" name="q4" value="b" class="mr-2">
                            Watt
                        </label>
                        <label class="block">
                            <input type="radio" name="q4" value="c" class="mr-2">
                            Pascal
                        </label>
                        <label class="block">
                            <input type="radio" name="q4" value="d" class="mr-2">
                            Joule
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">5. What type of energy does a moving object have?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q5" value="a" class="mr-2" required>
                            Potential Energy
                        </label>
                        <label class="block">
                            <input type="radio" name="q5" value="b" class="mr-2">
                            Kinetic Energy
                        </label>
                        <label class="block">
                            <input type="radio" name="q5" value="c" class="mr-2">
                            Thermal Energy
                        </label>
                        <label class="block">
                            <input type="radio" name="q5" value="d" class="mr-2">
                            Nuclear Energy
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">6. What is the SI unit of power?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q6" value="a" class="mr-2" required>
                            Joule
                        </label>
                        <label class="block">
                            <input type="radio" name="q6" value="b" class="mr-2">
                            Newton
                        </label>
                        <label class="block">
                            <input type="radio" name="q6" value="c" class="mr-2">
                            Watt
                        </label>
                        <label class="block">
                            <input type="radio" name="q6" value="d" class="mr-2">
                            Volt
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">7. What is approximately 3 × 10⁸ m/s?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q7" value="a" class="mr-2" required>
                            Speed of light
                        </label>
                        <label class="block">
                            <input type="radio" name="q7" value="b" class="mr-2">
                            Speed of sound
                        </label>
                        <label class="block">
                            <input type="radio" name="q7" value="c" class="mr-2">
                            Speed of Earth's rotation
                        </label>
                        <label class="block">
                            <input type="radio" name="q7" value="d" class="mr-2">
                            Speed of a jet plane
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">8. What law states that V = IR?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q8" value="a" class="mr-2" required>
                            Newton's Law
                        </label>
                        <label class="block">
                            <input type="radio" name="q8" value="b" class="mr-2">
                            Boyle's Law
                        </label>
                        <label class="block">
                            <input type="radio" name="q8" value="c" class="mr-2">
                            Hooke's Law
                        </label>
                        <label class="block">
                            <input type="radio" name="q8" value="d" class="mr-2">
                            Ohm's Law
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">9. What is the SI unit of magnetic field strength?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q9" value="a" class="mr-2" required>
                            Weber
                        </label>
                        <label class="block">
                            <input type="radio" name="q9" value="b" class="mr-2">
                            Tesla
                        </label>
                        <label class="block">
                            <input type="radio" name="q9" value="c" class="mr-2">
                            Henry
                        </label>
                        <label class="block">
                            <input type="radio" name="q9" value="d" class="mr-2">
                            Gauss
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-semibold mb-4">10. What is the SI unit of pressure?</p>
                    <div class="space-y-2">
                        <label class="block">
                            <input type="radio" name="q10" value="a" class="mr-2" required>
                            Newton
                        </label>
                        <label class="block">
                            <input type="radio" name="q10" value="b" class="mr-2">
                            Bar
                        </label>
                        <label class="block">
                            <input type="radio" name="q10" value="c" class="mr-2">
                            Pascal
                        </label>
                        <label class="block">
                            <input type="radio" name="q10" value="d" class="mr-2">
                            Atmosphere
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition-colors">
                    Submit Quiz
                </button>
            </form>
        <?php endif; ?>
        </div>
    </div>
</body>
</html>

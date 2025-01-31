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
    if (isset($_POST['q1']) && $_POST['q1'] == "b") { $score++; }
    if (isset($_POST['q2']) && $_POST['q2'] == "a") { $score++; }
    if (isset($_POST['q3']) && $_POST['q3'] == "c") { $score++; }
    if (isset($_POST['q4']) && $_POST['q4'] == "b") { $score++; }
    if (isset($_POST['q5']) && $_POST['q5'] == "d") { $score++; }
    if (isset($_POST['q6']) && $_POST['q6'] == "a") { $score++; }
    if (isset($_POST['q7']) && $_POST['q7'] == "c") { $score++; }
    if (isset($_POST['q8']) && $_POST['q8'] == "b") { $score++; }
    if (isset($_POST['q9']) && $_POST['q9'] == "d") { $score++; }
    if (isset($_POST['q10']) && $_POST['q10'] == "a") { $score++; }
    
    $percentage = round(($score / $total_questions) * 100, 2);
    $submitted = true;
    
    // Record quiz attempt with error handling
    try {
        // Find the subject_id and corresponding course_id for this quiz
        $subject_query = $conn->prepare("
            SELECT s.id as subject_id, s.name as subject_name, c.id as course_id 
            FROM subjects s
            JOIN courses c ON s.id = c.subject_id
            WHERE s.name LIKE '%Chemistry%'
        ");
        $subject_query->execute();
        $subject_result = $subject_query->get_result();
        $subject_row = $subject_result->fetch_assoc();
        $subject_id = $subject_row ? $subject_row['subject_id'] : null;
        $course_id = $subject_row ? $subject_row['course_id'] : null;

        // Debug output
        error_log("Chemistry Quiz - Subject/Course Query Results: " . print_r($subject_row, true));

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
                    error_log("Successfully enrolled in Chemistry course. User: $user_id");
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
                error_log("Successfully recorded Chemistry quiz attempt. User: $user_id, Score: $percentage%");
            }
        } else {
            error_log("Subject/Course ID not found for Chemistry quiz - Please check subjects and courses tables");
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
    <title>Chemistry Basics Quiz</title>
    <link href="../dist/output.css" rel="stylesheet">
</head>
<body class="bg-orange-50 min-h-screen flex flex-col">
    <?php include '../includes/header.php'; ?>
    
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
            <h1 class="text-3xl font-bold text-center mb-8">Chemistry Basics Quiz</h1>
            
            <?php if (!$submitted): ?>
                <form method="POST" class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">1. What is the atomic number of Carbon?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q1" 
                                       value="a" 
                                       class="mr-2" required>
                                5
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q1" 
                                       value="b" 
                                       class="mr-2" required>
                                6
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q1" 
                                       value="c" 
                                       class="mr-2" required>
                                7
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q1" 
                                       value="d" 
                                       class="mr-2" required>
                                8
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">2. What is the chemical symbol for Gold?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q2" 
                                       value="a" 
                                       class="mr-2" required>
                                Au
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q2" 
                                       value="b" 
                                       class="mr-2" required>
                                Ag
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q2" 
                                       value="c" 
                                       class="mr-2" required>
                                Fe
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q2" 
                                       value="d" 
                                       class="mr-2" required>
                                Cu
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">3. What is the most abundant gas in Earth's atmosphere?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q3" 
                                       value="a" 
                                       class="mr-2" required>
                                Oxygen
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q3" 
                                       value="b" 
                                       class="mr-2" required>
                                Carbon Dioxide
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q3" 
                                       value="c" 
                                       class="mr-2" required>
                                Nitrogen
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q3" 
                                       value="d" 
                                       class="mr-2" required>
                                Hydrogen
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">4. What is the pH of pure water?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q4" 
                                       value="a" 
                                       class="mr-2" required>
                                0
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q4" 
                                       value="b" 
                                       class="mr-2" required>
                                7
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q4" 
                                       value="c" 
                                       class="mr-2" required>
                                14
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q4" 
                                       value="d" 
                                       class="mr-2" required>
                                10
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">5. What is the chemical formula for table salt?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q5" 
                                       value="a" 
                                       class="mr-2" required>
                                H2O
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q5" 
                                       value="b" 
                                       class="mr-2" required>
                                CO2
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q5" 
                                       value="c" 
                                       class="mr-2" required>
                                H2SO4
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q5" 
                                       value="d" 
                                       class="mr-2" required>
                                NaCl
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">6. What is the smallest unit of matter?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q6" 
                                       value="a" 
                                       class="mr-2" required>
                                Atom
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q6" 
                                       value="b" 
                                       class="mr-2" required>
                                Molecule
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q6" 
                                       value="c" 
                                       class="mr-2" required>
                                Cell
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q6" 
                                       value="d" 
                                       class="mr-2" required>
                                Electron
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">7. What type of bond involves the sharing of electrons?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q7" 
                                       value="a" 
                                       class="mr-2" required>
                                Ionic bond
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q7" 
                                       value="b" 
                                       class="mr-2" required>
                                Hydrogen bond
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q7" 
                                       value="c" 
                                       class="mr-2" required>
                                Covalent bond
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q7" 
                                       value="d" 
                                       class="mr-2" required>
                                Metallic bond
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">8. What is the atomic number of Oxygen?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q8" 
                                       value="a" 
                                       class="mr-2" required>
                                6
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q8" 
                                       value="b" 
                                       class="mr-2" required>
                                8
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q8" 
                                       value="c" 
                                       class="mr-2" required>
                                10
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q8" 
                                       value="d" 
                                       class="mr-2" required>
                                12
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">9. What is the chemical formula for water?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q9" 
                                       value="a" 
                                       class="mr-2" required>
                                CO2
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q9" 
                                       value="b" 
                                       class="mr-2" required>
                                NaCl
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q9" 
                                       value="c" 
                                       class="mr-2" required>
                                O2
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q9" 
                                       value="d" 
                                       class="mr-2" required>
                                H2O
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="font-semibold mb-4">10. What is the process of solid changing directly to gas called?</p>
                        <div class="space-y-2">
                            <label class="block">
                                <input type="radio" 
                                       name="q10" 
                                       value="a" 
                                       class="mr-2" required>
                                Sublimation
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q10" 
                                       value="b" 
                                       class="mr-2" required>
                                Evaporation
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q10" 
                                       value="c" 
                                       class="mr-2" required>
                                Condensation
                            </label>
                            <label class="block">
                                <input type="radio" 
                                       name="q10" 
                                       value="d" 
                                       class="mr-2" required>
                                Melting
                            </label>
                        </div>
                    </div>

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

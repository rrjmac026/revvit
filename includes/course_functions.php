<?php
function initializeTables($conn) {
    // Create subjects table
    $create_subjects = "
        CREATE TABLE IF NOT EXISTS subjects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT
        )
    ";
    $conn->query($create_subjects);

    // Create courses table
    $create_courses = "
        CREATE TABLE IF NOT EXISTS courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            subject_id INT NOT NULL,
            title VARCHAR(200) DEFAULT NULL,
            description TEXT,
            FOREIGN KEY (subject_id) REFERENCES subjects(id)
        )
    ";
    $conn->query($create_courses);

    // Create user_courses table
    $create_user_courses = "
        CREATE TABLE IF NOT EXISTS user_courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            course_id INT NOT NULL,
            status ENUM('Not Started', 'In Progress', 'Completed') DEFAULT 'Not Started',
            progress DECIMAL(5,2) DEFAULT 0,
            started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            UNIQUE KEY unique_user_course (user_id, course_id)
        )
    ";
    $conn->query($create_user_courses);

    // Create quiz_attempts table
    $create_quiz_attempts = "
        CREATE TABLE IF NOT EXISTS quiz_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            subject_id INT NOT NULL,
            total_questions INT NOT NULL,
            correct_answers INT NOT NULL,
            score_percentage DECIMAL(5,2) NOT NULL,
            attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $conn->query($create_quiz_attempts);

    // Insert default subjects if they don't exist
    $subjects = [
        'PHP' => 'Learn PHP programming',
        'Chemistry' => 'Basic chemistry concepts',
        'Physics' => 'Basic physics concepts',
        'Mathematics' => 'Basic mathematics',
        'Biology' => 'Basic biology concepts',
        'JavaScript' => 'Learn JavaScript programming',
        'Python' => 'Learn Python programming',
        'Computer Science' => 'Introduction to computer science',
        'Cybersecurity' => 'Basic cybersecurity principles',
        'English' => 'English language basics',
        'French' => 'Basic French language',
        'Spanish' => 'Basic Spanish language',
        'Networking' => 'Basic networking concepts'
    ];

    foreach ($subjects as $name => $description) {
        $stmt = $conn->prepare("INSERT IGNORE INTO subjects (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
    }

    // Insert default courses if they don't exist
    $courses = [
        'PHP' => 'PHP Basics',
        'Chemistry' => 'Chemistry Basics',
        'Physics' => 'Physics Basics',
        'Mathematics' => 'Mathematics Basics',
        'Biology' => 'Biology Basics',
        'JavaScript' => 'JavaScript Basics',
        'Python' => 'Python Basics',
        'Computer Science' => 'Computer Science Basics',
        'Cybersecurity' => 'Cybersecurity Basics',
        'English' => 'English Basics',
        'French' => 'French Basics',
        'Spanish' => 'Spanish Basics',
        'Networking' => 'Networking Basics'
    ];

    foreach ($courses as $subject => $title) {
        // Get subject ID
        $stmt = $conn->prepare("SELECT id FROM subjects WHERE name = ?");
        $stmt->bind_param("s", $subject);
        $stmt->execute();
        $result = $stmt->get_result();
        $subject_row = $result->fetch_assoc();
        $subject_id = $subject_row['id'] ?? null;

        if ($subject_id) {
            // Check if course already exists
            $check_stmt = $conn->prepare("SELECT id FROM courses WHERE subject_id = ?");
            $check_stmt->bind_param("i", $subject_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows == 0) {
                // Dynamically check if 'title' column exists
                $column_check = $conn->query("SHOW COLUMNS FROM courses LIKE 'title'");
                
                if ($column_check->num_rows > 0) {
                    // Insert with title
                    $stmt = $conn->prepare("INSERT INTO courses (subject_id, title, description) VALUES (?, ?, ?)");
                    $description = "Learn the basics of " . $subject;
                    $stmt->bind_param("iss", $subject_id, $title, $description);
                } else {
                    // Insert without title
                    $stmt = $conn->prepare("INSERT INTO courses (subject_id, description) VALUES (?, ?)");
                    $description = "Learn the basics of " . $subject;
                    $stmt->bind_param("is", $subject_id, $description);
                }
                
                $stmt->execute();
            }
        }
    }
}

function getSubjects($conn) {
    initializeTables($conn); // Ensure tables exist
    $subjects_query = "SELECT * FROM subjects";
    $result = $conn->query($subjects_query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getUserCourses($conn, $user_id) {
    initializeTables($conn); // Ensure tables exist
    $courses_query = "
        SELECT 
            c.id, 
            s.name AS subject_name, 
            uc.status,
            COALESCE(
                (
                    SELECT MAX(score_percentage)
                    FROM quiz_attempts qa
                    WHERE qa.user_id = uc.user_id
                    AND qa.subject_id = s.id
                ), 0
            ) as progress
        FROM 
            user_courses uc
        JOIN 
            courses c ON uc.course_id = c.id
        JOIN 
            subjects s ON c.subject_id = s.id
        WHERE 
            uc.user_id = ?
        GROUP BY
            c.id, s.name, uc.status
        ORDER BY 
            uc.enrolled_at DESC
    ";
    
    $stmt = $conn->prepare($courses_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function enrollInCourse($conn, $user_id, $course_id) {
    // If user_id is null, try to get it from session
    if ($user_id === null) {
        session_start();
        $user_id = $_SESSION['user_id'] ?? null;
    }

    // Validate user_id
    if ($user_id === null) {
        error_log("enrollInCourse: No user ID provided or found in session");
        return false;
    }

    // Check if already enrolled
    $check_query = "SELECT id FROM user_courses WHERE user_id = ? AND course_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $user_id, $course_id);
    $check_stmt->execute();
    $existing = $check_stmt->get_result();

    if ($existing->num_rows > 0) {
        return true; // Already enrolled
    }

    // Prepare enrollment query
    $query = "INSERT INTO user_courses (user_id, course_id, enrolled_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("enrollInCourse: Failed to prepare statement. Error: " . $conn->error);
        return false;
    }

    $stmt->bind_param("ii", $user_id, $course_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("enrollInCourse: Failed to enroll. User ID: $user_id, Course ID: $course_id, Error: " . $stmt->error);
        return false;
    }
}

function recordQuizAttempt($conn, $user_id, $quiz_id, $score, $subject_id = null) {
    // If user_id is null, try to get it from session
    if ($user_id === null) {
        session_start();
        $user_id = $_SESSION['user_id'] ?? null;
    }

    // Validate user_id
    if ($user_id === null) {
        error_log("recordQuizAttempt: No user ID provided or found in session");
        return false;
    }

    // Validate quiz_id
    if ($quiz_id === null) {
        error_log("recordQuizAttempt: No quiz ID provided");
        return false;
    }

    // Check and add missing columns to quiz_attempts table
    $columns_to_check = [
        'score_percentage' => "ALTER TABLE quiz_attempts ADD COLUMN score_percentage DECIMAL(5,2) NULL",
        'correct_answers' => "ALTER TABLE quiz_attempts ADD COLUMN correct_answers INT NULL",
        'total_questions' => "ALTER TABLE quiz_attempts ADD COLUMN total_questions INT NULL",
        'completed_at' => "ALTER TABLE quiz_attempts ADD COLUMN completed_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP",
        'course_id' => "ALTER TABLE quiz_attempts ADD COLUMN course_id INT NULL",
        'subject_id' => "ALTER TABLE quiz_attempts ADD COLUMN subject_id INT NULL"
    ];

    foreach ($columns_to_check as $column => $alter_query) {
        $column_check_query = "SHOW COLUMNS FROM quiz_attempts LIKE '$column'";
        $column_check_result = $conn->query($column_check_query);
        
        if ($column_check_result->num_rows === 0) {
            // Column doesn't exist, add it
            if (!$conn->query($alter_query)) {
                error_log("recordQuizAttempt: Failed to add $column column. Error: " . $conn->error);
            }
        }
    }

    // Validate foreign key references
    $user_check_query = "SELECT id FROM users WHERE id = ?";
    $user_check_stmt = $conn->prepare($user_check_query);
    $user_check_stmt->bind_param("i", $user_id);
    $user_check_stmt->execute();
    $user_result = $user_check_stmt->get_result();
    
    if ($user_result->num_rows === 0) {
        error_log("recordQuizAttempt: Invalid user ID: $user_id");
        return false;
    }

    // Validate quiz_id reference
    $quiz_check_query = "SELECT id, course_id FROM quizzes WHERE id = ?";
    $quiz_check_stmt = $conn->prepare($quiz_check_query);
    $quiz_check_stmt->bind_param("i", $quiz_id);
    $quiz_check_stmt->execute();
    $quiz_result = $quiz_check_stmt->get_result();
    
    if ($quiz_result->num_rows === 0) {
        error_log("recordQuizAttempt: Invalid quiz ID: $quiz_id");
        return false;
    }

    // Get course_id from quiz if not provided
    $quiz_row = $quiz_result->fetch_assoc();
    $course_id = $quiz_row['course_id'];

    // Validate course_id reference
    if ($course_id !== null) {
        $course_check_query = "SELECT id FROM courses WHERE id = ?";
        $course_check_stmt = $conn->prepare($course_check_query);
        $course_check_stmt->bind_param("i", $course_id);
        $course_check_stmt->execute();
        $course_result = $course_check_stmt->get_result();
        
        if ($course_result->num_rows === 0) {
            error_log("recordQuizAttempt: Invalid course ID: $course_id");
            $course_id = null;
        }
    }

    // Validate subject_id reference if provided
    if ($subject_id !== null) {
        $subject_check_query = "SELECT id FROM subjects WHERE id = ?";
        $subject_check_stmt = $conn->prepare($subject_check_query);
        $subject_check_stmt->bind_param("i", $subject_id);
        $subject_check_stmt->execute();
        $subject_result = $subject_check_stmt->get_result();
        
        if ($subject_result->num_rows === 0) {
            error_log("recordQuizAttempt: Invalid subject ID: $subject_id");
            $subject_id = null;
        }
    }

    // Calculate correct answers (assuming total questions is 10)
    $total_questions = 10;
    $correct_answers = floor($score * $total_questions);
    $score_percentage = $score * 100;

    // Prepare the base query
    $base_columns = ['user_id', 'quiz_id', 'course_id', 'score_percentage', 'correct_answers', 'total_questions', 'completed_at'];
    $base_placeholders = implode(', ', array_fill(0, count($base_columns), '?'));
    $base_column_names = implode(', ', $base_columns);

    // Prepare the query with optional subject_id
    $query = "INSERT INTO quiz_attempts ($base_column_names" . 
             ($subject_id !== null ? ", subject_id" : "") . 
             ") VALUES ($base_placeholders" . 
             ($subject_id !== null ? ", ?" : "") . 
             ")";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("recordQuizAttempt: Failed to prepare statement. Error: " . $conn->error);
        return false;
    }

    // Dynamically build parameter types and values
    $param_types = 'iiiidii'; // base types
    $current_time = date('Y-m-d H:i:s');
    
    // Prepare bind parameters
    $bind_params = [
        &$param_types,
        &$user_id, 
        &$quiz_id, 
        &$course_id, 
        &$score_percentage, 
        &$correct_answers, 
        &$total_questions,
        &$current_time
    ];

    // Add subject_id to bind parameters if not null
    if ($subject_id !== null) {
        $param_types .= 'i';
        $bind_params[] = &$subject_id;
    }

    // Update the first parameter with the modified type string
    $bind_params[0] = &$param_types;

    // Use call_user_func_array to dynamically call bind_param
    call_user_func_array([$stmt, 'bind_param'], $bind_params);
    
    if ($stmt->execute()) {
        $attempt_id = $stmt->insert_id;
        error_log("recordQuizAttempt: Successfully recorded quiz attempt. ID: $attempt_id, User ID: $user_id, Quiz ID: $quiz_id, Score: $score_percentage%");
        return $attempt_id;
    } else {
        error_log("recordQuizAttempt: Failed to record quiz attempt. User ID: $user_id, Quiz ID: $quiz_id, Score: $score_percentage%, Error: " . $stmt->error);
        return false;
    }
}

function updateCourseProgress($conn, $user_id, $course_id, $score_percentage) {
    $update_query = "
        UPDATE user_courses 
        SET 
            status = CASE 
                WHEN ? >= 70 THEN 'Completed'
                ELSE 'In Progress' 
            END,
            progress = ?,
            completed_at = CASE 
                WHEN ? >= 70 THEN NOW()
                ELSE NULL 
            END
        WHERE 
            user_id = ? AND course_id = ?
    ";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("dddii", $score_percentage, $score_percentage, $score_percentage, $user_id, $course_id);
    $success = $stmt->execute();
    
    error_log("Course progress updated for user $user_id in course $course_id. New progress: $score_percentage%. Success: " . ($success ? "Yes" : "No"));
    return $success;
}

function getCourseStatistics($conn, $user_id) {
    initializeTables($conn); // Ensure tables exist
    
    $stats_query = "
        SELECT 
            COUNT(DISTINCT course_id) as enrolled_courses,
            (
                SELECT COUNT(DISTINCT subject_id)
                FROM quiz_attempts
                WHERE user_id = ? AND score_percentage = 100
            ) as perfect_scores,
            (
                SELECT COUNT(DISTINCT subject_id)
                FROM quiz_attempts qa
                WHERE user_id = ? 
                AND subject_id NOT IN (
                    SELECT DISTINCT subject_id 
                    FROM quiz_attempts 
                    WHERE user_id = ? 
                    AND score_percentage = 100
                )
            ) as in_progress_courses,
            (
                SELECT COUNT(DISTINCT id)
                FROM quiz_attempts
                WHERE user_id = ?
            ) as total_quiz_attempts,
            (
                SELECT AVG(score_percentage)
                FROM quiz_attempts
                WHERE user_id = ?
            ) as average_score
        FROM 
            user_courses
        WHERE 
            user_id = ?
    ";
    
    $stmt = $conn->prepare($stats_query);
    $stmt->bind_param("iiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stats = $result->fetch_assoc();
    if (!$stats) {
        $stats = [
            'enrolled_courses' => 0,
            'perfect_scores' => 0,
            'in_progress_courses' => 0,
            'total_quiz_attempts' => 0,
            'average_score' => 0
        ];
    }
    
    error_log("Course statistics for user $user_id: " . json_encode($stats));
    return $stats;
}

function getCourseIdFromQuiz($conn, $quiz_name) {
    initializeTables($conn); // Ensure tables exist
    
    // Extract the subject name from the quiz file name (e.g., 'php_basics.php' -> 'php')
    $subject = strtolower(explode('_', basename($quiz_name, '.php'))[0]);
    
    // Mapping for more flexible subject matching
    $subject_map = [
        'php' => 'PHP',
        'javascript' => 'JavaScript',
        'python' => 'Python',
        'biology' => 'Biology',
        'chemistry' => 'Chemistry',
        'physics' => 'Physics',
        'computer' => 'Computer Science',
        'cybersecurity' => 'Cybersecurity',
        'english' => 'English',
        'french' => 'French',
        'spanish' => 'Spanish',
        'algebra' => 'Mathematics',
        'geometry' => 'Mathematics',
        'trigonometry' => 'Mathematics'
    ];

    // Use mapping or default to capitalized subject
    $mapped_subject = $subject_map[$subject] ?? ucfirst($subject);
    
    // Get the course ID based on the subject
    $query = "
        SELECT c.id
        FROM courses c
        JOIN subjects s ON c.subject_id = s.id
        WHERE LOWER(s.name) = LOWER(?)
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $mapped_subject);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        error_log("Found course ID " . $row['id'] . " for quiz " . $quiz_name);
        return $row['id'];
    }
    
    // If no course found, try to create a default course
    $subject_stmt = $conn->prepare("SELECT id FROM subjects WHERE LOWER(name) = LOWER(?)");
    $subject_stmt->bind_param("s", $mapped_subject);
    $subject_stmt->execute();
    $subject_result = $subject_stmt->get_result();
    $subject_row = $subject_result->fetch_assoc();
    
    if ($subject_row) {
        $course_insert_stmt = $conn->prepare("INSERT INTO courses (subject_id, title, description) VALUES (?, ?, ?)");
        $title = $mapped_subject . " Basics";
        $description = "Learn the basics of " . $mapped_subject;
        $course_insert_stmt->bind_param("iss", $subject_row['id'], $title, $description);
        $course_insert_stmt->execute();
        
        $new_course_id = $conn->insert_id;
        error_log("Created new course ID $new_course_id for quiz " . $quiz_name);
        return $new_course_id;
    }
    
    error_log("No course found for quiz " . $quiz_name);
    return null;
}

function ensureQuizExists($conn, $quiz_id, $course_id = null, $subject_id = null, $title = null) {
    // If quiz_id is not provided, we can't ensure its existence
    if (!$quiz_id) {
        error_log("ensureQuizExists: No quiz_id provided");
        return false;
    }

    // First, check if the quiz already exists
    $check_query = "SELECT id FROM quizzes WHERE id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $quiz_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    // If quiz exists, return true
    if ($result->num_rows > 0) {
        return true;
    }

    // If no details provided, we can't create the quiz
    if ($course_id === null || $subject_id === null || $title === null) {
        error_log("ensureQuizExists: Insufficient details to create quiz. Quiz ID: $quiz_id");
        return false;
    }

    // Insert the quiz
    $insert_query = "INSERT INTO quizzes (id, title, course_id, subject_id, difficulty) VALUES (?, ?, ?, ?, 'Medium')";
    $insert_stmt = $conn->prepare($insert_query);
    
    if (!$insert_stmt) {
        error_log("ensureQuizExists: Failed to prepare insert statement. Error: " . $conn->error);
        return false;
    }

    $insert_stmt->bind_param("isii", $quiz_id, $title, $course_id, $subject_id);
    
    if ($insert_stmt->execute()) {
        error_log("ensureQuizExists: Created new quiz. ID: $quiz_id, Title: $title");
        return true;
    } else {
        error_log("ensureQuizExists: Failed to insert quiz. Error: " . $insert_stmt->error);
        return false;
    }
}
?>

<?php
session_start();
require_once 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Course Category Icons (Base64 encoded SVG)
$course_icons = [
    'programming' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSIjMzM4NkZGIj48cGF0aCBkPSJNMjAuMTM3IDQuNjU0YTkuMzY3IDkuMzY3IDAgMCAwLTMuNzQ4LTIuNDc1Yy0xLjM3Ni0uNTc2LTIuODU5LS44NzktNC4zODktLjg3OS0xLjUzIDAtMy4wMTMuMzAzLTQuMzg5Ljg3OWE5LjM2NyA5LjM2NyAwIDAgMC0zLjc0OCAyLjQ3NUMyLjg1NyA2LjM3NSAxLjk5OSA4LjU5NCAxLjk5OSAxMWMwIDMuMDg5IDEuNzY4IDUuODc0IDQuNDc3IDcuMzM5bC0uOTg3IDIuNjU3YS41LjUgMCAwIDAgLjc0Ni42MzFsMy42NzctMi4wNTRhOS40NjYgOS40NjYgMCAwIDAgNC4wODcgMGwzLjY3NyAyLjA1NGEuNS41IDAgMCAwIC43NDYtLjYzMWwtLjk4Ny0yLjY1N0MyMC4yMzEgMTYuODc4IDIyIDE0LjA4OSAyMiAxMWMwLTIuNDA2LS44NTgtNC42MjUtMi40NjMtNi4zNDZ6TTcuNSAxM2EyIDIgMCAxIDEgMC04IDIgMiAwIDAgMSAwIDR6bTkgMGEyIDIgMCAxIDEgMC04IDIgMiAwIDAgMSAwIDR6Ii8+PC9zdmc+',
    
    'science' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSIjMTBCOTgxIj48cGF0aCBkPSJNMTkuNDM5IDguNDM5bC0zLjg3OCAzLjg3OGMtLjUxMy41MTMtLjc2OSAxLjE4Ni0uNzY5IDEuODYxdjQuODIyYTEuNSAxLjUgMCAwIDEtMS41IDEuNWgtMi41YTEuNSAxLjUgMCAwIDEtMS41LTEuNXYtNC44MjJjMC0uNjc1LS4yNTYtMS4zNDgtLjc2OS0xLjg2MWwtMy44NzgtMy44NzhBMi41IDIuNSAwIDAgMSA2LjUgNS4zNjNoMTFhMi41IDIuNSAwIDAgMSAxLjc2OSA0LjA3NnoiLz48L3N2Zz4=',
    
    'mathematics' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSIjRkY1NzIyIj48cGF0aCBkPSJNMTIgMmwtNS41IDkuMTRMMi41IDE1LjVhMi41IDIuNSAwIDAgMCAyLjUgMi41aDQuNXY0YTEgMSAwIDAgMCAxIDFoNGExIDEgMCAwIDAgMS0xdi00aDQuNWEyLjUgMi41IDAgMCAwIDIuNS0yLjVsLTQuLTQuMzZMMTIgMnptMCAxLjczNmw4LjYgNy42NTRhLjUuNSAwIDAgMCAuNDIuMjFoNC40OGEuNS41IDAgMCAxIC40NzMuNjU3bC0yLjM3NCA0LjA5NmEuNS41IDAgMCAxLS40NzMuMzQzSDVhLjUuNSAwIDAgMS0uNDczLS42NTdsMi4zNzQtNC4wOTZhLjUuNSAwIDAgMCAuMDk5LS4yMUwxMiAzLjczNnoiLz48L3N2Zz4=',
    
    'language' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSIjOUMwMUMxIj48cGF0aCBkPSJNMTIuMjc0IDIuNjU0YTEgMSAwIDAgMC0xLjU0OCAwbC04IDEwYTEgMSAwIDAgMCAuNzc0IDEuNjQ2aDQuNXY3YTEgMSAwIDAgMCAxIDFoNWExIDEgMCAwIDAgMS0xdi03aDQuNWExIDEgMCAwIDAgLjc3NC0xLjY0NmwtOC0xMHoiLz48L3N2Zz4=',
    
    'technology' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSIjMDBCQ0Q0Ij48cGF0aCBkPSJNMTYgNmEyIDIgMCAxIDEtNCAwIDIgMiAwIDAgMSA0IDB6bS02IDhhMiAyIDAgMSAwIDAtNCAyIDIgMCAwIDAgMCA0em06IDRhMi43NSAyLjc1IDAgMSAxLTUuNSAwIDIuNzUgMi43NSAwIDAgMSA1LjUgMHoiLz48L3N2Zz4='
];

// Available Courses
$courses = [
    'programming' => [
        'title' => 'Programming Courses',
        'icon' => $course_icons['programming'],
        'subjects' => [
            'basic_php' => [
                'name' => 'Basic PHP',
                'description' => 'Learn the fundamentals of PHP programming',
                'difficulty' => 'Beginner',
                'icon' => 'php-icon.png',
                'quiz_link' => 'quiz/php_basics.php'
            ],
            'python_basics' => [
                'name' => 'Python Basics',
                'description' => 'Introduction to Python programming',
                'difficulty' => 'Beginner',
                'icon' => 'python-icon.png',
                'quiz_link' => 'quiz/python_basics.php'
            ],
            'javascript_fundamentals' => [
                'name' => 'JavaScript Fundamentals',
                'description' => 'Core concepts of JavaScript',
                'difficulty' => 'Intermediate',
                'icon' => 'js-icon.png',
                'quiz_link' => 'quiz/javascript_basics.php'
            ]
        ]
    ],
    'science' => [
        'title' => 'Science Courses',
        'icon' => $course_icons['science'],
        'subjects' => [
            'general_physics' => [
                'name' => 'General Physics',
                'description' => 'Fundamental principles of physical sciences',
                'difficulty' => 'Intermediate',
                'icon' => 'physics-icon.png',
                'quiz_link' => 'quiz/general_physics.php'
            ],
            'biology_basics' => [
                'name' => 'Biology Basics',
                'description' => 'Introduction to life sciences',
                'difficulty' => 'Beginner',
                'icon' => 'biology-icon.png',
                'quiz_link' => 'quiz/biology_basics.php'
            ],
            'chemistry_fundamentals' => [
                'name' => 'Chemistry Fundamentals',
                'description' => 'Basic concepts of chemistry',
                'difficulty' => 'Intermediate',
                'icon' => 'chemistry-icon.png',
                'quiz_link' => 'quiz/chemistry_basics.php'
            ]
        ]
    ],
    'mathematics' => [
        'title' => 'Mathematics Courses',
        'icon' => $course_icons['mathematics'],
        'subjects' => [
            'algebra_basics' => [
                'name' => 'Algebra Basics',
                'description' => 'Foundational algebraic concepts',
                'difficulty' => 'Beginner',
                'icon' => 'algebra-icon.png',
                'quiz_link' => 'quiz/algebra_basics.php'
            ],
            'geometry_intro' => [
                'name' => 'Geometry Introduction',
                'description' => 'Basic geometric principles',
                'difficulty' => 'Beginner',
                'icon' => 'geometry-icon.png',
                'quiz_link' => 'quiz/geometry_basics.php'
            ],
            'trigonometry' => [
                'name' => 'Trigonometry',
                'description' => 'Advanced trigonometric concepts',
                'difficulty' => 'Advanced',
                'icon' => 'trig-icon.png',
                'quiz_link' => 'quiz/trigonometry.php'
            ]
        ]
    ],
    'language' => [
        'title' => 'Language Courses',
        'icon' => $course_icons['language'],
        'subjects' => [
            'english_grammar' => [
                'name' => 'English Grammar',
                'description' => 'Improve your English language skills',
                'difficulty' => 'Beginner',
                'icon' => 'english-icon.png',
                'quiz_link' => 'quiz/english_grammar.php'
            ],
            'spanish_basics' => [
                'name' => 'Spanish Basics',
                'description' => 'Introduction to Spanish language',
                'difficulty' => 'Beginner',
                'icon' => 'spanish-icon.png',
                'quiz_link' => 'quiz/spanish_basics.php'
            ],
            'french_conversation' => [
                'name' => 'French Conversation',
                'description' => 'Basic French conversational skills',
                'difficulty' => 'Intermediate',
                'icon' => 'french-icon.png',
                'quiz_link' => 'quiz/french_conversation.php'
            ]
        ]
    ],
    'technology' => [
        'title' => 'Technology Courses',
        'icon' => $course_icons['technology'],
        'subjects' => [
            'computer_basics' => [
                'name' => 'Computer Basics',
                'description' => 'Fundamental computer skills',
                'difficulty' => 'Beginner',
                'icon' => 'computer-icon.png',
                'quiz_link' => 'quiz/computer_basics.php'
            ],
            'networking_intro' => [
                'name' => 'Networking Introduction',
                'description' => 'Basic network concepts',
                'difficulty' => 'Intermediate',
                'icon' => 'network-icon.png',
                'quiz_link' => 'quiz/networking_basics.php'
            ],
            'cybersecurity_fundamentals' => [
                'name' => 'Cybersecurity Fundamentals',
                'description' => 'Introduction to cybersecurity',
                'difficulty' => 'Advanced',
                'icon' => 'security-icon.png',
                'quiz_link' => 'quiz/cybersecurity_basics.php'
            ]
        ]
    ]
];

// Comprehensive Quiz Questions for Each Course Category
$quiz_questions = [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revvit - Course Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-primary { background-color: #f97316; }
        .bg-primary/10 { background-color: rgba(249, 115, 22, 0.1); }
        .hover:bg-primary/90:hover { background-color: rgba(249, 115, 22, 0.9); }
        .hover:bg-primary/10:hover { background-color: rgba(249, 115, 22, 0.1); }
        .text-primary { color: #f97316; }
        .hover:text-primary/80:hover { color: rgba(249, 115, 22, 0.8); }
        .border-primary { border-color: #f97316; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Choose Your Course</h1>
        
        <!-- Back to Dashboard Button -->
        <div class="container mx-auto px-4 mb-6">
            <div class="bg-orange-50 rounded-2xl shadow-xl transform hover:scale-[1.01] transition-all duration-300">
                <div class="flex items-center p-4 bg-primary/10 border-l-4 border-primary rounded-2xl">
                    <a href="dashboard.php" class="flex items-center text-primary hover:bg-primary/10 px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-x-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-primary group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                        </svg>
                        <span class="text-lg font-semibold text-primary group-hover:text-primary/80">
                            Back to Dashboard
                        </span>
                    </a>
                    <div class="ml-auto text-sm text-gray-600 hidden md:block">
                        Select your learning path and start your educational journey
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($courses as $category_key => $category): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-4"><?php echo htmlspecialchars($category['title']); ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($category['subjects'] as $subject_key => $subject): ?>
                        <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-xl transition-shadow">
                            <div class="flex items-center mb-4">
                                <img src="<?php echo htmlspecialchars($category['icon']); ?>" 
                                     alt="<?php echo htmlspecialchars($category['title']); ?>" 
                                     class="w-12 h-12 mr-4">
                                <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($subject['name']); ?></h3>
                            </div>
                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($subject['description']); ?></p>
                            
                            <!-- Direct Quiz Link -->
                            <button onclick="window.location.href='<?php echo htmlspecialchars($subject['quiz_link']); ?>'" 
                                    class="block w-full bg-primary text-white py-2 rounded-lg hover:bg-primary/90 transition-colors text-center font-semibold">
                                Start Quiz
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Quiz Modal JavaScript -->
        <script>
        // Add courses data to JavaScript
        const courses = <?php echo json_encode($courses); ?>;
        </script>

    </div>
</body>
</html>

<?php 
// Optional: Add tracking or logging of course views
if (isset($_GET['category']) && isset($_GET['subject'])) {
    // You could add a database log of course views here
    // Example:
    // $stmt = $conn->prepare("INSERT INTO course_views (user_id, category, subject) VALUES (?, ?, ?)");
    // $stmt->bind_param("iss", $user_id, $selected_category, $selected_subject);
    // $stmt->execute();
}
?>
<!-- community.php -->
<?php
session_start();
require_once 'db.php';
require_once 'includes/auth_check.php';
require_once 'includes/community_functions.php';
require_login();

// Debug session and user information
error_log("Session User ID: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("Full Session Data: " . print_r($_SESSION, true));
error_log("Full POST Data: " . print_r($_POST, true));

// Verify database connection
if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
}

// Verify users table
$user_check = $conn->query("SELECT COUNT(*) as user_count FROM users");
if ($user_check) {
    $user_count = $user_check->fetch_assoc()['user_count'];
    error_log("Total users in database: $user_count");
} else {
    error_log("Failed to check users table: " . $conn->error);
}

// Verify subjects table
$subject_check = $conn->query("SELECT COUNT(*) as subject_count FROM subjects");
if ($subject_check) {
    $subject_count = $subject_check->fetch_assoc()['subject_count'];
    error_log("Total subjects in database: $subject_count");
} else {
    error_log("Failed to check subjects table: " . $conn->error);
}

$user_id = $_SESSION['user_id'];

// Profile Picture Logic with Case-Insensitive Check
$default_profile_picture = 'images/default-profile.png';
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$profile_picture = $user_data['profile_picture'];

// Function to find file with case-insensitive match
function findFileIgnoreCase($directory, $filename) {
    if (empty($filename)) return false;
    
    $files = glob($directory . '/*', GLOB_NOSORT);
    foreach ($files as $file) {
        if (strtolower(basename($file)) === strtolower($filename)) {
            return $file;
        }
    }
    return false;
}

// Construct full path with case-insensitive check
$upload_dir = 'uploads/profile_pictures';
$full_path = findFileIgnoreCase($upload_dir, $profile_picture) ?: $default_profile_picture;

$display_picture = $full_path;

$stmt->close();

$success_message = '';
$error_message = '';

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_POST['action'] === 'create_post') {
        // Log all POST data for debugging
        error_log("POST Data for create_post: " . print_r($_POST, true));

        $content = trim($_POST['content'] ?? '');
        $subject_id = !empty($_POST['subject_id']) ? $_POST['subject_id'] : null;
        
        // Detailed validation logging
        error_log("User ID: $user_id");
        error_log("Content: $content");
        error_log("Subject ID: " . ($subject_id ?? 'NULL'));

        if (empty($content)) {
            $error_message = "Post content cannot be empty.";
            error_log("Post creation failed: Empty content");
        } else {
            // Use createPostNew instead of createPost
            $result = createPostNew($conn, $user_id, $content, $subject_id);
            
            if ($result) {
                $success_message = "Post created successfully!";
                error_log("Post created successfully");
                
                // Redirect to prevent form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error_message = "Failed to create post. Please try again.";
                error_log("Post creation failed: createPostNew returned false");
            }
        }
    } elseif ($_POST['action'] === 'add_comment') {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Log all POST data for debugging
        error_log("POST Data for add_comment: " . print_r($_POST, true));

        $content = trim($_POST['comment'] ?? '');
        $post_id = intval($_POST['post_id'] ?? 0);
        
        // Detailed validation logging
        error_log("User ID: $user_id");
        error_log("Post ID: $post_id");
        error_log("Comment Content: $content");

        if (empty($content)) {
            $error_message = "Comment cannot be empty.";
            error_log("Comment creation failed: Empty content");
        } elseif ($post_id <= 0) {
            $error_message = "Invalid post selected.";
            error_log("Comment creation failed: Invalid post ID");
        } else {
            $result = addComment($conn, $post_id, $user_id, $content);
            
            if ($result) {
                $success_message = "Comment added successfully!";
                error_log("Comment created successfully. Comment ID: $result");
                
                // Redirect to prevent form resubmission
                header("Location: " . $_SERVER['PHP_SELF'] . "?post_id=" . $post_id . "#post-" . $post_id);
                exit;
            } else {
                $error_message = "Failed to add comment. Please try again.";
                error_log("Comment creation failed: addComment returned false");
            }
        }
    } elseif ($_POST['action'] === 'toggle_like') {
        $post_id = $_POST['post_id'];
        toggleLike($conn, $post_id, $user_id);
        // No need for messages as this will be handled by AJAX
        exit;
    }
}

// Get posts
$subject_filter = isset($_GET['subject']) ? $_GET['subject'] : null;
$posts = getPosts($conn, $subject_filter);

// Get subjects for the dropdown
$subjects = getSubjects($conn);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community | RevvIt Learning</title>
    <link href="dist/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            document.documentElement.classList.toggle('dark');
        }
    }" 
    :class="{ 'dark': darkMode }"
    class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300"
>
    <?php include 'includes/header.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-4xl">
        <!-- Dark Mode Toggle -->
        <div class="fixed bottom-6 right-6 z-50">
            <!-- <button 
                @click="toggleDarkMode()"
                class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-full shadow-lg hover:bg-primary/20 dark:hover:bg-primary/30 transition-all"
            >
                <i x-show="!darkMode" class="fas fa-moon"></i>
                <i x-show="darkMode" class="fas fa-sun"></i>
            </button> -->
        </div>

        <!-- Create Post Section -->
        <section 
            x-data="{ expanded: false }"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 mb-12 overflow-hidden transition-all duration-300"
        >
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Create a Post</h2>
                    <!-- <button 
                        @click="expanded = !expanded"
                        class="text-primary hover:bg-primary/10 p-2 rounded-full transition-colors"
                    >
                        <i x-show="!expanded" class="fas fa-expand"></i>
                        <i x-show="expanded" class="fas fa-compress"></i>
                    </button> -->
                </div>

                <form 
                    method="POST" 
                    class="space-y-4 transition-all duration-300"
                    :class="{ 'max-h-96': expanded, 'max-h-40': !expanded }"
                >
                    <input type="hidden" name="action" value="create_post">
                    <textarea 
                        name="content" 
                        placeholder="Share your thoughts..." 
                        class="w-full p-4 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary/50 dark:bg-gray-700 dark:text-gray-200 transition-colors"
                        rows="4"
                        required
                    ></textarea>

                    <div class="flex justify-between items-center">
                        <select 
                            name="subject_id" 
                            class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-700 dark:text-gray-200"
                        >
                            <option value="">Select Subject</option>
                            <?php 
                            // Reset the internal pointer of the result set
                            $subjects->data_seek(0);
                            while ($subject = $subjects->fetch_assoc()): ?>
                                <option value="<?php echo $subject['id']; ?>">
                                    <?php echo htmlspecialchars($subject['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <button 
                            type="submit" 
                            class="bg-primary text-white px-4 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50"
                        >
                            Post
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Posts Feed -->
        <section class="space-y-8">
            <?php 
            // Ensure $posts is not false before using fetch_assoc()
            if ($posts && $posts->num_rows > 0) {
                while ($post = $posts->fetch_assoc()): 
            ?>
                <article 
                    x-data="{ showComments: false }"
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-2xl"
                >
                    <div class="p-6">
                        <!-- Post Header -->
                        <header class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <img 
                                    src="<?php echo htmlspecialchars($post['profile_picture'] ? 'uploads/profile_pictures/' . $post['profile_picture'] : 'images/default-profile.png'); ?>" 
                                    alt="Profile" 
                                    class="w-12 h-12 rounded-full object-cover border-2 border-primary/20"
                                >
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-200">
                                        <?php echo htmlspecialchars($post['author_name']); ?>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <?php echo getRelativeTime($post['created_at']); ?>
                                        <?php if ($post['subject_name']): ?>
                                            • <?php echo htmlspecialchars($post['subject_name']); ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </header>

                        <!-- Post Content -->
                        <div class="prose dark:prose-invert max-w-none mb-4">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>

                        <!-- Post Actions -->
                        <footer class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center space-x-6">
                                <button 
                                    onclick="toggleLike(<?php echo $post['id']; ?>)"
                                    class="flex items-center text-gray-500 dark:text-gray-400 hover:text-primary transition-colors group"
                                >
                                    <i 
                                        id="like-icon-<?php echo $post['id']; ?>"
                                        class="fas fa-heart mr-2 text-xl <?php echo hasUserLikedPost($conn, $post['id'], $user_id) ? 'text-primary' : 'group-hover:text-primary/50'; ?>"
                                    ></i>
                                    <span class="text-sm" id="like-count-<?php echo $post['id']; ?>">
                                        <?php echo $post['like_count'] ?? 0; ?>
                                    </span>
                                </button>

                                <button 
                                    @click="showComments = !showComments"
                                    class="flex items-center text-gray-500 dark:text-gray-400 hover:text-primary transition-colors group"
                                >
                                    <i class="fas fa-comment mr-2 text-xl group-hover:text-primary/50"></i>
                                    <span class="text-sm">
                                        <?php 
                                        // Fetch comment count if not already in the query
                                        $comment_count = getCommentCount($conn, $post['id']);
                                        echo $comment_count; 
                                        ?>
                                    </span>
                                </button>
                            </div>
                        </footer>

                        <!-- Comments Section -->
                        <section 
                            x-show="showComments" 
                            x-transition 
                            class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700"
                        >
                            <!-- Comment Input -->
                            <form 
                                method="POST" 
                                class="mb-4 flex items-center space-x-4"
                                x-show="showComments"
                            >
                                <input type="hidden" name="action" value="add_comment">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <img 
                                    src="<?php echo htmlspecialchars($display_picture); ?>" 
                                    alt="Your Profile" 
                                    class="w-10 h-10 rounded-full object-cover"
                                >
                                <div class="flex-grow">
                                    <textarea 
                                        name="comment" 
                                        placeholder="Write a comment..." 
                                        class="w-full p-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 dark:bg-gray-700 dark:text-gray-200 resize-none"
                                        rows="2"
                                        required
                                    ></textarea>
                                </div>
                                <button 
                                    type="submit" 
                                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors"
                                >
                                    Send
                                </button>
                            </form>

                            <!-- Comments List -->
                            <div class="space-y-4">
                                <?php
                                $comments = getComments($conn, $post['id']);
                                while ($comment = $comments->fetch_assoc()):
                                ?>
                                    <div class="flex items-start space-x-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                        <img 
                                            src="<?php echo htmlspecialchars($comment['profile_picture'] ? 'uploads/profile_pictures/' . $comment['profile_picture'] : 'images/default-profile.png'); ?>" 
                                            alt="Profile" 
                                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700"
                                        >
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-200 text-sm">
                                                        <?php echo htmlspecialchars($comment['author_name']); ?>
                                                    </h4>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        <?php echo getRelativeTime($comment['created_at']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-gray-800 dark:text-gray-300 text-sm">
                                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </section>
                    </div>
                </article>
            <?php 
                endwhile; 
            } else {
                // Display a message when no posts are available
            ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 text-center">
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">
                        No posts yet
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        Be the first to create a post and start the conversation!
                    </p>
                </div>
            <?php } ?>
        </section>
    </main>

    <script>
    function toggleLike(postId) {
        const likeIcon = document.getElementById('like-icon-' + postId);
        const likeCountSpan = document.getElementById('like-count-' + postId);

        fetch('ajax_like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'post_id=' + postId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                likeIcon.classList.toggle('text-primary');
                likeCountSpan.textContent = data.like_count;
            } else {
                console.error('Like toggle failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    </script>
</body>
</html>
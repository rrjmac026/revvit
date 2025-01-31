<?php
function createPost($conn, $user_id, $content, $subject_id = null) {
    // Ensure the table exists
    if (!createCommunityPostsTable($conn)) {
        error_log("createPost: Failed to create community_posts table");
        return false;
    }

    try {
        // Validate inputs
        if (!$user_id) {
            error_log("createPost: Invalid user_id: " . var_export($user_id, true));
            return false;
        }

        if (empty($content)) {
            error_log("createPost: Empty content");
            return false;
        }

        // Prepare the SQL statement
        $query = "INSERT INTO community_posts (user_id, content, subject_id, created_at) VALUES (?, ?, ?, NOW())";
        
        // Prepare and bind parameters
        $stmt = $conn->prepare($query);
        
        // Check if prepare was successful
        if (!$stmt) {
            error_log("createPost: Failed to prepare statement. MySQL Error: " . $conn->error);
            error_log("Query: " . $query);
            return false;
        }
        
        // Bind parameters
        $stmt->bind_param("iss", $user_id, $content, $subject_id);
        
        // Execute the statement
        $result = $stmt->execute();
        
        // Check if the post was created successfully
        if ($result) {
            $post_id = $stmt->insert_id;
            error_log("createPost: Successfully created post. Post ID: $post_id");
            return true;
        } else {
            error_log("createPost: Failed to execute. User ID: $user_id, Content: $content, Subject ID: " . ($subject_id ?? 'NULL') . ", MySQL Error: " . $stmt->error);
            return false;
        }
    } catch (Exception $e) {
        error_log("createPost: Caught exception: " . $e->getMessage());
        return false;
    }
}

function getPosts($conn, $subject_id = null, $limit = 20, $offset = 0) {
    try {
        // Base query to fetch posts with user details, like count, and comment count
        $query = "
            SELECT 
                cp.id, 
                cp.content, 
                cp.created_at, 
                u.name AS author_name, 
                u.profile_picture,
                s.name AS subject_name,
                (SELECT COUNT(*) FROM community_post_likes cpl WHERE cpl.post_id = cp.id) AS like_count,
                (SELECT COUNT(*) FROM community_comments cc WHERE cc.post_id = cp.id) AS comment_count
            FROM 
                community_posts cp
            JOIN 
                users u ON cp.user_id = u.id
            LEFT JOIN 
                subjects s ON cp.subject_id = s.id
        ";
        
        // Add subject filter if provided
        $params = [];
        $types = "";
        
        if ($subject_id !== null) {
            $query .= " WHERE cp.subject_id = ?";
            $params[] = $subject_id;
            $types = "i";
        }
        
        // Order by most recent first and add limit/offset
        $query .= " ORDER BY cp.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        // Prepare statement
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            error_log("getPosts: Failed to prepare statement. MySQL Error: " . $conn->error);
            error_log("Query: " . $query);
            return $conn->query("SELECT 1 WHERE 1=0"); // Return an empty result set
        }
        
        // Dynamically bind parameters
        if (!empty($params)) {
            $bindParams = [&$stmt, &$types];
            for ($i = 0; $i < count($params); $i++) {
                $bindParams[] = &$params[$i];
            }
            call_user_func_array('mysqli_stmt_bind_param', $bindParams);
        }
        
        // Execute statement
        if (!$stmt->execute()) {
            error_log("getPosts: Failed to execute statement. MySQL Error: " . $stmt->error);
            return $conn->query("SELECT 1 WHERE 1=0"); // Return an empty result set
        }
        
        // Get results
        $result = $stmt->get_result();
        
        // If no results, return an empty result set
        if (!$result) {
            error_log("getPosts: No results found.");
            return $conn->query("SELECT 1 WHERE 1=0");
        }
        
        return $result;
    } catch (Exception $e) {
        error_log("Error fetching posts: " . $e->getMessage());
        return $conn->query("SELECT 1 WHERE 1=0"); // Return an empty result set
    }
}

function getSubjects($conn) {
    try {
        // Prepare the SQL statement to fetch all subjects
        $query = "SELECT id, name FROM subjects ORDER BY name ASC";
        
        // Execute the query
        $result = $conn->query($query);
        
        // Check if query was successful
        if ($result) {
            return $result;
        } else {
            error_log("Failed to fetch subjects: " . $conn->error);
            return false;
        }
    } catch (Exception $e) {
        error_log("Error fetching subjects: " . $e->getMessage());
        return false;
    }
}

function getRelativeTime($timestamp) {
    $current_time = time();
    $post_time = strtotime($timestamp);
    $time_diff = $current_time - $post_time;

    if ($time_diff < 60) {
        return 'Just now';
    } elseif ($time_diff < 3600) {
        $minutes = floor($time_diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < 86400) {
        $hours = floor($time_diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($time_diff < 604800) {
        $days = floor($time_diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M d, Y', $post_time);
    }
}

function getUserPosts($conn, $user_id, $limit = 20, $offset = 0) {
    try {
        // Base query to fetch user's posts with details
        $query = "
            SELECT 
                cp.id, 
                cp.content, 
                cp.created_at, 
                u.name AS author_name, 
                u.profile_picture,
                s.name AS subject_name
            FROM 
                community_posts cp
            JOIN 
                users u ON cp.user_id = u.id
            LEFT JOIN 
                subjects s ON cp.subject_id = s.id
            WHERE 
                cp.user_id = ?
            ORDER BY 
                cp.created_at DESC 
            LIMIT ? OFFSET ?
        ";
        
        // Prepare statement
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $user_id, $limit, $offset);
        
        // Execute and return results
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $e) {
        error_log("Error fetching user posts: " . $e->getMessage());
        return false;
    }
}

function addComment($conn, $post_id, $user_id, $content) {
    try {
        // Validate inputs
        if (!$post_id || !$user_id || empty(trim($content))) {
            error_log("addComment: Invalid input. Post ID: $post_id, User ID: $user_id, Content: $content");
            return false;
        }

        // Verify post exists
        $post_check_query = "SELECT id FROM community_posts WHERE id = ?";
        $post_check_stmt = $conn->prepare($post_check_query);
        $post_check_stmt->bind_param("i", $post_id);
        $post_check_stmt->execute();
        $post_result = $post_check_stmt->get_result();
        
        if ($post_result->num_rows === 0) {
            error_log("addComment: Post not found. Post ID: $post_id");
            return false;
        }

        // Verify user exists
        $user_check_query = "SELECT id FROM users WHERE id = ?";
        $user_check_stmt = $conn->prepare($user_check_query);
        $user_check_stmt->bind_param("i", $user_id);
        $user_check_stmt->execute();
        $user_result = $user_check_stmt->get_result();
        
        if ($user_result->num_rows === 0) {
            error_log("addComment: User not found. User ID: $user_id");
            return false;
        }

        // Prepare the SQL statement
        $query = "INSERT INTO community_comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            error_log("addComment: Failed to prepare statement. MySQL Error: " . $conn->error);
            return false;
        }
        
        // Bind parameters
        $stmt->bind_param("iis", $post_id, $user_id, $content);
        
        // Execute the statement
        $result = $stmt->execute();
        
        // Check if the comment was created successfully
        if ($result) {
            $comment_id = $stmt->insert_id;
            error_log("addComment: Successfully created comment. Comment ID: $comment_id, Post ID: $post_id, User ID: $user_id");
            return $comment_id;
        } else {
            error_log("addComment: Failed to execute. Post ID: $post_id, User ID: $user_id, Content: $content, MySQL Error: " . $stmt->error);
            return false;
        }
    } catch (Exception $e) {
        error_log("addComment: Caught exception: " . $e->getMessage());
        return false;
    }
}

function hasUserLikedPost($conn, $post_id, $user_id) {
    try {
        $query = "SELECT COUNT(*) as liked FROM community_post_likes WHERE post_id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['liked'] > 0;
    } catch (Exception $e) {
        error_log("Error checking post like: " . $e->getMessage());
        return false;
    }
}

function getLikeCount($conn, $post_id) {
    try {
        $query = "SELECT COUNT(*) as like_count FROM community_post_likes WHERE post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['like_count'];
    } catch (Exception $e) {
        error_log("Error getting like count: " . $e->getMessage());
        return 0;
    }
}

function toggleLike($conn, $post_id, $user_id) {
    try {
        // Check if the user has already liked the post
        $check_query = "SELECT * FROM community_post_likes WHERE post_id = ? AND user_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $post_id, $user_id);
        $check_stmt->execute();
        $existing_like = $check_stmt->get_result()->fetch_assoc();

        if ($existing_like) {
            // Unlike: Remove the like
            $delete_query = "DELETE FROM community_post_likes WHERE post_id = ? AND user_id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("ii", $post_id, $user_id);
            $delete_stmt->execute();
            return false; // Unliked
        } else {
            // Like: Add the like
            $insert_query = "INSERT INTO community_post_likes (post_id, user_id, created_at) VALUES (?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ii", $post_id, $user_id);
            $insert_stmt->execute();
            return true; // Liked
        }
    } catch (Exception $e) {
        error_log("Error toggling like: " . $e->getMessage());
        return false;
    }
}

function createCommunityPostsTable($conn) {
    $queries = [
        "CREATE TABLE IF NOT EXISTS community_posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            content TEXT NOT NULL,
            subject_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL
        )",
        "CREATE TABLE IF NOT EXISTS community_comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES community_posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS community_post_likes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_like (post_id, user_id),
            FOREIGN KEY (post_id) REFERENCES community_posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"
    ];

    try {
        foreach ($queries as $query) {
            if (!$conn->query($query)) {
                error_log("Failed to create table: " . $conn->error);
                return false;
            }
        }
        return true;
    } catch (Exception $e) {
        error_log("Error creating community tables: " . $e->getMessage());
        return false;
    }
}

function getComments($conn, $post_id) {
    try {
        // Query to fetch comments with user details
        $query = "
            SELECT 
                cc.id, 
                cc.content, 
                cc.created_at, 
                u.name AS author_name, 
                u.profile_picture
            FROM 
                community_comments cc
            JOIN 
                users u ON cc.user_id = u.id
            WHERE 
                cc.post_id = ?
            ORDER BY 
                cc.created_at ASC
        ";
        
        // Prepare statement
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $post_id);
        
        // Execute and return results
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $e) {
        error_log("Error fetching comments: " . $e->getMessage());
        return false;
    }
}

function createPostNew($conn, $user_id, $content, $subject_id = null) {
    // Ensure the table exists
    if (!createCommunityPostsTable($conn)) {
        error_log("createPostNew: Could not create community_posts table");
        return false;
    }

    try {
        // Validate inputs with more detailed logging
        if (!$user_id) {
            error_log("createPostNew: Invalid user_id: " . var_export($user_id, true));
            return false;
        }

        if (empty($content)) {
            error_log("createPostNew: Empty content");
            return false;
        }

        // Validate user exists
        $user_check_query = "SELECT id FROM users WHERE id = ?";
        $user_check_stmt = $conn->prepare($user_check_query);
        $user_check_stmt->bind_param("i", $user_id);
        $user_check_stmt->execute();
        $user_result = $user_check_stmt->get_result();
        
        if ($user_result->num_rows === 0) {
            error_log("createPostNew: User not found. User ID: $user_id");
            return false;
        }

        // Validate subject if provided
        if ($subject_id !== null) {
            $subject_check_query = "SELECT id FROM subjects WHERE id = ?";
            $subject_check_stmt = $conn->prepare($subject_check_query);
            $subject_check_stmt->bind_param("i", $subject_id);
            $subject_check_stmt->execute();
            $subject_result = $subject_check_stmt->get_result();
            
            if ($subject_result->num_rows === 0) {
                error_log("createPostNew: Subject not found. Subject ID: $subject_id");
                $subject_id = null; // Set to null if subject doesn't exist
            }
        }

        // Prepare the SQL statement
        $query = "INSERT INTO community_posts (user_id, content, subject_id, created_at) VALUES (?, ?, ?, NOW())";
        
        // Prepare and bind parameters
        $stmt = $conn->prepare($query);
        
        // Check if prepare was successful
        if (!$stmt) {
            error_log("createPostNew: Failed to prepare statement. MySQL Error: " . $conn->error);
            error_log("Query: " . $query);
            return false;
        }
        
        // Bind parameters
        $stmt->bind_param("iss", $user_id, $content, $subject_id);
        
        // Execute the statement
        $result = $stmt->execute();
        
        // Check if the post was created successfully
        if ($result) {
            $post_id = $stmt->insert_id;
            error_log("createPostNew: Successfully created post. Post ID: $post_id, User ID: $user_id, Subject ID: " . ($subject_id ?? 'NULL'));
            return true;
        } else {
            error_log("createPostNew: Failed to execute. User ID: $user_id, Content: $content, Subject ID: " . ($subject_id ?? 'NULL') . ", MySQL Error: " . $stmt->error);
            return false;
        }
    } catch (Exception $e) {
        error_log("createPostNew: Caught exception: " . $e->getMessage());
        return false;
    }
}

function getCommentCount($conn, $post_id) {
    try {
        $query = "SELECT COUNT(*) as comment_count FROM community_comments WHERE post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['comment_count'];
    } catch (Exception $e) {
        error_log("Error getting comment count: " . $e->getMessage());
        return 0;
    }
}

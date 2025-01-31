<?php
require_once 'includes/auth_check.php';

// Use the authentication check function
checkAuthentication(true, true);

require_once 'db.php';

// Ensure user is logged in with a valid user ID
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user'];
$current_email = $_SESSION['email'] ?? ''; // Use email from session if available
$password_error = '';
$password_success = '';
$profile_picture_error = '';
$profile_picture_success = '';
$name_error = '';
$name_success = '';

// Fetch current user data from database
$stmt = $conn->prepare("SELECT email, profile_picture, name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $current_email = $row['email'];
    $current_profile_picture = $row['profile_picture'];
    $current_name = $row['name'] ?? '';
}
$stmt->close();

// Process password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = trim($_POST['old_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validate password inputs
    if (empty($old_password)) {
        $password_error = "Current password is required.";
    } elseif (empty($new_password)) {
        $password_error = "New password is required.";
    } elseif (strlen($new_password) < 6) {
        $password_error = "New password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "New passwords do not match.";
    } else {
        // Verify current password
        $check_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if (password_verify($old_password, $row['password'])) {
                // Hash new password
                $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                
                // Update password in database
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);
                
                if ($update_stmt->execute()) {
                    $password_success = "Password updated successfully!";
                } else {
                    $password_error = "Failed to update password. Please try again.";
                }
                $update_stmt->close();
            } else {
                $password_error = "Current password is incorrect.";
            }
        } else {
            $password_error = "User not found.";
        }
        $check_stmt->close();
    }
}

// Profile Picture Upload Handler
if (isset($_POST['update_profile_picture'])) {
    // Ensure uploads directory exists
    $upload_dir = 'uploads/profile_pictures/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Comprehensive file upload error handling
    if (!isset($_FILES['profile_picture'])) {
        $profile_picture_error = "No file was uploaded.";
        error_log("Profile Picture Upload Error: No file uploaded");
    } elseif ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
        ];
        
        $error_message = $upload_errors[$_FILES['profile_picture']['error']] ?? 'Unknown upload error';
        $profile_picture_error = "File upload failed: $error_message";
        error_log("Profile Picture Upload Error: $error_message");
    } else {
        // File was uploaded successfully, now validate it
        $file = $_FILES['profile_picture'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_file_size = 5 * 1024 * 1024; // 5MB

        // Check file type
        if (!in_array($file['type'], $allowed_types)) {
            $profile_picture_error = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
            error_log("Profile Picture Upload Error: Invalid file type - " . $file['type']);
        }
        // Check file size
        elseif ($file['size'] > $max_file_size) {
            $profile_picture_error = "File size exceeds 5MB limit.";
            error_log("Profile Picture Upload Error: File size too large - " . $file['size'] . " bytes");
        }
        else {
            // Generate unique filename
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = $user_id . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            // Attempt to move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Update user's profile picture in database
                $update_pic_query = "UPDATE users SET profile_picture = ? WHERE id = ?";
                $stmt = $conn->prepare($update_pic_query);
                $stmt->bind_param("si", $new_filename, $user_id);
                
                if ($stmt->execute()) {
                    $profile_picture_success = "Profile picture updated successfully!";
                    error_log("Profile Picture Upload Success: $new_filename");
                } else {
                    $profile_picture_error = "Failed to update profile picture in database.";
                    error_log("Profile Picture Database Update Error: " . $stmt->error);
                }
                $stmt->close();
            } else {
                $profile_picture_error = "Failed to move uploaded file.";
                error_log("Profile Picture Upload Error: Failed to move uploaded file to $upload_path");
            }
        }
    }

    // Redirect back to update-profile with profile-picture tab
    header("Location: update-profile.php?tab=profile-picture");
    exit();
}

// Process name update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_name'])) {
    $new_name = trim($_POST['name'] ?? '');
    
    if (empty($new_name)) {
        $name_error = "Name cannot be empty.";
    } else {
        $update_stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_name, $user_id);
        
        if ($update_stmt->execute()) {
            $name_success = "Name updated successfully!";
            $current_name = $new_name;
            $_SESSION['user'] = $new_name; // Update the session with the new name
        } else {
            $name_error = "Failed to update name. Please try again.";
        }
        $update_stmt->close();
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_email'])) {
    // Validate inputs
    $email = trim($_POST['email'] ?? '');
    $confirm_email = trim($_POST['confirmEmail'] ?? '');

    // Comprehensive validation
    if (empty($email)) {
        $error = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address format.";
    } elseif (empty($confirm_email)) {
        $error = "Please confirm your email address.";
    } elseif ($email !== $confirm_email) {
        $error = "Email addresses do not match.";
    } else {
        // Check if email is already in use by another user
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $email, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "This email is already in use by another account.";
        } else {
            // Update email in database
            $update_stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update_stmt->bind_param("si", $email, $user_id);
            
            if ($update_stmt->execute()) {
                // Update session email
                $_SESSION['email'] = $email;
                $success = "Email updated successfully!";
                $current_email = $email;
            } else {
                $error = "Failed to update email. Please try again.";
            }
            $update_stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | RevvIt</title>
    <link href="dist/output.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex flex-col">
    <header class="bg-primary text-white shadow-lg p-4 fixed w-full top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-white hover:text-white/80 transition-colors">
                <a href="dashboard.php" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 18.636A8.214 8.214 0 0 1 18 16.75c.967 0 1.905.166 2.75.47a.75.75 0 0 0 1-.707V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v14.103Z" />
                    </svg>
                    RevvIt!
                </a>
            </div>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="dashboard.php" class="text-white/70 hover:text-white transition-colors duration-300">Dashboard</a></li>
                    <li><a href="profile.php" class="text-white/70 hover:text-white transition-colors duration-300">Profile</a></li>
                    <li><a href="logout.php" class="text-white/70 hover:text-white transition-colors duration-300">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow pt-20 pb-16 container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Tabs Navigation -->
            <div class="flex border-b">
                <button 
                    type="button" 
                    onclick="switchTab('profile-picture')" 
                    class="px-6 py-3 hover:bg-gray-50 transition-colors border-b-2 <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'profile-picture') ? 'border-primary text-primary' : 'border-transparent'; ?>"
                >
                    Profile Picture
                </button>
                <button 
                    type="button" 
                    onclick="switchTab('name')" 
                    class="px-6 py-3 hover:bg-gray-50 transition-colors border-b-2 <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'name') ? 'border-primary text-primary' : 'border-transparent'; ?>"
                >
                    Name
                </button>
                <button 
                    type="button" 
                    onclick="switchTab('email')" 
                    class="px-6 py-3 hover:bg-gray-50 transition-colors border-b-2 <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'email') ? 'border-primary text-primary' : 'border-transparent'; ?>"
                >
                    Email
                </button>
                <button 
                    type="button" 
                    onclick="switchTab('password')" 
                    class="px-6 py-3 hover:bg-gray-50 transition-colors border-b-2 <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'password') ? 'border-primary text-primary' : 'border-transparent'; ?>"
                >
                    Password
                </button>
            </div>

            <div class="p-8">
                <!-- Profile Picture Tab -->
                <div id="profile-picture-tab" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] !== 'profile-picture') ? 'hidden' : ''; ?>">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Update Profile Picture</h2>
                        <p class="text-gray-600 mb-6">Choose a professional photo that represents you.</p>
                    </div>

                    <form 
                        method="POST" 
                        action="update-profile.php?tab=profile-picture" 
                        enctype="multipart/form-data" 
                        class="space-y-6"
                    >
                        <!-- Add hidden max file size input -->
                        <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
                        
                        <div class="flex flex-col items-center">
                            <?php 
                            $display_picture = !empty($current_profile_picture) 
                                ? 'uploads/profile_pictures/' . $current_profile_picture 
                                : 'images/default-profile.png'; 
                            ?>
                            <div class="relative mb-6">
                                <img 
                                    id="preview-image" 
                                    src="<?php echo htmlspecialchars($display_picture); ?>" 
                                    alt="Profile Picture" 
                                    class="w-64 h-64 rounded-full object-cover shadow-lg border-4 border-primary/20"
                                >
                            </div>
                            
                            <div class="w-full max-w-md">
                                <input 
                                    type="file" 
                                    id="profile_picture" 
                                    name="profile_picture" 
                                    accept="image/jpeg,image/png,image/jpg" 
                                    class="hidden"
                                    onchange="previewImage(event)"
                                >
                                <label 
                                    for="profile_picture" 
                                    class="w-full block text-center bg-primary text-white py-3 rounded-md hover:bg-primary/90 transition-colors cursor-pointer"
                                >
                                    Choose Image
                                </label>
                                <p class="text-sm text-gray-600 mt-2 text-center">
                                    JPG, PNG. Max 5MB. Recommended 1:1 aspect ratio.
                                </p>
                            </div>
                        </div>
                        
                        <?php 
                        // Display error or success messages
                        if (!empty($profile_picture_error)): 
                        ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <?php echo htmlspecialchars($profile_picture_error); ?>
                            </div>
                        <?php 
                        endif; 
                        
                        if (!empty($profile_picture_success)): 
                        ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <?php echo htmlspecialchars($profile_picture_success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <button 
                            type="submit" 
                            name="update_profile_picture" 
                            class="w-full bg-primary text-white py-3 rounded-md hover:bg-primary/90 focus:outline-none transition-colors"
                        >
                            Upload Profile Picture
                        </button>
                    </form>
                </div>

                <!-- Name Tab -->
                <div id="name-tab" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] !== 'name') ? 'hidden' : ''; ?>">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Update Name</h2>
                        <p class="text-gray-600 mb-6">Change your display name.</p>
                    </div>

                    <?php if ($name_error): ?>
                        <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6">
                            <?php echo htmlspecialchars($name_error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($name_success): ?>
                        <div class="bg-green-50 text-green-500 p-4 rounded-lg mb-6">
                            <?php echo htmlspecialchars($name_success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">New Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="<?php echo htmlspecialchars($current_name); ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                required
                            >
                        </div>

                        <button 
                            type="submit" 
                            name="update_name" 
                            class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/90 transition-colors"
                        >
                            Update Name
                        </button>
                    </form>
                </div>

                <!-- Email Update Tab -->
                <div id="email-tab" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] !== 'email') ? 'hidden' : ''; ?>">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Update Email Address</h2>
                        <p class="text-gray-600 mb-6">Change the email associated with your account.</p>
                    </div>

                    <?php if (isset($email_error)): ?>
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?php echo htmlspecialchars($email_error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="update-profile.php?tab=email" class="space-y-6">
                        <div>
                            <label for="current_email" class="block text-sm font-medium text-gray-700 mb-2">Current Email</label>
                            <input 
                                type="email" 
                                id="current_email" 
                                name="current_email" 
                                value="<?php echo htmlspecialchars($current_email); ?>" 
                                disabled 
                                class="w-full p-3 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed"
                            >
                        </div>
                        
                        <div>
                            <label for="new_email" class="block text-sm font-medium text-gray-700 mb-2">New Email Address</label>
                            <input 
                                type="email" 
                                id="new_email" 
                                name="email" 
                                required 
                                placeholder="Enter your new email" 
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                        </div>
                        
                        <div>
                            <label for="confirm_email" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Email Address</label>
                            <input 
                                type="email" 
                                id="confirm_email" 
                                name="confirmEmail" 
                                required 
                                placeholder="Confirm your new email" 
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            name="update_email" 
                            class="w-full bg-primary text-white py-3 rounded-md hover:bg-primary/90 focus:outline-none transition-colors"
                        >
                            Update Email
                        </button>
                    </form>
                </div>

                <!-- Password Change Tab -->
                <div id="password-tab" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] !== 'password') ? 'hidden' : ''; ?>">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Change Password</h2>
                        <p class="text-gray-600 mb-6">Create a strong, unique password to protect your account.</p>
                    </div>

                    <form method="POST" action="update-profile.php?tab=password" class="space-y-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="old_password" 
                                required 
                                placeholder="Enter your current password" 
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                        </div>
                        
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                required 
                                placeholder="Enter your new password" 
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                        </div>
                        
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required 
                                placeholder="Confirm your new password" 
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            name="change_password" 
                            class="w-full bg-primary text-white py-3 rounded-md hover:bg-primary/90 focus:outline-none transition-colors"
                        >
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Set active tab based on URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab) {
                document.querySelectorAll('.tab-button').forEach(button => {
                    if (button.getAttribute('data-tab') === tab) {
                        button.click();
                    }
                });
            }
        });

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-image');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function switchTab(tabName) {
            // Hide all tabs
            document.getElementById('profile-picture-tab').classList.add('hidden');
            document.getElementById('name-tab').classList.add('hidden');
            document.getElementById('email-tab').classList.add('hidden');
            document.getElementById('password-tab').classList.add('hidden');
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Update URL
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('tab', tabName);
            window.history.pushState({}, '', newUrl);
            
            // Update active tab styling
            const tabs = document.querySelectorAll('.border-b-2');
            tabs.forEach(tab => {
                if (tab.getAttribute('onclick').includes(tabName)) {
                    tab.classList.add('border-primary', 'text-primary');
                } else {
                    tab.classList.remove('border-primary', 'text-primary');
                    tab.classList.add('border-transparent');
                }
            });
        }
    </script>

    <footer class="bg-primary text-white py-8 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2">&copy; <?php echo date("Y"); ?> RevvIt!. All rights reserved.</p>
            <p>Contact Us: <a href="mailto:support@revvit.com" class="underline hover:text-white/80">support@revvit.com</a></p>
        </div>
    </footer>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>

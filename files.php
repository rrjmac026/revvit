<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT email, name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $username = $row['name'];
    $profile_picture = $row['profile_picture'];
}
$stmt->close();

function getFilesForUser($user_id) {
    global $conn;
    $sql = "SELECT id, filename, uploaded_at, file_size FROM files WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $files = array();
    while ($row = $result->fetch_assoc()) {
        $files[] = $row;
    }
    return $files;
}

function getFileStats($files) {
    $stats = [
        'total_files' => count($files),
        'total_size' => 0,
        'last_upload' => null
    ];

    foreach ($files as $file) {
        $stats['total_size'] += isset($file['file_size']) ? $file['file_size'] : 0;
        $upload_time = strtotime($file['uploaded_at']);
        if ($stats['last_upload'] === null || $upload_time > strtotime($stats['last_upload'])) {
            $stats['last_upload'] = $file['uploaded_at'];
        }
    }

    return $stats;
}

function formatFileSize($bytes) {
    if ($bytes === 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = floor(log($bytes, 1024));
    return number_format($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}

// Handle file deletion
if (isset($_POST['delete_file'])) {
    $file_id = isset($_POST['file_id']) ? intval($_POST['file_id']) : null;
    
    if ($file_id) {
        // First get the filename
        $sql = "SELECT filename FROM files WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $file_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($file = $result->fetch_assoc()) {
            $filepath = 'uploads/' . $file['filename'];
            
            // Delete from database
            $sql = "DELETE FROM files WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $file_id, $user_id);
            
            if ($stmt->execute()) {
                // If database deletion successful, delete the physical file
                if (file_exists($filepath) && unlink($filepath)) {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'File deleted successfully'];
                } else {
                    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'File deleted from database but could not delete physical file'];
                }
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error deleting file'];
            }
        }
        header('Location: files.php');
        exit();
    }
}

// Handle bulk file deletion
if (isset($_POST['delete_selected_files'])) {
    $selected_files = isset($_POST['selected_files']) ? $_POST['selected_files'] : [];
    
    if (!empty($selected_files)) {
        $success_count = 0;
        $db_success = 0;
        $file_success = 0;
        
        // First get all filenames
        $ids = array_map('intval', $selected_files);
        $ids_str = implode(',', $ids);
        
        $sql = "SELECT id, filename FROM files WHERE id IN ($ids_str) AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $files_to_delete = [];
        while ($file = $result->fetch_assoc()) {
            $files_to_delete[] = $file;
        }
        
        // Delete from database
        $sql = "DELETE FROM files WHERE id IN ($ids_str) AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        
        if ($stmt->execute()) {
            $db_success = 1;
            
            // Delete physical files
            foreach ($files_to_delete as $file) {
                $filepath = 'uploads/' . $file['filename'];
                if (file_exists($filepath) && unlink($filepath)) {
                    $file_success++;
                }
            }
            
            if ($file_success == count($files_to_delete)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'All selected files deleted successfully'];
            } else {
                $_SESSION['alert'] = ['type' => 'warning', 
                    'message' => "Deleted from database but only $file_success out of " . count($files_to_delete) . " physical files were removed"];
            }
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error deleting selected files'];
        }
        header('Location: files.php');
        exit();
    }
}

$files = getFilesForUser($user_id);
$stats = getFileStats($files);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Files | RevvIt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <?php require_once 'includes/header.php'; ?>

    <main class="flex-grow pt-20 pb-16 container mx-auto px-4">
        <div class="max-w-4xl mx-auto grid md:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="md:col-span-1 bg-white rounded-xl shadow-lg p-6 h-fit">
                <div class="flex flex-col items-center">
                    <?php 
                    $default_profile_picture = 'images/default-profile.png';
                    $display_picture = !empty($profile_picture) && file_exists('uploads/profile_pictures/' . $profile_picture) 
                        ? 'uploads/profile_pictures/' . $profile_picture 
                        : $default_profile_picture;
                    ?>
                    <div class="relative mb-4 w-48 h-48 mx-auto">
                        <img 
                            src="<?php echo htmlspecialchars($display_picture); ?>" 
                            alt="Profile Picture" 
                            class="w-full h-full rounded-full object-cover shadow-lg border-4 border-primary/20"
                        >
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($username); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($email); ?></p>
                    
                    <!-- Stats Section -->
                    <div class="w-full border-t pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">File Stats</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Files:</span>
                                <span class="font-medium text-gray-800"><?php echo $stats['total_files']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Size:</span>
                                <span class="font-medium text-gray-800"><?php echo formatFileSize($stats['total_size']); ?></span>
                            </div>
                            <?php if ($stats['last_upload']): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Last Upload:</span>
                                <span class="font-medium text-gray-800"><?php echo date('M j, Y', strtotime($stats['last_upload'])); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="w-full space-y-3 mt-4">
                        <a href="upload.php" class="w-full block text-center bg-primary/10 text-primary py-2 rounded-md hover:bg-primary/20 transition-colors">
                            <i class="fas fa-upload mr-2"></i> Upload Files
                        </a>
                        <a href="profile.php" class="w-full block text-center bg-primary/10 text-primary py-2 rounded-md hover:bg-primary/20 transition-colors">
                            <i class="fas fa-user mr-2"></i> Back to Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Files Section -->
            <div class="md:col-span-2 space-y-6">
                <!-- Files Management -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">My Files</h3>

                    <?php if (isset($_SESSION['alert'])): ?>
                        <div class="mb-4 p-4 rounded-md <?php echo $_SESSION['alert']['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                            <?php 
                            echo $_SESSION['alert']['message'];
                            unset($_SESSION['alert']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($files)): ?>
                        <form method="post" id="bulkDeleteForm" class="space-y-4">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                                    <label for="selectAll" class="text-sm text-gray-600">Select All</label>
                                </div>
                                <button type="submit" name="delete_selected_files" id="deleteSelectedBtn" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition-all duration-200 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed" 
                                    onclick="return confirm('Are you sure you want to delete all selected files?')" disabled>
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    <span>Delete</span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <?php foreach ($files as $file): ?>
                                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                        <div class="flex items-center space-x-4">
                                            <input type="checkbox" name="selected_files[]" value="<?php echo $file['id']; ?>" class="file-checkbox rounded border-gray-300">
                                            <div>
                                                <a href="uploads/<?php echo $file['filename']; ?>" target="_blank" class="text-primary hover:underline font-medium">
                                                    <?php echo htmlspecialchars($file['filename']); ?>
                                                </a>
                                                <p class="text-xs text-gray-500">
                                                    Uploaded: <?php echo date('F j, Y g:i A', strtotime($file['uploaded_at'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="deleteFile(<?php echo $file['id']; ?>)" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </form>

                        <!-- Hidden form for individual deletes -->
                        <form id="deleteFileForm" method="post" style="display: none;">
                            <input type="hidden" name="file_id" id="deleteFileId">
                            <input type="hidden" name="delete_file" value="1">
                        </form>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">No files uploaded yet.</p>
                            <a href="upload.php" class="inline-block bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-md transition-colors">
                                <i class="fas fa-upload mr-2"></i> Upload Your First File
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>

<script>
    // Function to handle individual file deletion
    function deleteFile(fileId) {
        if (confirm('Are you sure you want to delete this file?')) {
            document.getElementById('deleteFileId').value = fileId;
            document.getElementById('deleteFileForm').submit();
        }
    }

    // Select All functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.getElementsByClassName('file-checkbox');
        for (let checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
        updateDeleteButton();
    });

    // Update Select All when individual checkboxes change
    const fileCheckboxes = document.getElementsByClassName('file-checkbox');
    for (let checkbox of fileCheckboxes) {
        checkbox.addEventListener('change', function() {
            const selectAll = document.getElementById('selectAll');
            const allCheckboxes = document.getElementsByClassName('file-checkbox');
            let allChecked = true;
            let anyChecked = false;
            
            for (let cb of allCheckboxes) {
                if (!cb.checked) {
                    allChecked = false;
                } else {
                    anyChecked = true;
                }
            }
            
            selectAll.checked = allChecked;
            updateDeleteButton();
        });
    }

    // Update delete button state
    function updateDeleteButton() {
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        const checkboxes = document.getElementsByClassName('file-checkbox');
        let anyChecked = false;

        for (let cb of checkboxes) {
            if (cb.checked) {
                anyChecked = true;
                break;
            }
        }

        if (anyChecked) {
            deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            deleteBtn.classList.add('hover:shadow-lg');
            deleteBtn.disabled = false;
        } else {
            deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            deleteBtn.classList.remove('hover:shadow-lg');
            deleteBtn.disabled = true;
        }
    }

    // Initialize button state
    updateDeleteButton();
</script>
</html>

<?php
$conn->close();
?>
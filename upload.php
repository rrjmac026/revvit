<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    $files = $_FILES['files'];
    $upload_dir = 'uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $success_count = 0;
    $error_count = 0;
    
    // Handle multiple file uploads
    for ($i = 0; $i < count($files['name']); $i++) {
        $file_name = $files['name'][$i];
        $file_tmp = $files['tmp_name'][$i];
        $file_error = $files['error'][$i];
        $file_size = $files['size'][$i];
        
        if ($file_error === UPLOAD_ERR_OK) {
            // Generate unique filename
            $unique_filename = uniqid() . '_' . $file_name;
            $upload_path = $upload_dir . $unique_filename;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Insert file record into database
                $sql = "INSERT INTO files (user_id, filename, uploaded_at, file_size) VALUES (?, ?, NOW(), ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'isi', $user_id, $unique_filename, $file_size);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success_count++;
                } else {
                    $error_count++;
                    // Remove file if database insert fails
                    unlink($upload_path);
                }
            } else {
                $error_count++;
            }
        } else {
            $error_count++;
        }
    }
    
    if ($success_count > 0) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => "$success_count file(s) uploaded successfully" . ($error_count > 0 ? ", $error_count file(s) failed" : '')
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => "Failed to upload files"
        ];
    }
    
    header('Location: files.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files - Revvit</title>
    <link href="dist/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <?php require_once 'includes/header.php'; ?>

    <main class="flex-grow pt-20 pb-16 container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Upload Files</h1>
                    <a href="files.php" class="text-primary hover:underline">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Files
                    </a>
                </div>

                <form action="upload.php" method="post" enctype="multipart/form-data" class="space-y-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 mb-2">Drag and drop your files here, or</p>
                            <label class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-md cursor-pointer transition-colors inline-block">
                                <span>Browse Files</span>
                                <input type="file" name="files[]" multiple class="hidden" onchange="updateFileList(this)">
                            </label>
                        </div>
                        <div id="fileList" class="mt-4 space-y-2"></div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-md transition-colors">
                            Upload Files
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
    function updateFileList(input) {
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';
        
        for (const file of input.files) {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center text-sm text-gray-600';
            fileItem.innerHTML = `
                <i class="fas fa-file mr-2"></i>
                <span>${file.name}</span>
                <span class="ml-2">(${formatFileSize(file.size)})</span>
            `;
            fileList.appendChild(fileItem);
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    </script>
</body>
</html>

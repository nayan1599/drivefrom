
<?php

function uploadFile(
    string $field,
    string $folder = 'general',
    ?string $oldFile = null,
    string $prefix = 'file'
): ?string {

    // Upload directory and URL
    $baseDir = dirname(__DIR__) . '/uploads/';
    $uploadDir = $baseDir . $folder . '/';
    $uploadUrl = 'uploads/' . $folder . '/';

    // No new file selected
    if (
        !isset($_FILES[$field]) ||
        $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE
    ) {
        return $oldFile;
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('File upload failed.');
    }

    // Maximum 5 MB
    if ($file['size'] <= 0 || $file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('File size must be under 5 MB.');
    }

    // Allowed file types
    $allowed = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp',
        'pdf'  => 'application/pdf',
    ];

    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    if (!isset($allowed[$extension])) {
        throw new RuntimeException('Only JPG, PNG, WEBP and PDF files are allowed.');
    }

    // Verify actual MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if ($mime !== $allowed[$extension]) {
        throw new RuntimeException('Invalid file content.');
    }

    // Create folder if it does not exist
    if (
        !is_dir($uploadDir) &&
        !mkdir($uploadDir, 0755, true) &&
        !is_dir($uploadDir)
    ) {
        throw new RuntimeException('Could not create upload folder.');
    }

    // Generate unique filename
    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $prefix)
        . '_' . date('YmdHis')
        . '_' . bin2hex(random_bytes(8))
        . '.' . $extension;

    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Could not save uploaded file.');
    }

    @chmod($destination, 0644);

    // Delete old file only after successful upload
    if (!empty($oldFile)) {
        deleteUploadedFile($oldFile, $folder);
    }

    return $uploadUrl . $filename;
}


/**
 * Delete a file only from its expected upload folder.
 */
function deleteUploadedFile(
    string $filePath,
    string $folder = 'general'
): bool {

    $baseDir = realpath(dirname(__DIR__) . '/uploads/' . $folder);

    if ($baseDir === false) {
        return false;
    }

    $filename = basename(str_replace('\\', '/', $filePath));
    $fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . $filename);

    if (
        $fullPath === false ||
        !is_file($fullPath) ||
        dirname($fullPath) !== $baseDir
    ) {
        return false;
    }

    return unlink($fullPath);
}

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Handle document deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare("SELECT filename FROM documents WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $delete_id, 'user_id' => $_SESSION['user_id']]);
    $document = $stmt->fetch();

    if ($document && isset($document['filename'])) {
        $filepath = 'uploads/' . $document['filename'];

        if (file_exists($filepath) && unlink($filepath)) {
            $stmt = $pdo->prepare("DELETE FROM documents WHERE id = :id");
            $stmt->execute(['id' => $delete_id]);
            $message = "Document deleted successfully.";
        } else {
            $message = "Error: Could not delete the file.";
        }
    } else {
        $message = "Error: Document not found in the database.";
    }
}

// Handle document upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document']) && !isset($_POST['delete_id'])) {
    $file = $_FILES['document'];
    $filename = $file['name'];
    $filepath = 'uploads/' . basename($filename);

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $stmt = $pdo->prepare("INSERT INTO documents (user_id, filename) VALUES (:user_id, :filename)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'filename' => $filename
        ]);
        $message = "Document uploaded successfully.";
    } else {
        $message = "Error: Could not upload the document.";
    }
}

// Retrieve all uploaded documents
$stmt = $pdo->prepare("SELECT documents.*, users.username FROM documents INNER JOIN users ON documents.user_id = users.user_id");
$stmt->execute();
$documents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Upload a Document</h1>
        
        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="document" required>
            <button type="submit">Upload Document</button>
        </form>

        <h2>Uploaded Documents</h2>
        <?php if (count($documents) > 0): ?>
            <ul class="document-list">
                <?php foreach ($documents as $doc): ?>
                    <li>
                        <a href="uploads/<?php echo htmlspecialchars($doc['filename']); ?>" target="_blank">
                            <?php echo htmlspecialchars($doc['filename']); ?>
                        </a> - Uploaded by: <?php echo htmlspecialchars($doc['username']); ?>
                        - Date: <?php echo $doc['upload_date']; ?>
                        
                        <?php if ($doc['user_id'] == $_SESSION['user_id']): ?>
                            <form action="upload.php" method="POST" class="delete-form">
                                <input type="hidden" name="delete_id" value="<?php echo $doc['id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No documents uploaded yet.</p>
        <?php endif; ?>

        <a href="main.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>

<?php
// add_document_comment_column.php
require_once 'config/database.php';

try {
    $pdo->exec("ALTER TABLE internship_requests ADD COLUMN document_comment TEXT NULL COMMENT 'ความคิดเห็นตอบกลับเอกสาร'");
    echo "Added document_comment column successfully.";
} catch (PDOException $e) {
    echo "Error or column might already exist: " . $e->getMessage();
}
?>

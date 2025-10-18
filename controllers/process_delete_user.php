<?php
require_once '../includes/db_connect.php';

// Check if 'id' is provided and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Prepare statement with correct table name
    $stmt = $conn->prepare("DELETE FROM majoy WHERE id = ?");
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>
                alert('User deleted successfully.');
                window.location.href = '../pages/user_list.php';
            </script>";
        } else {
            echo "<script>
                alert('No user found with this ID.');
                window.location.href = '../pages/user_list.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Error deleting user: " . $stmt->error . "');
            window.location.href = '../pages/user_list.php';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('Invalid user ID.');
        window.location.href = '../pages/user_list.php';
    </script>";
}

$conn->close();
?>

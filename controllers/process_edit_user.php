<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = intval($_POST['id'] ?? 0);
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $course    = trim($_POST['course'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';

    if ($id <= 0 || empty($firstname) || empty($lastname) || empty($email) || empty($course) || empty($username)) {
        echo "<script>
                alert('Please complete all fields.');
                window.location.href='../pages/user_edit.php?id=$id';
              </script>";
        exit;
    }

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE majoy SET firstname=?, lastname=?, email=?, course=?, username=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $course, $username, $hashed, $id);
    } else {
        $sql = "UPDATE majoy SET firstname=?, lastname=?, email=?, course=?, username=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $firstname, $lastname, $email, $course, $username, $id);
    }

    if ($stmt->execute()) {
        echo "<script>
                alert('User updated successfully!');
                window.location.href='../pages/user_list.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating user.');
                window.location.href='../pages/user_edit.php?id=$id';
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href='../pages/user_list.php';
          </script>";
}

$conn->close();
?>

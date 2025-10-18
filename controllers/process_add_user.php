<?php
require_once '../includes/db_connect.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST'){


    $fname=trim($_POST['fname']);
    $lname=trim($_POST['lname']);
    $email=trim($_POST['email']);
    $course=trim($_POST['course']);
    $username=trim($_POST['username']);
    $password=trim($_POST['password']);

 if (empty($fname) || empty($lname) || empty($email) || empty($course) || empty($username) || empty($password)){

    echo "<script>
     alert('All fields are required');
     window.location.href='../pages/add_user.php';
    </script>";
    exit();
 }
 $hashed_password = password_hash($password, PASSWORD_DEFAULT);
 $stmt  = $conn->prepare("INSERT INTO majoy (first_name, last_name, email, course, username, password) VALUES (?, ?, ?, ?, ?, ?)");
 $stmt->bind_param("ssssss", $fname, $lname, $email, $course, $username, $hashed_password);

 if ($stmt->execute()){
    echo "<script>
     alert('User added successfully');
     window.location.href='../pages/user_list.php';
    </script>";
 } else {
    echo "<script>
     alert('Error: Could not execute the query: " . $stmt->error . "');
     window.location.href='../pages/user_list.php';
    </script>";
 }

   $stmt->close();
  $conn->close();
} else {
   header('Location: ../pages/user_list.php');
 exit();

}


<?php
require 'config.php';
if (isset($_POST['register'])) {
    $errMsg = '';
    // Get data from FROM
    $parentname = $_POST['parentname'];
    $mobileno = $_POST['mobileno'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    try {
        $stmtuser = $connect->prepare(
            'SELECT * FROM tbl_users WHERE user_mobile = :mobile'
        );
        $stmtuser->execute([':mobile' => $mobileno]);
        $datauser = $stmtuser->fetch(PDO::FETCH_ASSOC);
        //exit;
        if (empty($datauser)) {
            $stmt = $connect->prepare(
                'INSERT INTO tbl_users (user_name, user_pass, user_mobile, user_email) VALUES (:user_name, :user_pass, :user_mobile, :user_email)'
            );
            $stmt->execute([
                ':user_name' => $parentname,
                ':user_pass' => $password,
                ':user_mobile' => $mobileno,
                ':user_email' => $email,
            ]);
            date_default_timezone_set('Etc/UTC');
            $stmtuser = $connect->prepare(
                'SELECT * FROM tbl_users WHERE user_mobile = :mobile'
            );
            $stmtuser->execute([':mobile' => $mobileno]);
            $datauser = $stmtuser->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_name'] = $datauser['user_name'];
            $_SESSION['user_id'] = $datauser['id'];
            $_SESSION['user_mobile'] = $datauser['user_mobile'];
            $_SESSION['role'] = $datauser['role'];
            $_SESSION['status'] = $datauser['status'];
            echo '<script>window.location.replace("index.php")</script>';
            exit();
        } else {
            $stmt = $connect->prepare(
                'UPDATE tbl_users set user_name = :user_name, user_pass = :user_pass, user_email = :user_email where user_mobile = :user_mobile'
            );
            $stmt->execute([
                ':user_name' => $parentname,
                ':user_pass' => $password,
                ':user_mobile' => $mobileno,
                ':user_email' => $email,
            ]);
            $stmtuser = $connect->prepare(
                'SELECT * FROM tbl_users WHERE user_mobile = :mobile'
            );
            $stmtuser->execute([':mobile' => $mobileno]);
            $datauser = $stmtuser->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_name'] = $datauser['user_name'];
            $_SESSION['user_id'] = $datauser['id'];
            $_SESSION['user_mobile'] = $datauser['user_mobile'];
            $_SESSION['role'] = $datauser['role'];
            $_SESSION['status'] = $datauser['status'];
            echo '<script>window.location.replace("index.php")</script>';
            exit();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'joined') {
    $successMsg = 'Registration successful Now you can <a href="/">login</a>';
}
?>
<?php
require 'config.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
}
if (isset($_POST['login'])) {
    $errMsg = '';
    // Get data from FORM
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    if ($mobile == '') {
        $errMsg = 'Enter mobile no';
    }
    if ($password == '') {
        $errMsg = 'Enter password';
    }
    if ($errMsg == '') {
        try {
            $stmt = $connect->prepare(
                'SELECT * FROM tbl_users WHERE user_mobile = :mobile && user_pass = :pass && otp_verification = :verification'
            );
            $stmt->execute([
                ':mobile' => $mobile,
                ':pass' => $password,
                ':verification' => 1,
            ]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data == false) {
                $errMsg = "User $mobile not found.";
            } else {
                if ($password == $data['user_pass']) {
                    $_SESSION['user_name'] = $data['user_name'];
                    $_SESSION['user_id'] = $data['id'];
                    $_SESSION['user_mobile'] = $data['user_mobile'];
                    $_SESSION['role'] = $data['role'];
                    $_SESSION['status'] = $data['status'];
                    header('Location: index.php');
                    exit();
                } else {
                    $errMsg = 'Password not match.';
                }
            }
        } catch (PDOException $e) {
            $errMsg = $e->getMessage();
        }
    } else {
        $errMsg = 'Error in Logging In.';
    }
}
?>
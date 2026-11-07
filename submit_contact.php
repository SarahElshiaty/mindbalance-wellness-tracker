<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        $stmt = $pdo->prepare("
          INSERT INTO contact_messages (name, email, message)
          VALUES (?, ?, ?)
        ");
        $stmt->execute([$name, $email, $message]);

        // flash success
        $_SESSION['flash_message']      = 'Your message was sent successfully!';
        $_SESSION['flash_message_type'] = 'success';
        header('Location: contact.php');
        exit;
    } else {
        $_SESSION['flash_message']      = 'All fields are required.';
        $_SESSION['flash_message_type'] = 'danger';
        header('Location: contact.php');
        exit;
    }
} else {
    header('Location: contact.php');
    exit;
}

<?php
include '../database/db_connect.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['role'])) {
    $id = $_GET['id'];
    $new_role = $_GET['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $id);
    $stmt->execute();

    header("Location: manage_users.php");
}
?>

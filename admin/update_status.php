<?php
include('database.php'); // Include your existing database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $sql = "UPDATE booking SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $booking_id);

    if ($stmt->execute()) {
        header("Location: appointment.php"); // Redirect back to the appointment list
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}
?>

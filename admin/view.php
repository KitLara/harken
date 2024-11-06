<?php
include('database.php'); // Include your existing database connection

// Get the patient ID from the URL
$patient_id = isset($_GET['patient']) ? intval($_GET['patient']) : 0;

// Fetch user details based on the patient ID
$user_details = null;

if ($patient_id > 0) {
    $query = $conn->prepare("SELECT full_name, address, date_of_birth, age, contact FROM booking WHERE booking_id = ?");
    $query->bind_param("i", $patient_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user_details = $result->fetch_assoc();
    } else {
        $user_details = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">

    <title>Patient Information</title>
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4">Patient Information</h2>
        
        <?php if ($user_details): ?>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td><?php echo htmlspecialchars($user_details['full_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo htmlspecialchars($user_details['address']); ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td><?php echo htmlspecialchars($user_details['date_of_birth']); ?></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td><?php echo htmlspecialchars($user_details['age']); ?></td>
                    </tr>
                    <tr>
                        <th>Contact Number</th>
                        <td><?php echo htmlspecialchars($user_details['contact']); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>No patient information found.</p>
        <?php endif; ?>

        <a href="patient.php" class="btn btn-secondary mt-4">Back to Patient List</a>
    </div>

    <script src="./js/script.js"></script>
</body>
</html>

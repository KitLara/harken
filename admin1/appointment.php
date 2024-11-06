<?php
include('database.php'); // Include your existing database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Query to fetch all data from the booking table
$sql = "SELECT 
            b.booking_id, 
            CONCAT(u.first_name, ' ', 
                   LEFT(u.middle_name, 1), '. ', 
                   u.last_name) AS full_name, 
            b.address, 
            b.contact, 
            b.date_of_birth, 
            b.age, 
            b.date_of_appointment, 
            b.time_of_appointment, 
            b.status, 
            s.service_name 
        FROM booking b 
        JOIN users u ON b.users_id = u.users_id 
        JOIN service s ON b.service_id = s.service_id"; // Joining with users and services to get more details

$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error); // Output the error message
}

$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row; // Fetch all rows into the appointments array
    }
} else {
    echo "No appointments found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/appointment.css">
    
    <title>HARKEN</title>
</head>
<body>

    <!-- SIDEBAR -->
    <?php include("./nav/sidebar.php"); ?>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <?php include("./nav/navbar.php"); ?>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="container mt-5">
                <h2 class="mb-4">Appointment List</h2>

                <!-- Appointments Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Patient Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Date of Birth</th>
                            <th>Age</th>
                            <th>Service Name</th>
                            <th>Date of Appointment</th>
                            <th>Time of Appointment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['booking_id']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['address']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['contact']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['date_of_birth']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['age']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['date_of_appointment']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['time_of_appointment']); ?></td>
                                <td>
                                    <form method="POST" action="update_status.php">
                                        <input type="hidden" name="booking_id" value="<?php echo $appointment['booking_id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="Pending" <?php echo $appointment['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Approved" <?php echo $appointment['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="Cancelled" <?php echo $appointment['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="./js/script.js"></script>

    
</body>
</html>

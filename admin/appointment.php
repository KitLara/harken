<?php
include('database.php'); // Include your existing database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the search query from the URL
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Prepare and execute the search query
$results = [];
if (!empty($search_query)) {
    $stmt = $conn->prepare("SELECT * FROM booking WHERE full_name LIKE ? LIMIT 10");
    $like_query = "%" . $search_query . "%"; // Adding wildcards for partial matching
    $stmt->bind_param("s", $like_query);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}

// Get the selected status from the form submission
$selected_status = isset($_POST['status']) ? $_POST['status'] : 'Pending';

// Query to fetch all data from the booking table
$sql = "SELECT 
            b.booking_id, 
            b.full_name, 
            b.address, 
            b.contact, 
            b.date_of_birth, 
            b.age, 
            b.date_of_appointment, 
            b.time_of_appointment, 
            b.status, 
            s.service_name 
        FROM booking b 
        JOIN service s ON b.service_id = s.service_id"; 

$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error); // Output the error message
}

// Initialize the appointments array
$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row; // Fetch all rows into the appointments array
    }
} else {
    echo "No appointments found."; // Handle the case where no appointments are found
}

// Filter appointments based on selected status
$filtered_appointments = array_filter($appointments, function($appointment) use ($selected_status) {
    return $appointment['status'] === $selected_status;
});

// Combine search results with filtered appointments
if (!empty($search_query)) {
    $filtered_appointments = array_filter($filtered_appointments, function($appointment) use ($search_query) {
        return stripos($appointment['full_name'], $search_query) !== false; // Case-insensitive match
    });
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

<style>
    .form-input {
    display: flex; /* To align input and button */
    align-items: center; /* Center align vertically */
}

.form-input input[type="search"] {
    width: 300px; /* Adjust the width as needed */
    height: 40px; /* Increase the height for a bigger look */
    padding: 10px; /* Add some padding for better appearance */
    font-size: 16px; /* Increase font size for readability */
    border: 1px solid #ccc; /* Border styling */
    border-radius: 5px; /* Slightly rounded corners */
    margin-right: 10px; /* Space between input and button */
}

.search-btn {
    height: 40px; /* Match the height of the input */
    padding: 0 20px; /* Add horizontal padding */
    font-size: 16px; /* Increase font size */
    background-color: #007bff; /* Button background color */
    color: white; /* Button text color */
    border: none; /* Remove border */
    border-radius: 5px; /* Slightly rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
}

.search-btn:hover {
    background-color: #0056b3; /* Darker color on hover */
}

</style>
<body>

    <!-- SIDEBAR -->
    <?php include("./nav/sidebar.php"); ?>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">

        <!-- MAIN -->
        <main>
            <div class="container mt-5">
                <h2 class="mb-4">Appointment List</h2>

                <form action="" method="GET" class="form-inline mb-4">
    <div class="form-input">
        <input type="search" name="query" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>" required>
        <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
    </div>
</form>


                <form method="POST" class="mb-4">
                    <label for="status">Filter by Status: </label>
                    <select name="status" id="status" onchange="this.form.submit()">
                        <option value="Pending" <?php echo $selected_status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Approved" <?php echo $selected_status === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="Cancelled" <?php echo $selected_status === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </form>

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
                        <?php if (count($filtered_appointments) > 0): ?>
                            <?php foreach ($filtered_appointments as $appointment): ?>
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
    <div class="btn-group" role="group" aria-label="Status Options">
        <button type="submit" name="status" value="Pending" class="btn btn-secondary <?php echo $appointment['status'] === 'Pending' ? 'active' : ''; ?>">Pending</button>
        <button type="submit" name="status" value="Approved" class="btn btn-success <?php echo $appointment['status'] === 'Approved' ? 'active' : ''; ?>">Approved</button>
        <button type="submit" name="status" value="Cancelled" class="btn btn-danger <?php echo $appointment['status'] === 'Cancelled' ? 'active' : ''; ?>">Cancelled</button>
    </div>
</form>
  
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">No appointments found for this status.</td>
                            </tr>
                        <?php endif; ?>
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

<?php
include('database.php'); // Include your existing database connection

// Get the patient ID from the URL, if it exists
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
        // Handle case where no user is found
        $user_details = null;
    }
}

// Fetch all patients for the "Other Patients" section
$other_patients_query = $conn->query("SELECT booking_id, full_name FROM booking WHERE booking_id != $patient_id");
$other_patients = $other_patients_query->fetch_all(MYSQLI_ASSOC);
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

    <!-- SIDEBAR -->
    <?php include("./nav/sidebar.php"); ?>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <?php include ("./nav/navbar.php"); ?>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
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

                <h4 class="mt-5">Other Patients</h4>
                <ul class="list-group">
                    <?php foreach ($other_patients as $patient): ?>
                        <li class="list-group-item">
                            <a href="patient.php?patient=<?php echo $patient['booking_id']; ?>">
                                <?php echo htmlspecialchars($patient['full_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <a href="transaction.php" class="btn btn-secondary mt-4">Back to Transactions</a>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="./js/script.js"></script>
</body>
</html>

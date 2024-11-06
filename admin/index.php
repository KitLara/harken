<?php
include('database.php'); // Include your existing database connection

// if (isset($_SESSION['acc_type'])) {
//     // Check user account type
//     if ($_SESSION['acc_type'] == 1) {
//         // User is of type 1, but not allowed to access type 2 functionality
//         // Redirect to an appropriate page, e.g., an error page or their dashboard
//         header("Location:../forbidden.php"); // or another page
//         exit();
//     }
// }

// Queries to fetch total counts for each status
$total_approved = $conn->query("SELECT COUNT(*) as total FROM booking WHERE status = 'Approved'")->fetch_assoc()['total'];
$total_pending = $conn->query("SELECT COUNT(*) as total FROM booking WHERE status = 'Pending'")->fetch_assoc()['total'];
$total_cancelled = $conn->query("SELECT COUNT(*) as total FROM booking WHERE status = 'Cancelled'")->fetch_assoc()['total'];

// Query to fetch today's appointments
$appointments_query = "SELECT full_name, time_of_appointment, status FROM booking WHERE DATE(date_of_appointment) = CURDATE()";

$appointments = $conn->query($appointments_query);

if (!$appointments) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
   
    <link rel="stylesheet" href="./css/style.css">
    <title>HARKEN</title>
</head>
<body>

    <!-- SIDEBAR -->
    <?php include("./nav/sidebar.php"); ?>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">


        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Home</a></li>
                    </ul>
                </div>
                <a href="#" class="btn-download">
                    <i class='bx bxs-cloud-download'></i>
                    <span class="text">Download PDF</span>
                </a>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-user-check'></i>
                    <span class="text">
                        <h3><?php echo $total_approved; ?></h3>
                        <p>Approved</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3><?php echo $total_pending; ?></h3>
                        <p>Pending</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-user-x'></i>
                    <span class="text">
                        <h3><?php echo $total_cancelled; ?></h3>
                        <p>Cancelled</p>
                    </span>
                </li>
            </ul>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Today's Appointments</h3>
                        <i class='bx bx-search'></i>
                        <i class='bx bx-filter'></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Time of Check Up</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($appointments->num_rows > 0): ?>
                                <?php while ($row = $appointments->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <img src="img/people.png">
                                            <p><?php echo htmlspecialchars($row['full_name']); ?></p>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['time_of_appointment']); ?></td>
                                        <td><span class="status <?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No appointments found for today.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="./js/script.js"></script>
</body>
</html>

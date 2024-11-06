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

// Get the search query from the URL
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

// Fetch other patients based on the search query
$other_patients_query = $conn->prepare("SELECT booking_id, full_name FROM booking WHERE booking_id != ? AND full_name LIKE ?");
$like_query = "%" . $search_query . "%";
$other_patients_query->bind_param("is", $patient_id, $like_query);
$other_patients_query->execute();
$other_patients_result = $other_patients_query->get_result();
$other_patients = $other_patients_result->fetch_all(MYSQLI_ASSOC);
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


        <!-- MAIN -->
        <main>
            <div class="container mt-5">
                <h2 class="mb-4">Patient Information</h2>
                
                <!-- Search Form -->
                <form action="" method="GET" class="form-inline mb-4">
                    <div class="form-input">
                        <input type="search" name="search_query" placeholder="Search patients..." value="<?php echo htmlspecialchars($search_query); ?>" required>
                        <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($other_patients as $patient): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($patient['full_name']); ?>
                                </td>
                                <td>
                                    <a href="view.php?patient=<?php echo $patient['booking_id']; ?>" class="btn btn-primary">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <a href="patient.php" class="btn btn-secondary mt-4">Back to Patient List</a>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="./js/script.js"></script>
</body>
</html>

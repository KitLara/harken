<?php
include('database.php'); // Include your existing database connection

// Get the user ID from the URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch user details based on the user ID
$user_details = null;

if ($user_id > 0) {
    $query = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user_details = $result->fetch_assoc();
    } else {
        // Handle case where no user is found
        echo "No user found.";
        exit();
    }
}

// Handle form submission for updating user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    // Update user information
    $update_query = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    $update_query->bind_param("ssssi", $first_name,  $last_name, $email, $user_id);

    if ($update_query->execute()) {
        header("Location: acc_management.php"); // Redirect to the account management page after success
        exit();
    } else {
        echo "Error updating user: " . $update_query->error;
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

    <title>Edit User</title>
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
                <h2 class="mb-4">Edit User Information</h2>

                <?php if ($user_details): ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_details['first_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_details['last_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_details['email']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="acc_management.php" class="btn btn-secondary">Cancel</a>
                    </form>
                <?php else: ?>
                    <p>No user information available to edit.</p>
                <?php endif; ?>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="./js/script.js"></script>
</body>
</html>

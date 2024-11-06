<?php
include('database.php'); // Include your existing database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$service = null;

// Handle GET requests for editing a service
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['service_id'])) {
    $id = $_GET['service_id'];
    $stmt = $conn->prepare("SELECT * FROM service WHERE service_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
}

// Handle POST requests for adding or editing a service
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        // Validate inputs
        if (empty($_POST['service_name']) || empty($_POST['service_desc']) || !isset($_POST['service_price'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            exit;
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] == UPLOAD_ERR_OK) {
            $targetDir = "uploads/"; // Ensure this directory exists and is writable
            $imagePath = $targetDir . basename($_FILES['service_image']['name']);
            move_uploaded_file($_FILES['service_image']['tmp_name'], $imagePath);
        }

        $name = $_POST['service_name'];
        $description = $_POST['service_desc'];
        $price = (float)$_POST['service_price'];

        if ($_POST['action'] == 'add') {
            // Insert new service
            $stmt = $conn->prepare("INSERT INTO service (service_name, service_desc, service_price, service_image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $name, $description, $price, $imagePath);
        } else if ($_POST['action'] == 'edit') {
            // Update existing service
            $id = $_POST['service_id'];
            $stmt = $conn->prepare("UPDATE service SET service_name=?, service_desc=?, service_price=?, service_image=? WHERE service_id=?");
            $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title><?= $service ? 'Edit Service' : 'Add Service' ?></title>
</head>
<body>
<div class="container mt-5">
    <h2><?= $service ? 'Edit Service' : 'Add Service' ?></h2>
    <form id="serviceForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?= $service ? 'edit' : 'add' ?>">
        <?php if ($service): ?>
            <input type="hidden" name="service_id" value="<?= $service['service_id']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="service_name">Service Name</label>
            <input type="text" id="service_name" name="service_name" class="form-control" value="<?= $service['service_name'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="service_desc">Description</label>
            <textarea id="service_desc" name="service_desc" class="form-control" required><?= $service['service_desc'] ?? ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="service_price">Price</label>
            <input type="number" id="service_price" name="service_price" class="form-control" value="<?= $service['service_price'] ?? ''; ?>" step="0.01" required>
        </div>
        
        <div class="form-group">
            <label for="service_image">Image</label>
            <input type="file" id="service_image" name="service_image" class="form-control" accept="image/*">
            <?php if ($service && $service['service_image']): ?>
                <img src="<?= htmlspecialchars($service['service_image']); ?>" alt="Current Image" style="max-width: 100px; margin-top: 10px;">
            <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= $service ? 'Update Service' : 'Add Service' ?></button>
        <a href="service.php" class="btn btn-secondary">Back to Services</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $('#serviceForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'manage_service.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success') {
                    alert('Service saved successfully!');
                    window.location.href = 'service.php'; // Redirect to the services page
                } else {
                    alert(jsonResponse.message || 'Failed to save service.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error saving service. Please check the console for details.');
                console.error('AJAX error:', textStatus, errorThrown);
            }
        });
    });
</script>
</body>
</html>

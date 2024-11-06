<?php
include('database.php'); // Include your existing database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the search query from the URL
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

// Fetch existing services based on the search query
$services = [];
$sql = "SELECT * FROM service WHERE service_name LIKE ?";
$stmt = $conn->prepare($sql);
$like_query = "%" . $search_query . "%";
$stmt->bind_param("s", $like_query);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Handle POST requests (Add, Edit, Delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // Validate inputs
                if (isset($_POST['service_name'], $_POST['service_desc'], $_POST['service_price'])) {
                    $name = $_POST['service_name'];
                    $description = $_POST['service_desc'];
                    $price = (float)$_POST['service_price'];

                    // Handle image upload
                    $imagePath = null;
                    if (isset($_FILES['service_img']) && $_FILES['service_img']['error'] == UPLOAD_ERR_OK) {
                        $targetDir = "img/"; // Changed to img directory
                        $imagePath = $targetDir . basename($_FILES['service_img']['name']);
                        move_uploaded_file($_FILES['service_img']['tmp_name'], $imagePath);
                    }

                    $stmt = $conn->prepare("INSERT INTO service (service_name, service_desc, service_price, service_image) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssds", $name, $description, $price, $imagePath);
                    
                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success', 'insertId' => $stmt->insert_id, 'imagePath' => $imagePath]);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
                }
                break;

            case 'edit':
                // Check for required fields in edit
                if (isset($_POST['service_id'], $_POST['service_name'], $_POST['service_desc'], $_POST['service_price'])) {
                    $id = $_POST['service_id'];
                    $name = $_POST['service_name'];
                    $description = $_POST['service_desc'];
                    $price = (float)$_POST['service_price'];

                    // Handle image upload if provided
                    $imagePath = null;
                    if (isset($_FILES['service_img']) && $_FILES['service_img']['error'] == UPLOAD_ERR_OK) {
                        $targetDir = "img/"; // Changed to img directory
                        $imagePath = $targetDir . basename($_FILES['service_img']['name']);
                        move_uploaded_file($_FILES['service_img']['tmp_name'], $imagePath);
                    }

                    // Update statement
                    $stmt = $conn->prepare("UPDATE service SET service_name=?, service_desc=?, service_price=?, service_image=? WHERE service_id=?");
                    $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $id);

                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
                }
                break;

            case 'delete':
                if (isset($_POST['service_id'])) {
                    $id = $_POST['service_id'];

                    $stmt = $conn->prepare("DELETE FROM service WHERE service_id=?");
                    $stmt->bind_param("i", $id);
                    
                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Missing service ID']);
                }
                break;
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
    <link rel="stylesheet" href="./css/style.css">
    <title>Services</title>
</head>

<body>
    <?php include ("./nav/sidebar.php") ?>
    <div class="container">
        <h1>Service Management</h1>

        <!-- Form to Add a New Service -->
        <h2>Add New Service</h2>
        <form id="addServiceForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <input type="text" name="service_name" placeholder="Service Name" required>
            <input type="text" name="service_desc" placeholder="Service Description" required>
            <input type="number" name="service_price" placeholder="Service Price" required>
            <input type="file" name="service_img" accept="image/*" required>
            <button type="submit" class="btn btn-primary">Add Service</button>
        </form>

        <!-- List of Services -->
        <h2>Available Services</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo $service['service_id']; ?></td>
                    <td><?php echo $service['service_name']; ?></td>
                    <td><?php echo $service['service_desc']; ?></td>
                    <td><?php echo $service['service_price']; ?></td>
                    <td><img src="<?php echo $service['service_image']; ?>" alt="<?php echo $service['service_name']; ?>" width="100"></td>
                    <td>
                        <button class="btn btn-warning" onclick="editService(<?php echo $service['service_id']; ?>)">Edit</button>
                        <button class="btn btn-danger" onclick="deleteService(<?php echo $service['service_id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript functions for editing and deleting services
        function editService(serviceId) {
            alert(`Editing service ID: ${serviceId}`);
            // Logic to populate the edit form with the service details can go here
        }

        function deleteService(serviceId) {
            if (confirm(`Are you sure you want to delete service ID: ${serviceId}?`)) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('service_id', serviceId);

                fetch('service.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Service deleted successfully');
                    location.reload(); // Reload the page to see changes
                });
            }
        }
    </script>
</body>
</html>

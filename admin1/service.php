<?php
include('database.php'); // Include your existing database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch existing services
$services = [];
$result = $conn->query("SELECT * FROM service");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
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

                $stmt = $conn->prepare("INSERT INTO service (service_name, service_desc, service_price, service_image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssds", $name, $description, $price, $imagePath);
                
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'insertId' => $stmt->insert_id, 'imagePath' => $imagePath]);
                } else {
                    error_log("Error: " . $stmt->error);
                    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                }
                break;

            case 'edit':
                $id = $_POST['service_id'];
                $name = $_POST['service_name'];
                $description = $_POST['service_desc'];
                $price = (float)$_POST['service_price'];

                // Handle image upload if provided
                $imagePath = null;
                if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] == UPLOAD_ERR_OK) {
                    $targetDir = "uploads/";
                    $imagePath = $targetDir . basename($_FILES['service_image']['name']);
                    move_uploaded_file($_FILES['service_image']['tmp_name'], $imagePath);
                }

                $stmt = $conn->prepare("UPDATE service SET service_name=?, service_desc=?, service_price=?, service_image=? WHERE service_id=?");
                $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $id);

                if ($stmt->execute()) {
                    // error_log("Error: " . $stmt->error);
                    // echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                    echo json_encode(['status' => 'success']);
                    header('location:service.php');
                } else {
                    error_log("Error: " . $stmt->error);
                    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                    // echo json_encode(['status' => 'success']);
                }
                break;

            case 'delete':
                $id = $_POST['service_id'];

                $stmt = $conn->prepare("DELETE FROM service WHERE service_id=?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    // error_log("Error: " . $stmt->error);
                    // echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                    echo json_encode(['status' => 'success']);
                } else {
                    error_log("Error: " . $stmt->error);
                    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                    // echo json_encode(['status' => 'success']);
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
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <title>Services</title>
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
                <h2 class="mb-4">List of Services</h2>

                <ul class="list-group" id="service-list">
                    <?php foreach ($services as $service): ?>
                        <li class="list-group-item" data-id="<?= $service['service_id']; ?>">
                            <strong><?= htmlspecialchars($service['service_name']); ?></strong><br>
                            <span><?= htmlspecialchars($service['service_desc']); ?></span><br>
                            <span>Price: $<?= number_format($service['service_price'], 2); ?></span><br>
                            <?php if ($service['service_image']): ?>
                                <img src="<?= htmlspecialchars($service['service_image']); ?>" alt="<?= htmlspecialchars($service['service_name']); ?>" style="max-width: 100px;"/><br>
                            <?php endif; ?>
                            <button class="btn btn-warning btn-sm float-right mx-1" onclick="editService(this)">Edit</button>
                            <button class="btn btn-danger btn-sm float-right" onclick="deleteService(this)">Delete</button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button class="btn btn-primary mt-4" data-toggle="modal" data-target="#addServiceModal">Add Service</button>
                <a href="transaction.php" class="btn btn-secondary mt-4">Back to Transactions</a>
            </div>
        </main>
        <!-- MAIN -->

        <!-- Add Service Modal -->
        <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceModalLabel">Add Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="new-service-name" class="form-control mb-2" placeholder="Service Name" required>
                        <input type="text" id="new-service-description" class="form-control mb-2" placeholder="Description" required>
                        <input type="number" id="new-service-price" class="form-control mb-2" placeholder="Price" step="0.01" required>
                        <input type="file" id="new-service-image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="addService()">Add Service</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- CONTENT -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function addService() {
            const serviceName = document.getElementById('new-service-name').value;
            const serviceDescription = document.getElementById('new-service-description').value;
            const servicePrice = document.getElementById('new-service-price').value;
            const serviceImage = document.getElementById('new-service-image').files[0];

            if (serviceName && serviceDescription && servicePrice && serviceImage) {
                const formData = new FormData();
                formData.append('action', 'add');
                formData.append('service_name', serviceName);
                formData.append('service_desc', serviceDescription);
                formData.append('service_price', servicePrice);
                formData.append('service_image', serviceImage);

                $.ajax({
                    url: 'service.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        try {
                            const jsonResponse = JSON.parse(response);
                            if (jsonResponse.status === 'success') {
                                const list = document.getElementById('service-list');
                                const newItem = document.createElement('li');
                                newItem.className = 'list-group-item';
                                newItem.innerHTML = `
                                    <strong>${serviceName}</strong><br>
                                    <span>${serviceDescription}</span><br>
                                    <span>Price: $${servicePrice}</span><br>
                                    <img src="${jsonResponse.imagePath}" alt="${serviceName}" style="max-width: 100px;"/><br>
                                    <button class="btn btn-warning btn-sm float-right mx-1" onclick="editService(this)">Edit</button>
                                    <button class="btn btn-danger btn-sm float-right" onclick="deleteService(this)">Delete</button>
                                `;
                                newItem.setAttribute('data-id', jsonResponse.insertId);
                                list.appendChild(newItem);
                                $('#addServiceModal').modal('hide');
                                document.getElementById('new-service-name').value = '';
                                document.getElementById('new-service-description').value = '';
                                document.getElementById('new-service-price').value = '';
                                document.getElementById('new-service-image').value = '';
                            } else {
                                alert(jsonResponse.message || 'Failed to add service. Please try again.');
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            alert('An unexpected error occurred. Please try again.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding service. Please check the console for details.');
                        console.error('AJAX error:', textStatus, errorThrown);
                    }
                });
            } else {
                alert('Please fill in all fields.');
            }
        }

        function editService(button) {
            const serviceItem = button.parentElement;
            const serviceId = serviceItem.getAttribute('data-id');
            const serviceName = serviceItem.querySelector('strong').innerText;
            const serviceDescription = serviceItem.querySelector('span').innerText;
            const servicePrice = serviceItem.querySelector('span:nth-child(3)').innerText.replace('Price: $', '');

            const newName = prompt('Edit Service Name:', serviceName);
            const newDescription = prompt('Edit Service Description:', serviceDescription);
            const newPrice = prompt('Edit Service Price:', servicePrice);

            if (newName && newDescription && newPrice) {
                $.post('service.php', {
                    action: 'edit',
                    service_id: serviceId,
                    service_name: newName,
                    service_desc: newDescription,
                    service_price: newPrice
                }, function(response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        serviceItem.querySelector('strong').innerText = newName;
                        serviceItem.querySelector('span').innerText = newDescription;
                        serviceItem.querySelector('span:nth-child(3)').innerText = `Price: $${newPrice}`;
                    } else {
                        alert(jsonResponse.message || 'Failed to edit service.');
                    }
                }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                    alert('Error editing service. Please check the console for details.');
                    console.error('AJAX error:', textStatus, errorThrown);
                });
            }
        }

        function deleteService(button) {
            const serviceItem = button.parentElement;
            const serviceId = serviceItem.getAttribute('data-id');

            if (confirm('Are you sure you want to delete this service?')) {
                $.post('service.php', {
                    action: 'delete',
                    service_id: serviceId
                }, function(response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        serviceItem.remove();
                    } else {
                        alert(jsonResponse.message || 'Failed to delete service.');
                    }
                }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting service. Please check the console for details.');
                    console.error('AJAX error:', textStatus, errorThrown);
                });
            }
        }
    </script>
</body>
</html>

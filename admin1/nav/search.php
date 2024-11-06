<?php
include('database.php'); // Include your existing database connection

// Get the search query from the URL
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Search Results</title>
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>

        <?php if (!empty($results)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Age</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $patient): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($patient['address']); ?></td>
                            <td><?php echo htmlspecialchars($patient['date_of_birth']); ?></td>
                            <td><?php echo htmlspecialchars($patient['age']); ?></td>
                            <td><?php echo htmlspecialchars($patient['contact']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>

    <script src="./js/script.js"></script>
</body>
</html>

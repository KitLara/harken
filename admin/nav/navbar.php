<?php
session_start();
include('database.php'); // Include your database connection

// Initialize the first_name variable
$first_name = "Guest"; // Default value if not logged in

// Fetch the user's first name if logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT first_name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $first_name = $user['first_name']; // Assign the first name to the variable
    }
}
?>

<nav>
    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode" onclick="toggleDarkMode()"></label>
    <div class="greeting" style="float: right;">Hi, "<?php echo htmlspecialchars($first_name); ?>"</div>
</nav>

<script>
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode'); // Assuming you have a CSS class for dark mode
}
</script>

<style>
body.dark-mode {
    background-color: #121212; /* Dark background */
    color: #ffffff; 
    /* Light text */
}
nav{
    margin-right: 200;
}
</style>

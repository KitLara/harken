
	<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page name
?>

<section id="sidebar">
    <a href="#" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">HARKEN</span>
    </a>
    <ul class="side-menu top">
        <li class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">
            <a href="index.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="<?= ($current_page == 'appointment.php') ? 'active' : '' ?>">
            <a href="appointment.php">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Appointment</span>
            </a>
        </li>
        <li class="<?= ($current_page == 'transaction.php') ? 'active' : '' ?>">
            <a href="transaction.php">
                <i class='bx bxs-doughnut-chart'></i>
                <span class="text">Transactions</span>
            </a>
        </li>
        <li class="<?= ($current_page == 'patient.php') ? 'active' : '' ?>">
            <a href="patient.php">
                <i class='bx bxs-group'></i>
                <span class="text">Patients</span>
            </a>
        </li>
        <li class="<?= ($current_page == 'service.php') ? 'active' : '' ?>">
            <a href="service.php">
                <i class='bx bxs-message-dots'></i>
                <span class="text">Services</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <a href="#">
                <i class='bx bxs-cog'></i>
                <span class="text">Account Management</span>
            </a>
        </li>
        <li>
            <a href="#" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>

<script>
    const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');
    const sideMenu = document.querySelector('#sidebar .side-menu.top');

    sideMenu.addEventListener('click', function(event) {
        const target = event.target.closest('a'); // Get the clicked link
        
        if (target) {
            // Remove 'active' class from all
            allSideMenu.forEach(i => {
                i.parentElement.classList.remove('active');
            });
            // Add 'active' class to the clicked item
            target.parentElement.classList.add('active');
        }
    });
</script>


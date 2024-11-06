<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="./css/style.css">

	<title>Transactions</title>
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
            <h2 class="mb-4">Completed Transactions</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Service Avail</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>Dental Cleaning</td>
                        <td>2024-10-15</td>
                        <td>
                            <a href="view_patient.html?id=1" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>Eye Exam</td>
                        <td>2024-10-12</td>
                        <td>
                            <a href="view_patient.html?id=2" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Emily Johnson</td>
                        <td>Physical Therapy</td>
                        <td>2024-10-10</td>
                        <td>
                            <a href="view_patient.html?id=3" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Michael Brown</td>
                        <td>Blood Test</td>
                        <td>2024-10-08</td>
                        <td>
                            <a href="view_patient.html?id=4" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Linda Davis</td>
                        <td>Vaccination</td>
                        <td>2024-10-05</td>
                        <td>
                            <a href="view_patient.html?id=5" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="./js/script.js"></script>
</body>
</html>
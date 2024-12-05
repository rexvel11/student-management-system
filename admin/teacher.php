<?php
@include '../connect.php';
@include '../admin-only.php';

// Fetch professors data from the database
function getProfessors($conn) {
	$query = "SELECT * FROM user_tbl WHERE user_type = 'professor'";
	$result = mysqli_query($conn, $query);

	$professors = [];
	if ($result && mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$professors[] = $row;
		}
	}
	return $professors;
}

$professors = getProfessors($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="style.css">

	

	<title>Professors Page</title>
</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<span class="text">Student Management System</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="/student-management-system/admin/index.php">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="/student-management-system/admin/student.php">
					<i class='bx bxs-group'></i>
					<span class="text">Students</span>
				</a>
			</li>
			<li class="active">
				<a href="/student-management-system/admin/teacher.php">
					<i class='bx bxs-group'></i>
					<span class="text">Teachers</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Teachers Information List</h1>
				</div>
				<li>
					<a href="/student-management-system/logout.php" class="logout">
						<i class='bx bxs-log-out-circle'></i>
						<span class="text">Logout</span>
					</a>
				</li>
			</div>

			<div class="professor-table">
				<table>
					<thead>
						<tr>
							<th>Name</th>
							<th>Username</th>
							<th>Email</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!empty($professors)) {
							foreach ($professors as $professor) {
								echo "<tr>";
								echo "<td>" . htmlspecialchars($professor['name']) . "</td>";
								echo "<td>" . htmlspecialchars($professor['username']) . "</td>";
								echo "<td>" . htmlspecialchars($professor['email']) . "</td>";
								echo "<td>
										<a href='/student-management-system/forgot-pass/reset.php?email=" . urlencode($professor['email']) . "&from=admin' class='btn-view'>Change Pass</a>
									</td>";
								echo "</tr>";
							}
						} else {
							echo "<tr><td colspan='4'>No professors found.</td></tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<script src="script.js"></script>
</body>
</html>

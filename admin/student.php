<?php
@include '../connect.php';
@include '../admin-only.php';

// Fetch students data from the database
function getStudents($conn) {
	$query = "SELECT * FROM user_tbl WHERE user_type = 'student'";
	$result = mysqli_query($conn, $query);

	$students = [];
	if ($result && mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$students[] = $row;
		}
	}
	return $students;
}

$students = getStudents($conn);
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

	<title>Students Page</title>

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
			<li class="active">
				<a href="/student-management-system/admin/student.php">
					<i class='bx bxs-group'></i>
					<span class="text">Students</span>
				</a>
			</li>
			<li>
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
					<h1>Students Information List</h1>
				</div>
				<li>
					<a href="/student-management-system/logout.php" class="logout">
						<i class='bx bxs-log-out-circle'></i>
						<span class="text">Logout</span>
					</a>
				</li>
			</div>

			<div class="student-table">
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
						if (!empty($students)) {
							foreach ($students as $student) {
								echo "<tr>";
								echo "<td>" . htmlspecialchars($student['name']) . "</td>";
								echo "<td>" . htmlspecialchars($student['username']) . "</td>";
								echo "<td>" . htmlspecialchars($student['email']) . "</td>";
								echo "<td>
										<a href='/student-management-system/forgot-pass/reset.php?email=" . urlencode($student['email']) . "&from=admin' class='btn-view'>Change Pass</a>
									</td>";
								echo "</tr>";
							}
						} else {
							echo "<tr><td colspan='5'>No students found.</td></tr>";
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

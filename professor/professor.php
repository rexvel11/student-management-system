<?php
@include '../connect.php';

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

	<title>Professors Page</title>

	<style>
		.student-table table {
	width: 100%;
	border-collapse: collapse;
	margin-top: 20px;
}
.student-table th, .student-table td {
	border: 1px solid #ddd;
	padding: 8px;
	text-align: left;
}
.student-table th {
	background-color: #f4f4f4;
	font-weight: bold;
}

.btn-view, .btn-edit {
	padding: 5px 10px;
	margin: 2px;
	border: none;
	color: #fff;
	cursor: pointer;
}
.btn-view {
	background-color: #4CAF50; /* Green */
}
.btn-edit {
	background-color: #008CBA; /* Blue */
}

	</style>

</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<span class="text">Student Management System</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="/student-management-system/admin/student.php">
					<i class='bx bxs-group'></i>
					<span class="text">Students</span>
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
										<a href='/student-management-system/forgot-pass/reset.php?email=" . urlencode($student['email']) . "&from=professor' class='btn-view'>Change Pass</a>
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

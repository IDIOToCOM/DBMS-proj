<?php
// Include database connection
include 'connector.php';

// Fetch data from the students table
$sql = "SELECT student_id, first_name, last_name, email, phone, enrollment_number, department FROM students ORDER BY enrollment_number ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to check for duplicate or triplicate records
function checkDuplicates($students, $column) {
    $countValues = array_count_values(array_column($students, $column));
    $duplicates = [];
    foreach ($countValues as $key => $count) {
        if ($count > 1) {
            $duplicates[$key] = $count; // Store count of duplicates
        }
    }
    return $duplicates;
}

// Check for duplicate/triplicate values
$duplicateFirstNames = checkDuplicates($students, 'first_name');
$duplicateEmails = checkDuplicates($students, 'email');
$duplicateEnrollment = checkDuplicates($students, 'enrollment_number');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #05a6a6 0%, #0575e6 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(90deg, #0693e3, #00d084);
            padding: 20px;
            color: white;
            border: 1px solid white;
        }
        
        .card-body {
            padding: 20px;
        }
        
        h2 {
            margin: 0;
            font-weight: 600;
        }
        
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            flex: 1;
            min-width: 200px;
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(90deg, #0693e3, #00d084);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .stat-icon i {
            font-size: 1.8rem;
            color: white;
        }
        
        .stat-info h3 {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }
        
        .stat-info p {
            margin: 5px 0 0;
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: linear-gradient(90deg, #0693e3, #00d084);
            color: white;
            font-weight: 500;
        }
        
        tr:hover {
            background: rgba(6, 147, 227, 0.03);
        }
        
        .duplicate {
            position: relative;
        }
        
        .duplicate td {
            position: relative;
        }
        
        .duplicate-marker {
            position: absolute;
            top: 0;
            right: 0;
            background: #ff9800;
            color: white;
            padding: 2px 6px;
            font-size: 0.7rem;
            border-radius: 0 0 0 5px;
        }
        
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 25px;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .add-new {
            background: linear-gradient(90deg, #28a745, #218838);
        }
        
        .view-normalized {
            background: linear-gradient(90deg, #0693e3, #00d084);
        }
        
        .export-data {
            background: linear-gradient(90deg, #6c757d, #495057);
        }
        
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-container input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .search-container button {
            padding: 12px 20px;
            background: linear-gradient(90deg, #0693e3, #00d084);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        nav a {
            color: #0693e3;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        nav a:hover {
            background: rgba(6, 147, 227, 0.1);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav>
            <div class="nav-logo">
                <i class="fas fa-graduation-cap"></i> Student Enrollment Management System
            </div>
            <div class="nav-links">
                <a href="homepage.php"><i class="fas fa-home"></i> Home</a>
                <a href="index.php"><i class="fas fa-user-plus"></i> Add Students</a>
                <a href="normalization.php"><i class="fas fa-database"></i> View Normalized Data</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-table"></i> Student Records</h2>
            </div>
            <div class="card-body">
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Students</h3>
                            <p><?php echo count($students); ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clone"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Duplicate Enrollments</h3>
                            <p><?php echo count($duplicateEnrollment); ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Duplicate Emails</h3>
                            <p><?php echo count($duplicateEmails); ?></p>
                        </div>
                    </div>
                </div>

                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search by name, enrollment number, or department...">
                    <button onclick="searchTable()"><i class="fas fa-search"></i> Search</button>
                </div>

                <table id="studentsTable">
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Enrollment Number</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($students as $student): 
                        // Determine if this row contains duplicate data
                        $isDuplicateFirstName = isset($duplicateFirstNames[$student['first_name']]);
                        $isDuplicateEmail = isset($duplicateEmails[$student['email']]);
                        $isDuplicateEnrollment = isset($duplicateEnrollment[$student['enrollment_number']]);
                        $rowClass = ($isDuplicateFirstName || $isDuplicateEmail || $isDuplicateEnrollment) ? 'duplicate' : '';
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($student['first_name']); ?>
                            <?php if ($isDuplicateFirstName): ?>
                                <span class="duplicate-marker" title="<?php echo $duplicateFirstNames[$student['first_name']]; ?> occurrences">
                                    <?php echo $duplicateFirstNames[$student['first_name']]; ?>x
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($student['email']); ?>
                            <?php if ($isDuplicateEmail): ?>
                                <span class="duplicate-marker" title="<?php echo $duplicateEmails[$student['email']]; ?> occurrences">
                                    <?php echo $duplicateEmails[$student['email']]; ?>x
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['phone']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($student['enrollment_number']); ?>
                            <?php if ($isDuplicateEnrollment): ?>
                                <span class="duplicate-marker" title="<?php echo $duplicateEnrollment[$student['enrollment_number']]; ?> occurrences">
                                    <?php echo $duplicateEnrollment[$student['enrollment_number']]; ?>x
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['department']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $student['student_id']; ?>" title="Edit"><i class="fas fa-edit" style="color: #0693e3;"></i></a>
                            <a href="delete.php?id=<?php echo $student['student_id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this student?');"><i class="fas fa-trash" style="color: #dc3545; margin-left: 10px;"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <div class="btn-container">
                    <a href="index.php" class="btn add-new"><i class="fas fa-user-plus"></i> Add New Students</a>
                    <a href="normalization.php" class="btn view-normalized"><i class="fas fa-database"></i> View Normalized Data</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('studentsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.indexOf(input) > -1) {
                        found = true;
                        break;
                    }
                }
                
                if (found) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        // Enable search on Enter key press
        document.getElementById('searchInput').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                searchTable();
            }
        });
    </script>
</body>
</html>
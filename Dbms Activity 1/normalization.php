<?php
// Include database connection
include 'connector.php';

// Fetch all student data
$sql = "SELECT * FROM students ORDER BY enrollment_number ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * Helper function to check and normalize multi-valued attributes.
 */
function normalizeValues($value)
{
    return strpos($value, ',') !== false ? explode(',', $value) : [$value];
}

// Prepare data for 3NF - extract unique departments
$departments = [];
$departmentIds = [];
$counter = 1;

foreach ($students as $student) {
    $depts = normalizeValues($student['department']);
    foreach ($depts as $dept) {
        $dept = trim($dept);
        if (!empty($dept) && !in_array($dept, $departments)) {
            $departments[] = $dept;
            $departmentIds[$dept] = $counter++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Normalization - Student Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        body {
            background: linear-gradient(135deg, #05a6a6 0%, #0575e6 100%);
            padding: 20px;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
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
        }

        .card-body {
            padding: 20px;
        }

        h2 {
            margin: 0;
            font-weight: 600;
        }

        h3 {
            background: linear-gradient(90deg, #0693e3, #00d084);
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin: 0;
            font-size: 1.2rem;
        }

        .explanation {
            background: rgba(6, 147, 227, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            border-left: 4px solid #0693e3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 0 0 8px 8px;
        }

        th,
        td {
            padding: 12px 15px;
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

        tr:last-child td {
            border-bottom: none;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Changed to 2 columns only */
            gap: 20px;
            margin-bottom: 30px;
        }

        .primary-key {
            background-color: rgba(6, 147, 227, 0.1);
            font-weight: 600;
        }

        .foreign-key {
            background-color: rgba(0, 208, 132, 0.1);
            font-style: italic;
        }

        .key-legend {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .key-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .key-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        .primary-key-color {
            background-color: rgba(6, 147, 227, 0.2);
            border: 1px solid rgba(6, 147, 227, 0.5);
        }

        .foreign-key-color {
            background-color: rgba(0, 208, 132, 0.2);
            border: 1px solid rgba(0, 208, 132, 0.5);
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

        .btn-back {
            background: linear-gradient(90deg, #0693e3, #00d084);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(6, 147, 227, 0.2);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(6, 147, 227, 0.3);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav>
            <div class="nav-logo">
                <i class="fas fa-graduation-cap"></i> Student Enrollment System
            </div>
            <div class="nav-links">
                <a href="homepage.php"><i class="fas fa-home"></i> Home</a>
                <a href="index.php"><i class="fas fa-user-plus"></i> Add Students</a>
                <a href="rawdata.php"><i class="fas fa-table"></i> Records</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-database"></i> Database Normalization</h2>
            </div>
            <div class="card-body">
                </div>

                <!-- First Normal Form (1NF) -->
                <h3>First Normal Form (1NF)</h3>
                <div class="explanation">
                    <p></p>
                </div>
                <table>
                    <tr>
                        <!-- <th class="primary-key">student_id</th> -->
                        <th>first_name</th>
                        <th>last_name</th>
                        <th>email</th>
                        <th>phone</th>
                        <th>enrollment_number</th>
                        <th>department</th>
                    </tr>
                    <?php
                    foreach ($students as $student):
                        // Normalize values to ensure 1NF (no multi-valued attributes)
                        $firstNames = normalizeValues($student['first_name']);
                        $lastNames = normalizeValues($student['last_name']);
                        $emails = normalizeValues($student['email']);
                        $phones = normalizeValues($student['phone']);
                        $departments = normalizeValues($student['department']);

                        // Create rows for each combination to demonstrate 1NF
                        foreach ($firstNames as $firstName) {
                            foreach ($lastNames as $lastName) {
                                foreach ($emails as $email) {
                                    foreach ($phones as $phone) {
                                        foreach ($departments as $department) {
                                            ?>
                                            <tr>
                                                <!-- <td class="primary-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                                <td><?= htmlspecialchars(trim($firstName)); ?></td>
                                                <td><?= htmlspecialchars(trim($lastName)); ?></td>
                                                <td><?= htmlspecialchars(trim($email)); ?></td>
                                                <td><?= htmlspecialchars(trim($phone)); ?></td>
                                                <td><?= htmlspecialchars($student['enrollment_number']); ?></td>
                                                <td><?= htmlspecialchars(trim($department)); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                            }
                        }
                    endforeach;
                    ?>
                </table>

                <!-- Second Normal Form (2NF) -->
                <h3>Second Normal Form (2NF)</h3>
                <div class="explanation">
                    <p></p>
                </div>

                <div class="tables-grid">
                    <!-- Students Table -->
                    <div>
                        <h3>Students Table</h3>
                        <table>
                            <tr>
                                <!-- <th class="primary-key">student_id</th> -->
                                <th>first_name</th>
                                <th>last_name</th>
                            </tr>
                            <?php foreach ($students as $student): 
                                $firstNames = normalizeValues($student['first_name']);
                                $lastNames = normalizeValues($student['last_name']);
                                
                                foreach ($firstNames as $firstName):
                                    foreach ($lastNames as $lastName):
                            ?>
                                <tr>
                                    <!-- <td class="primary-key"><?= htmlspecialchars(string: $student['student_id']); ?></td> -->
                                    <td><?= htmlspecialchars(trim($firstName)); ?></td>
                                    <td><?= htmlspecialchars(trim($lastName)); ?></td>
                                </tr>
                            <?php 
                                    endforeach;
                                endforeach;
                            endforeach; 
                            ?>
                        </table>
                    </div>
                    <!-- Student Contacts Table -->
                    <div>
                        <h3>Student Contacts</h3>
                        <table>
                            <tr>
                                <th class="primary-key">contact_id</th>
                                <!-- <th class="foreign-key">student_id</th> -->
                                <th>email</th>
                                <th>phone</th>
                            </tr>
                            <?php
                            $contactId = 1;
                            foreach ($students as $student):
                                $emails = normalizeValues($student['email']);
                                $phones = normalizeValues($student['phone']);
                                
                                foreach ($emails as $email):
                                    foreach ($phones as $phone):
                            ?>
                                <tr>
                                    <td class="primary-key"><?= $contactId++; ?></td>
                                    <!-- <td class="foreign-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                    <td><?= htmlspecialchars(trim($email)); ?></td>
                                    <td><?= htmlspecialchars(trim($phone)); ?></td>
                                </tr>
                            <?php 
                                    endforeach;
                                endforeach;
                            endforeach; 
                            ?>
                        </table>
                    </div>

                    <!-- Enrollments Table -->
                    <div>
                        <h3>Enrollments</h3>
                        <table>
                            <tr>
                                <th class="primary-key">enrollment_id</th>
                                <!-- <th class="foreign-key">student_id</th> -->
                                <th>enrollment_number</th>
                            </tr>
                            <?php
                            $enrollmentId = 1;
                            foreach ($students as $student):
                                ?>
                                <tr>
                                    <td class="primary-key"><?= $enrollmentId++; ?></td>
                                    <!-- <td class="foreign-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                    <td><?= htmlspecialchars($student['enrollment_number']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>

                <!-- Third Normal Form (3NF) -->
                <h3>Third Normal Form (3NF)</h3>
                <div class="explanation">
                    <p></p>
                </div>

                <div class="tables-grid">
                    <!-- Students Table - 3NF -->
                    <div>
                        <h3>Students</h3>
                        <table>
                            <tr>
                                <!-- <th class="primary-key">student_id</th> -->
                                <th>first_name</th>
                                <th>last_name</th>
                            </tr>
                            <?php foreach ($students as $student): 
                                $firstNames = normalizeValues($student['first_name']);
                                $lastNames = normalizeValues($student['last_name']);
                                
                                foreach ($firstNames as $firstName):
                                    foreach ($lastNames as $lastName):
                            ?>
                                <tr>
                                    <!-- <td class="primary-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                    <td><?= htmlspecialchars(trim($firstName)); ?></td>
                                    <td><?= htmlspecialchars(trim($lastName)); ?></td>
                                </tr>
                            <?php 
                                    endforeach;
                                endforeach;
                            endforeach; 
                            ?>
                        </table>
                    </div>

                    <!-- Contacts Table - 3NF -->
                    <div>
                        <h3>Contacts</h3>
                        <table>
                            <tr>
                                <th class="primary-key">contact_id</th>
                                <!-- <th class="foreign-key">student_id</th> -->
                                <th>email</th>
                                <th>phone</th>
                            </tr>
                            <?php
                            $contactId = 1;
                            foreach ($students as $student):
                                $emails = normalizeValues($student['email']);
                                $phones = normalizeValues($student['phone']);
                                
                                // For 3NF, we'll treat email and phone as separate entities
                                foreach ($emails as $email):
                            ?>
                                <tr>
                                    <td class="primary-key"><?= $contactId++; ?></td>
                                    <!-- <td class="foreign-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                    <td><?= htmlspecialchars(trim($email)); ?></td>
                                    <td></td>
                                </tr>
                            <?php 
                                endforeach;
                                
                                foreach ($phones as $phone):
                            ?>
                                <tr>
                                    <td class="primary-key"><?= $contactId++; ?></td>
                                    <!-- <td class="foreign-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                    <td></td>
                                    <td><?= htmlspecialchars(trim($phone)); ?></td>
                                </tr>
                            <?php 
                                endforeach;
                            endforeach; 
                            ?>
                        </table>
                    </div>

                    <!-- Enrollments Table - 3NF -->
                    <div>
                        <h3>Enrollments</h3>
                        <table>
                            <tr>
                                <th class="primary-key">enrollment_id</th>
                                <!-- <th class="foreign-key">student_id</th> -->
                                <th>enrollment_number</th>
                            </tr>
                            <?php
                            $enrollmentId = 1;
                            foreach ($students as $student):
                                ?>
                                <tr>
                                    <td class="primary-key"><?= $enrollmentId++; ?></td>
                                    <!-- <td class="foreign-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                    <td><?= htmlspecialchars($student['enrollment_number']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                    <!-- Departments Table - 3NF -->
                    <div>
                        <h3>Departments</h3>
                        <table>
                            <tr>
                                <th class="primary-key">department_id</th>
                                <th>department_name</th>
                            </tr>
                            <?php foreach ($departments as $dept): ?>
                                <tr>
                                    <td class="primary-key"><?= htmlspecialchars($departmentIds[$dept]); ?></td>
                                    <td><?= htmlspecialchars($dept); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                    

                    <!-- Student-Department Relationship - 3NF -->
                    <div>
                        <h3>Student-Department</h3>
                        <table>
                            <tr>
                                <th class="primary-key">id</th>
                                <!-- <th class="foreign-key">student_id</th> -->
                                <th class="foreign-key">department_id</th>
                            </tr>
                            <?php
                            $relationId = 1;
                            foreach ($students as $student):
                                $depts = normalizeValues($student['department']);
                                foreach ($depts as $dept):
                                    $dept = trim($dept);
                                    if (!empty($dept)):
                                        ?>
                                        <tr>
                                            <td class="primary-key"><?= $relationId++; ?></td>
                                            <!-- <td class="foreign-key"><?= htmlspecialchars($student['student_id']); ?></td> -->
                                            <td class="foreign-key"><?= htmlspecialchars($departmentIds[$dept]); ?></td>
                                        </tr>
                                    <?php
                                    endif;
                                endforeach;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>

                <a href="rawdata.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Raw Data
                </a>
            </div>
        </div>
    </div>
</body>

</html>
<?php
// Include database connection
include 'connector.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['first_name'] as $index => $first_name) {
        $last_name = $_POST['last_name'][$index];
        $email = $_POST['email'][$index];
        $phone = $_POST['phone'][$index];
        $enrollment_number = $_POST['enrollment_number'][$index];
        $department = $_POST['department'][$index];

        // Prepare SQL statement to insert data into students table
        $sql = "INSERT INTO students (first_name, last_name, email, phone, enrollment_number, department) 
                VALUES (:first_name, :last_name, :email, :phone, :enrollment_number, :department)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone' => $phone,
            ':enrollment_number' => $enrollment_number,
            ':department' => $department
        ]);
    }
    // Redirect after successful insertion
    header('Location: rawdata.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        /* General reset and box-sizing for table */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #05a6a6 0%, #0575e6 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(90deg, #0693e3, #00d084);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
            border: 1px solid white;
        }

        h2 {
            margin: 0;
            font-weight: 600;
        }

        form {
            background: white;
            padding: 25px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            table-layout: fixed;
            /* Set table layout to fixed to prevent overlap */
        }

        th {
            background: linear-gradient(90deg, #0693e3, #00d084);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
            word-wrap: break-word;
        }

        td {
            padding: 12px 15px;
            /* Ensure proper padding for cells */
            border-bottom: 1px solid #eee;
            word-wrap: break-word;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #0693e3;
            box-shadow: 0 0 0 3px rgba(6, 147, 227, 0.1);
        }

        .btn-container {
            display: flex;
            gap: 15px;
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn i {
            margin-right: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .add-row {
            background: linear-gradient(90deg, #28a745, #218838);
        }

        .submit {
            background: linear-gradient(90deg, #0693e3, #00d084);
        }

        .delete {
            background: linear-gradient(90deg, #dc3545, #c82333);
        }

        .confirmation-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 400px;
            max-width: 90%;
            z-index: 100;
        }

        .confirmation-dialog p {
            margin-bottom: 25px;
            font-size: 1.1rem;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
            display: none;
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

        .row-actions {
            display: flex;
            justify-content: center;
        }

        #studentTable {
            border-color: none;
        }

        /* Optional: Make sure rows and inputs are responsive */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            input[type="text"] {
                padding: 10px;
            }
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
                <a href="rawdata.php"><i class="fas fa-table"></i> Records</a>
            </div>
        </nav>

        <div class="header">
            <h2><i class="fas fa-user-plus"></i> Register New Students</h2>
        </div>

        <form method="POST" action="" autocomplete="off">
            <table id="studentTable">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Enrollment Number</th>
                    <th>Department</th>
                    <th class="hidden">Action</th>
                </tr>
                <tr>
                    <td><input type="text" name="first_name[]" placeholder="Enter first name" required></td>
                    <td><input type="text" name="last_name[]" placeholder="Enter last name" required></td>
                    <td><input type="text" name="email[]" placeholder="Enter email"></td>
                    <td><input type="text" name="phone[]" placeholder="Enter phone"></td>
                    <td><input type="text" name="enrollment_number[]" placeholder="Enter enrollment number" required>
                    </td>
                    <td><input type="text" name="department[]" placeholder="Enter department"></td>
                    <td class="row-actions"></td>
                </tr>
            </table>

            <div class="btn-container">
                <button type="button" class="btn add-row" onclick="addRow()"><i class="fas fa-plus"></i> Add
                    Student</button>
                <button type="submit" class="btn submit"><i class="fas fa-save"></i> Save All</button>
            </div>
        </form>
    </div>

    <div id="overlay" class="overlay"></div>
    <div id="confirmationDialog" class="confirmation-dialog" style="display: none;">
        <p><i class="fas fa-exclamation-triangle"
                style="font-size: 2rem; color: #dc3545; margin-bottom: 15px;"></i><br>Are you sure you want to delete
            this student record?</p>
        <div class="btn-container">
            <button id="confirmDelete" class="btn delete"><i class="fas fa-trash"></i> Delete</button>
            <button id="cancelDelete" class="btn submit"><i class="fas fa-times"></i> Cancel</button>
        </div>
    </div>

    <script>
        function addRow() {
            let table = document.getElementById("studentTable");
            let newRow = table.insertRow();

            let columns = ["first_name", "last_name", "email", "phone", "enrollment_number", "department"];
            for (let i = 0; i < columns.length; i++) {
                let cell = newRow.insertCell(i);
                let input = document.createElement("input");
                input.type = "text";
                input.name = columns[i] + "[]";
                input.placeholder = `Enter ${columns[i].replace("_", " ")}`;
                if (i < 2 || i === 4) input.required = true;
                cell.appendChild(input);
            }

            // Add delete button
            let deleteCell = newRow.insertCell(columns.length);
            deleteCell.className = "row-actions";
            let deleteButton = document.createElement("button");
            deleteButton.innerHTML = '<i class="fas fa-trash"></i> Delete';
            deleteButton.className = "btn delete";
            deleteButton.style.padding = "8px 15px";
            deleteButton.onclick = function () {
                showConfirmationDialog(newRow.rowIndex);
            };
            deleteCell.appendChild(deleteButton);

            // Show the "Action" header if there is more than one row
            toggleActionHeader();

            // Focus on the first input of the new row
            newRow.cells[0].querySelector("input").focus();
        }

        function toggleActionHeader() {
            let table = document.getElementById("studentTable");
            let rows = table.rows.length;
            let actionHeader = document.querySelector("#studentTable th:last-child");

            if (rows > 2) { // 2 because the first row is the header
                actionHeader.classList.remove("hidden");
            } else {
                actionHeader.classList.add("hidden");
            }
        }

        function showConfirmationDialog(rowIndex) {
            let dialog = document.getElementById("confirmationDialog");
            let overlay = document.getElementById("overlay");
            dialog.style.display = "block";
            overlay.style.display = "block";

            document.getElementById("confirmDelete").onclick = function () {
                document.getElementById("studentTable").deleteRow(rowIndex);
                dialog.style.display = "none";
                overlay.style.display = "none";
                toggleActionHeader(); // Check if the "Action" header should be hidden after deletion
            };

            document.getElementById("cancelDelete").onclick = function () {
                dialog.style.display = "none";
                overlay.style.display = "none";
            };
        }

        // Initial check to hide the "Action" header if there is only one row
        document.addEventListener("DOMContentLoaded", function () {
            toggleActionHeader();
        });
    </script>
</body>

</html>
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "30mm");
if (!$conn) { exit("DB Error"); }

$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
$role  = $_SESSION['role'] ?? '';
$Email = $_SESSION['Email'] ?? '';

// Build SQL query
if ($role === 'admin') {
    // Admin sees all pending
    $sql = "SELECT * FROM application 
            WHERE status = 'pending'
            AND (Name LIKE '%$search%' OR Rollno LIKE '%$search%')";
} else {
    // Users see all, but action limited to own
    $sql = "SELECT * FROM application 
            WHERE status = 'pending'
            AND (Name LIKE '%$search%' OR Rollno LIKE '%$search%')";
}

$result = mysqli_query($conn, $sql);
$sno = 1;

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['Name']; // Using Name as ID per your setup

        echo "<tr>
                <td>{$sno}</td>
                <td>" . htmlspecialchars($row['Name']) . "</td>
                <td>" . htmlspecialchars($row['Rollno']) . "</td>
                <td>" . htmlspecialchars($row['Email']) . "</td>
                <td>" . htmlspecialchars($row['Department']) . "</td>
                <td>" . htmlspecialchars($row['Grievances']) . "</td>
                <td>";

        // ------------------------
        // USER ACTION LOGIC
        // ------------------------
        if ($role === 'user') {
            if ($Email === $row['Email']) {
                // Own grievance: show Edit/Delete
                echo "
                    <a href='edit.php?id={$id}' class='btn btn-warning btn-sm'>
                        <i class='bi bi-pencil-square'> Edit</i>
                    </a>
                    <a href='delete.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>
                        <i class='bi bi-trash'> Delete</i>
                    </a>";
            } else {
                // Other's grievance: no action
                echo "<span class='text-muted fst-italic'>You cannot take action</span>";
            }
        }

        // ------------------------
        // ADMIN ACTION LOGIC
        // ------------------------
        else if ($role === 'admin') {
            echo "
                <a href='mark_solved.php?id={$id}&status=resolved' class='btn btn-success btn-sm' title='Mark as Resolved'>
                    <i class='bi bi-check-circle'> Approve</i>
                </a>
                <a href='mark_solved.php?id={$id}&status=denied' class='btn btn-danger btn-sm' title='Deny' onclick='return confirm(\"Deny this grievance?\")'>
                    <i class='bi bi-ban'> Rejected</i>
                </a>";
        }

        echo "</td></tr>";
        $sno++;
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No pending grievances found.</td></tr>";
}

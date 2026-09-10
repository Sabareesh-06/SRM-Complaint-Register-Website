<?php
$conn = mysqli_connect("localhost", "root", "", "30mm");
if (!$conn) { 
    exit("DB Error"); 
}

$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, trim($_POST['search'])) : "";

// ✅ Use CORRECT column names in WHERE clause
$sql = "SELECT * FROM application
        WHERE name LIKE '%$search%'
           OR email LIKE '%$search%'
           OR rollno LIKE '%$search%'
           OR department LIKE '%$search%'        -- was 'dept'
           OR grievances LIKE '%$search%'         -- was 'address'
        ORDER BY name ASC";

$result = mysqli_query($conn, $sql);

$sno = 1;

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Use correct column for ID — 'name' is fine if it's unique
        $id = $row['name']; 

        echo "<tr>";
        echo "<td>" . $sno++ . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rollno']) . "</td>";     // Roll No
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['department']) . "</td>"; // ✅ was 'dept'
        echo "<td>" . htmlspecialchars($row['grievances']) . "</td>"; // ✅ was 'address'
        echo "<td>
                <a href='edit.php?id=" . urlencode($id) . "' class='btn btn-warning btn-sm'>
                    <i class='bi bi-pencil-square'> Edit</i>
                </a>
                <a href='delete.php?id=" . urlencode($id) . "'
                   onclick=\"return confirm('Delete record for " . addslashes($id) . "?')\"
                   class='btn btn-danger btn-sm'>
                    <i class='bi bi-trash3'> Delete</i>
                </a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
}

mysqli_close($conn);
?>
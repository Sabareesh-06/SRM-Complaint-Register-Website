<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "30mm");
if (!$conn) { exit("DB Error"); }

$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, trim($_POST['search'])) : "";

// Query to show BOTH resolved and denied
$sql = "SELECT * FROM application
        WHERE (status = 'resolved' OR status = 'denied')
          AND (
            Name LIKE '%$search%' OR
            Email LIKE '%$search%' OR
            Rollno LIKE '%$search%' OR
            Department LIKE '%$search%'
          )
        ORDER BY Name ASC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Determine badge color based on status
        $badgeClass = ($row['status'] == 'denied') ? 'bg-danger' : 'bg-success';
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Rollno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Department']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Grievances']) . "</td>";
        echo "<td>";
        echo "<span class='badge $badgeClass'>" . htmlspecialchars(ucfirst($row['status'])) . "</span> ";
        if ($_SESSION['role'] == 'admin') 
        {
            echo "<form method='POST' action='delete.php' style='display:inline;' 
            onsubmit=\"return confirm('Permanently delete this record?');\">
            <input type='hidden' name='id' value='" . htmlspecialchars($row['Name']) . "' /> 
            <button type='submit' class='btn btn-danger btn-sm'>
            <i class='bi bi-trash'> Delete</i>
            </button>
            </form>";
        }

        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No processed grievances found.</td></tr>";
}
mysqli_close($conn);
?>
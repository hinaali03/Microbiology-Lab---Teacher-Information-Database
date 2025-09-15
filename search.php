<?php
include('db_connect.php');

$searchQuery = $_GET['searchQuery'];
$filter = $_GET['filter'];

$sql = "";

if ($filter == "all") {
    // Search both Professor names and Research Domains
    $sql = "SELECT p.P_ID, p.P_Name AS Professor, p.P_Rank AS Position, p.Education, p.PhD_Thesis, p.Research_Domain AS 'Research Domain', p.Email, p.Contact,
                   i.Total_Publications, i.Recent_Publication, i.Books, i.Links, 
                   f.Total_BS_Student, f.Total_MS_Student, f.Total_PhD_Student
            FROM professors_info p
            JOIN publications i ON p.P_ID = i.P_ID
            JOIN fyp f ON i.P_ID = f.P_ID
            WHERE p.P_Name LIKE '%$searchQuery%' 
               OR p.P_Rank LIKE '%$searchQuery%' 
               OR p.Research_Domain LIKE '%$searchQuery%'";  // Searching by Research Domain
} else if ($filter == "Professors") {
    $sql = "SELECT  p.P_ID, p.P_Name AS Professor, p.P_Rank AS Position, p.Education, p.PhD_Thesis, p.Research_Domain AS 'Research Domain', p.Email, p.Contact 
    FROM professors_info p WHERE P_Name LIKE '%$searchQuery%' 
            OR Research_Domain LIKE '%$searchQuery%'";  // Searching by Research Domain
} else if ($filter == "Publications") {
    $sql = "SELECT p.P_ID, p.P_Name AS Professor, i.Total_Publications, i.Recent_Publication, i.Books, i.Links 
            FROM professors_info p
            JOIN publications i ON p.P_ID = i.P_ID
            WHERE p.P_Name LIKE '%$searchQuery%' 
            OR i.Recent_Publication LIKE '%$searchQuery%'";  // Searching by Recent Publication (if needed)
} else if ($filter == "FYP") {
    $sql = "SELECT p.P_ID, p.P_Name AS Professor, p.Research_Domain, f.Total_BS_Student AS 'BS Students', f.Total_MS_Student AS 'MS Students', f.Total_PhD_Student AS 'PhD Students' 
            FROM professors_info p 
            JOIN fyp f ON p.P_ID = f.P_ID 
            WHERE p.P_Name LIKE '%$searchQuery%' 
            OR p.Research_Domain LIKE '%$searchQuery%'";  // Searching by Research Domain
}

$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Start the table
    echo "<table>";

    // Fetch the column names from the result set
    $columns = $result->fetch_fields();

    // Loop through each column and create the table header dynamically
    echo "<tr>";
    foreach ($columns as $column) {
        // Dynamically generate column headers
        echo "<th>" . htmlspecialchars($column->name) . "</th>";
    }
    echo "</tr>";

    // Loop through each row and display data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($columns as $column) {
            // Dynamically generate the row content
            $cellData = isset($row[$column->name]) ? htmlspecialchars($row[$column->name]) : 'N/A';

            // Check if the column is 'Links' and if the content is a valid URL
            if ($column->name == 'Links' && filter_var($cellData, FILTER_VALIDATE_URL)) {
                // If it's a valid URL, make it a clickable link
                $cellData = "<a href=\"$cellData\" target=\"_blank\">$cellData</a>";
            }

            echo "<td>" . $cellData . "</td>";
        }
        echo "</tr>";
    }

    // Close the table tag
    echo "</table>";
} else {
    echo "<p>No results found</p>";
}
?>

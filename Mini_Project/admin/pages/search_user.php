<?php
include_once "../config/dbconnect.php"; 

$searchQuery = isset($_POST['query']) ? trim($_POST['query']) : "";
$searchQueryLower = strtolower($searchQuery);
$searchParam = "%{$searchQuery}%";

$filterStatus = null;
$params = [];
$types = "";

// Check for specific keyword filters
if ($searchQueryLower === "active") {
    $filterStatus = 1;
    $searchQuery = ""; // clear general search
} elseif ($searchQueryLower === "deactivate" || $searchQueryLower === "deactivated") {
    $filterStatus = 0;
    $searchQuery = ""; // clear general search
}

// Start base SQL
$sql = "SELECT * FROM user_form WHERE 1=1";

// If general search is present
if (!empty($searchQuery)) {
    $sql .= " AND (
        user_id LIKE ? OR 
        name LIKE ? OR 
        email LIKE ? OR 
        phone LIKE ? OR 
        created_on LIKE ?
    )";
    $params = array_fill(0, 5, $searchParam);
    $types = str_repeat('s', 5);
}

// If active/deactivated filter is applied
if ($filterStatus !== null) {
    $sql .= " AND is_active = ?";
    $params[] = $filterStatus;
    $types .= "i";
}

$sql .= " ORDER BY user_id ASC LIMIT 50";

// Prepare and execute
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Output
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profileImage = !empty($row['profile_image']) 
            ? "../../User/images/" . htmlspecialchars($row['profile_image']) 
            : "../../User/images/image.jpg";

        $isActive = (int)$row['is_active'];
        $btnClass = $isActive ? 'danger' : 'success';
        $btnText = $isActive ? 'Deactivate' : 'Activate';

        echo "<tr>
                <td>{$row['user_id']}</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>{$row['created_on']}</td>
                <td>
                    <img src='{$profileImage}' 
                         width='50' 
                         class='profile-img' 
                         onerror=\"this.onerror=null; this.src='../../User/images/image.jpg';\">
                </td>
                <td>
                    <button class='btn btn-{$btnClass} btn-toggle' 
                            data-id='{$row['user_id']}' 
                            data-status='{$isActive}' 
                            title='{$btnText} user'>
                        {$btnText}
                    </button>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
}

$stmt->close();
?>

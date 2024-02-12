<?php
session_start();
include './assets/php/config.php';
function getUserId()
{
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}
function updateLevel($userId, $levelNumber, $points)
{
    global $conn;
    $columnName = "level_" . $levelNumber;
    $nextLevel = $levelNumber + 1;
    $nextColumnName = "level_" . $nextLevel;
    if ($columnName === "level_9") {
        $sql = "UPDATE users SET $columnName = ? WHERE id = ?";
    } else {
        $sql = "UPDATE users SET $columnName = ?, $nextColumnName = CASE WHEN $nextColumnName = 69 THEN 96 ELSE $nextColumnName END WHERE id = ?";
    }
    $points = (int) $points;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $points, $userId);
    if ($stmt->execute()) {
        echo "\033[32mRecord updated successfully.\033[0m\n";
        if ($columnName === "level_9") {
            echo "\033[32mSpecial action for 'level_9'.\033[0m\n";
        }
    } else {
        echo "\033[31mError updating record: \033[0m\n" . $stmt->error;
    }
    $stmt->close();
}
if (isset($_GET['id'], $_GET['points'])) {
    $levelNumber = $_GET['id'];
    $points = $_GET['points'];
    $userId = getUserId();
    if ($userId) {
        updateLevel($userId, $levelNumber, $points);
        exit();
    } else {
        echo "\033[31mThe user is not logged in.\033[0m\n";
    }
} else {
    echo "\033[31mInvalid or missing 'id' or 'points' parameter in URL.\033[0m\n";
}
$conn->close();
?>
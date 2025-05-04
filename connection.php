<?php
$connection = mysqli_connect('localhost','root','','db_tourph');
// Fetch package availability from database
$packageAvailability = array();
$query = "SELECT package_name, availability FROM packages";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $packageAvailability[$row['package_name']] = $row['availability']; // Fixed this line
}
?>
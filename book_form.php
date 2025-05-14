<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get all form data
$user_id = $_SESSION['user_id'];
$phone = mysqli_real_escape_string($connection, $_POST['phone']);
$address = mysqli_real_escape_string($connection, $_POST['address']);
$package = mysqli_real_escape_string($connection, $_POST['package']);
$guests = intval($_POST['guests']);
$arrivals = $_POST['arrivals'];
$leaving = $_POST['leaving'];

// Debug: Check if we're receiving the package name
error_log("Attempting to book package: " . $package);

// Start transaction
mysqli_begin_transaction($connection);

try {
    // 1. Verify package exists (no availability check needed)
    $check_query = "SELECT 1 FROM packages WHERE package_name = '$package'";
    error_log("Checking package exists with query: " . $check_query);
    $check_result = mysqli_query($connection, $check_query);
    
    if (!$check_result || mysqli_num_rows($check_result) === 0) {
        throw new Exception("Package '$package' not found in database");
    }

    // 2. Insert the booking record
    $insert_query = "INSERT INTO booking (user_id, phone, address, package, guests, arrivals, leaving) 
                    VALUES ($user_id, '$phone', '$address', '$package', $guests, '$arrivals', '$leaving')";
    error_log("Inserting booking with query: " . $insert_query);
    
    if (!mysqli_query($connection, $insert_query)) {
        throw new Exception("Booking failed: " . mysqli_error($connection));
    }

    // Commit transaction if successful
    mysqli_commit($connection);
    error_log("Booking successful for $package");
    
    echo "<script>
        alert('Booking successful!');
        window.location.href = 'index.php';
    </script>";
    exit;

} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($connection);
    error_log("Booking error: " . $e->getMessage());
    
    echo "<script>
        alert('Booking failed: " . addslashes($e->getMessage()) . "');
        window.history.back();
    </script>";
    exit;
} finally {
    // Close connection
    mysqli_close($connection);
}
?>
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
    // 1. First verify package exists and get current availability
    $check_query = "SELECT availability FROM packages WHERE package_name = '$package' FOR UPDATE";
    error_log("Checking availability with query: " . $check_query);
    $check_result = mysqli_query($connection, $check_query);
    
    if (!$check_result || mysqli_num_rows($check_result) === 0) {
        throw new Exception("Package '$package' not found in database");
    }
    
    $package_data = mysqli_fetch_assoc($check_result);
    $current_availability = $package_data['availability'];
    error_log("Current availability for $package: $current_availability");
    
    if ($current_availability <= 0) {
        throw new Exception("Sorry, '$package' is no longer available");
    }

    // 2. Insert the booking record
    $insert_query = "INSERT INTO booking (user_id, phone, address, package, guests, arrivals, leaving) 
                    VALUES ($user_id, '$phone', '$address', '$package', $guests, '$arrivals', '$leaving')";
    error_log("Inserting booking with query: " . $insert_query);
    
    if (!mysqli_query($connection, $insert_query)) {
        throw new Exception("Booking failed: " . mysqli_error($connection));
    }

    // 3. Update package availability (decrement by 1)
    $update_query = "UPDATE packages SET availability = availability - 1 WHERE package_name = '$package'";
    error_log("Updating availability with query: " . $update_query);
    
    if (!mysqli_query($connection, $update_query)) {
        throw new Exception("Availability update failed: " . mysqli_error($connection));
    }
    
    // Verify the update affected a row
    if (mysqli_affected_rows($connection) === 0) {
        throw new Exception("No rows updated - package name might not match exactly");
    }

    // Commit transaction if all successful
    mysqli_commit($connection);
    error_log("Booking and availability update successful for $package");
    
    echo "<script>
        alert('Booking successful! Package availability has been updated.');
        window.location.href = 'index.php';
    </script>";
    exit;

} catch (Exception $e) {
    // Rollback on any error
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
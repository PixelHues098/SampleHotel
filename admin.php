<?php
session_start();
include('connection.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Set default active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'users';

// Pagination settings
$records_per_page = 5;

// Search functionality
$search_query = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = mysqli_real_escape_string($connection, $_GET['search']);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        // Add user logic
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        mysqli_query($connection, $query);
        $success = "User added successfully!";
    }

    if (isset($_POST['edit_user'])) {
        // Edit user logic
        $id = $_POST['user_id'];
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        $query = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
        mysqli_query($connection, $query);
        $success = "User updated successfully!";
    }

    if (isset($_POST['delete_user'])) {
        // Delete user logic
        $id = $_POST['user_id'];
        $query = "DELETE FROM users WHERE id=$id";
        mysqli_query($connection, $query);
        $success = "User deleted successfully!";
    }

    if (isset($_POST['edit_package'])) {
        // Edit package logic
        $id = $_POST['package_id'];
        $package_name = mysqli_real_escape_string($connection, $_POST['package_name']);
        $description = mysqli_real_escape_string($connection, $_POST['description']);
        $price = $_POST['price'];
        $availability = isset($_POST['availability']) ? 1 : 0;

        $query = "UPDATE packages SET package_name='$package_name', description='$description', price=$price, availability=$availability WHERE id=$id";
        mysqli_query($connection, $query);
        $success = "Package updated successfully!";
    }

    if (isset($_POST['delete_package'])) {
        // Delete package logic
        $id = $_POST['package_id'];
        $query = "DELETE FROM packages WHERE id=$id";
        mysqli_query($connection, $query);
        $success = "Package deleted successfully!";
    }

    if (isset($_POST['add_booking'])) {
        // Add booking logic
        $user_id = $_POST['user_id'];
        $phone = mysqli_real_escape_string($connection, $_POST['phone']);
        $address = mysqli_real_escape_string($connection, $_POST['address']);
        $package = mysqli_real_escape_string($connection, $_POST['package']);
        $guests = $_POST['guests'];
        $arrivals = $_POST['arrivals'];
        $leaving = $_POST['leaving'];
        $payment_method = mysqli_real_escape_string($connection, $_POST['payment_method']);
        $reference_number = isset($_POST['reference_number']) ? mysqli_real_escape_string($connection, $_POST['reference_number']) : '';
        $status = 'Pending';

        // Get package price
        $price_query = mysqli_query($connection, "SELECT price FROM packages WHERE package_name = '$package'");
        $price_row = mysqli_fetch_assoc($price_query);
        $total_amount = $price_row['price'] * $guests;

        // Get payment details based on method
        $payment_details = [];
        switch ($payment_method) {
            case 'GCash':
                $payment_details = [
                    'number' => mysqli_real_escape_string($connection, $_POST['gcash_number']),
                    'name' => mysqli_real_escape_string($connection, $_POST['gcash_name'])
                ];
                break;
            case 'Maya':
                $payment_details = [
                    'number' => mysqli_real_escape_string($connection, $_POST['maya_number']),
                    'name' => mysqli_real_escape_string($connection, $_POST['maya_name'])
                ];
                break;
            case 'PayPal':
                $payment_details = [
                    'email' => mysqli_real_escape_string($connection, $_POST['paypal_email']),
                    'name' => mysqli_real_escape_string($connection, $_POST['paypal_name'])
                ];
                break;
        }

        $payment_details_json = json_encode($payment_details);

        $query = "INSERT INTO booking (user_id, phone, address, package, guests, arrivals, leaving, payment_method, payment_details, reference_number, total_amount, status) 
                  VALUES ($user_id, '$phone', '$address', '$package', $guests, '$arrivals', '$leaving', '$payment_method', '$payment_details_json', '$reference_number', $total_amount, '$status')";
        mysqli_query($connection, $query);
        $success = "Booking added successfully!";
    }

    if (isset($_POST['edit_booking'])) {
        // Edit booking logic
        $id = $_POST['booking_id'];
        $user_id = $_POST['user_id'];
        $phone = mysqli_real_escape_string($connection, $_POST['phone']);
        $address = mysqli_real_escape_string($connection, $_POST['address']);
        $package = mysqli_real_escape_string($connection, $_POST['package']);
        $guests = $_POST['guests'];
        $arrivals = $_POST['arrivals'];
        $leaving = $_POST['leaving'];
        $payment_method = mysqli_real_escape_string($connection, $_POST['payment_method']);
        $reference_number = isset($_POST['reference_number']) ? mysqli_real_escape_string($connection, $_POST['reference_number']) : '';
        $status = $_POST['status'];

        // Get package price
        $price_query = mysqli_query($connection, "SELECT price FROM packages WHERE package_name = '$package'");
        $price_row = mysqli_fetch_assoc($price_query);
        $total_amount = $price_row['price'] * $guests;

        // Get payment details based on method
        $payment_details = [];
        switch ($payment_method) {
            case 'GCash':
                $payment_details = [
                    'number' => mysqli_real_escape_string($connection, $_POST['gcash_number']),
                    'name' => mysqli_real_escape_string($connection, $_POST['gcash_name'])
                ];
                break;
            case 'Maya':
                $payment_details = [
                    'number' => mysqli_real_escape_string($connection, $_POST['maya_number']),
                    'name' => mysqli_real_escape_string($connection, $_POST['maya_name'])
                ];
                break;
            case 'PayPal':
                $payment_details = [
                    'email' => mysqli_real_escape_string($connection, $_POST['paypal_email']),
                    'name' => mysqli_real_escape_string($connection, $_POST['paypal_name'])
                ];
                break;
        }

        $payment_details_json = json_encode($payment_details);

        $query = "UPDATE booking SET 
                  user_id=$user_id, 
                  phone='$phone', 
                  address='$address', 
                  package='$package', 
                  guests=$guests, 
                  arrivals='$arrivals', 
                  leaving='$leaving',
                  payment_method='$payment_method',
                  payment_details='$payment_details_json',
                  reference_number='$reference_number',
                  total_amount=$total_amount,
                  status='$status'
                  WHERE id=$id";
        mysqli_query($connection, $query);
        $success = "Booking updated successfully!";
    }

    if (isset($_POST['delete_booking'])) {
        // Delete booking logic
        $id = $_POST['booking_id'];
        $query = "DELETE FROM booking WHERE id=$id";
        mysqli_query($connection, $query);
        $success = "Booking deleted successfully!";
    }

    if (isset($_POST['update_booking_status'])) {
        // Update booking status logic
        $id = $_POST['booking_id'];
        $status = $_POST['status'];

        $query = "UPDATE booking SET status='$status' WHERE id=$id";
        mysqli_query($connection, $query);
        $success = "Booking status updated successfully!";
    }
}

// Fetch data with pagination and search for each tab
// ==================================================
// USERS TAB
$user_page = isset($_GET['user_page']) ? (int)$_GET['user_page'] : 1;
$user_offset = ($user_page - 1) * $records_per_page;

$user_query = "SELECT * FROM users";
if ($search_query && $active_tab == 'users') {
    $user_query .= " WHERE name LIKE '%$search_query%' OR email LIKE '%$search_query%'";
}
$user_query .= " LIMIT $user_offset, $records_per_page";

$users = mysqli_query($connection, $user_query);
$total_users = mysqli_query($connection, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($total_users)['total'];
$total_user_pages = ceil($total_users / $records_per_page);

// PACKAGES TAB
$package_page = isset($_GET['package_page']) ? (int)$_GET['package_page'] : 1;
$package_offset = ($package_page - 1) * $records_per_page;

$package_query = "SELECT * FROM packages";
if ($search_query && $active_tab == 'packages') {
    $package_query .= " WHERE package_name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
}
$package_query .= " LIMIT $package_offset, $records_per_page";

$packages = mysqli_query($connection, $package_query);
$total_packages = mysqli_query($connection, "SELECT COUNT(*) as total FROM packages");
$total_packages = mysqli_fetch_assoc($total_packages)['total'];
$total_package_pages = ceil($total_packages / $records_per_page);

// BOOKINGS TAB
$booking_page = isset($_GET['booking_page']) ? (int)$_GET['booking_page'] : 1;
$booking_offset = ($booking_page - 1) * $records_per_page;

$booking_query = "SELECT booking.*, users.name as user_name FROM booking LEFT JOIN users ON booking.user_id = users.id";
if ($search_query && $active_tab == 'bookings') {
    $booking_query .= " WHERE users.name LIKE '%$search_query%' OR booking.package LIKE '%$search_query%' OR booking.phone LIKE '%$search_query%' OR booking.payment_method LIKE '%$search_query%' OR booking.status LIKE '%$search_query%'";
}
$booking_query .= " ORDER BY booking.created_at DESC LIMIT $booking_offset, $records_per_page";

$bookings = mysqli_query($connection, $booking_query);
$total_bookings = mysqli_query($connection, "SELECT COUNT(*) as total FROM booking");
$total_bookings = mysqli_fetch_assoc($total_bookings)['total'];
$total_booking_pages = ceil($total_bookings / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casa Luna Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            padding: 20px;
        }

        .search-box {
            max-width: 300px;
        }

        .pagination .page-item.active .page-link {
            background-color: lightblue;
            border-color: #343a40;
        }

        .pagination .page-link {
            color: #343a40;
        }

        /* Calendar Styles */
        #calendar-container {
            margin-top: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
        }

        .day-header {
            padding: 10px;
            font-weight: bold;
            border-right: 1px solid #fff;
        }

        .day-header:last-child {
            border-right: none;
        }

        .calendar-day {
            min-height: 120px;
            padding: 5px;
            border-right: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
            position: relative;
        }

        .calendar-day:nth-child(7n) {
            border-right: none;
        }

        .calendar-day.other-month {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .calendar-day.today {
            background-color: #e9f7fe;
        }

        .booking-event {
            font-size: 12px;
            padding: 2px 5px;
            margin: 2px 0;
            background-color: #4e73df;
            color: white;
            border-radius: 3px;
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .booking-event:hover {
            opacity: 0.9;
        }

        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Payment Status Badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Payment method icons */
        .payment-method-icon {
            width: 24px;
            height: 24px;
            margin-right: 5px;
            vertical-align: middle;
        }

        /* Payment details modal */
        .payment-details-table th {
            width: 30%;
        }

        /* Quick status update dropdown */
        .status-dropdown {
            min-width: 120px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_tab == 'users' ? 'active' : ''; ?>" href="?tab=users">
                                <i class="bi bi-people me-2"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_tab == 'packages' ? 'active' : ''; ?>"
                                href="?tab=packages">
                                <i class="bi bi-box-seam me-2"></i>Packages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_tab == 'bookings' ? 'active' : ''; ?>"
                                href="?tab=bookings">
                                <i class="bi bi-calendar-check me-2"></i>Bookings
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link <?php echo $active_tab == 'calendar' ? 'active' : ''; ?>" href="?tab=calendar">
                                <i class="bi bi-calendar-date me-2"></i>Calendar View
                            </a>
                        </li> -->
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class>Casa Luna Admin Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="btn btn-sm btn-outline-secondary">Welcome,
                                <?php echo $_SESSION['admin_username']; ?></span>
                        </div>
                    </div>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <!-- Tab Content Container -->
                <div class="tab-content">
                    <!-- Users Tab -->
                    <div class="tab-pane <?php echo $active_tab == 'users' ? 'active' : ''; ?>" id="users">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Users Management</h3>
                            <div>
                                <form method="GET" class="d-flex">
                                    <input type="hidden" name="tab" value="users">
                                    <div class="input-group search-box">
                                        <input type="text" class="form-control" name="search" placeholder="Search users..." value="<?php echo $active_tab == 'users' ? htmlspecialchars($search_query) : ''; ?>">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-plus"></i> Add User
                        </button>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = mysqli_fetch_assoc($users)): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo $user['created_at']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal<?php echo $user['id']; ?>">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal<?php echo $user['id']; ?>">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Edit User Modal -->
                                        <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1"
                                            aria-labelledby="editUserModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="admin.php?tab=users">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="user_id"
                                                                value="<?php echo $user['id']; ?>">
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Name</label>
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name"
                                                                    value="<?php echo htmlspecialchars($user['name']); ?>"
                                                                    required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="email" class="form-control" id="email"
                                                                    name="email"
                                                                    value="<?php echo htmlspecialchars($user['email']); ?>"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="edit_user"
                                                                class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete User Modal -->
                                        <div class="modal fade" id="deleteUserModal<?php echo $user['id']; ?>" tabindex="-1"
                                            aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="admin.php?tab=users">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="user_id"
                                                                value="<?php echo $user['id']; ?>">
                                                            <p>Are you sure you want to delete this user:
                                                                <?php echo htmlspecialchars($user['name']); ?>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" name="delete_user"
                                                                class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                            <!-- Users Pagination -->
                            <nav aria-label="Users pagination">
                                <ul class="pagination justify-content-center">
                                    <?php if ($user_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?tab=users&user_page=<?php echo $user_page - 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_user_pages; $i++): ?>
                                        <li class="page-item <?php echo $i == $user_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?tab=users&user_page=<?php echo $i; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($user_page < $total_user_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?tab=users&user_page=<?php echo $user_page + 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                            <div class="text-muted text-center">
                                Showing <?php echo ($user_offset + 1) . ' to ' . min($user_offset + $records_per_page, $total_users); ?> of <?php echo $total_users; ?> users
                            </div>
                        </div>
                    </div>

                    <!-- Packages Tab -->
                    <div class="tab-pane <?php echo $active_tab == 'packages' ? 'active' : ''; ?>" id="packages">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Packages Management</h3>
                            <div>
                                <form method="GET" class="d-flex">
                                    <input type="hidden" name="tab" value="packages">
                                    <div class="input-group search-box">
                                        <input type="text" class="form-control" name="search" placeholder="Search packages..." value="<?php echo $active_tab == 'packages' ? htmlspecialchars($search_query) : ''; ?>">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Package Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Availability</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($package = mysqli_fetch_assoc($packages)): ?>
                                        <tr>
                                            <td><?php echo $package['id']; ?></td>
                                            <td><?php echo htmlspecialchars($package['package_name']); ?></td>
                                            <td><?php echo htmlspecialchars($package['description']); ?></td>
                                            <td><?php echo number_format($package['price'], 2); ?></td>
                                            <td><?php echo $package['availability'] ? 'Available' : 'Not Available'; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editPackageModal<?php echo $package['id']; ?>">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deletePackageModal<?php echo $package['id']; ?>">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Edit Package Modal -->
                                        <div class="modal fade" id="editPackageModal<?php echo $package['id']; ?>"
                                            tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="admin.php?tab=packages">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="package_id"
                                                                value="<?php echo $package['id']; ?>">
                                                            <div class="mb-3">
                                                                <label for="package_name" class="form-label">Package
                                                                    Name</label>
                                                                <input type="text" class="form-control" id="package_name"
                                                                    name="package_name"
                                                                    value="<?php echo htmlspecialchars($package['package_name']); ?>"
                                                                    required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description"
                                                                    class="form-label">Description</label>
                                                                <textarea class="form-control" id="description"
                                                                    name="description" rows="3"
                                                                    required><?php echo htmlspecialchars($package['description']); ?></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="price" class="form-label">Price</label>
                                                                <input type="number" step="0.01" class="form-control"
                                                                    id="price" name="price"
                                                                    value="<?php echo $package['price']; ?>" required>
                                                            </div>
                                                            <div class="mb-3 form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="availability" name="availability" <?php echo $package['availability'] ? 'checked' : ''; ?>>
                                                                <label class="form-check-label"
                                                                    for="availability">Available</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="edit_package"
                                                                class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Package Modal -->
                                        <div class="modal fade" id="deletePackageModal<?php echo $package['id']; ?>"
                                            tabindex="-1" aria-labelledby="deletePackageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deletePackageModalLabel">Delete Package
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="admin.php?tab=packages">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="package_id"
                                                                value="<?php echo $package['id']; ?>">
                                                            <p>Are you sure you want to delete this package:
                                                                <?php echo htmlspecialchars($package['package_name']); ?>?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" name="delete_package"
                                                                class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                            <!-- Packages Pagination -->
                            <nav aria-label="Packages pagination">
                                <ul class="pagination justify-content-center">
                                    <?php if ($package_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?tab=packages&package_page=<?php echo $package_page - 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_package_pages; $i++): ?>
                                        <li class="page-item <?php echo $i == $package_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?tab=packages&package_page=<?php echo $i; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($package_page < $total_package_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?tab=packages&package_page=<?php echo $package_page + 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                            <div class="text-muted text-center">
                                Showing <?php echo ($package_offset + 1) . ' to ' . min($package_offset + $records_per_page, $total_packages); ?> of <?php echo $total_packages; ?> packages
                            </div>
                        </div>
                    </div>

                    <!-- Bookings Tab -->
                    <div class="tab-pane <?php echo $active_tab == 'bookings' ? 'active' : ''; ?>" id="bookings">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Bookings Management</h3>
                            <div>
                                <form method="GET" class="d-flex">
                                    <input type="hidden" name="tab" value="bookings">
                                    <div class="input-group search-box">
                                        <input type="text" class="form-control" name="search" placeholder="Search bookings..." value="<?php echo $active_tab == 'bookings' ? htmlspecialchars($search_query) : ''; ?>">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                            <i class="bi bi-plus"></i> Add Booking
                        </button>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Package</th>
                                        <th>Dates</th>
                                        <th>Payment</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = mysqli_fetch_assoc($bookings)):
                                        $payment_details = json_decode($booking['payment_details'], true);
                                    ?>
                                        <tr>
                                            <td><?php echo $booking['id']; ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($booking['user_name']); ?><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($booking['phone']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                            <td>
                                                <?php echo date('M j', strtotime($booking['arrivals'])); ?> -
                                                <?php echo date('M j, Y', strtotime($booking['leaving'])); ?><br>
                                                <small><?php echo $booking['guests']; ?> guest(s)</small>
                                            </td>
                                            <td>
                                                <?php if ($booking['payment_method'] == 'GCash'): ?>
                                                    <img src="images/GCash_logo.png" class="payment-method-icon" alt="GCash">
                                                <?php elseif ($booking['payment_method'] == 'Maya'): ?>
                                                    <img src="images/Paymaya_logo.png" class="payment-method-icon" alt="Maya">
                                                <?php elseif ($booking['payment_method'] == 'PayPal'): ?>
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/1200px-PayPal.svg.png" class="payment-method-icon" alt="PayPal">
                                                <?php endif; ?>
                                                <?php echo $booking['payment_method']; ?>
                                            </td>
                                            <td>₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                                                    <?php echo $booking['status']; ?>
                                                </span>
                                                <?php if ($booking['reference_number']): ?>
                                                    <br><small>Ref: <?php echo $booking['reference_number']; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionDropdown<?php echo $booking['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown<?php echo $booking['id']; ?>">
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editBookingModal<?php echo $booking['id']; ?>">
                                                                <i class="bi bi-pencil"></i> Edit Booking
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#paymentDetailsModal<?php echo $booking['id']; ?>">
                                                                <i class="bi bi-credit-card"></i> Payment Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="admin.php?tab=bookings" class="mb-0">
                                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                                <div class="px-3 py-1">
                                                                    <label class="form-label small">Quick Status Update</label>
                                                                    <select name="status" class="form-select form-select-sm status-dropdown" onchange="this.form.submit()">
                                                                        <option value="Pending" <?php echo $booking['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                        <option value="Paid" <?php echo $booking['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                                                        <option value="Completed" <?php echo $booking['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                                        <option value="Cancelled" <?php echo $booking['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                                    </select>
                                                                    <input type="hidden" name="update_booking_status" value="1">
                                                                </div>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteBookingModal<?php echo $booking['id']; ?>">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Payment Details Modal -->
                                        <div class="modal fade" id="paymentDetailsModal<?php echo $booking['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Payment Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table payment-details-table">
                                                            <tr>
                                                                <th>Payment Method:</th>
                                                                <td>
                                                                    <?php if ($booking['payment_method'] == 'GCash'): ?>
                                                                        <img src="images/GCash_logo.png" class="payment-method-icon" alt="GCash">
                                                                    <?php elseif ($booking['payment_method'] == 'Maya'): ?>
                                                                        <img src="images/Paymaya_logo.png" class="payment-method-icon" alt="Maya">
                                                                    <?php elseif ($booking['payment_method'] == 'PayPal'): ?>
                                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/1200px-PayPal.svg.png" class="payment-method-icon" alt="PayPal">
                                                                    <?php endif; ?>
                                                                    <?php echo $booking['payment_method']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status:</th>
                                                                <td>
                                                                    <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                                                                        <?php echo $booking['status']; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Amount:</th>
                                                                <td>₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                                            </tr>
                                                            <?php if ($booking['reference_number']): ?>
                                                                <tr>
                                                                    <th>Reference #:</th>
                                                                    <td><?php echo $booking['reference_number']; ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php if ($payment_details): ?>
                                                                <?php foreach ($payment_details as $key => $value): ?>
                                                                    <tr>
                                                                        <th><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</th>
                                                                        <td><?php echo htmlspecialchars($value); ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Booking Modal -->
                                        <div class="modal fade" id="editBookingModal<?php echo $booking['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Booking</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="admin.php?tab=bookings">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="user_id" class="form-label">User</label>
                                                                    <select class="form-select" id="user_id" name="user_id" required>
                                                                        <?php
                                                                        $users_for_select = mysqli_query($connection, "SELECT * FROM users");
                                                                        while ($user = mysqli_fetch_assoc($users_for_select)):
                                                                        ?>
                                                                            <option value="<?php echo $user['id']; ?>" <?php echo $user['id'] == $booking['user_id'] ? 'selected' : ''; ?>>
                                                                                <?php echo htmlspecialchars($user['name']); ?>
                                                                            </option>
                                                                        <?php endwhile; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="package" class="form-label">Package</label>
                                                                    <select class="form-select" id="package" name="package" required>
                                                                        <?php
                                                                        $packages_for_select = mysqli_query($connection, "SELECT * FROM packages");
                                                                        while ($package = mysqli_fetch_assoc($packages_for_select)):
                                                                        ?>
                                                                            <option value="<?php echo htmlspecialchars($package['package_name']); ?>" <?php echo $package['package_name'] == $booking['package'] ? 'selected' : ''; ?>>
                                                                                <?php echo htmlspecialchars($package['package_name']); ?>
                                                                                (₱<?php echo number_format($package['price'], 2); ?>)
                                                                            </option>
                                                                        <?php endwhile; ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="phone" class="form-label">Phone</label>
                                                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($booking['phone']); ?>" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="guests" class="form-label">Guests</label>
                                                                    <input type="number" class="form-control" id="guests" name="guests" value="<?php echo $booking['guests']; ?>" required>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="address" class="form-label">Address</label>
                                                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($booking['address']); ?>" required>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="arrivals" class="form-label">Arrival Date</label>
                                                                    <input type="date" class="form-control" id="arrivals" name="arrivals" value="<?php echo $booking['arrivals']; ?>" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="leaving" class="form-label">Leaving Date</label>
                                                                    <input type="date" class="form-control" id="leaving" name="leaving" value="<?php echo $booking['leaving']; ?>" required>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="status" class="form-label">Status</label>
                                                                    <select class="form-select" id="status" name="status" required>
                                                                        <option value="Pending" <?php echo $booking['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                        <option value="Paid" <?php echo $booking['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                                                        <option value="Completed" <?php echo $booking['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                                        <option value="Cancelled" <?php echo $booking['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="reference_number" class="form-label">Reference Number</label>
                                                                    <input type="text" class="form-control" id="reference_number" name="reference_number" value="<?php echo htmlspecialchars($booking['reference_number']); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Payment Method</label>
                                                                <div class="payment-methods">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="payment_method" id="gcash<?php echo $booking['id']; ?>" value="GCash" <?php echo $booking['payment_method'] == 'GCash' ? 'checked' : ''; ?>>
                                                                        <label class="form-check-label" for="gcash<?php echo $booking['id']; ?>">
                                                                            <img src="images/GCash_logo.png" height="20" alt="GCash">
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="payment_method" id="maya<?php echo $booking['id']; ?>" value="Maya" <?php echo $booking['payment_method'] == 'Maya' ? 'checked' : ''; ?>>
                                                                        <label class="form-check-label" for="maya<?php echo $booking['id']; ?>">
                                                                            <img src="images/Paymaya_logo.png" height="20" alt="Maya">
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="payment_method" id="paypal<?php echo $booking['id']; ?>" value="PayPal" <?php echo $booking['payment_method'] == 'PayPal' ? 'checked' : ''; ?>>
                                                                        <label class="form-check-label" for="paypal<?php echo $booking['id']; ?>">
                                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/1200px-PayPal.svg.png" height="20" alt="PayPal">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="paymentDetailsContainer<?php echo $booking['id']; ?>">
                                                                <?php if ($booking['payment_method'] == 'GCash'): ?>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="gcash_number" class="form-label">GCash Number</label>
                                                                            <input type="text" class="form-control" id="gcash_number" name="gcash_number" value="<?php echo htmlspecialchars($payment_details['number'] ?? ''); ?>" pattern="^09\d{9}$" maxlength="11">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="gcash_name" class="form-label">Account Name</label>
                                                                            <input type="text" class="form-control" id="gcash_name" name="gcash_name" value="<?php echo htmlspecialchars($payment_details['name'] ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php elseif ($booking['payment_method'] == 'Maya'): ?>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="maya_number" class="form-label">Maya Number</label>
                                                                            <input type="text" class="form-control" id="maya_number" name="maya_number" value="<?php echo htmlspecialchars($payment_details['number'] ?? ''); ?>" pattern="^09\d{9}$" maxlength="11">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="maya_name" class="form-label">Account Name</label>
                                                                            <input type="text" class="form-control" id="maya_name" name="maya_name" value="<?php echo htmlspecialchars($payment_details['name'] ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php elseif ($booking['payment_method'] == 'PayPal'): ?>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="paypal_email" class="form-label">PayPal Email</label>
                                                                            <input type="email" class="form-control" id="paypal_email" name="paypal_email" value="<?php echo htmlspecialchars($payment_details['email'] ?? ''); ?>">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="paypal_name" class="form-label">Account Name</label>
                                                                            <input type="text" class="form-control" id="paypal_name" name="paypal_name" value="<?php echo htmlspecialchars($payment_details['name'] ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="edit_booking" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Booking Modal -->
                                        <div class="modal fade" id="deleteBookingModal<?php echo $booking['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Booking</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="admin.php?tab=bookings">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                            <p>Are you sure you want to delete this booking for <?php echo htmlspecialchars($booking['user_name']); ?>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" name="delete_booking" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                            <!-- Bookings Pagination -->
                            <nav aria-label="Bookings pagination">
                                <ul class="pagination justify-content-center">
                                    <?php if ($booking_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?tab=bookings&booking_page=<?php echo $booking_page - 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_booking_pages; $i++): ?>
                                        <li class="page-item <?php echo $i == $booking_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?tab=bookings&booking_page=<?php echo $i; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($booking_page < $total_booking_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?tab=bookings&booking_page=<?php echo $booking_page + 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                            <div class="text-muted text-center">
                                Showing <?php echo ($booking_offset + 1) . ' to ' . min($booking_offset + $records_per_page, $total_bookings); ?> of <?php echo $total_bookings; ?> bookings
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Tab
                    <div class="tab-pane <?php echo $active_tab == 'calendar' ? 'active' : ''; ?>" id="calendar">
                       <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Booking Calendar</h3>
                            <div>
                                <button class="btn btn-primary" id="prev-month">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </button>
                                <button class="btn btn-primary" id="next-month">
                                    Next <i class="bi bi-chevron-right"></i>
                                </button>
                                <button class="btn btn-secondary" id="today-btn">
                                    Today
                                </button>
                            </div>
                        </div>

                        <div class="text-center mb-3">
                            <h4 id="current-month"></h4>
                        </div>

                        <div id="calendar-container">
                            <div id="calendar-header" class="row g-0 text-center bg-dark text-white">
                                <div class="col day-header">Sunday</div>
                                <div class="col day-header">Monday</div>
                                <div class="col day-header">Tuesday</div>
                                <div class="col day-header">Wednesday</div>
                                <div class="col day-header">Thursday</div>
                                <div class="col day-header">Friday</div>
                                <div class="col day-header">Saturday</div>
                            </div>
                            <div id="calendar-body" class="row g-0 border">
                                <!-- Calendar cells will be generated by JavaScript -->
                            </div>
                        </div>
                    </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="admin.php?tab=users">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookingModalLabel">Add New Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="admin.php?tab=bookings" id="addBookingForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">User</label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <?php
                                    $users_for_select = mysqli_query($connection, "SELECT * FROM users");
                                    while ($user = mysqli_fetch_assoc($users_for_select)):
                                    ?>
                                        <option value="<?php echo $user['id']; ?>">
                                            <?php echo htmlspecialchars($user['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="package" class="form-label">Package</label>
                                <select class="form-select" id="package" name="package" required>
                                    <?php
                                    $packages_for_select = mysqli_query($connection, "SELECT * FROM packages");
                                    while ($package = mysqli_fetch_assoc($packages_for_select)):
                                    ?>
                                        <option value="<?php echo htmlspecialchars($package['package_name']); ?>">
                                            <?php echo htmlspecialchars($package['package_name']); ?>
                                            (₱<?php echo number_format($package['price'], 2); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guests" class="form-label">Guests</label>
                                <input type="number" class="form-control" id="guests" name="guests" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="arrivals" class="form-label">Arrival Date</label>
                                <input type="date" class="form-control" id="arrivals" name="arrivals" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="leaving" class="form-label">Leaving Date</label>
                                <input type="date" class="form-control" id="leaving" name="leaving" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number (Optional)</label>
                            <input type="text" class="form-control" id="reference_number" name="reference_number">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div class="payment-methods">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="GCash" checked>
                                    <label class="form-check-label" for="gcash">
                                        <img src="images/GCash_logo.png" height="20" alt="GCash">
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="maya" value="Maya">
                                    <label class="form-check-label" for="maya">
                                        <img src="images/Paymaya_logo.png" height="20" alt="Maya">
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="PayPal">
                                    <label class="form-check-label" for="paypal">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/1200px-PayPal.svg.png" height="20" alt="PayPal">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="paymentDetailsContainer">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gcash_number" class="form-label">GCash Number</label>
                                    <input type="text" class="form-control" id="gcash_number" name="gcash_number" pattern="^09\d{9}$" maxlength="11">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gcash_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" id="gcash_name" name="gcash_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_booking" class="btn btn-primary">Add Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activate tab based on URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');

            if (tab) {
                const tabLink = document.querySelector(`.nav-link[href="?tab=${tab}"]`);
                if (tabLink) {
                    tabLink.classList.add('active');
                }
            }

            // Clear search when switching tabs
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (this.getAttribute('href').includes('tab=')) {
                        const searchInputs = document.querySelectorAll('input[name="search"]');
                        searchInputs.forEach(input => input.value = '');
                    }
                });
            });

            // Payment method selection handler
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    updatePaymentDetailsFields(this.value);
                });
            });

            // Function to update payment details fields based on selected method
            function updatePaymentDetailsFields(method) {
                const container = document.getElementById('paymentDetailsContainer');

                switch (method) {
                    case 'GCash':
                        container.innerHTML = `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gcash_number" class="form-label">GCash Number</label>
                                    <input type="text" class="form-control" id="gcash_number" name="gcash_number" pattern="^09\d{9}$" maxlength="11">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gcash_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" id="gcash_name" name="gcash_name">
                                </div>
                            </div>
                        `;
                        break;
                    case 'Maya':
                        container.innerHTML = `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="maya_number" class="form-label">Maya Number</label>
                                    <input type="text" class="form-control" id="maya_number" name="maya_number" pattern="^09\d{9}$" maxlength="11">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="maya_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" id="maya_name" name="maya_name">
                                </div>
                            </div>
                        `;
                        break;
                    case 'PayPal':
                        container.innerHTML = `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="paypal_email" class="form-label">PayPal Email</label>
                                    <input type="email" class="form-control" id="paypal_email" name="paypal_email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="paypal_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" id="paypal_name" name="paypal_name">
                                </div>
                            </div>
                        `;
                        break;
                }
            }

            // For edit modals
            document.querySelectorAll('[id^="paymentDetailsContainer"]').forEach(container => {
                const bookingId = container.id.replace('paymentDetailsContainer', '');
                const radioButtons = document.querySelectorAll(`input[name="payment_method"]`);

                radioButtons.forEach(radio => {
                    radio.addEventListener('change', function() {
                        updateEditPaymentDetailsFields(this.value, bookingId);
                    });
                });
            });

            function updateEditPaymentDetailsFields(method, bookingId) {
                const container = document.getElementById(`paymentDetailsContainer${bookingId}`);

                switch (method) {
                    case 'GCash':
                        container.innerHTML = `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gcash_number" class="form-label">GCash Number</label>
                                    <input type="text" class="form-control" id="gcash_number" name="gcash_number" pattern="^09\d{9}$" maxlength="11">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gcash_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" id="gcash_name" name="gcash_name">
                                </div>
                            </div>
                        `;
                        break;
                    case 'Maya':
                        container.innerHTML = `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="maya_number" class="form-label">Maya Number</label>
                                    <input type="text" class="form-control" id="maya_number" name="maya_number" pattern="^09\d{9}$" maxlength="11">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="maya_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" id="maya_name" name="maya_name">
                                </div>
                            </div>
                        `;
                        break;
                    case 'PayPal':
                        container.innerHTML = `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="paypal_email" class="form-label">PayPal Email</label>
                                    <input type="email" class="form-control" id="paypal_email" name="paypal_email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="paypal_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" id="paypal_name" name="paypal_name">
                                </div>
                            </div>
                        `;
                        break;
                }
            }
        });

        // Calendar functionality
        document.addEventListener('DOMContentLoaded', function() {
            let currentDate = new Date();

            // Helper function to format date as YYYY-MM-DD
            function formatDate(date) {
                const d = new Date(date);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Function to show booking details in a modal
            function showBookingDetails(date) {
                // Show loading indicator
                const loadingHTML = `
                <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading booking details...</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                document.body.insertAdjacentHTML('beforeend', loadingHTML);
                const loadingModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
                loadingModal.show();

                fetch(`get_bookings.php?date=${date}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(bookings => {
                        // Remove loading modal
                        document.getElementById('bookingDetailsModal').remove();

                        // Create modal HTML
                        let modalHTML = `
                        <div class="modal fade" id="bookingDetailsModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Bookings for ${date}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-info text-white">
                                                        <h6 class="mb-0">Arrivals</h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="list-group list-group-flush" id="arrivals-list">
                    `;

                        // Filter arrivals (bookings that start on this date)
                        const arrivals = Array.isArray(bookings) ? bookings.filter(booking => booking.arrivals === date) : [];

                        if (arrivals.length > 0) {
                            arrivals.forEach(booking => {
                                modalHTML += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${booking.user_name}</h6>
                                            <small class="text-muted">${booking.package}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">${booking.guests}</span>
                                    </div>
                                    <small class="d-block mt-1"><i class="bi bi-telephone"></i> ${booking.phone}</small>
                                </div>
                            `;
                            });
                        } else {
                            modalHTML += '<div class="list-group-item text-muted">No arrivals</div>';
                        }

                        modalHTML += `
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-warning text-dark">
                                                        <h6 class="mb-0">Departures</h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="list-group list-group-flush" id="departures-list">
                    `;

                        // Filter departures (bookings that end on this date)
                        const departures = Array.isArray(bookings) ? bookings.filter(booking => booking.leaving === date) : [];

                        if (departures.length > 0) {
                            departures.forEach(booking => {
                                modalHTML += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${booking.user_name}</h6>
                                            <small class="text-muted">${booking.package}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">${booking.guests}</span>
                                    </div>
                                    <small class="d-block mt-1"><i class="bi bi-telephone"></i> ${booking.phone}</small>
                                </div>
                            `;
                            });
                        } else {
                            modalHTML += '<div class="list-group-item text-muted">No departures</div>';
                        }

                        modalHTML += `
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">Current Guests</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush" id="current-guests-list">
                    `;

                        // Filter current guests (bookings that span this date)
                        const currentGuests = Array.isArray(bookings) ?
                            bookings.filter(booking => booking.arrivals <= date && booking.leaving >= date) : [];

                        if (currentGuests.length > 0) {
                            currentGuests.forEach(booking => {
                                modalHTML += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${booking.user_name}</h6>
                                            <small class="text-muted">${booking.package}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">${booking.guests}</span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="d-block"><i class="bi bi-calendar"></i> ${booking.arrivals} to ${booking.leaving}</small>
                                        <small class="d-block"><i class="bi bi-telephone"></i> ${booking.phone}</small>
                                    </div>
                                </div>
                            `;
                            });
                        } else {
                            modalHTML += '<div class="list-group-item text-muted">No current guests</div>';
                        }

                        modalHTML += `
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                        // Add modal to DOM
                        document.body.insertAdjacentHTML('beforeend', modalHTML);
                        const detailsModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
                        detailsModal.show();

                        // Clean up modal when closed
                        document.getElementById('bookingDetailsModal').addEventListener('hidden.bs.modal', function() {
                            this.remove();
                        });
                    })
                    .catch(error => {
                        console.error('Booking details error:', error);

                        // Remove loading modal if it exists
                        const existingModal = document.getElementById('bookingDetailsModal');
                        if (existingModal) existingModal.remove();

                        // Show error modal
                        const errorHTML = `
                        <div class="modal fade" id="bookingDetailsModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Error</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Failed to load booking details.</p>
                                        <div class="alert alert-danger mt-3">
                                            <small>${error.message}</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="window.location.reload()">Try Again</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                        document.body.insertAdjacentHTML('beforeend', errorHTML);
                        new bootstrap.Modal(document.getElementById('bookingDetailsModal')).show();
                    });
            }

            // Initialize calendar
            function renderCalendar(date) {
                const calendarBody = document.getElementById('calendar-body');
                calendarBody.innerHTML = '';

                // Get first day of month and total days
                const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                const daysInMonth = lastDay.getDate();

                // Get starting day of week (0-6)
                let startDay = firstDay.getDay();

                // Create calendar cells
                let dayCount = 1;
                let calendarHTML = '';

                // Create 6 rows (weeks)
                for (let i = 0; i < 6; i++) {
                    calendarHTML += '<div class="row g-0">';

                    // Create 7 cells (days) per week
                    for (let j = 0; j < 7; j++) {
                        if ((i === 0 && j < startDay) || dayCount > daysInMonth) {
                            // Previous or next month days
                            const prevMonthDays = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
                            const dayNum = (i === 0 && j < startDay) ?
                                (prevMonthDays - startDay + j + 1) :
                                (dayCount - daysInMonth);

                            calendarHTML += `
                            <div class="col calendar-day other-month">
                                <div class="day-number">${dayNum}</div>
                            </div>
                        `;
                        } else {
                            // Current month days
                            const currentDay = new Date(date.getFullYear(), date.getMonth(), dayCount);
                            const isToday = currentDay.toDateString() === new Date().toDateString();

                            calendarHTML += `
                            <div class="col calendar-day ${isToday ? 'today' : ''}" 
                                 data-date="${formatDate(currentDay)}">
                                <div class="day-number">${dayCount}</div>
                                <div class="day-events" id="events-${formatDate(currentDay)}"></div>
                            </div>
                        `;
                            dayCount++;
                        }
                    }

                    calendarHTML += '</div>';
                }

                calendarBody.innerHTML = calendarHTML;

                // Load bookings for the month
                loadBookingsForMonth(date);

                // Update month/year display
                document.getElementById('current-month').textContent =
                    date.toLocaleString('default', {
                        month: 'long',
                        year: 'numeric'
                    });
            }

            // Load bookings for the month
            function loadBookingsForMonth(date) {
                const month = date.getMonth() + 1;
                const year = date.getFullYear();

                // Debug log
                console.log(`Fetching bookings for month: ${month}, year: ${year}`);

                fetch(`get_bookings.php?month=${month}&year=${year}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(bookings => {
                        console.log('Received bookings:', bookings); // Debug log
                        if (!Array.isArray(bookings)) {
                            throw new Error('Invalid data received');
                        }

                        bookings.forEach(booking => {
                            const eventElement = document.createElement('div');
                            eventElement.className = 'booking-event';
                            eventElement.title = `${booking.package} - ${booking.user_name}\n${booking.arrivals} to ${booking.leaving}`;
                            eventElement.innerHTML = `
                    <strong>${booking.package}</strong><br>
                    ${booking.user_name}
                `;

                            const arrivalDate = new Date(booking.arrivals);
                            const leavingDate = new Date(booking.leaving);

                            // Add event to all days in the booking range
                            for (let d = new Date(arrivalDate); d <= leavingDate; d.setDate(d.getDate() + 1)) {
                                const dateStr = formatDate(d);
                                const eventsContainer = document.getElementById(`events-${dateStr}`);
                                if (eventsContainer) {
                                    eventsContainer.appendChild(eventElement.cloneNode(true));
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error loading bookings:', error);
                        // Show error to user
                        alert('Failed to load bookings. Please check console for details.');
                    });
            }

            // Navigation buttons
            document.getElementById('prev-month').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });

            document.getElementById('next-month').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });

            document.getElementById('today-btn').addEventListener('click', function() {
                currentDate = new Date();
                renderCalendar(currentDate);
            });

            // Add click event to calendar days
            document.getElementById('calendar-body').addEventListener('click', function(e) {
                const dayElement = e.target.closest('.calendar-day:not(.other-month)');
                if (dayElement) {
                    const date = dayElement.dataset.date;
                    if (date) {
                        showBookingDetails(date);
                    }
                }
            });

            // Initialize calendar
            renderCalendar(currentDate);
        });

        // SCHEDULING VALIDATION
        document.addEventListener('DOMContentLoaded', function() {
            const addBookingForm = document.getElementById('addBookingForm');
            const arrivalsInput = document.getElementById('arrivals');
            const leavingInput = document.getElementById('leaving');
            const leavingFeedback = document.getElementById('leavingFeedback');

            // Validate dates when either field changes
            arrivalsInput.addEventListener('change', validateModalDates);
            leavingInput.addEventListener('change', validateModalDates);

            // Validate before form submission
            addBookingForm.addEventListener('submit', function(e) {
                if (!validateModalDates()) {
                    e.preventDefault();
                }
            });

            function validateModalDates() {
                const arrivalsDate = new Date(arrivalsInput.value);
                const leavingDate = new Date(leavingInput.value);

                if (arrivalsInput.value && leavingInput.value) {
                    if (leavingDate < arrivalsDate) {
                        leavingInput.classList.add('is-invalid');
                        leavingFeedback.style.display = 'block';
                        return false;
                    } else {
                        leavingInput.classList.remove('is-invalid');
                        leavingFeedback.style.display = 'none';
                    }
                }
                return true;
            }
        });
    </script>
</body>

</html>
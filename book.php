<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = 'book.php';
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$selected_package = $_GET['package'] ?? '';

// Fetch user details
$user_query = $connection->prepare("SELECT name, email FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Fetch all booked dates for the selected package
$booked_dates = [];
if (!empty($selected_package)) {
    $date_query = $connection->prepare("SELECT arrivals, leaving FROM booking WHERE package = ?");
    $date_query->bind_param("s", $selected_package);
    $date_query->execute();
    $date_result = $date_query->get_result();
    
    while ($row = $date_result->fetch_assoc()) {
        $booked_dates[] = [
            'from' => $row['arrivals'],
            'to' => $row['leaving']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BOOK NOW!</title>

   <!-- swiper css link -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- flatpickr (calendar) -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
   <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .flatpickr-day.booked {
         background: #ff4444;
         color: white;
         border-color: #ff4444;
      }
      .flatpickr-day.booked:hover {
         background: #ff2222;
      }
      .flatpickr-day.booked.startRange, 
      .flatpickr-day.booked.endRange {
         background: #ff0000;
      }
      .flatpickr-day.booked.inRange {
         background: rgba(255, 68, 68, 0.2);
         box-shadow: -5px 0 0 rgba(255, 68, 68, 0.2), 5px 0 0 rgba(255, 68, 68, 0.2);
      }
      .date-legend {
         display: flex;
         justify-content: center;
         margin: 15px 0;
         gap: 20px;
         flex-wrap: wrap;
      }
      .legend-item {
         display: flex;
         align-items: center;
         font-size: 14px;
         margin: 5px 0;
      }
      .legend-color {
         width: 20px;
         height: 20px;
         margin-right: 8px;
         border-radius: 3px;
      }
      .booking-form-container {
         max-width: 1200px;
         margin: 0 auto;
         padding: 20px;
      }
      .flatpickr-input {
         background-color: #f9f9f9;
         border: 1px solid #ddd;
         padding: 12px 15px;
         border-radius: 5px;
         font-size: 16px;
         width: 100%;
      }
      .flatpickr-calendar {
         box-shadow: 0 5px 15px rgba(0,0,0,0.1);
         border-radius: 8px;
      }
   </style>
</head>
<body>
   
<!-- header section starts -->
<section class="header">
   <a href="index.php" class="logo"><img src="images/logo.jpg" alt="" style="width:450px;height:100px;"></a>
   <nav class="navbar">
      <a href="index.php"><i class="fas fa-home"></i> Home</a>
      <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
      <a href="package.php"><i class="fas fa-box"></i> Package</a>
      <a href="book.php"><i class="fas fa-book"></i> Book</a>
      <?php if (isset($_SESSION['user_id'])): ?>
         <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      <?php else: ?>
         <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
      <?php endif; ?>
   </nav>
   <div id="menu-btn" class="fas fa-bars"></div>
</section>
<!-- header section ends -->

<div class="heading" style="background:url(images/header-bg-3.png) no-repeat">
   <h1>Book Now</h1>
</div>

<!-- booking section starts -->
<div class="booking-form-container">
   <section class="booking">  
      <h1 class="heading-title">Book your Indulgement!</h1>

      <?php if (isset($_SESSION['user_id'])): ?>
      <form action="book_form.php" method="post" class="book-form">
         <div class="flex">
            <div class="inputBox">
               <span>Name :</span>
               <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
            </div>
            <div class="inputBox">
               <span>Email :</span>
               <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>
            <div class="inputBox">
               <span>Phone :</span>
               <input type="text" name="phone" placeholder="Enter your number" required maxlength="11" pattern="^09\d{9}$" inputmode="numeric" title="Phone number must start with 09 and be exactly 11 digits">
            </div>
            <div class="inputBox">
               <span>Address :</span>
               <input type="text" placeholder="Enter your address" name="address" required>
            </div>
            <div class="inputBox">
               <span>Package :</span>
               <input type="text" name="package" value="<?php echo htmlspecialchars($selected_package); ?>" readonly>
            </div>
            <div class="inputBox">
               <span>Number of guests:</span>
               <input type="number" placeholder="Enter number of guests" name="guests" required min="1">
            </div>
            <div class="inputBox">
               <span>Arrival Date :</span>
               <input type="text" class="flatpickr-input" name="arrivals" id="arrivals" placeholder="Select arrival date" required readonly>
            </div>
            <div class="inputBox">
               <span>Departure Date :</span>
               <input type="text" class="flatpickr-input" name="leaving" id="leaving" placeholder="Select departure date" required readonly>
            </div>
         </div>

         <div class="btn-center">
            <input type="submit" value="Submit Booking" class="btn" name="send">
         </div>  
      </form>
      <?php else: ?>
      <div style="text-align:center; margin-top: 20px;">
         <p style="font-size: 18px;">You must be logged in to book a trip.</p>
         <a href="login.php" class="btn">Login</a>
         <a href="register.php" class="btn">Register</a>
      </div>
      <?php endif; ?>
   </section>
</div>
<!-- booking section ends -->

<!-- footer section starts -->
<section class="footer">
   <div class="box-container">
      <div class="box">
         <h3>quick links</h3>
         <a href="index.php"> <i class="fas fa-angle-right"></i> home</a>
         <a href="about.php"> <i class="fas fa-angle-right"></i> about</a>
         <a href="package.php"> <i class="fas fa-angle-right"></i> package</a>
         <a href="book.php"> <i class="fas fa-angle-right"></i> book</a>
      </div>
      <div class="box">
         <h3>extra links</h3>
         <a href="#"> <i class="fas fa-angle-right"></i> ask questions</a>
         <a href="#"> <i class="fas fa-angle-right"></i> about us</a>
         <a href="#"> <i class="fas fa-angle-right"></i> privacy policy</a>
         <a href="#"> <i class="fas fa-angle-right"></i> terms of use</a>
      </div>
      <div class="box">
         <h3>contact info</h3>
         <a href="#"> <i class="fas fa-phone"></i> +63-931-223-2777 </a>
         <a href="#"> <i class="fas fa-phone"></i> +111-222-3333 </a>
         <a href="#"> <i class="fas fa-envelope"></i> CasaLuna@gmail.com </a>
         <a href="#"> <i class="fas fa-map"></i> San Pablo City, Laguna - 4000 </a>
      </div>
      <div class="box">
         <h3>follow us</h3>
         <a href="#"> <i class="fab fa-facebook-f"></i> facebook </a>
         <a href="#"> <i class="fab fa-twitter"></i> twitter </a>
         <a href="#"> <i class="fab fa-instagram"></i> instagram </a>
         <a href="#"> <i class="fab fa-linkedin"></i> linkedin </a>
      </div>
   </div>
   <div class="credit"> created by <span>Casa Luna.</span> | all rights reserved! </div>
</section>
<!-- footer section ends -->

<!-- swiper js link -->
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<!-- flatpickr js -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookedRanges = <?php echo json_encode($booked_dates); ?>;
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate());

    // Format dates for flatpickr
    const disabledRanges = bookedRanges.map(range => ({
        from: range.from,
        to: range.to
    }));

    // Function to style booked dates
    function styleBookedDates() {
        document.querySelectorAll('.flatpickr-day').forEach(day => {
            const date = new Date(day.dateObj);
            day.classList.remove('booked', 'startRange', 'endRange', 'inRange');
            
            // Check if date falls in any booked range
            bookedRanges.forEach(range => {
                const start = new Date(range.from);
                const end = new Date(range.to);
                
                if (date >= start && date <= end) {
                    day.classList.add('booked');
                    if (date.toDateString() === start.toDateString()) {
                        day.classList.add('startRange');
                    } else if (date.toDateString() === end.toDateString()) {
                        day.classList.add('endRange');
                    } else {
                        day.classList.add('inRange');
                    }
                }
            });
        });
    }

    // Initialize arrival date picker
    const arrivalsPicker = flatpickr("#arrivals", {
        minDate: tomorrow,
        dateFormat: "Y-m-d",
        disable: disabledRanges,
        onReady: function(selectedDates, dateStr, instance) {
            styleBookedDates();
        },
        onMonthChange: function(selectedDates, dateStr, instance) {
            setTimeout(styleBookedDates, 10);
        },
        onChange: function(selectedDates, dateStr, instance) {
            styleBookedDates();
            if (selectedDates.length) {
                leavingPicker.set('minDate', selectedDates[0]);
                if (leavingPicker.selectedDates[0] && 
                    leavingPicker.selectedDates[0] < selectedDates[0]) {
                    leavingPicker.clear();
                }
            }
        }
    });

    // Initialize leaving date picker
    const leavingPicker = flatpickr("#leaving", {
        minDate: tomorrow,
        dateFormat: "Y-m-d",
        disable: disabledRanges,
        onReady: function(selectedDates, dateStr, instance) {
            styleBookedDates();
        },
        onMonthChange: function(selectedDates, dateStr, instance) {
            setTimeout(styleBookedDates, 10);
        },
        onChange: function(selectedDates, dateStr, instance) {
            styleBookedDates();
            if (selectedDates.length && arrivalsPicker.selectedDates.length) {
                const arrival = arrivalsPicker.selectedDates[0];
                const leaving = selectedDates[0];
                
                const isRangeValid = !isRangeBooked(arrival, leaving);
                if (!isRangeValid) {
                    alert("Your selected dates include booked periods. Please choose different dates.");
                    leavingPicker.clear();
                }
            }
        }
    });

    // Check if any date in range is booked
    function isRangeBooked(startDate, endDate) {
        return bookedRanges.some(range => {
            const rangeStart = new Date(range.from);
            const rangeEnd = new Date(range.to);
            return (startDate <= rangeEnd && endDate >= rangeStart);
        });
    }

    // Form submission validation
    document.querySelector('.book-form').addEventListener('submit', function(e) {
        if (!arrivalsPicker.selectedDates.length || !leavingPicker.selectedDates.length) {
            e.preventDefault();
            alert('Please select both arrival and departure dates');
            return;
        }
        
        const arrival = arrivalsPicker.selectedDates[0];
        const leaving = leavingPicker.selectedDates[0];
        
        if (leaving < arrival) {
            e.preventDefault();
            alert('Departure date cannot be before arrival date');
            return;
        }
        
        if (isRangeBooked(arrival, leaving)) {
            e.preventDefault();
            alert('The selected dates include booked periods. Please choose different dates.');
            return;
        }
    });
});
</script>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
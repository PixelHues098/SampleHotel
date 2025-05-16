<?php
// Connect to database
include 'connection.php';

// Start session
session_start();

// Check if user is logged in and fetch their bookings if they are
$userBookings = [];
$hasPending = false;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $bookingQuery = "SELECT package, status FROM booking WHERE user_id = '$userId' ORDER BY created_at DESC";
    $bookingResult = mysqli_query($connection, $bookingQuery);
    
    while ($row = mysqli_fetch_assoc($bookingResult)) {
        $userBookings[] = $row;
        if ($row['status'] == 'Pending') {
            $hasPending = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ABOUT US</title>

  <!-- swiper css link  -->
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css">

  <style>
      /* Booking status floating component styles */
      .booking-status-container {
         position: fixed;
         right: 20px;
         bottom: 20px;
         z-index: 1000;
      }
      
      .booking-status-btn {
         background-color: <?php echo $hasPending ? '#f39c12' : '#3a86ff'; ?>;
         color: white;
         width: 60px;
         height: 60px;
         border-radius: 50%;
         display: flex;
         justify-content: center;
         align-items: center;
         cursor: pointer;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
         font-size: 24px;
         transition: all 0.3s ease;
      }
      
      .booking-status-btn:hover {
         transform: scale(1.1);
      }
      
      .booking-status-badge {
         position: absolute;
         top: -5px;
         right: -5px;
         background-color: #e74c3c;
         color: white;
         border-radius: 50%;
         width: 20px;
         height: 20px;
         display: flex;
         justify-content: center;
         align-items: center;
         font-size: 12px;
      }
      
      .booking-modal {
         display: none;
         position: fixed;
         z-index: 1001;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.5);
      }
      
      .booking-modal-content {
         background-color: #fefefe;
         margin: 10% auto;
         padding: 20px;
         border-radius: 10px;
         width: 80%;
         max-width: 600px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      }
      
      .close-modal {
         color: #aaa;
         float: right;
         font-size: 28px;
         font-weight: bold;
         cursor: pointer;
      }
      
      .close-modal:hover {
         color: black;
      }
      
      .booking-item {
         padding: 10px;
         border-bottom: 1px solid #eee;
         display: flex;
         justify-content: space-between;
      }
      
      .status-pending {
         color: #f39c12;
         font-weight: bold;
      }
      
      .status-paid {
         color: #27ae60;
         font-weight: bold;
      }
      
      .status-completed {
         color: #3498db;
         font-weight: bold;
      }
      
      .status-cancelled {
         color: #e74c3c;
         font-weight: bold;
      }
      
      .booking-title {
         font-size: 24px;
         margin-bottom: 20px;
         color: #333;
         text-align: center;
      }
   </style>

</head>

<body>

  <!-- header section starts  -->

  <section class="header">

    <a href="index.php" class="logo"><img src="images/logo.jpg" alt="" style="width:500px;height:100px;"></a>

    <nav class="navbar">
      <a href="index.php"><i class="fas fa-home"></i> Home</a>
      <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
      <a href="package.php"><i class="fas fa-box"></i> Package</a>
      <a href="book.php"><i class="fas fa-book"></i> Book</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>

  </section>

  <!-- header section ends -->

  <div class="heading" style="background:url(images/header-bg-1.png) no-repeat">
    <h1>About Us</h1>
  </div>

  <!-- about section starts  -->

  <section class="about home-about">

    <div class="image">
      <img src="images/lobby.jpg" alt="Beautiful Island Destination">
    </div>

    <div class="content">
      <h3>Why Choose Casa Luna?</h3>
      <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quis, at! Nisi asperiores similique, quas autem, maiores nobis molestiae dicta cupiditate molestias minus recusandae alias dolorem minima sequi eveniet consectetur blanditiis beatae libero praesentium iusto tempore hic? Maxime quae alias sapiente accusantium iste dolor quas est labore, mollitia corrupti iure itaque.</p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia molestiae eveniet commodi quaerat iure quo aspernatur explicabo vero animi beatae.</p>

      <div class="icons-container">
        <div class="icons">
          <i class="fas fa-map"></i>
          <span>Top Venues</span>
        </div>
        <div class="icons">
          <i class="fas fa-hand-holding-usd"></i>
          <span>Affordable Pricing</span>
        </div>
        <div class="icons">
          <i class="fas fa-headset"></i>
          <span>24/7 Customer Support</span>
        </div>
      </div>
    </div>

  </section>

  <!-- about section ends -->

  <!-- reviews section starts  -->

  <section class="reviews">
    <h1 class="heading-title">Client Testimonials</h1>

    <div class="swiper reviews-slider">
      <div class="swiper-wrapper">

        <div class="swiper-slide slide">
          <div class="testimonial-content">
            <div class="stars">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p>"Casa Luna made our dream vacation come true! Everything was well-planned—from accommodations to excursions. We can't wait for our next trip!"</p>
            <div class="client-info">
              <img src="https://ui-avatars.com/api/?name=Maria+Reyes&background=FFA500&color=ffffff&rounded=true" alt="Maria Reyes">
              <div>
                <h3>Maria Reyes</h3>
                <span>Customer</span>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide slide">
          <div class="testimonial-content">
            <div class="stars">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p>"Excellent service and great attention to detail. Casa Luna gave us an amazing hotel experience. Highly recommend!"</p>
            <div class="client-info">
              <img src="https://ui-avatars.com/api/?name=David+Cruz&background=FFA500&color=ffffff&rounded=true" alt="David Cruz">
              <div>
                <h3>David Cruz</h3>
                <span>Customer</span>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide slide">
          <div class="testimonial-content">
            <div class="stars">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p>"The staff was helpful and responsive throughout the booking. The rooms were stunning. Thank you, Casa Luna!"</p>
            <div class="client-info">
              <img src="https://ui-avatars.com/api/?name=Anna+Lim&background=FFA500&color=ffffff&rounded=true" alt="Anna Lim">
              <div>
                <h3>Anna Lim</h3>
                <span>Customer</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- reviews section ends -->

  <!-- Booking status floating component -->
   <?php if (isset($_SESSION['user_id']) && !empty($userBookings)): ?>
   <div class="booking-status-container">
      <div class="booking-status-btn" id="bookingStatusBtn">
         <i class="fas fa-calendar-check"></i>
         <?php if ($hasPending): ?>
            <div class="booking-status-badge">!</div>
         <?php endif; ?>
      </div>
   </div>
   
   <!-- Booking status modal -->
   <div id="bookingModal" class="booking-modal">
      <div class="booking-modal-content">
         <span class="close-modal">&times;</span>
         <h2 class="booking-title">Your Bookings</h2>
         <?php foreach ($userBookings as $booking): ?>
            <div class="booking-item">
               <span><?php echo htmlspecialchars($booking['package']); ?></span>
               <span class="status-<?php echo strtolower($booking['status']); ?>">
                  <?php echo htmlspecialchars($booking['status']); ?>
               </span>
            </div>
         <?php endforeach; ?>
      </div>
   </div>
   <?php endif; ?>

  <!-- footer section starts  -->

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



  <!-- swiper js link  -->
  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

  <!-- custom js file link  -->
  <script src="js/script.js"></script>

  <!-- booking_status modal -->
  <script src="js/booking_status.js"></script>

</body>

</html>
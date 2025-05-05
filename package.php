<?php
// Connect to database
include 'connection.php';

// Fetch all packages from database
$packages = [];
$query = "SELECT package_name, description, price, availability FROM packages";
$result = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($result)) {
   $packages[] = $row; // Store all package data
}

// Fetch package availability separately if needed
$packageAvailability = [];
$availabilityQuery = "SELECT package_name, availability FROM packages";
$availabilityResult = mysqli_query($connection, $availabilityQuery);
while ($row = mysqli_fetch_assoc($availabilityResult)) {
   $packageAvailability[$row['package_name']] = $row['availability'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>CHOOSE YOUR PACKAGE</title>

   <!-- swiper css link  -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .btn-disabled {
         background-color: #cccccc !important;
         cursor: not-allowed !important;
         pointer-events: none !important;
         opacity: 0.7;
      }

      .availed-message {
         color: #ff0000;
         font-weight: bold;
         margin-top: 15px;
         font-size: 14px;
         text-align: center;
         padding: 5px 0;
      }

      .btn-center {
         display: flex;
         flex-direction: column;
         align-items: center;
         gap: 10px;
      }
   </style>

</head>

<body>

   <!-- header section starts  -->
   <section class="header">
      <a href="index.php" class="logo"><img src="images/logo.png" alt="" style="width:500px;height:100px;"></a>
      <nav class="navbar">
         <a href="index.php"><i class="fas fa-home"></i> Home</a>
         <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
         <a href="package.php"><i class="fas fa-box"></i> Package</a>
         <a href="book.php"><i class="fas fa-book"></i> Book</a>
      </nav>
      <div id="menu-btn" class="fas fa-bars"></div>
   </section>
   <!-- header section ends -->

   <div class="heading" style="background:url(images/header-bg-2.png) no-repeat">
      <h1>Packages</h1>
   </div>

   <!-- packages section starts  -->
   <section class="home-packages">
      <h1 class="heading-title">Choose your Luxury</h1>
      <div class="box-container">
         <?php
         $imageIndex = 1;
         foreach ($packages as $package):
            $imageSrc = "images/img-" . $imageIndex . ".jpg";
            $imageIndex++;
            if ($imageIndex > 15) $imageIndex = 1; // Loop images if more than 15 packages
         ?>
            <div class="box">
               <div class="image">
                  <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($package['package_name']); ?>">
               </div>
               <div class="content">
                  <h3><?php echo htmlspecialchars($package['package_name']); ?></h3>
                  <p><?php echo htmlspecialchars($package['description']); ?></p>
                  <h2>₱<?php echo number_format($package['price'], 2); ?> per night</h2>
                  <div class="btn-center">
                     <?php if (isset($packageAvailability[$package['package_name']]) && $packageAvailability[$package['package_name']] == 0): ?>
                        <span class="btn btn-disabled">Book Now</span>
                        <div class="availed-message">Package availed</div>
                     <?php else: ?>
                        <a href="book.php?package=<?php echo urlencode($package['package_name']); ?>" class="btn">Book Now</a>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   </section>
   <!-- packages section ends -->

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
   <script src="https://unpkg.com/swiper@11/swiper-bundle.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>
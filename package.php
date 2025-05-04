<?php
// Connect to database
include 'connection.php';
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
      margin-top: 15px;  /* Increased spacing */
      font-size: 14px;   /* Slightly smaller font */
      text-align: center; /* Center align */
      padding: 5px 0;    /* Add some vertical padding */
   }
   .btn-center {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;  /* Adds space between button and message */
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

         <!-- package 1 -->
         <div class="box">
            <div class="image">
               <img src="images/img-1.jpg" alt="">
            </div>
            <div class="content">
               <h3>Serenity Suite</h3>
               <p>A peaceful retreat with a balcony overlooking a garden, perfect for relaxation.</p>
               <h2>₱5,500 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Serenity Suite']) && $packageAvailability['Serenity Suite'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Serenity Suite" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 2 -->
         <div class="box">
            <div class="image">
               <img src="images/img-2.jpg" alt="">
            </div>
            <div class="content">
               <h3>Ocean Breeze Deluxe</h3>
               <p>Spacious room with a stunning sea view and a private veranda.</p>
               <h2>₱6,800 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Ocean Breeze Deluxe']) && $packageAvailability['Ocean Breeze Deluxe'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Ocean Breeze Deluxe" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 3 -->
         <div class="box">
            <div class="image">
               <img src="images/img-3.jpg" alt="">
            </div>
            <div class="content">
               <h3>Bamboo Haven</h3>
               <p>Eco-friendly room with bamboo furnishings and a tropical vibe.</p>
               <h2>₱4,200 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Bamboo Haven']) && $packageAvailability['Bamboo Haven'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Bamboo Haven" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 4 -->
         <div class="box">
            <div class="image">
               <img src="images/img-4.jpg" alt="">
            </div>
            <div class="content">
               <h3>Royal Heritage Room</h3>
               <p>Elegant, vintage-inspired room with classic Filipino decor.</p>
               <h2>₱7,500 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Royal Heritage Room']) && $packageAvailability['Royal Heritage Room'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Royal Heritage Room" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 5 -->
         <div class="box">
            <div class="image">
               <img src="images/img-5.jpg" alt="">
            </div>
            <div class="content">
               <h3>Mountain Peak Villa</h3>
               <p>Private villa with panoramic mountain views and a cozy fireplace.</p>
               <h2>₱8,900 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Mountain Peak Villa']) && $packageAvailability['Mountain Peak Villa'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Mountain Peak Villa" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 6 -->
         <div class="box">
            <div class="image">
               <img src="images/img-6.jpg" alt="">
            </div>
            <div class="content">
               <h3>Urban Loft</h3>
               <p>Modern, industrial-style loft with city skyline views.</p>
               <h2>₱5,000 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Urban Loft']) && $packageAvailability['Urban Loft'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Urban Loft" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 7 -->
         <div class="box">
            <div class="image">
               <img src="images/img-7.jpg" alt="">
            </div>
            <div class="content">
               <h3>Sunset Pavilion</h3>
               <p>Open-air pavilion with a perfect sunset view, ideal for couples.</p>
               <h2>₱6,300 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Sunset Pavilion']) && $packageAvailability['Sunset Pavilion'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Sunset Pavilion" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 8 -->
         <div class="box">
            <div class="image">
               <img src="images/img-8.jpg" alt="">
            </div>
            <div class="content">
               <h3>Zen Garden Room</h3>
               <p>Minimalist room with a small Zen garden and meditation space.</p>
               <h2>₱4,800 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Zen Garden Room']) && $packageAvailability['Zen Garden Room'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Zen Garden Room" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 9 -->
         <div class="box">
            <div class="image">
               <img src="images/img-9.jpg" alt="">
            </div>
            <div class="content">
               <h3>Executive Skyline Suite</h3>
               <p>High-floor suite with a luxurious workspace and cityscape views.</p>
               <h2>₱9,200 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Executive Skyline Suite']) && $packageAvailability['Executive Skyline Suite'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Executive Skyline Suite" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 10 -->
         <div class="box">
            <div class="image">
               <img src="images/img-10.jpg" alt="">
            </div>
            <div class="content">
               <h3>Tropical Oasis</h3>
               <p>A vibrant room with palm-themed decor and a private jacuzzi.</p>
               <h2>₱7,000 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Tropical Oasis']) && $packageAvailability['Tropical Oasis'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Tropical Oasis" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 11 -->
         <div class="box">
            <div class="image">
               <img src="images/img-11.jpg" alt="">
            </div>
            <div class="content">
               <h3>Cozy Nook</h3>
               <p>Small but charming budget-friendly room with warm lighting.</p>
               <h2>₱3,500 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Cozy Nook']) && $packageAvailability['Cozy Nook'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Cozy Nook" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 12 -->
         <div class="box">
            <div class="image">
               <img src="images\img-12.jpg" alt="">
            </div>
            <div class="content">
               <h3>Paradise Lagoon Room</h3>
               <p>Overwater-inspired room with direct pool access.</p>
               <h2>₱7,800 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Paradise Lagoon Room']) && $packageAvailability['Paradise Lagoon Room'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Paradise Lagoon Room" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 13 -->
         <div class="box">
            <div class="image">
               <img src="images/img-13.jpg" alt="">
            </div>
            <div class="content">
               <h3>Adventure Pod</h3>
               <p>Compact, high-tech room designed for backpackers and explorers.</p>
               <h2>₱3,000 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Adventure Pod']) && $packageAvailability['Adventure Pod'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Adventure Pod" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 14 -->
         <div class="box">
            <div class="image">
               <img src="images/img-14.jpg" alt="">
            </div>
            <div class="content">
               <h3>Celestial Suite</h3>
               <p>A dreamy room with a starry ceiling projection and mood lighting.</p>
               <h2>₱6,500 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['Celestial Suite']) && $packageAvailability['Celestial Suite'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=Celestial Suite" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- package 15 -->
         <div class="box">
            <div class="image">
               <img src="images/img-15.jpg" alt="">
            </div>
            <div class="content">
               <h3>The Presidential Grandeur</h3>
               <p>Ultra-luxurious suite with a living area, dining space, and butler service.</p>
               <h2>₱15,000 per night</h2>
               <div class="btn-center">
                  <?php if (isset($packageAvailability['The Presidential Grandeur']) && $packageAvailability['The Presidential Grandeur'] == 0): ?>
                     <span class="btn btn-disabled">Book Now</span>
                     <div class="availed-message">Package availed</div>
                  <?php else: ?>
                     <a href="book.php?package=The Presidential Grandeur" class="btn">Book Now</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

      </div>

      <!-- <div class="load-more"><span class="btn">load more</span></div> -->

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
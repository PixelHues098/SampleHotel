<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>HOME</title>

   <!-- swiper css link  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
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

   <!-- home section starts  -->

   <section class="home">

      <div class="swiper home-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide" style="background:url(images/home-slide-1.jpg) no-repeat">
               <div class="content">
                  <span>Explore. Discover. Unwind.</span>
                  <h3>Discover beyond relaxation.</h3>
                  <a href="package.php" class="btn">discover more</a>
               </div>
            </div>

            <div class="swiper-slide slide" style="background:url(images/home-slide-2.jpg) no-repeat">
               <div class="content">
                  <span>Explore. Discover. Unwind.</span>
                  <h3>Don't just live, Relax.</h3>
                  <a href="package.php" class="btn">discover more</a>
               </div>
            </div>

            <div class="swiper-slide slide" style="background:url(images/home-slide-3.jpg) no-repeat">
               <div class="content">
                  <span>Explore. Discover. Unwind.</span>
                  <h3>Indulge in Pleasant - Relaxing venues.</h3>
                  <a href="package.php" class="btn">discover more</a>
               </div>
            </div>

         </div>

         <div class="swiper-button-next"></div>
         <div class="swiper-button-prev"></div>

      </div>

   </section>

   <!-- home section ends -->

   <!-- services section starts  -->

   <section class="services">

      <h1 class="heading-title"> Our Services </h1>

      <div class="box-container">

         <div class="box">
            <img src="images/icon-1.png" alt="">
            <h3>Luxury & Comfort</h3>
         </div>

         <div class="box">
            <img src="images/icon-2.png" alt="">
            <h3>Dining & Culinary</h3>
         </div>

         <div class="box">
            <img src="images/icon-3.png" alt="">
            <h3>Wellness & Spa</h3>
         </div>

         <div class="box">
            <img src="images/icon-4.png" alt="">
            <h3>Adventure & Experiences</h3>
         </div>

         <div class="box-align1">
            <img src="images/icon-5.png" alt="">
            <h3>Exclusive & Personalized</h3>
         </div>

         <div class="box-align2">
            <img src="images/icon-6.png" alt="">
            <h3>Core Hospitality Services</h3>
         </div>

      </div>

   </section>

   <!-- services section ends -->

   <!-- home about section starts  -->

   <section class="home-about">

      <div class="image">
         <img src="images/casa-luna.jpg" alt="">
      </div>

      <div class="content">
         <h3>About Us</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt praesentium voluptatibus alias natus in
            sunt iste temporibus voluptatum pariatur rem, asperiores quos molestiae id fugiat ipsum, corporis porro,
            nihil quidem voluptate laudantium!</p>
         <a href="about.php" class="btn">read more</a>
      </div>

   </section>

   <!-- home about section ends -->

   <!-- home packages section starts  -->

   <section class="home-packages">

      <h1 class="heading-title"> Our Packages </h1>

      <div class="box-container">

         <div class="box">
            <div class="image">
               <img src="images/img-1.jpg" alt="">
            </div>
            <div class="content">
            <h3>Serenity Suite</h3>
               <p>A peaceful retreat with a balcony overlooking a garden, perfect for relaxation.</p>
               <h2>₱5,500 per night</h2>
               <div class="btn-center">
                  <a href="book.php" class="btn">Book Now</a>
               </div>
            </div>
         </div>

         <div class="box">
            <div class="image">
               <img src="images/img-2.jpg" alt="">
            </div>
            <div class="content">
            <h3>Ocean Breeze Deluxe</h3>
               <p>Spacious room with a stunning sea view and a private veranda.</p>
               <h2>₱6,800 per night</h2>
               <div class="btn-center">
                  <a href="book.php" class="btn">Book Now</a>
               </div>
            </div>
         </div>

         <div class="box">
            <div class="image">
               <img src="images/img-3.jpg" alt="">
            </div>
            <div class="content">
            <h3>Bamboo Haven</h3>
               <p>Eco-friendly room with bamboo furnishings and a tropical vibe.</p>
               <h2>₱4,200 per night</h2>
               <div class="btn-center">
                  <a href="book.php" class="btn">Book Now</a>
               </div>
            </div>
         </div>

      </div>


      <div class="load-more"> <a href="package.php" class="btn">Load More</a> </div>

   </section>

   <!-- home packages section ends -->

   <!-- home offer section starts  -->

   <section class="home-offer">
      <div class="content">
         <h3>🌟 Limited-Time Offer: Up to 50% OFF on Unforgettable Relaxation! 🌟</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet quo reprehenderit hic nisi sequi quas
            ratione illo suscipit praesentium! Molestiae, tempore quibusdam! Sint quidem tenetur accusantium ad sed
            vitae nemo commodi et iusto ea cum pariatur fugiat praesentium, architecto molestiae, quos iste ut quod.
            Perferendis optio a quod alias provident.</p>
         <a href="book.php" class="btn">Book Now</a>
      </div>
   </section>

   <!-- home offer section ends -->


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
   <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>
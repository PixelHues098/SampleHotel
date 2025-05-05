<?php
session_start();

include 'connection.php'; // Ensure you include your DB connection

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = 'book.php';
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = $connection->prepare("SELECT name, email FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BOOK NOW!</title>

   <!-- swiper css link  -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->

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

<!-- booking section starts  -->

<section class="booking">  

   <h1 class="heading-title">Book your Indulgement!</h1>

   <?php if (isset($_SESSION['user_id'])): ?>

   <!-- Show form only if user is logged in -->
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
         
            <?php $selected_package = $_GET['package'] ?? ''; ?>
         <div class="inputBox">
            <span>Package :</span>
               <input type="text" name="package" value="<?php echo htmlspecialchars($selected_package); ?>" readonly>
         </div>
         <div class="inputBox">
            <span>Number of guests:</span>
            <input type="number" placeholder="Enter number of guests" name="guests" required>
         </div>
         <div class="inputBox">
            <span>Arrivals :</span>
            <input type="date" name="arrivals" required>
         </div>
         <div class="inputBox">
            <span>Leaving :</span>
            <input type="date" name="leaving" required>
         </div>
      </div> 
      <div class="btn-center">
         <input type="submit" value="submit" class="btn" name="send">
      </div>  
   </form>

   <?php else: ?>

   <!-- Show message to login -->
   <div style="text-align:center; margin-top: 20px;">
      <p style="font-size: 18px;">You must be logged in to book a trip.</p>
      <a href="login.php" class="btn">Login</a>
      <a href="register.php" class="btn">Register</a>
   </div>

   <?php endif; ?>

</section>

<!-- booking section ends -->


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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const arrivalsInput = document.querySelector('input[name="arrivals"]');
    const leavingInput = document.querySelector('input[name="leaving"]');
    const form = document.querySelector('.book-form');
    
    // Create error message element
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.style.color = 'red';
    errorElement.style.marginTop = '10px';
    errorElement.style.display = 'none';
    leavingInput.parentNode.appendChild(errorElement);
    
    // Validate dates when either field changes
    arrivalsInput.addEventListener('change', validateDates);
    leavingInput.addEventListener('change', validateDates);
    
    // Validate before form submission
    form.addEventListener('submit', function(e) {
        if (!validateDates()) {
            e.preventDefault();
        }
    });
    
    function validateDates() {
        const arrivalsDate = new Date(arrivalsInput.value);
        const leavingDate = new Date(leavingInput.value);
        
        if (arrivalsInput.value && leavingInput.value) {
            if (leavingDate < arrivalsDate) {
                errorElement.textContent = 'Error: Leaving date cannot be before arrival date!';
                errorElement.style.display = 'block';
                leavingInput.style.borderColor = 'red';
                return false;
            } else {
                errorElement.style.display = 'none';
                leavingInput.style.borderColor = '';
            }
        }
        return true;
    }
});
</script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>




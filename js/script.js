let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');

menu.onclick = () => {
   menu.classList.toggle('fa-times');
   navbar.classList.toggle('active');
};

window.onscroll = () => {
   menu.classList.remove('fa-times');
   navbar.classList.remove('active');
};

// Initialize Swiper with proper autoplay settings
document.addEventListener('DOMContentLoaded', function() {
   // Mobile menu toggle
   let menu = document.querySelector('#menu-btn');
   let navbar = document.querySelector('.header .navbar');
   
   menu.onclick = () => {
     menu.classList.toggle('fa-times');
     navbar.classList.toggle('active');
   };
   
   window.onscroll = () => {
     menu.classList.remove('fa-times');
     navbar.classList.remove('active');
   };
 
   // Home slider with proper autoplay
   const homeSwiper = new Swiper(".home-slider", {
     loop: true,
     speed: 800,
     autoplay: {
       delay: 3000,
       disableOnInteraction: false,
     },
     navigation: {
       nextEl: ".swiper-button-next",
       prevEl: ".swiper-button-prev",
     },
     effect: 'fade',
     fadeEffect: {
       crossFade: true
     },
     on: {
       init: function() {
         console.log('Swiper initialized successfully');
       },
     }
   });
 
   // Reviews slider
   const reviewSwiper = new Swiper(".reviews-slider", {
     grabCursor: true,
     loop: true,
     autoHeight: true,
     spaceBetween: 20,
     breakpoints: {
       0: { slidesPerView: 1 },
       700: { slidesPerView: 2 },
       1000: { slidesPerView: 3 },
     }
   });
 
   // Load more functionality
   const loadMoreBtn = document.querySelector('.packages .load-more .btn');
   let currentItem = 3;
 
   if (loadMoreBtn) {
     loadMoreBtn.onclick = () => {
       let boxes = [...document.querySelectorAll('.packages .box-container .box')];
       for (let i = currentItem; i < currentItem + 3; i++) {
         if (boxes[i]) boxes[i].style.display = 'inline-block';
       };
       currentItem += 3;
       if (currentItem >= boxes.length) {
         loadMoreBtn.style.display = 'none';
       }
     }
   }
});
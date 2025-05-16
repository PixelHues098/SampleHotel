
      // Booking status modal functionality
      document.addEventListener('DOMContentLoaded', function() {
         const modal = document.getElementById('bookingModal');
         const btn = document.getElementById('bookingStatusBtn');
         const span = document.getElementsByClassName('close-modal')[0];
         
         if (btn && modal && span) {
            btn.onclick = function() {
               modal.style.display = 'block';
            }
            
            span.onclick = function() {
               modal.style.display = 'none';
            }
            
            window.onclick = function(event) {
               if (event.target == modal) {
                  modal.style.display = 'none';
               }
            }
         }
      });

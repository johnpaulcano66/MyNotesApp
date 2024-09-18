<?php
session_start();
// Destroy the session
session_destroy();
// Redirect to a different page after logout
header("Location: index.php");
exit();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logout Confirmation</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Add animation classes */
    .modal.fade .modal-dialog {
      -webkit-transform: translate(0, -50%);
      -ms-transform: translate(0, -50%);
      transform: translate(0, -50%);
      -webkit-transition: -webkit-transform 0.3s ease-out;
      transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
      -webkit-transform: translate(0, 0);
      -ms-transform: translate(0, 0);
      transform: translate(0, 0);
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Logout Confirmation</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal Body -->
        <div class="modal-body">
          <p>Are you sure you want to log out?</p>
        </div>
        
        <!-- Modal Footer -->
        <div class="modal-footer">
          <a href="notebook.php" type="button" class="btn btn-secondary">Cancel</button>
          <a href="logout_process.php" class="btn btn-primary">Logout</a>
        </div>
        
      </div>
    </div>
  </div>
  
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
  // Show the modal when the document is ready
  $('#logoutModal').modal('show');
  
  // Add animation class when modal is shown
  $('#logoutModal').on('shown.bs.modal', function (e) {
    $(this).find('.modal-dialog').addClass('animated fadeInDown');
  });
  
  // Remove animation class when modal is hidden
  $('#logoutModal').on('hidden.bs.modal', function (e) {
    $(this).find('.modal-dialog').removeClass('animated fadeInDown');
  });
});
</script>

<!-- Prevent user from going back after logout -->
<script>
  history.pushState(null, null, window.location.href);
  window.addEventListener('popstate', function () {
    history.pushState(null, null, window.location.href);
  });
</script>

</body>
</html>

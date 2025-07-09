<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Admin') {
    header("Location: login.html");
    exit();
}
?>

<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>Breezed HTML Bootstrap Template</title>

<!--

Breezed Template

https://templatemo.com/tm-543-breezed

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/templatemo-breezed.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">

    <link rel="stylesheet" href="assets/css/lightbox.css">

    </head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">
                            Hi, Admin
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="#about">About</a></li>
                            <li class="scroll-to-section"><a href="admin_report.php">Report</a></li>
                            <li class="submenu">
                                <a href="javascript:;">Admin menu</a>
                                <ul>
                                    <li><a href="admin_manage_users.php">Manage Users</a></li>
                                    <li><a href="claim_page.php">Claimed Items</a></li>
                                    <li><a href="admin_profile.php">Edit Profile</a></li>
									<li><a href="admin_appointment_list.php">View Appointments</a></li>

                                </ul>
                            </li>
                            <li class="scroll-to-section"><a href="index.html">Logout</a></li> 
                            </div>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner header-text" id="top">
        <div class="Modern-Slider">
          <!-- Item -->
          <div class="item">
            <div class="img-fill">
                <img src="assets/images/slide-01.jpg" alt="">
                <div class="text-content">
                  <h3>Lost and Found</h3>
                  <h5>Edit items listing</h5>
                 <a href="admin_lost_items.php" class="main-stroked-button">Lost Items</a>
				 <a href="admin_found_items.php" class="main-filled-button">Found Items</a>

                </div>
            </div>
          
          <!-- // Item -->
        </div>
    </div>
    <div class="scroll-down scroll-to-section"><a href="#about"><i class="fa fa-arrow-down"></i></a></div>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** About Area Starts ***** -->
    <section class="section bg-light" id="about">
  <div class="container">
    <div class="row align-items-center">
      <!-- Left Content -->
      <div class="col-lg-6 col-md-6 col-xs-12 mb-4">
        <div class="left-text-content">
          <div class="section-heading mb-4">
            <h6>Welcome to</h6>
            <h2>Kolej Malinja, UiTM Kedah</h2>
          </div>
          <p>
            At <strong>Kolej Malinja</strong>, we don’t just provide a place to sleep — we offer a thriving, supportive environment for learning, leadership, and connection. 
          </p>
          <p>
            From modern facilities to engaging programs, every student is empowered to grow personally and academically.
          </p>
          <a href="#" class="main-button-icon mt-3">
            Discover More <i class="fa fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Right Features with Font Awesome Icons -->
      <div class="col-lg-6 col-md-6 col-xs-12">
        <div class="row text-center">
          <div class="col-md-6 col-sm-6 mb-4">
            <div class="service-item">
              <i class="fa fa-users fa-3x text-primary mb-2"></i>
              <h4>Friendly Staff</h4>
              <p>Our wardens and admin team are always ready to help.</p>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 mb-4">
            <div class="service-item">
              <i class="fa fa-building fa-3x text-success mb-2"></i>
              <h4>Modern Facilities</h4>
              <p>Spacious rooms, internet access, and common areas to study or relax.</p>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 mb-4">
            <div class="service-item">
              <i class="fa fa-heart fa-3x text-danger mb-2"></i>
              <h4>Community Focus</h4>
              <p>Join clubs, events, and volunteer efforts to connect with others.</p>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 mb-4">
            <div class="service-item">
              <i class="fa fa-shield-alt fa-3x text-warning mb-2"></i>
              <h4>Safety First</h4>
              <p>24/7 security and a supportive environment for all residents.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

    <!-- ***** About Area Ends ***** -->

    <!-- jQuery -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script> 
    <script src="assets/js/slick.js"></script> 
    <script src="assets/js/lightbox.js"></script> 
    <script src="assets/js/isotope.js"></script> 
    
    <!-- Global Init -->
    <script src="assets/js/custom.js"></script>

    <script>

        $(function() {
            var selectedClass = "";
            $("p").click(function(){
            selectedClass = $(this).attr("data-rel");
            $("#portfolio").fadeTo(50, 0.1);
                $("#portfolio div").not("."+selectedClass).fadeOut();
            setTimeout(function() {
              $("."+selectedClass).fadeIn();
              $("#portfolio").fadeTo(50, 1);
            }, 500);
                
            });
        });

    </script>

  </body>
</html>
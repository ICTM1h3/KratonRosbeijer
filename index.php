<?php
session_start();

// Load the file which handles loading the actual webpage.
include 'helpers/roles.php';
include 'helpers/tabs.php';
include 'helpers/rendering.php';
include 'helpers/database.php';
include 'helpers/email.php';
include 'helpers/validators.php';
include 'helpers/config.php';
//Set default title and setup body
$pagetitle = "Kraton Rosbeijer";
$body = renderPage();

if (isset($_GET['no_layout'])) {
	echo $body;
	return;
}

$tabs = getTabsForCurrentUser();

?>

<!-- PAGE SETUP -->
<!DOCTYPE html>
<html>
<head>
    <!-- responsive meta tag-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- links to the bootstrap functions and the css stylesheet -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="css-style-sheet.css" type="text/css">
	<title><?php echo htmlentities($pagetitle); ?> </title>

    <!-- Bootstrap core CSS 
    <link href="../../../../dist/css/bootstrap.min.css" rel="stylesheet">
    -->

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">
    <style type="text/css">:root #content > #right > .dose > .dosesingle,
    :root #content > #center > .dose > .dosesingle
    {display:none !important;}</style>
</head>

<body>
    <!-- navbar with small logo in the left corner-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div>
    <a class="navbar-brand" href="#">
    <img class="imglogo" src="img/logo_kraton.png" width="30" height="30" alt="">Kraton Rosbeijer</a>
  </div>
  <div class="navbar-collapse collapse" id="navbarsExampleDefault" style="">
    <ul class="navbar-nav mr-auto">
	  <?php foreach ($tabs as $tab) { ?>
	  <li class="nav-item">
	  <a href="<?= $tab['href'] ?>"><span><?= $tab['title'] ?></span></a>
	  </li>
	  <?php } ?>
    </ul>

    <!-- registration button -->
    <form class="form-inline my-2 my-lg-0" action=""> <!-- add action form registration --> 
        <button class="btn btn-outline-light" type="submit">Registreren</button>
    </form>

     <!-- login button -->
    <form class="form-inline my-2 my-lg-0" action=""> <!-- add action form login -->
        <button class="btn btn-outline-light" type="submit">Login</button>
    </form>
  </div>
</nav>

<main role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div class="container">
        <header class="container">
          <img src="img/header_kraton.png">
        </header>
        </div>
      </div>

      <div class="container">
        <!-- Row of columns -->
        <div class="row">
          <div class="col-12 col-sm-6 col-lg-8">
		  <?php
		  echo $body;
		  ?>
          </div>
          <div class="col-6 col-lg-4">
            <!-- facebook and twitter plugins, on the right of the website -->
            <div class="sidebar ">
              <a class="text" href="https://www.facebook.com/KratonRosbeijer/">KratonRosbeijer op Facebook</a>
              <div class="fb-page" data-href="https://www.facebook.com/KratonRosbeijer/"
              data-width="250" data-small-header="false" data-adapt-container-width="true"
              data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/KratonRosbeijer/"
              class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/KratonRosbeijer/">Kraton Rosbeijer</a></blockquote>
              </div>
            </div>
            <div class="sidebar">
              <a class="twitter-follow-button" href="https://twitter.com/kratonrosbeijer">Follow @Kraton Rosbeijer</a>
              <a class="twitter-timeline" data-width="250" data-height="250" data-theme="light" href="https://twitter.com/kratonrosbeijer?ref_src=twsrc%5Etfw">Tweets by kratonrosbeijer</a>
              <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
          </div>
        <hr>
      </div> <!-- /container -->
    </main>

    <footer class="expand-lg">
      <div class="row">
        <div class="col-6 col-sm-4">
          <a class="navbar-brand text" href="#">
          <img class="imglogo" src="logo_kraton.png" width="30" height="30" alt="">Kraton Rosbeijer</a>
        </div>
        <div class="col-6 col-sm-4">
          <p class="footertext">Hamersveldseweg 57 </br>
             3833 GL Leusden </br>
             Telefoon: 033-8871111</br>
             www.kratonrosbeijer.nl</br>
             info@kratonrosbeijer.nl</br>
             www.facebook.com/KratonRosbeijer</br>
             Twitter @KratonRosbeijer
          </p>
        </div>
        <div class="col-6 col-sm-4">
        <div>
          <ul>
            <!-- add correct link to pages-->
            <li><p class="footertext">Pages</p></li>
            <li><a class="footertext" href="">Home</a></li>
            <li><a class="footertext" href="">Menu</a></li>
            <li><a class="footertext" href="">Reserveren</a></li>
            <li><a class="footertext" href="">Afhalen</a></li>
            <li><a class="footertext" href="">Cadeaubon</a></li>
            <li><a class="footertext" href="">Catering</a></li>
            <li><a class="footertext" href="">Nieuws</a></li>
            <li><a class="footertext" href="">Vacatures</a></li>
          </ul>
        </div>
        </div>
      </div>
    </footer>

<!-- script function for the facebook plugin -->
<div id="fb-root"></div>
        <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.11';
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

<!-- script at the end of the pages so it loads faster-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>
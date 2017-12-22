<?php
//set the page title to the correct name
 setTitle("Catering pagina");
//query to select the text for the cateringpage from the database
 $text = base_query("SELECT * FROM setting WHERE name = 'CateringText' ")->fetch();
?>


<div style="margin-bottom: 10px;">
<?php
  //the catering text from the database
  echo($text['Value']);
?>
<!-- the photo carousel-->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
    </ol>
    <!--the photos for in the carousel-->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="img/6.jpg" alt="Eerste slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="img/12.jpg" alt="Tweede slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="img/13.jpg" alt="Derde slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="img/18.jpg" alt="Vierde slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="img/28.jpg" alt="Vijfde slide">
    </div>
  </div>
</div>

</div>
<!-- the script the make the carousel work-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script>$('.carousel').carousel({interval : 5000})</script>
<?php
//require ('includes/config.inc.php');
$page_title = 'Food delivery site';
include ('includes/header.html');

//checking if a user is logged in 
echo '<h1> Welcome';
if (isset($_SESSION['customer_fname'])){
	echo ", {$_SESSION['customer_fname']}";
}
echo '!</h1>';
?>

<!--style for the the index page-->
<style>
.mySlides {
	display: none;
}
img {
	vertical-align: middle;
}

/* Slideshow container */
.slideshow-container {
  max-width: 300px;
  position: relative;
  margin: auto;
}

/* The dots/bullets/indicators */
.dot {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active {
  background-color: #717171;
}

/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}

@-webkit-keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .text {font-size: 11px}
}
</style>

<!-- Slideshow container -->
<div class="slideshow-container">

  <!-- Full-width images with number and caption text -->
  <div class="mySlides fade">
    <img src="upload/slideshow/ca_chowmein.jpg" style="width:100%;">
  </div>

  <div class="mySlides fade">
    <img src="upload/slideshow/cafr.jpg" style="width:100%;">
  </div>

  <div class="mySlides fade">
    <img src="upload/slideshow/tb_chow.jpg" style="width:100%;">
  </div>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
</div>

	<h1>About Us</h1>
	<p>Dine With Chinese is a western chinese restaurant and we serve the most authentic chinese western food here. Our mission is to give our customers a place to celebrate lives, special moments by offering the best food, service and repect.</p>
		
	<p>This restaurant is located in the heart of Hastings since 2005. Inspired by our ancesters, we provide delicious chinese food to our customers. Come and dine with us for some chinese kick. We value our customer's opinion so please do write reviews on our website about the food, service and more. We will try to reply to them and keep looking for ways to make our service better. </p>

<!--javascript for the slideshow in the home page-->
<script>
	var slideIndex = 0;
	showSlides();

	function showSlides() {
	  	var i;
	  	var slides = document.getElementsByClassName("mySlides");
	  	for (i = 0; i < slides.length; i++) {
	    	slides[i].style.display = "none";
	  	}
	  	slideIndex++;
	  	if (slideIndex > slides.length) {slideIndex = 1}
	  	slides[slideIndex-1].style.display = "block";
	  	setTimeout(showSlides, 2000); // Change image every 2 seconds
	}
</script>

<?php
include ('includes/footer.html');
?>



<!DOCTYPE html>
<html>

<head>
<title>home.com</title>

<link rel="stylesheet" href="style1.css">
<link rel="stylesheet" href="hom.css">
</head>

<body>
 <header>
 <img class="VBlogo" src="villbill1.png" alt="Village Bank Logo">
    <h1 class="VB3">WELCOME TO VILLBANK</h1>
    <button class="profbutt" onclick="window.location.href='myprofile.php'">
        <img class="prof" src="profile.jpg" alt="Village Bank profile">
        <p><b>Profile</b></p>
    </button>
  </header>
  
  <nav>
    <a href="villbank.php#details"><button>ABOUT US</button></a>
    <a href="villbank.php#contacts"><button>CONTACTS</button></a>
    <a href="villbank.php#help"><button>HELP</button></a>
    <a href="villbank.php#how-to"><button>HOW TO USE</button></a>
</nav>

<div class="slideshow">
    <img class="slide fade" src="pic7.jpg" alt="villBank for all">
    <img class="slide fade" src="pic8.jpg" alt="villBank for all">
    <img class="slide fade" src="pic9.jpg" alt="villBank for all">
    <img class="slide fade" src="pic10.jpg" alt="villBank for all">
</div>

<div class="homebuttons">
    <img id="VBlogob" src="villbill1.png" alt="Village Bank Logo">
    <a href="leaderaccount.php"><b>GROUP ACCOUNT</b></a><br><br>
    <hr>
    <a id="space" href="villbank.php"><b>VILLBANK</b></a>
    <a href="LOANS.php"><b>LOANS</b></a><br><br>
    <a href="TRANS.php"><b>TRANSACTIONS</b></a>
    <a href="receipt.php"><b>RECEIPTS</b></a><br><br>
</div>

<script>
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("slide");
  if (n > slides.length) {slideIndex = 1}  // Wrap to first
  if (n < 1) {slideIndex = slides.length} // Wrap to last
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[slideIndex-1].style.display = "block";
}

// Optional: Automatic slide change (adjust interval as needed)
setInterval(() => plusSlides(1), 3000); // Change slide every 3 seconds
</script>

 <footer>
 <h1 class="foot"><img class="VBlogo1" src="villbill1.png" alt="Village Bank Logo">Best group saving website.</h1>
 <a class="trust" href="aboutus">Why you can trust VILLBANK?</a>
 <hr>
 <p>About us<br>Help and info<br>Contact us<br></p>
 <p>@villbank.com.zm</p>
 <br>
 <p>VillBANK is an online platform that allows people in the community save money and at the same time borrow money as a loan with low rates. VillBank is better because it allows users to deposit and borrow money remotely as long as the group leader approves the loan for withdraw transaction. It helps in keeping track of the transactions and gives receipts of members in a transparent and reliable manner to group bank members.</p>
  </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MoveMates Property Rental</title>

  <link rel="shortcut icon" href="../src/icons/home.png">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- HEADER -->
  <!-- Navbar -->
<header class="navbar">
  <div class="logo">
    <i class="fas fa-home"></i>
    <span>MoveMates</span>
  </div>

  <nav>
    <a href="#home" class="active">Home</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
  </nav>

  <div class="user-icon" id="userIcon">
    <i class="fas fa-user-circle"></i>
  </div>
</header>

<!-- Sign In Modal -->
<div class="modal" id="signInModal">
  <div class="modal-content">
    <div class="modal-header">
      <i class="fas fa-home"></i>
      <h2>Access Your Account</h2>
    </div>

    <form id="signInForm">
      <label>Email Address</label>
      <input type="email" placeholder="Enter your email" required>

      <label>Password</label>
      <input type="password" placeholder="Enter your password" required>

      <div class="options">
        <label><input type="checkbox"> Remember me</label>
        <a href="#">Forgot Password?</a>
      </div>

      <button type="submit" class="sign-in-btn">Sign In</button>

      <p class="signup-text">
        Don’t have an account?
        <a href="#" class="signup-link">Sign up</a>
      </p>
    </form>

    <span class="close-btn" id="closeModal">&times;</span>
  </div>
</div>

<!-- Font Awesome -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
/>

  <!-- HERO / HOME SECTION -->
  <section id="home" class="hero-section">
    <div class="hero-content">
      <h1>Elevate Your Living Experience</h1>
      <p>Explore a Wide Range of Properties</p>

      <form class="search-form">
        <div class="dropdowns">
          <select>
            <option>Rent or Buy</option>
            <option>Rent</option>
            <option>Buy</option>
          </select>

          <div class="middle">
            <select>
              <option>Location</option>
              <option>Manila</option>
              <option>Cebu</option>
              <option>Davao</option>
            </select>
            <button type="button" class="search-btn">Search</button>
          </div>

          <select>
            <option>Type</option>
            <option>Apartment</option>
            <option>Room</option>
            <option>House</option>
          </select>
        </div>
      </form>
    </div>
  </section>

  <!-- NEW RESULTS SECTION -->
  <section id="search-results" class="search-results-section">
    <h2>Available Properties</h2>
    <div class="property-results">
      <div class="property-card">
        <img src="../src/images/1.jpg" alt="Property 1">
        <p>Beautiful 2-bedroom apartment in the city center.</p>
      </div>
      <div class="property-card">
        <img src="../src/images/2.jpg" alt="Property 2">
        <p>Modern family house near the park.</p>
      </div>
    </div>
  </section>

  <!-- ABOUT SECTION -->
  <section class="about-section" id="about">
  <div class="about-container">
    <div class="about-image">
      <img src="../src/images/rent.jpg" alt="About MoveMates">
    </div>
    <div class="about-content">
      <h2>About Our Platform</h2>
      <p>
        Finding a place to live shouldn’t be stressful. MoveMates was created to make property searching and advertising more transparent and efficient.
        We aim to build a trusted online community where property owners can easily list their spaces and seekers can find homes that suit their needs and budget.
      </p>

      <h3>Our Vision</h3>
      <p>
        To redefine the way people find and rent properties — making the process effortless, transparent, and secure for everyone.
      </p>

      <h3>Our Mission</h3>
      <p>
        Our mission is to connect property owners and seekers through a user-friendly platform that promotes trust, convenience, and accessibility.
      </p>

      <h3>Why Choose MoveMates?</h3>
      <ul>
        <li>🔍 <strong>Smart Search:</strong> Easily find homes that match your budget and preferences.</li>
        <li>🏠 <strong>Verified Listings:</strong> Browse only trusted and authentic property ads.</li>
        <li>💬 <strong>Direct Communication:</strong> Contact owners instantly without middlemen.</li>
        <li>🔒 <strong>Safe & Secure:</strong> Your privacy and safety are our top priorities.</li>
      </ul>
    </div>
  </div>
</section>

  <!-- CONTACT SECTION -->
 <section id="contact" class="contact-section">
  <div class="contact-container">
    <div class="contact-header">
      <h2 class="section-title">Contact Us</h2>
      <p>We’d love to hear from you! Fill out the form to get in touch.</p>
    </div>

    <form id="contactForm" class="contact-form">
      <div class="form-left">
        <div class="form-group">
          <label for="Name">Name:</label>
          <input id="Name" name="Name" type="text" placeholder="Your Name" required>
        </div>

        <div class="form-group">
          <label for="Email">Email:</label>
          <input id="Email" name="Email" type="email" placeholder="Your Email" required>
        </div>
      </div>

      <div class="form-right">
        <div class="form-group">
          <label for="Message">Message:</label>
          <textarea id="Message" name="Message" placeholder="Your Message" rows="6" required></textarea>
        </div>

        <!-- Send Message Button below message box -->
        <button type="submit" class="send-btn">Send Message</button>
      </div>
    </form>
    <div id="formStatus" class="form-status">
      <div id="toast" class="toast">Message Sent!
      </div>
    </div>
  </div>

  <footer class="footer">
    <hr>
    <p>© 2025 MoveMates. Developed by John Carl Monacillo. All rights reserved.</p>
  </footer>
</section>

  <!-- SMOOTH SCROLL SCRIPT -->
  <script>
    const resultsSection = document.querySelector('#search-results');
    resultsSection.style.display = 'none'; // hide initially

    document.querySelector('.search-btn').addEventListener('click', (e) => {
      e.preventDefault();
      resultsSection.style.display = 'block';
      resultsSection.scrollIntoView({ behavior: 'smooth' });
    });
    
   const form = document.getElementById("contactForm");
  const toast = document.getElementById("toast");

  form.addEventListener("submit", function(event) {
    event.preventDefault(); // prevent reload
    form.reset(); // clear input fields

    // Show toast
    toast.classList.add("show");

    // Hide toast after 3 seconds
    setTimeout(() => {
      toast.classList.remove("show");
    }, 3000);
  });

  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll("nav a");

  window.addEventListener("scroll", () => {
    let current = "";

    sections.forEach(section => {
      const sectionTop = section.offsetTop - 80; // adjust for header height
      const sectionHeight = section.clientHeight;
      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        current = section.getAttribute("id");
      }
    });

    navLinks.forEach(link => {
      link.classList.remove("active");
      if (link.getAttribute("href") === "#" + current) {
        link.classList.add("active");
      }
    });
  });

  // Smooth scroll on click (optional)
  navLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const targetId = link.getAttribute("href");
      document.querySelector(targetId).scrollIntoView({
        behavior: "smooth"
      });
    });
  });

  const userIcon = document.getElementById('userIcon');
const modal = document.getElementById('signInModal');
const closeModal = document.getElementById('closeModal');
const signform = document.getElementById('signInForm');

userIcon.addEventListener('click', () => {
  modal.style.display = 'flex';
});

closeModal.addEventListener('click', () => {
  modal.style.display = 'none';
});

window.addEventListener('click', (e) => {
  if (e.target === modal) modal.style.display = 'none';
});

signform.addEventListener('submit', (e) => {
  e.preventDefault();
  alert('Signed in successfully!');
  form.reset();
  modal.style.display = 'none';
});

  </script>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>My Portfolio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" href="img/icon.png">
    <link rel='stylesheet' href='css/index.css'>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header class="navbar">
  <div class="logo">
    <img src="img/icon.png" alt="icon">
    <p>KALOY</p>
  </div>

<nav>
    <a href="#home" class="active">Home</a>
    <a href="#about">About</a>
    <a href="#tools">Projects</a>
    <a href="#contact">Contact</a>
</nav>
    <a href="login.php" class="logout">Logout</a>
</header>
<section class="home-section" id="home">
    <div class="text-container">
        <p class="hello">Hello<span class="dot">,</span></p>
        <p class="name">I'm John Carl Monacillo</p>
        <p class="role">A BSIT STUDENT</p>

        <div class="buttons">
            <a href="#contact" class="btn primary">Got a Project?</a>
            <a href="#" class="btn outline">My Resume</a>
        </div>
    </div>

    <div class="img-container">
        <div class="img-bg"></div>
        <img src="img/profile.jpg" alt="profile">
    </div>
</section>


<section class="about-section" id="about">

    <div class="services">
        <div class="service-item">
            <img src="img/24.svg" alt="service1">
            <p>Web Development</p>
        </div>

        <div class="service-item">
            <img src="img/25.svg" alt="service2">
            <p>App Development</p>
        </div>

        <div class="service-item">
            <img src="img/26.svg" alt="service3">
            <p>Game Development</p>
        </div>
    </div>
    <div class="abouts">
        <h2>About Me</h2>
        <p>
            I'm passionate about technology, design, and building practical 
            solutions through code. I enjoy learning new tools, improving my skills,
            and working on projects that challenge me to think creatively.
            My goal is to grow as a developer and contribute to meaningful digital experiences.
        </p>
    </div>
</section>

<section class="tools-section" id="tools">
    <div class="project-title">
        <h1>Projects</h1>
    </div>

    <div class="projects-grid">
        <div class="project-card">
            <img src="img/1.png" alt="project_1">
            <p class="project-name">AIS</p>
            <div class="tech-stack">
                <span>HTML</span>
                <span>CSS</span>
                <span>JS</span>
                <span>PHP</span>
                <span>MySQL</span>
                <span>Bootstrap</span>
            </div>
        </div>

        <div class="project-card">
            <img src="img/3.jpg" alt="project_2">
            <p class="project-name">Flood Control</p>
            <div class="tech-stack">
                <span>GDScript</span>
            </div>
        </div>

        <div class="project-card">
            <img src="img/2.png" alt="project_3">
            <p class="project-name">MoveMates</p>
            <div class="tech-stack">
                <span>HTML</span>
                <span>CSS</span>
                <span>JS</span>
                <span>PHP</span>
                <span>MySQL</span>
            </div>
        </div>
    </div>
</section>
<div class="tools-carousel-container">
    <h2 class="tools-title">Softwares & Tools I Use</h2>

     <div class="tools-marquee">
        <div class="tools-track">
        <div class="tool-card"><img src="img/11.png" alt="HTML"></div>
        <div class="tool-card"><img src="img/13.svg" alt="CSS"></div>
        <div class="tool-card"><img src="img/14.svg" alt="Javascript"></div>
        <div class="tool-card"><img src="img/15.svg" alt="Git"></div>
        <div class="tool-card"><img src="img/11.svg" alt="Bootstrap"></div>
        <div class="tool-card"><img src="img/10.svg" alt="Visual Studio"></div>
        <div class="tool-card"><img src="img/4.svg" alt="Chatgpt"></div>
        <div class="tool-card"><img src="img/5.svg" alt="PHP"></div>
        <div class="tool-card"><img src="img/6.svg" alt="MySQL"></div>
        <div class="tool-card"><img src="img/7.svg" alt="Xampp"></div>
        <div class="tool-card"><img src="img/8.svg" alt="Canva"></div>
        <div class="tool-card"><img src="img/9.svg" alt="Java"></div>

        <div class="tool-card"><img src="img/11.png" alt="HTML"></div>
        <div class="tool-card"><img src="img/13.svg" alt="CSS"></div>
        <div class="tool-card"><img src="img/14.svg" alt="Javascript"></div>
        <div class="tool-card"><img src="img/15.svg" alt="Git"></div>
        <div class="tool-card"><img src="img/11.svg" alt="Bootstrap"></div>
        <div class="tool-card"><img src="img/10.svg" alt="Visual Studio"></div>
        <div class="tool-card"><img src="img/4.svg" alt="Chatgpt"></div>
        <div class="tool-card"><img src="img/5.svg" alt="PHP"></div>
        <div class="tool-card"><img src="img/6.svg" alt="MySQL"></div>
        <div class="tool-card"><img src="img/7.svg" alt="Xampp"></div>
        <div class="tool-card"><img src="img/8.svg" alt="Canva"></div>
        <div class="tool-card"><img src="img/9.svg" alt="Java"></div>
    </div>
    </div>
    </div>
<section class="contact-section" id="contact">

    <div class="contact-left">
        <p class="contact-subtitle">Contacts</p>
        <h1 class="contact-title">Have a project?<br>Let’s talk!</h1>

        <button class="contact-submit-left">Submit</button>
    </div>

    <form class="contact-right">
        <div class="form-group">
            <label>Name</label>
            <input type="text" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" required>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea required></textarea>
        </div>
    </form>

</section>


<footer class="footer">
    <hr>
    
    <p>© 2025 Designed and Developed by John Carl Monacillo. All rights reserved.</p>

    <div class="footer-icons">
        <a href="https://facebook.com/kaloy404" target="_blank">
            <i class="fa-brands fa-facebook-f"></i>
        </a>

        <a href="https://github.com/kaloy404" target="_blank">
            <i class="fa-brands fa-github"></i>
        </a>

        <a href="https://instagram.com/kaloy_404" target="_blank">
            <i class="fa-brands fa-instagram"></i>
        </a>
    </div>
</footer>
<script src="js/index.js"></script>
</body>
</html>
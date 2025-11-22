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

    <script src='main.js'></script>
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
            <img src="img/4.svg" alt="service1">
            <p>Web Development</p>
        </div>

        <div class="service-item">
            <img src="img/5.svg" alt="service2">
            <p>App Development</p>
        </div>

        <div class="service-item">
            <img src="img/6.svg" alt="service3">
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



<section class="contact-section" id="contact">

</section>





<footer class="footer">
    <hr>
    <p>© 2025 Designed and Developed by John Carl Monacillo. All rights reserved.</p>
  </footer>
</body>
</html>
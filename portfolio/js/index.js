// Navigation highlighting on scroll
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll("nav a");

window.addEventListener("scroll", () => {
  let current = "";

  sections.forEach(section => {
    const sectionTop = section.offsetTop - 150; // adjust for header height
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

// Smooth scroll
navLinks.forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();
    const target = document.querySelector(link.getAttribute("href"));
    target.scrollIntoView({ behavior: "smooth" });
  });
});

document.getElementById("downloadResume").addEventListener("click", function(e) {
    e.preventDefault(); // Prevent default link behavior

    // Path to your resume file
    const fileUrl = "files/Resume.pdf";  // adjust the path

    // Create a temporary link element
    const a = document.createElement("a");
    a.href = fileUrl;
    a.download = "Resume.pdf"; // file name for download
    document.body.appendChild(a);
    a.click(); // Trigger the download
    document.body.removeChild(a); // Clean up
});
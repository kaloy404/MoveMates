// Hide search results initially
const resultsSection = document.querySelector('#search-results');
if (resultsSection) resultsSection.style.display = 'none';

// Show search results when search button clicked
const searchBtn = document.querySelector('.search-btn');
if (searchBtn) {
  searchBtn.addEventListener('click', (e) => {
    e.preventDefault();
    resultsSection.style.display = 'block';
    resultsSection.scrollIntoView({ behavior: 'smooth' });
  });
}

// Contact form toast
const form = document.getElementById("contactForm");
const toast = document.getElementById("toast");

if (form && toast) {
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    form.reset();
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 3000);
  });
}

// Navigation highlighting on scroll
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll("nav a");

window.addEventListener("scroll", () => {
  let current = "";
  sections.forEach(section => {
    const sectionTop = section.offsetTop - 80;
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
    const targetId = link.getAttribute("href");
    document.querySelector(targetId).scrollIntoView({
      behavior: "smooth"
    });
  });
});

// ============================
// Modal handling + Phone input
// ============================
document.addEventListener("DOMContentLoaded", () => {
  const userIcon = document.getElementById("userIcon");
  const signInModal = document.getElementById("signInModal");
  const signUpModal = document.getElementById("signupModal");
  const closeSignIn = document.getElementById("closeSignIn");
  const closeSignUp = document.getElementById("closeSignUp");
  const openSignUp = document.getElementById("openSignUp");
  const openSignIn = document.getElementById("openSignIn");
  const errorMsg = document.querySelector("#phone-error");
  let iti = null; // track intl-tel-input instance

  // --- Helper: reset sign-up form fully ---
  function resetSignUpForm() {
    const form = signUpModal.querySelector("form");
    const phoneInput = document.querySelector("#phone");

    if (form) form.reset();

    if (phoneInput) {
      phoneInput.value = "";
      phoneInput.style.borderColor = "#ccc";
      if (errorMsg) errorMsg.style.display = "none";

      // destroy intl-tel-input
      if (iti) {
        iti.destroy();
        iti = null;
        phoneInput.classList.remove("iti-initialized");
      }
    }
  }

  // --- Helper: reset sign-in form fully ---
  function resetSignInForm() {
    const form = signInModal.querySelector("form");
    if (form) form.reset();
  }

  // --- Open Sign In ---
  if (userIcon) {
    userIcon.addEventListener("click", () => {
      signInModal.style.display = "flex";
    });
  }

  // --- Close Sign In ---
  if (closeSignIn) {
    closeSignIn.addEventListener("click", () => {
      signInModal.style.display = "none";
      resetSignInForm();
    });
  }

  // --- Close Sign Up ---
  if (closeSignUp) {
    closeSignUp.addEventListener("click", () => {
      signUpModal.style.display = "none";
      resetSignUpForm();
    });
  }

  // --- Switch to Sign Up ---
  if (openSignUp) {
    openSignUp.addEventListener("click", (e) => {
      e.preventDefault();
      signInModal.style.display = "none";
      resetSignUpForm();
      signUpModal.style.display = "flex";

      const phoneInput = document.querySelector("#phone");
      if (phoneInput) {
        // Recreate intl-tel-input every time
        iti = window.intlTelInput(phoneInput, {
          initialCountry: "auto",
          geoIpLookup: function (callback) {
            fetch("https://ipapi.co/json")
              .then((res) => res.json())
              .then((data) => callback(data.country_code))
              .catch(() => callback("us"));
          },
          preferredCountries: ["ph", "us", "jp", "ca", "gb"],
          autoPlaceholder: "aggressive",
          nationalMode: false,
          formatOnDisplay: true,
          utilsScript:
            "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
        });

        // Update placeholder dynamically
        phoneInput.addEventListener("countrychange", () => {
              phoneInput.placeholder = iti.getNumberPlaceholder();
        });

        // Prevent placeholder override when typing
        phoneInput.addEventListener("focus", () => {
          if (!phoneInput.value) {
            phoneInput.placeholder = iti.getNumberPlaceholder();
          }
        });

        // Validate on blur
        phoneInput.addEventListener("blur", () => {
          const value = phoneInput.value.trim();
          if (!value) {
            errorMsg.style.display = "none";
            phoneInput.style.borderColor = "#ccc";
            return;
          }
          if (!iti.isValidNumber()) {
            errorMsg.style.display = "block";
            phoneInput.style.borderColor = "red";
          } else {
            errorMsg.style.display = "none";
            phoneInput.style.borderColor = "#00bcd4";
          }
        });
      }
    });
  }

  // --- Switch to Sign In ---
  if (openSignIn) {
    openSignIn.addEventListener("click", (e) => {
      e.preventDefault();
      signUpModal.style.display = "none";
      resetSignUpForm(); // 🧹 Clear everything from sign-up
      signInModal.style.display = "flex";
      resetSignInForm(); // 🧼 Also reset sign-in form cleanly
    });
  }
});

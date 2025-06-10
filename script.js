document.addEventListener("DOMContentLoaded", () => {
  const sections = document.querySelectorAll("section");
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target); // animate once
        }
      });
    },
    { threshold: 0.15 }
  );

  sections.forEach((section) => {
    observer.observe(section);
  });
});

const sections = document.querySelectorAll('section');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target); // Optional: stop observing once visible
    }
  });
}, { threshold: 0.1 });

// Cookie policy script
window.addEventListener('load', () => {
  const cookieBanner = document.getElementById('cookieConsent');
  const acceptBtn = document.getElementById('acceptCookies');

  if (!localStorage.getItem('cookiesAccepted')) {
    cookieBanner.style.display = 'block';
  }

  acceptBtn.addEventListener('click', () => {
    localStorage.setItem('cookiesAccepted', 'true');
    cookieBanner.style.display = 'none';
  });
});

  function toggleChat() {
    const chatbot = document.getElementById("chatbot");
    chatbot.style.display = chatbot.style.display === "flex" ? "none" : "flex";
  }

  function sendMessage(event) {
    event.preventDefault();
    const input = document.getElementById("user-input");
    const message = input.value.trim();
    if (message === "") return;

    const chatBox = document.getElementById("chatbot-messages");

    // User message
    const userDiv = document.createElement("div");
    userDiv.className = "user-message";
    userDiv.textContent = message;
    chatBox.appendChild(userDiv);

    // Bot response
    const botDiv = document.createElement("div");
    botDiv.className = "bot-message";
    botDiv.textContent = "Thanks for your message! We'll get back to you shortly.";
    chatBox.appendChild(botDiv);

    chatBox.scrollTop = chatBox.scrollHeight;
    input.value = "";
  }

  
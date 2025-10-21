// ======== Ice Cream Shop ========

// routes 
const routes = new Set(["home", "login", "register", "products", "about", "contact"]);
const root = document.getElementById("app-root");

//change year
const yearSpan = document.getElementById("year");
if (yearSpan) yearSpan.textContent = new Date().getFullYear();

// Path 
function getBasePath() {
  return window.location.pathname.includes("/frontend/")
    ? "frontend/views/"
    : "views/";
}

//loads html
async function loadView(viewName) {
  const path = getBasePath();
  const filePath = `${path}${viewName}.html`;
  try {
    const res = await fetch(filePath, { cache: "no-cache" });
    if (!res.ok) throw new Error(`View not found: ${filePath}`);

    const html = await res.text();
    root.innerHTML = html;
    highlightActiveLink(`#${viewName}`);
    window.scrollTo({ top: 0, behavior: "instant" });
  } catch (err) {
    console.error("Fetch error:", err);
    root.innerHTML = `
      <div class="alert alert-danger mt-4">
        Could not load view: <strong>${viewName}</strong><br>
        <small>${err.message}</small>
      </div>
    `;
  }
}

// active 
function highlightActiveLink(hash) {
  document.querySelectorAll(".nav-link").forEach(link => {
    if (link.getAttribute("href") === hash) link.classList.add("active");
    else link.classList.remove("active");
  });
}

// Hash to URL  
function handleRoute() {
  const raw = window.location.hash.replace(/^#/, "").trim();
  const page = raw && routes.has(raw) ? raw : "home";
  loadView(page);
}

// hash changes 
window.addEventListener("hashchange", handleRoute);
document.addEventListener("DOMContentLoaded", handleRoute);

// ======== Ice Cream Shop ========

const API_BASE = "/api";

function getToken() { return localStorage.getItem("token"); }
function setToken(t) { t ? localStorage.setItem("token", t) : localStorage.removeItem("token"); }
function setRoleId(r) { localStorage.setItem("role_id", String(r || 2)); }
function isAdmin() { return localStorage.getItem("role_id") === "1"; }

async function api(path, { method="GET", body=null, auth=false } = {}) {
  const headers = { "Content-Type": "application/json" };
  if (auth && getToken()) headers["Authorization"] = "Bearer " + getToken();

  const res = await fetch(API_BASE + path, {
    method,
    headers,
    body: body ? JSON.stringify(body) : null
  });

  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.error || "Request failed");
  return data;
}

function applyRoleUI() {
  document.querySelectorAll("[data-admin-only='1']").forEach(el => {
    el.style.display = isAdmin() ? "" : "none";
  });
}


// routes 
const routes = new Set(["home", "login", "register", "products", "about", "contact"]);

let root; 

// change year
function updateYear() {
  const yearSpan = document.getElementById("year");
  if (yearSpan) yearSpan.textContent = new Date().getFullYear();
}

// Path 
function getBasePath() {
  return "views/";
}



// loads html
async function loadView(viewName) {
  const path = getBasePath();
  const filePath = `${path}${viewName}.html`;
  try {
    const res = await fetch(filePath, { cache: "no-store" }); 
    if (!res.ok) throw new Error(`View not found: ${filePath}`);

    const html = await res.text();
    root.innerHTML = html;
    if (viewName === "login") bindLogin();
    if (viewName === "register") bindRegister();
    if (viewName === "products") loadProducts();
    applyRoleUI();

    highlightActiveLink(`#${viewName}`);
    window.scrollTo({ top: 0, behavior: "auto" });
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
  const raw = window.location.hash.replace(/^#\/?/, "").trim().split("?")[0];
  const page = raw && routes.has(raw) ? raw : "home";
  if (!window.location.hash || !raw) {
    window.location.hash = "#home";
    return;
  }
  loadView(page);
}

// hash changes 
window.addEventListener("hashchange", handleRoute);
document.addEventListener("DOMContentLoaded", () => {
  root = document.getElementById("app-root");
  updateYear();
  handleRoute();
});

function bindLogin() {
  const form = document.getElementById("loginForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPass").value;
    const msg = document.getElementById("loginMsg");
    if (msg) msg.textContent = "";

    try {
      const res = await api("/auth/login", { method:"POST", body:{ email, password } });
      setToken(res.token);
      setRoleId(res.user.role_id);
      window.location.hash = "#products";
    } catch (err) {
      if (msg) msg.textContent = err.message;
    }
  });
}

function bindRegister() {
  const form = document.getElementById("registerForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const username = document.getElementById("regName").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const password = document.getElementById("regPass").value;
    const msg = document.getElementById("registerMsg");
    if (msg) msg.textContent = "";

    try {
      await api("/auth/register", { method:"POST", body:{ username, email, password } });
      window.location.hash = "#login";
    } catch (err) {
      if (msg) msg.textContent = err.message;
    }
  });
}

async function loadProducts() {
  const list = document.getElementById("productsList");
  if (!list) return;

  try {
    const items = await api("/products");
    list.innerHTML = items.map(p => `
      <div class="card mb-2">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-bold">${p.name ?? "No name"}</div>
              <div class="text-muted small">${p.description ?? ""}</div>
            </div>
            <button class="btn btn-sm btn-danger" data-admin-only="1" onclick="deleteProduct(${p.id})">Delete</button>
          </div>
        </div>
      </div>
    `).join("");
    applyRoleUI();
  } catch (err) {
    list.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
  }
}

window.deleteProduct = async function(id) {
  try {
    await api(`/products/${id}`, { method:"DELETE", auth:true });
    await loadProducts();
  } catch (err) {
    alert(err.message);
  }
};

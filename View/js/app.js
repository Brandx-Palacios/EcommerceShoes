document.addEventListener("DOMContentLoaded", () => {
  let sideMenu = document.querySelectorAll(".nav-link");
  let activeLink = localStorage.getItem("activeLink");

  if (activeLink) {
    // Remove 'active' class from all links
    sideMenu.forEach((link) => {
      link.parentElement.classList.remove("active");
    });

    // Add 'active' class to the stored active link
    document.querySelector(`[href="${activeLink}"]`).parentElement.classList.add("active");
  }

  sideMenu.forEach((item) => {
    item.addEventListener("click", () => {
      // Remove 'active' class from all links
      sideMenu.forEach((link) => {
        link.parentElement.classList.remove("active");
      });

      // Add 'active' class to the clicked link
      let li = item.parentElement;
      li.classList.add("active");

      // Store the href value in local storage
      localStorage.setItem("activeLink", item.getAttribute("href"));
    });
  });
});

let menuBar = document.querySelector(".menu-btn");
let sideBar = document.querySelector(".sidebar");

// Cerrar el menú por defecto al cargar la página
sideBar.classList.add("hide");

sideBar.addEventListener("mouseenter", () => {
  sideBar.classList.remove("hide");
});

sideBar.addEventListener("mouseleave", () => {
  sideBar.classList.add("hide");
});

document.addEventListener("DOMContentLoaded", () => {
  let switchMode = document.getElementById("switch-mode");
  let isDarkMode = localStorage.getItem("darkMode");

  if (isDarkMode === "true") {
    switchMode.checked = true;
    document.body.classList.add("dark");
  }

  switchMode.addEventListener("change", (e) => {
    if (e.target.checked) {
      document.body.classList.add("dark");
      localStorage.setItem("darkMode", "true");
    } else {
      document.body.classList.remove("dark");
      localStorage.setItem("darkMode", "false");
    }
  });

  // Resto de tu código...
});


let searchFrom = document.querySelector(".content nav form");
let searchBtn = document.querySelector(".search-btn");
let searchIcon = document.querySelector(".search-icon");
searchBtn.addEventListener("click", (e) => {
  if (window.innerWidth < 576) {
    e.preventDefault();
    searchFrom.classList.toggle("show");
    if (searchFrom.classList.contains("show")) {
      searchIcon.classList.replace("fa-search", "fa-times");
    } else {
      searchIcon.classList.replace("fa-times", "fa-search");
    }
  }
});

window.addEventListener("resize", () => {
  if (window.innerWidth > 576) {
    searchIcon.classList.replace("fa-times", "fa-search");
    searchFrom.classList.remove("show");
  }
  if (window.innerWidth < 768) {
    sideBar.classList.add("hide");
  }
});

if (window.innerWidth < 768) {
  sideBar.classList.add("hide");
}
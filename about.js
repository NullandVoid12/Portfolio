document.addEventListener("DOMContentLoaded", () => {
    const burgerIcon = document.getElementById("burger-icon");
    const closeBtn = document.getElementById("closebtn");
    const overlay = document.getElementById("myNav");
    const burger = document.getElementById("burger")
    
  
    // ✅ Handle burger icon click (open overlay)
    if (burgerIcon && overlay) {
      burgerIcon.addEventListener("click", () => {
        console.log("Burger menu clicked");
        overlay.style.width = "100%";
        overlay.style.zIndex = "2";
        burger.style.zIndex = "1"; 
      });
    }
  
    // ✅ Handle close button click (close overlay)
    if (closeBtn && overlay) {
      closeBtn.addEventListener("click", () => {
        overlay.style.width = "0%";
      });
    }
  
    // ✅ Sidebar toggle logic
    const elementToggleFunc = function (elem) {
      elem.classList.toggle("active");
    };
  
    const sidebar = document.querySelector("[data-sidebar]");
    const sidebarBtn = document.querySelector("[data-sidebar-btn]");
    if (sidebar && sidebarBtn) {
      sidebarBtn.addEventListener("click", function () {
        elementToggleFunc(sidebar);
      });
    }
  
    // ✅ Navigation between pages
    const navigationLinks = document.querySelectorAll('[data-nav-link]');
    const pages = document.querySelectorAll('[data-page]');
  
    navigationLinks.forEach((link, index) => {
      link.addEventListener('click', function () {
        pages.forEach((page, i) => {
          if (this.innerHTML.toLowerCase() === page.dataset.page) {
            page.classList.add('active');
            navigationLinks[i].classList.add('active');
            window.scrollTo(0, 0);
          } else {
            page.classList.remove('active');
            navigationLinks[i].classList.remove('active');
          }
        });
      });
    });
  
    // ✅ Load page from URL hash (e.g., #contact)
    const hash = window.location.hash.substring(1);
    if (hash) {
      const targetPage = document.querySelector(`[data-page="${hash}"]`);
      if (targetPage) {
        pages.forEach(p => p.classList.remove('active'));
        navigationLinks.forEach(link => link.classList.remove('active'));
  
        targetPage.classList.add('active');
        navigationLinks.forEach(link => {
          if (link.textContent.trim().toLowerCase() === hash) {
            link.classList.add('active');
          }
        });
  
        window.scrollTo(0, 0);
      }
    }
  });
  
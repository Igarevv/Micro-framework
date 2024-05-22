document.addEventListener("DOMContentLoaded", function(event) {

  const showNavbar = (toggleId, navId, bodyId, headerId) => {
    const toggle = document.getElementById(toggleId),
      nav = document.getElementById(navId),
      bodypd = document.getElementById(bodyId),
      headerpd = document.getElementById(headerId);

    const saveSidebarState = (state) => {
      localStorage.setItem('sidebarState', state);
    };

    const getSidebarState = () => {
      return localStorage.getItem('sidebarState');
    };

    if (toggle && nav && bodypd && headerpd) {
      toggle.addEventListener('click', () => {
        nav.classList.toggle('show');
        toggle.classList.toggle('bx-x');
        bodypd.classList.toggle('body-pd');
        headerpd.classList.toggle('body-pd');

        if (nav.classList.contains('show')) {
          saveSidebarState('open');
        } else {
          saveSidebarState('closed');
        }
      });

      const savedSidebarState = getSidebarState();
      if (savedSidebarState === 'open') {
        nav.classList.add('show');
        toggle.classList.add('bx-x');
        bodypd.classList.add('body-pd');
        headerpd.classList.add('body-pd');
      }
    }
  }

  showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

  const linkColor = document.querySelectorAll('.nav_link');

  function colorLink() {
    if (linkColor) {
      linkColor.forEach(l => l.classList.remove('active'));
      this.classList.add('active');

      localStorage.setItem('activeLink', this.getAttribute('href'));
    }
  }

  linkColor.forEach(l => l.addEventListener('click', colorLink));

  const currentPath = window.location.pathname;
  let activeLinkSet = false;
  linkColor.forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.add('active');
      activeLinkSet = true;
    }
  });

  if (!activeLinkSet) {
    const activeLink = localStorage.getItem('activeLink');
    if (activeLink) {
      const link = document.querySelector(`.nav_link[href="${activeLink}"]`);
      if (link) {
        link.classList.add('active');
      }
    }
  }

  let generalAddButton = document.getElementById("generalAddButton");
  let csvUploadButton = document.getElementById("csvUploadButton");
  let generalForm = document.getElementById("generalForm");
  let csvForm = document.getElementById("csvForm");

  const savedButtonState = localStorage.getItem('buttonState');

  function setActiveTab(tab) {
    document.querySelectorAll(".nav-pills button").forEach(function(btn) {
      btn.classList.remove("active");
    });

    tab.classList.add("active");
  }

  if (savedButtonState) {
    if (savedButtonState === 'generalAddButton') {
      setActiveTab(generalAddButton);
      generalForm.style.display = "flex";
      csvForm.style.display = "none";
    } else if (savedButtonState === 'csvUploadButton') {
      setActiveTab(csvUploadButton);
      csvForm.style.display = "flex";
      generalForm.style.display = "none";
    }
  }

  [generalAddButton, csvUploadButton].forEach(function(button) {
    button.addEventListener("click", function() {
      setActiveTab(button);
      if (button === generalAddButton) {
        generalForm.style.display = "flex";
        csvForm.style.display = "none";
        localStorage.setItem('buttonState', 'generalAddButton');
      } else {
        csvForm.style.display = "flex";
        generalForm.style.display = "none";
        localStorage.setItem('buttonState', 'csvUploadButton');
      }
    });
  });
});

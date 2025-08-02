//```````````````````` humberger menu
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebarMenu");
    const toggleButton = document.getElementById("toggleBtn");
    const overlay = document.getElementById("overlay");
    const subMenuItems = document.querySelectorAll(".submenu a");

    function toggleSidebar() {
        sidebar.classList.toggle("show");
        overlay.classList.toggle("show");
    }

    function closeSidebar() {
        sidebar.classList.remove("show");
        overlay.classList.remove("show");
    }

    toggleButton.addEventListener("click", toggleSidebar);
    overlay.addEventListener("click", closeSidebar);

    subMenuItems.forEach(subItem => {
        subItem.addEventListener("click", closeSidebar);
    });

    document.addEventListener("click", function (event) {
        if (
            sidebar.classList.contains("show") &&
            !event.target.closest("#sidebarMenu") &&
            !event.target.closest("#toggleBtn")
        ) {
            closeSidebar();
        }
    });
});

//```````````````````` end humberger menu

//```````````````````` dropdown
const dropdownButtons = document.querySelectorAll('.dropdown-toggle');
dropdownButtons.forEach(button => {
    button.addEventListener('click', function (event) {
        event.stopPropagation(); // Prevents click from propagating to window
        const dropdownMenu = this.nextElementSibling; // Assumes the menu is right after the button
        
        if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none'; 
        } else {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none'); // Close others
            dropdownMenu.style.display = 'block'; 
        }
    });
});
window.addEventListener('click', function(event) {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (!event.target.closest('.dropdown-toggle') && !event.target.closest('.dropdown-menu')) {
            menu.style.display = 'none';
        }
    });
});
//```````````````````` end dropdown

//`````````````````````` clock
function updateClock() {
    let now = new Date();
    let options = { timeZone: 'Asia/Tehran', hour12: false };
    
    let hours = now.toLocaleString('en-US', { ...options, hour: '2-digit' });
    let minutes = now.toLocaleString('en-US', { ...options, minute: '2-digit' });

    let day = now.toLocaleDateString('en-US', { ...options, weekday: 'long' });
    let date = now.toLocaleDateString('en-US', { ...options, year: 'numeric', month: 'long', day: 'numeric' });

    document.getElementById("clock").textContent = `${hours}:${minutes}`;
    document.getElementById("date").textContent = `${day} - ${date}`;
}
setInterval(updateClock, 1000);
updateClock();
//`````````````````````` end clock

//```````````````````` item menu
document.addEventListener("DOMContentLoaded", function () {
  const contentBoxes = document.querySelectorAll(".tab-content");
  const links = document.querySelectorAll("[data-content]");
  const allLis = document.querySelectorAll("li");

  links.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const targetId = this.getAttribute("data-content");

      links.forEach(l => l.classList.remove("active"));
      allLis.forEach(li => li.classList.remove("active"));

      this.classList.add("active");

      const li = this.closest("li");
      if (li) li.classList.add("active");

      const parentLi = li?.closest("ul")?.closest("li");
      if (parentLi) parentLi.classList.add("active");

      contentBoxes.forEach(box => box.classList.remove("active"));
      const targetBox = document.getElementById(targetId);
      if (targetBox) targetBox.classList.add("active");
    });
  });
});

//```````````````````` item menu

//```````````````````` copy url in top

  document.getElementById("btn-copy").addEventListener("click", function () {
    const copyInput = document.getElementById("copyText");
    const textToCopy = copyInput.value;

    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(textToCopy)
        .then(() => showSuccess())
        .catch(err => {
          console.error('Clipboard API failed:', err);
          fallbackCopyText(copyInput);
        });
    } else {
      fallbackCopyText(copyInput);
    }
  });

  function fallbackCopyText(inputElement) {
    inputElement.select();
    inputElement.setSelectionRange(0, 99999);

    try {
      const successful = document.execCommand('copy');
      if (successful) {
        showSuccess();
      } else {
        showError();
      }
    } catch (err) {
      console.error('Fallback copy failed:', err);
      showError();
    }
  }

  function showSuccess() {
    Swal.fire({
      icon: 'success',
      title: 'Copied!',
      text: 'The link has been copied to clipboard.',
      timer: 1500,
      customClass: {
        popup: 'custom-success'
      },
      showConfirmButton: false
    });
  }

  function showError() {
    Swal.fire({
      icon: 'error',
      title: 'Oops!',
      text: 'Failed to copy the link.',
    });
  }

//```````````````````` end copy url in top

//```````````````````` copy url in table
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".copy-link").forEach(function (link) {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const textToCopy = this.getAttribute("data-link");

      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(textToCopy)
          .then(() => showSuccess())
          .catch(err => {
            console.error("Clipboard API failed:", err);
            fallbackCopy(textToCopy);
          });
      } else {
        fallbackCopy(textToCopy);
      }
    });
  });

  function fallbackCopy(text) {
    const tempInput = document.createElement("input");
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); 
    try {
      const successful = document.execCommand("copy");
      if (successful) {
        showSuccess();
      } else {
        showError();
      }
    } catch (err) {
      console.error("Fallback copy failed:", err);
      showError();
    }
    document.body.removeChild(tempInput);
  }

  function showSuccess() {
    Swal.fire({
      icon: 'success',
      title: 'Copied!',
      text: 'The link has been copied to clipboard.',
      timer: 1500,
      customClass: {
        popup: 'custom-success'
      },
      showConfirmButton: false
    });
  }

  function showError() {
    Swal.fire({
      icon: 'error',
      title: 'Oops!',
      text: 'Failed to copy the link.',
    });
  }
});
//```````````````````` copy url in table
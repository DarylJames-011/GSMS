const btn = document.getElementById("profilebtn");
const dropdown = document.getElementById("dropdown");

function closeDropdown() {
  dropdown.classList.remove("opacity-100", "scale-100", "translate-y-0", "pointer-events-auto");
  dropdown.classList.add("opacity-0", "scale-95", "-translate-y-2", "pointer-events-none");
}

function openDropdown() {
  dropdown.classList.remove("opacity-0", "scale-95", "-translate-y-2", "pointer-events-none");
    dropdown.classList.add("opacity-100", "scale-100", "translate-y-0", "pointer-events-auto");
}

btn.addEventListener("click", (e) => {
  e.stopPropagation(); // Prevent the document click listener from closing it immediately

  if (dropdown.classList.contains("opacity-100")) {
    closeDropdown();
  } else {
    openDropdown();
  }
});

// Close if clicked outside
document.addEventListener("click", (e) => {
  if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
    closeDropdown();
  }
});



 export function showSnackbar(message, type = "info", duration = 3000) {
    const snackbar = document.getElementById("snackbar");

    // Set text
    snackbar.textContent = message;

    // Background color
    const colors = { success:"#16a34a", error:"#dc2626", info:"#2563eb" };
    snackbar.style.backgroundColor = colors[type] || colors.info;

    // Show
    snackbar.style.opacity = "1";
    snackbar.style.pointerEvents = "auto";
    snackbar.style.transform = "translateX(-50%) translateY(0)";

    // Hide after duration
    setTimeout(() => {
        snackbar.style.opacity = "0";
        snackbar.style.pointerEvents = "none";
        snackbar.style.transform = "translateX(-50%) translateY(50px)"; // slide down
    }, duration);
}
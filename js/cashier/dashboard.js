  const modal = document.getElementById("modal");
  const overlay = document.getElementById("overlay");
  const modalContent = document.getElementById("modalContent");
  const btnYes = document.getElementById("modalConfirm");
  const btnNo = document.getElementById("modalcancel");

    function updateDateTime() {
    const now = new Date();

    const date = now.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });

    const time = now.toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit',
    });

    document.getElementById("time").textContent = `${time}`;
    document.getElementById("date").textContent = `${date}`;
  }

  updateDateTime();
  setInterval(updateDateTime, 1000);


  function openModal(
    title,
    message,
    confirmCallback,
    confirmColor = "#FF7979",
    noColor = "#1A2F58",
    confirmBorderColor = null,
    noBorderColor = null
  ) {
    // Set text
    document.getElementById("title").textContent = title;
    document.getElementById("desc").textContent = message;

    // Set button colors
    btnYes.style.backgroundColor = confirmColor;
    btnYes.style.borderColor = confirmBorderColor || confirmColor;

    btnNo.style.backgroundColor = noColor;
    btnNo.style.borderColor = noBorderColor || noColor;

    // Set button actions
    btnYes.onclick = () => {
      confirmCallback();
      closeModal();
    };

    btnNo.onclick = closeModal;

    // Show modal
    modal.classList.remove("opacity-0", "invisible", "pointer-events-none");
    modal.classList.add("opacity-100");

    overlay.classList.remove("opacity-0", "invisible");
    overlay.classList.add("opacity-100");

    modalContent.classList.remove("opacity-0", "scale-95");
    modalContent.classList.add("opacity-100", "scale-100");
  }

  function overlays() {
    modal.classList.remove("opacity-0", "invisible", "pointer-events-none");
    modal.classList.add("opacity-100");

    overlay.classList.remove("opacity-0", "invisible");
    overlay.classList.add("opacity-100");

      overlay.addEventListener("click", overlayclose);
  }


   const panel = document.getElementById('shiftPanel');

 function openPanel() {

  setTimeout(() => {
    overlays();
  }, 500);

  setTimeout(() => {
    panel.classList.remove("translate-x-full");
    panel.classList.add("translate-x-0");
  }, 400);

}

function closePanel() {
  overlayclose();
  panel.style.transitionDelay = "100ms"; // no delay for closing
  panel.classList.remove("translate-x-0");
  panel.classList.add("translate-x-full");
}


  function overlayclose() {
    modal.classList.remove("opacity-100");
    modal.classList.add("opacity-0");

    overlay.classList.remove("opacity-100");
    overlay.classList.add("opacity-0");
   
     setTimeout(() => {
      modal.classList.add("invisible", "pointer-events-none");
      overlay.classList.add("invisible");
    }, 300);

  }

  function closeModal() { 

  panel.style.transitionDelay = "100ms"; // no delay for closing
  panel.classList.remove("translate-x-0");
  panel.classList.add("translate-x-full");

    modal.classList.remove("opacity-100");
    modal.classList.add("opacity-0");

    overlay.classList.remove("opacity-100");
    overlay.classList.add("opacity-0");

    modalContent.classList.remove("opacity-100", "scale-100");
    modalContent.classList.add("opacity-0", "scale-95");

    setTimeout(() => {
      modal.classList.add("invisible", "pointer-events-none");
      overlay.classList.add("invisible");
    }, 300);
  }

  overlay.addEventListener("click", closeModal);

  function showSnackbar(message, type = "info", duration = 3000) {
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

 Chart.defaults.font.family = "'Inter', sans-serif";
  Chart.defaults.font.size = 10;

  const ctx = document.getElementById('line');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [
        {
          label: 'This Week',
          data: [120, 150, 90, 180, 160, 200, 220],
          borderColor: '#1F3A69',
          backgroundColor: 'rgba(59, 130, 246, 0.2)',
          tension: 0.3,
          fill: true,
          pointRadius: 4
        },
        {
          label: 'Last Week',
          data: [100, 140, 110, 170, 150, 190, 210],
          borderColor: '#ad0741',
          backgroundColor: 'rgba(184, 22, 79, 0.2)',
          tension: 0.3,
          fill: true,
          borderDash: [5, 5],
          pointRadius: 4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            font: { size: 10 }
          }
        },
        tooltip: {
          titleFont: { size: 10 },
          bodyFont: { size: 10 }
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Day of the Week',
            font: { size: 10 }
          },
          ticks: {
            font: { size: 10 }
          }
        },
        y: {
          title: {
            display: true,
            text: 'Sales ($)',
            font: { size: 10 }
          },
          ticks: {
            font: { size: 10 },
            beginAtZero: true
          }
        }
      }
    }
  });


const ctx1 = document.getElementById('trends');

  new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [
        {
          label: 'Gasoline',
          data: [120, 140, 130, 150, 160, 180, 170],
          backgroundColor: '#3B82F6' // blue
        },
        {
          label: 'Diesel',
          data: [200, 220, 210, 230, 240, 260, 250],
          backgroundColor: '#B7CCF0' // amber
        },
        {
          label: 'Lubricants',
          data: [30, 25, 28, 35, 40, 45, 42],
          backgroundColor: '#BDE3FF' // green
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 12,
          }
        },
        tooltip: {
          mode: 'index',
          intersect: false
        }
      },
      scales: {
        x: {
          stacked: true,
          ticks: {
            font: { size: 10 }
          }
        },
        y: {
          stacked: true,
          ticks: {
            font: { size: 10 },
            beginAtZero: true
          }
        }
      }
    }
  });

let shiftStartTime = null;
let shiftTimerInterval = null;

const shiftbutton = document.getElementById('shift_btn');
const btn_ttl = document.getElementById('shift_ttl');
const btn_des = document.getElementById('shift_des');
const clockimg = document.getElementById('clock_img');
const statusColorEl = document.getElementById('status_color');
document.addEventListener('DOMContentLoaded', () => {
    loadActiveShift();
});

function loadActiveShift() {
    fetch('../config/active_shift.php', {
        method: 'GET',
        credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
        if (data.shift_start) {
            onShift = true;
            shiftStartTime = new Date(data.shift_start);
            updateShiftStatus();
            // Update every minute
            shiftTimerInterval = setInterval(updateShiftStatus, 60000);
            updateButtonUI();

            if (statusColorEl) {
                statusColorEl.style.backgroundColor = "#32a836"; // overrides Tailwind class
            }


        }
    })
    .catch(err => console.error('Fetch error:', err));
}

let onShift = false;

function setShiftActiveUI() {

    btn_ttl.textContent = "Shift in Progress...";
    btn_des.textContent = "Click to end your shift.";

    shiftbutton.style.backgroundColor = "#FFEBB5";
    shiftbutton.style.borderColor = "#7D7325";
    shiftbutton.style.boxShadow = "0 13px 20px 1px rgba(172, 172, 68, 0.62)";

    btn_ttl.style.color = "#69691F";
    btn_des.style.color = "#7D7325";

    clockimg.src = "../assets/clock_activ.png";
}

function setShiftInactiveUI() {

    btn_ttl.textContent = "Start Shift";
    btn_des.textContent = "Click to start your shift.";

    shiftbutton.style.backgroundColor = "#BDE3FF";
    shiftbutton.style.borderColor = "#1E5780";
    shiftbutton.style.boxShadow = "0 13px 20px 1px rgba(68,129,172,0.62)";

    btn_ttl.style.color = "#1A2F58";
    btn_des.style.color = "#1E5780";

    statusColorEl.style.backgroundColor = "red"; // overrides Tailwind class
    document.getElementById('shift_status').textContent = "Shift not started yet";

    clockimg.src = "../assets/clock.png";
}

function updateButtonUI() {
    if (onShift) {
        setShiftActiveUI();
    } else {
        setShiftInactiveUI();
    }
}

shiftbutton.addEventListener("click", function () {

    if (!onShift) {
        // Start Shift modal colors
        openModal(
            "Start Shift",
            "Are you sure you want to start your shift?",
            async () => {
                startShift();
                onShift = true;
                updateButtonUI();
                showSnackbar("Shift Started", "success");
            },
            '#1A2F58',    
            '#FF7979',  
            '#1A2F58',   
            '#A00000'
        );

    } else {
        // End Shift modal colors
        openModal(
            "End Shift",
            "Are you sure you want to end your shift?",
            async () => {
                endShift();
            },
           '#FF7979' , '#1A2F58', '#A00000', '#1A2F58'   // hover color
        );
    }

});

async function endShift() {
    try {
        const response = await fetch('../config/end_shift.php', {
            method: 'POST',
            credentials: 'include',
        });
        const data = await response.json();

        if (data.message) {
            onShift = false;
            updateButtonUI();
            showSnackbar('Shift Ended', 'success');
            openPanel();
        } else if (data.error) {
            console.error(data.error);
            alert(data.error);
        }
    } catch (err) {
        console.error('Fetch error:', err);
    }
}


function startShift() {
    fetch('../config/start_shift.php', {
        method: 'POST',
        credentials: 'include',   // include session so PHP knows the user
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())  // parse JSON from PHP
    .then(data => {
        if (data.shift_start) {
            
            onShift = true;
            shiftStartTime = new Date(data.shift_start);
            if (shiftTimerInterval) clearInterval(shiftTimerInterval);
            shiftTimerInterval = setInterval(updateShiftStatus, 60000); 
            updateButtonUI();
            updateShiftStatus();
            if (statusColorEl) {
                statusColorEl.style.backgroundColor = "#32a836"; // overrides Tailwind class
            }

            
        } 
        else if (data.error) {
            console.error('Error:', data.error);
        }
        
    })
    .catch(error => console.error('Fetch error:', error));
}

function updateShiftStatus() {
    if (!shiftStartTime) return;
  
    const now = new Date();
    const diffSeconds = Math.floor((now - shiftStartTime) / 1000);
    let displayText = "Shift started ";

  

    if (diffSeconds < 60) {
        displayText += "a few seconds ago";
    } else if (diffSeconds < 3600) {
        const minutes = Math.floor(diffSeconds / 60);
        displayText += `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
    } else if (diffSeconds < 86400) {
        const hours = Math.floor(diffSeconds / 3600);
        displayText += `${hours} hour${hours !== 1 ? 's' : ''} ago`;
    } else {
        const days = Math.floor(diffSeconds / 86400);
        displayText += `${days} day${days !== 1 ? 's' : ''} ago`;
    }

    document.getElementById('shift_status').textContent = displayText;
}

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

fetchShift();

async function fetchShift() {
    try {
        const response = await fetch('../config/active_shift.php', { credentials: 'include' });
        const data = await response.json();
        shiftStart = data.shift_start; // update global variable
        updateShiftIndicator(shiftStart);
    } catch (err) {
        console.error('Error fetching shift:', err);
    }
}


function fetchTranasction() {
    fetch("../config/dashboard.php?action=getTransaction")
    .then(response => response.json())
    .then(data => {

        const container = document.getElementById("transactionContainer");

        let html = "";

        data.forEach(transaction => {

            const formattedTotal = Number(transaction.total_amt).toLocaleString('en-PH', {
                style: 'currency',
                currency: 'PHP'
            });

            html += `
            <div class="transaction-card w-full p-3 flex flex-row gap-2 rounded-lg hover:bg-[#EBEBEB] transition-colors"
            data-transaction="${transaction.transaction_id}"
            data-date="${transaction.date_created}"
            data-status="${transaction.status}">

                <img class="status-icon" src="../assets/Check1.png">

                <div class="flex flex-col gap-3 justify-between w-full">

                    <div class="flex flex-row justify-between">
                        <button class="font-semibold transopen">
                            <u>${transaction.transaction_no}</u>
                        </button>
                        <span>${transaction.date_created}</span>
                    </div>

                    <div class="flex flex-row justify-between">
                        <span>${transaction.payment_method}</span>
                        <span>${formattedTotal}</span>
                    </div>

                </div>

            </div>
            `;
        });

        container.innerHTML = html;

        document.querySelectorAll('.transaction-card').forEach(card => {
            const status = card.dataset.status; // get status from data-status
            const img = card.querySelector('.status-icon'); // target the img element

            if (status === 'Void') {
                img.src = '../assets/Void.png'; // use void image
            } 
            else {
                img.src = '../assets/Check1.png'; // default / completed image
            }
        });


        
    });

}

function timeAgo(dateString) {
    const now = new Date();
    const past = new Date(dateString);
    const diffMs = now - past; // difference in milliseconds
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHour = Math.floor(diffMin / 60);

    if (diffSec < 10) return 'a few seconds ago';
    if (diffSec < 60) return `${diffSec} seconds ago`;
    if (diffMin === 1) return 'a minute ago';
    if (diffMin < 60) return `${diffMin} minutes ago`;
    if (diffHour === 1) return 'an hour ago';
    if (diffHour < 24) return `${diffHour} hours ago`;
    return 'more than a day ago';
}

const log_btn = document.getElementById('log-btn');

log_btn.onclick = () => {
    openModal1('logout');
    document.getElementById('log-out').onclick = () => {
                endShift();
                        setTimeout(() => {
                        window.location.href='../config/logout.php';
                        }, 500); // 500ms delay to give endShift time to complete
                }
}

async function endShift() {
    try {
        const response = await fetch('../config/end_shift.php', {
            method: 'POST',
            credentials: 'include', // important for session
        });

        const data = await response.json();

        if (response.ok && !data.error) {
            
            shiftStart = null; // no active shift
            
            updateShiftIndicator(null); // update button and status
            updateShiftCards();
            showSnackbar('Shift has been ended', 'success');
            closeform();
        } else {
            console.error(data.error);
            alert('Error ending shift: ' + (data.error || 'Unknown'));
        }
    } catch (err) {
        console.error(err);
    }
}


function updateShiftIndicator(shiftStart) {
    const statusColor = document.getElementById("status_color");
    const statusText = document.getElementById("shift_status");

    if (!shiftStart) {
        // Shift not started
        statusColor.classList.remove('bg-green-500');
        statusColor.classList.add('bg-red-500');
        statusText.innerText = "Shift not started yet";

        // Reset button to Start Shift
        const shift_title = document.getElementById('shift_t');
        const shift_body = document.getElementById('shift_s');
        const shift_icon = document.getElementById('start-icon');
        const s_icon = document.getElementById('icon');

        shift_title.textContent = "Start your Shift";
        shift_body.textContent = "Start your shift to begin recording transactions.";
        shift_icon.classList.remove("bg-[#FFBDBD]");
        shift_icon.classList.add("bg-[#B6FFAF]");
        s_icon.classList.remove("text-[#A34E4E]");
        s_icon.classList.add("text-[#55A34E]");

        start_btn.dataset.shiftActive = 'false';
    } else {
        // Shift is active
        shiftbtn();
        statusColor.classList.remove('bg-red-500');
        statusColor.classList.add('bg-green-500');
        statusText.innerText = `Started ${timeAgo(shiftStart)}`;

        start_btn.dataset.shiftActive = 'true';
    }
}


async function initShiftIndicator() {
    // fetch active shift from PHP
    const response = await fetch('../config/active_shift.php', { credentials: 'include' });
    const data = await response.json();
    shiftStart = data.shift_start;

    // update immediately
    updateShiftIndicator(shiftStart);

    // update every 30 seconds (no need to do every second for “time ago”)
    setInterval(() => updateShiftIndicator(shiftStart), 30000);
}
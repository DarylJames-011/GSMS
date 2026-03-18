const modalContent = document.getElementById("modalContent");
const modalWrapper = document.getElementById('wrapper');
const modalOverlay = document.getElementById('overlay');

const announcementStyles = {
    general: {
        bg: '#FAFAFF',
        img: '../assets/general.png',
        border: '#1A2F58'
    },
    alert: {
        bg: '#FFD4D4',
        img: '../assets/alert.png',
        border: '#D43131'
    },
    update: {
        bg: '#F3FFF6',
        img: '../assets/update.png',
        border: '#459A5F'
    }
};


function openModal1(templateId) {
  const template = document.getElementById(templateId);
  modalContent.innerHTML = "";
  modalContent.appendChild(template.content.cloneNode(true));

  modalWrapper.classList.remove('opacity-0', 'pointer-events-none');
  requestAnimationFrame(() => {
    modalOverlay.classList.remove('opacity-0');
    modalContent.classList.remove('opacity-0', 'translate-y-5');
  });

 
}

async function loadFuelPrices() {
    try {
        const response = await fetch('../config/admin/dashboard.php?action=getfuelprice');
        const data = await response.json();

        if (!data.success) {
            console.error("Failed to load fuel prices");
            return;
        }

        const prices = data.data;

        document.getElementById('diesel-price').textContent =
            `₱${parseFloat(prices.Diesel).toFixed(2)}`;

        document.getElementById('premium-price').textContent =
            `₱${parseFloat(prices.Premium).toFixed(2)}`;

        document.getElementById('unleaded-price').textContent =
            `₱${parseFloat(prices.Unleaded).toFixed(2)}`;

    } catch (error) {
        console.error("Error fetching fuel prices:", error);
    }
}


async function loadRevenue() {
    try {
        const response = await fetch('../config/admin/dashboard.php?action=getAlltotal');
        const data = await response.json();

        if (data.success) {
            const revenueCard = document.querySelector('#allTimeRevenue'); 
            revenueCard.textContent = `₱ ${data.revenue}`;
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

async function loadDailyRevenue() {
    try {
        const response = await fetch('../config/admin/dashboard.php?action=getTodaytotal');
        const data = await response.json();

        if (data.success) {
            const dailyRevenue = document.querySelector('#dailyRevenue');
            dailyRevenue.textContent = `₱ ${data.revenue}`;
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

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

document.getElementById('chn-btn').onclick = () => {
    openModal1('changeprice');
    loadFuelPrices();
    

    document.getElementById('goconfirm').onclick = () => {
    const diesel = document.getElementById('diesel-input').value.trim();
    const premium = document.getElementById('premium-input').value.trim();
    const unleaded = document.getElementById('unleaded-input').value.trim();

        if (!diesel && !premium && !unleaded) {
        alert("Please enter at least one fuel price to change.");
        return; // stop execution
    }

        openModal1('confirmchg');
    confirmchange({
        diesel,
        premium,
        unleaded
    });

    }
}

const filterSelect = document.getElementById('sales-filter');
const tbody = document.getElementById('sales-tbody');

async function loadSalesOverview(filter = 'Today') {
    try {
        const response = await fetch(`../config/admin/dashboard.php?action=fetchrevenue&filter=${filter}`);
        const data = await response.json();
             
        tbody.innerHTML = '';

        if (!data.success || data.sales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3">No sales data</td></tr>';
            return;
        }

        data.sales.forEach(item => {
            const row = document.createElement('tr');
            row.className = "bg-[#F5F5F6] even:bg-[#E3E7F4]";
            row.innerHTML = `
                <td class="px-1 py-1">${item.product_name}</td>
                <td class="px-1 py-1">${Number(item.units_sold).toFixed(2)} ${item.unit_type}</td>
                <td class="px-1 py-1">₱${Number(item.revenue).toLocaleString()}</td>
            `;
            tbody.appendChild(row);
        });
    } catch (err) {
        console.error("Error loading sales overview:", err);
    }
}

// Initial load
loadSalesOverview();

// Update table when filter changes
filterSelect.addEventListener('change', () => {
    loadSalesOverview(filterSelect.value);
});

function confirmchange(prices) {
    document.getElementById('savechange').onclick = () => {
        changeprice(prices);
    }

}

async function changeprice(prices) {
    try {
        const response = await fetch('../config/admin/dashboard.php?action=changeprice', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(prices)
        });

        const data = await response.json();

        if (!data.success) {
            console.error(data.message);
            return;
        }

        closeform();
        showSnackbar('Price Changed Successfully','success');

        // optional: refresh prices on UI
        loadFuelPrices();

    } catch (error) {
        console.error("Error updating prices:", error);
    }
}


async function updateFuelCard() {
    try {
        const response = await fetch('../config/admin/dashboard.php?action=getfuelcap');
        const data = await response.json();

        if (!data.success) return console.error(data.message);

        data.fuels.forEach(fuel => {
            const row = document.querySelector(`#fuel-${fuel.type}`);
            if (!row) return;

            const bar = row.querySelector('.bar-fill');
            const percentLabel = row.querySelector('.percent-label');

            if(fuel.percent > 50){
                bar.className = 'bar-fill h-full bg-green-500 transition-all duration-500';
            } else if(fuel.percent > 20){
                bar.className = 'bar-fill h-full bg-yellow-500 transition-all duration-500';
            } else {
                bar.className = 'bar-fill h-full bg-red-500 transition-all duration-500';
            }

            bar.style.width = `${fuel.percent}%`;
            percentLabel.textContent = `${fuel.percent}%`;
        });
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

async function updateLowStockCard() {
    try {
        const response = await fetch('../config/admin/dashboard.php?action=lowstock');
        const data = await response.json();

        if (!data.success) return console.error(data.message);

        const card = document.getElementById('low-stock-card');
        const text = document.getElementById('low-stock-text');
        const img = document.getElementById('low-stock-img');
        const title = document.getElementById('low-title');

        if (data.lowCount === 0) {
            // All stocks okay → green
            card.style.backgroundColor = '#D4FFE1';
            card.style.borderColor = '#38AC5B';
            title.style.color = '#5BA070';
            text.style.color = '#53A36C';
            text.textContent = "All stocks are fine!";
            img.src = "../assets/Check.png";
        } else {
            // Low stock → red
            card.style.backgroundColor = '#FFE1D4';
            card.style.borderColor = '#F56565';
            title.style.color = '#F56565';
            text.style.color = '#F56565';
            text.textContent = `${data.lowCount} product(s) low in stock`;
            img.src = "../assets/Error.png";
        }

    } catch (error) {
        console.error('Fetch error:', error);
    }
}

updateLowStockCard();
setInterval(updateLowStockCard, 30000);
updateFuelCard();
loadDailyRevenue();
loadRevenue();

function closeform() { 
  modalContent.classList.add('opacity-0', 'translate-y-5'); 
  modalOverlay.classList.add('opacity-0'); 
  setTimeout(() => { modalWrapper.classList.add('opacity-0', 'pointer-events-none'); 
    modalContent.innerHTML = ""; }, 500); 

} 

document.getElementById('ann-btn').onclick = () => {
    openModal1('addann');
    announcement();
}

function announcement() {
    const typeSelect = document.getElementById('announcement-type');
    const iconDiv = document.getElementById('announcement-icon');
    const iconImg = document.getElementById('announcement-img');

typeSelect.addEventListener('change', () => {
    const selected = typeSelect.value; // general / alert / update
    const style = announcementStyles[selected];

    iconDiv.style.backgroundColor = style.bg;
    iconDiv.style.borderColor = style.border;
    iconImg.src = style.img;
});

document.getElementById('create-announcement').addEventListener('click', async () => {
    // grab values
    const type = document.getElementById('announcement-type').value;
    const title = document.getElementById('announcement-title').value.trim();
    const body = document.getElementById('announcement-body').value.trim();

    if (!title || !body) {
        alert("Please fill in both title and body.");
        return;
    }

    try {
        const response = await fetch('../config/admin/dashboard.php?action=createannounce', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ type, title, body })
        });

        const data = await response.json();

        if (data.success) {
            showSnackbar('Announcement created successfully','success');
            
            // optionally clear fields
            document.getElementById('announcement-title').value = '';
            document.getElementById('announcement-body').value = '';
            document.getElementById('announcement-type').value = 'general';
            closeform();
        } else {
            alert("Error: " + data.message);
        }

    } catch (error) {
        console.error("Error sending announcement:", error);
    }
});
}


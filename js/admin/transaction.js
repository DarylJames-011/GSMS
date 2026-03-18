const revenueText = document.getElementById('revenue-text');
const revenueFilter = document.getElementById('revenue-filter');

async function loadRevenue(filter = 'Today') {
    try {
        const response = await fetch(`../config/admin/transaction.php?action=fetchrevenue&filter=${filter}`);
        const data = await response.json();

        if (!data.success) return;

        revenueText.textContent = `₱ ${Number(data.revenue).toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
        })}`;

    } catch (err) {
        console.error("Error loading revenue:", err);
    }
}

// initial load
loadRevenue();

// filter change
revenueFilter.addEventListener('change', () => {
    loadRevenue(revenueFilter.value);
});

const litersText = document.getElementById('liters-text');
const fuelFilter = document.getElementById('fuel-filter');

async function loadLiters(filter = 'All') {
    try {
        const response = await fetch(
            `/ALPHA%20STAGE/config/admin/transaction.php?action=fetchliters&filter=${encodeURIComponent(filter)}`
        );

        const data = await response.json();

        if (!data.success) {
            console.error("Failed to fetch liters");
            return;
        }

        litersText.textContent = `${Number(data.liters).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })} L`;

    } catch (err) {
        console.error("Error loading liters:", err);
    }
}

// initial load
loadLiters();

// when dropdown changes
fuelFilter.addEventListener('change', () => {
    loadLiters(fuelFilter.value);
});

const searchInput = document.getElementById('transaction-search');
const transactionsTbody = document.getElementById('full-transactions-tbody');

// Store the latest loaded transactions for filtering
let allTransactions = [];

// Modify your loadAllTransactions function to save results
async function loadAllTransactions() {
    try {
        const response = await fetch(`/ALPHA%20STAGE/config/admin/transaction.php?action=fetchalltransactions`);
        const data = await response.json();
        
        if (!data.success) return;
        console.log(data);
        allTransactions = data.transactions; // save globally
        renderTransactions(allTransactions);
        

    } catch (err) {
        console.error("Error loading full transactions:", err);
    }
}

// Function to render table rows
function renderTransactions(transactions) {
    transactionsTbody.innerHTML = '';
    if (transactions.length === 0) {
        transactionsTbody.innerHTML = `<tr><td colspan="6">No transactions found</td></tr>`;
        return;
    }

    transactions.forEach((t, index) => { // <--- notice index for even-row
        const row = document.createElement('tr');
        row.className = "cursor-pointer transition-colors";

        // Set even/odd background
        const evenBg = index % 2 === 1 ? '#DEEAFF' : '#F5F5F6';
        row.style.backgroundColor = evenBg;

        // Hover effect
        row.addEventListener('mouseenter', () => row.style.backgroundColor = '#8BA2D0'); // black on hover
        row.addEventListener('mouseleave', () => row.style.backgroundColor = evenBg); // revert

        // Format date
        const date = new Date(t.date_created);
        const formattedDate = date.toLocaleString('en-PH', {
            month: '2-digit',
            day: '2-digit',
            year: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Status color
        let statusColor = "bg-gray-500";
        if (t.status === "Confirmed") statusColor = "bg-[#38AC5B]";
        if (t.status === "Void") statusColor = "bg-red-500";

        row.innerHTML = `
            <td class="px-2 py-2">${t.transaction_no}</td>
            <td class="px-2 py-2">${formattedDate}</td>
            <td class="px-2 py-2">${t.payment_method}</td>
            <td class="px-2 py-2">${t.cashier_name}</td>
            <td class="px-2 py-2">₱${Number(t.total_amt).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td class="px-2 py-2">
                <p class="${statusColor} px-1 rounded-full text-white text-[10px]">
                    <span>${t.status}</span>
                </p>
            </td>
        `;

        row.onclick = () => showTransactionDetails(t.transaction_id);

        transactionsTbody.appendChild(row);
    });
}

async function showTransactionDetails(transactionId) {
    try {
        const response = await fetch(`/ALPHA%20STAGE/config/admin/transaction.php?action=fetchtransactiondetails&id=${transactionId}`);
        const data = await response.json();
        console.log(data);
        if (!data.success) return;

        // Hide empty panel, show details
        document.getElementById('transaction-details').classList.add('hidden');
        document.getElementById('transaction-info').classList.remove('hidden');

        // Fill info
        document.getElementById('detail-id').textContent = data.transaction.transaction_no;
        document.getElementById('detail-datetime').textContent = new Date(data.transaction.date_created).toLocaleString('en-PH');
        document.getElementById('detail-payment').textContent = data.transaction.payment_method;
        document.getElementById('detail-reference').textContent = data.transaction.reference_num ?? 'N/A';
        document.getElementById('detail-total').textContent = `₱${Number(data.transaction.total_amt).toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 })}`;
        document.getElementById('detail-change').textContent = `₱${Number(data.transaction.amt_received - data.transaction.total_amt).toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 })}`;

        // Fill items table
        const tbody = document.getElementById('detail-items');
        tbody.innerHTML = '';
        let total = 0;

        data.items.forEach(item => {
            const subtotal = Number(item.subtotal);
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-1 ">${item.product_name}</td>
                <td class="py-1">${item.quantity}</td>
                <td class="py-1">₱${subtotal.toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 })}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('transtotal').textContent = total.toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 });

    } catch (err) {
        console.error("Error loading transaction details:", err);
    }
}

// Event listener for search
searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    const filtered = allTransactions.filter(t =>
        t.transaction_no.toLowerCase().includes(query) ||
        t.cashier_name.toLowerCase().includes(query) ||
        t.payment_method.toLowerCase().includes(query) ||
        t.status.toLowerCase().includes(query)
    );
    renderTransactions(filtered);
});

// load it
loadAllTransactions();
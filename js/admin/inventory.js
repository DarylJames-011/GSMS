import { showSnackbar } from './general.js';

function initializeFuelForm() {
  const form = modalContent.querySelector('#fuelForm');
  const dateReceived = modalContent.querySelector('#date_r');
  const dateOrdered = modalContent.querySelector('#date_o');
  const fuelSelect = modalContent.querySelector('#fuel_typ');
  const productForm = modalContent.querySelector('#productForm');
  const closeBtn = modalContent.querySelector('#closeform');
  if (closeBtn) closeBtn.addEventListener('click', closeform);

  if (form) {
      // Date setup
  const today = new Date().toISOString().split("T")[0];
  dateReceived.value = today;
  dateReceived.min = today;

  const today1 = new Date();
  const formatted = `${today1.getFullYear()}-${String(today1.getMonth()+1).padStart(2,'0')}-${String(today1.getDate()).padStart(2,'0')}`;
  dateOrdered.max = formatted;

  

  // Form submission
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(form);
    fetch('../config/admin/inventory.php', { method: 'POST', body: formData })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'error' && data.field) {
          const input = modalContent.querySelector('#'+data.field);
          let bgClass = (data.field === 'qty') ? 'bg-red-100' : 'bg-red-50';
          input.classList.add('border-red-500', bgClass);
          input.addEventListener('input', () => {
            input.classList.remove('border-red-500', bgClass);
            if (data.field === 'qty') input.classList.add('bg-gray-50');
          }, { once: true });
          showSnackbar(data.message, data.status);
        } else {
          showSnackbar(data.message, data.status);
          closeform();
          fuelchange();
          updateRecentOrders();
          form.reset();
        }
      })
      .catch(err => showSnackbar('Error submitting form','error'));
  });

  // Fuel dropdown
  if (fuelSelect) {
    fuelSelect.addEventListener('change', function() {
      updateFuelDisplay(this.value);
    });
    updateFuelDisplay(fuelSelect.value); // initial update
  }
  } if(productForm) { 
 const fileInput = document.getElementById('imginput');
 const container = document.getElementById('imgbox');


  fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        // Replace the entire div content with just the uploaded image
        container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain rounded" alt="Uploaded Image">`;
      }
      reader.readAsDataURL(file);
    }

    
  });
  

  productForm.addEventListener('submit', function(e) {
  e.preventDefault(); 
  const formData = new FormData(productForm);

  fetch('../config/admin/inventory.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
     showSnackbar(data.message, data.status);

      // Reset form & preview
      productForm.reset();
      closeform();
      loadProducts();
    } else {
      showSnackbar(data.message, data.status);
    }
  })
  .catch(err => {
    console.error("Error submitting form:", err);
    alert("An error occurred while saving the product");
  });
});
  }
  else {return;}




}

function updateFuelDisplay(selectedFuel) { 
  fetch("../config/admin/inventory.php?fuel=" + encodeURIComponent(selectedFuel))
   .then(res => res.json()) .then(data => { if (data.error) return console.error(data.error); 
    const litersFormatted = Number(data.currentLiters).toLocaleString() + " L "; 
    document.getElementById("selectedtype").textContent = selectedFuel; 
    document.getElementById("fuelLiters").textContent = litersFormatted; 
    document.getElementById("percentage").textContent = data.percentage + "%"; 
    document.getElementById("statustext").textContent = data.status; 
    const statusColors = { "Sufficient Stock": "#48BA6B", "Monitoring": "#B7BA48", "Critical Low": "#F01400" }; 
    document.getElementById("statusdot").style.backgroundColor = statusColors[data.status] || "#CCCCCC"; }) 
    .catch(err => console.error("Fetch error:", err)); }


function openModal(templateId) {
  const template = document.getElementById(templateId);
  modalContent.innerHTML = "";
  modalContent.appendChild(template.content.cloneNode(true));

  modalWrapper.classList.remove('opacity-0', 'pointer-events-none');
  requestAnimationFrame(() => {
    modalOverlay.classList.remove('opacity-0');
    modalContent.classList.remove('opacity-0', 'translate-y-5');
  });

 
  initializeFuelForm();
}

function closeform() { 
  modalContent.classList.add('opacity-0', 'translate-y-5'); 
  modalOverlay.classList.add('opacity-0'); 
  setTimeout(() => { modalWrapper.classList.add('opacity-0', 'pointer-events-none'); 
    modalContent.innerHTML = ""; }, 500); }


const modalWrapper = document.getElementById('wrapper');
const modalOverlay = document.getElementById('overlay');
const modalContent = document.getElementById('modalContent');
const fuelBtn = document.getElementById('fuelopen');
const prodbtn = document.getElementById('prodbtn');

fuelBtn.addEventListener('click', () => { 
  openModal('fuelform'); 
});

prodbtn.addEventListener('click', () => {
  openModal('addProduct');
})


document.addEventListener("DOMContentLoaded", function() {
  fuelchange();
  updateRecentOrders();
  loadProducts();
});


function fuelchange () {  
const FIXED_CAPACITY = 20000;

fetch('../config/admin/inventory.php?action=fuel')
  .then(res => res.json())
  .then(fuels => {
    fuels.forEach((fuel, index) => {
      const button = document.getElementById(`tank${index + 1}`);
      if (!button) return;

      const litersSpan = button.querySelector('.liters');
      const percentSpan = button.querySelector('.percent'); // ← added
      const barFill = button.querySelector('.bar-fill');
      const statusText = button.querySelector('.status-text');
      const percentage = Math.round((fuel.current / FIXED_CAPACITY) * 100);

      if (litersSpan) litersSpan.textContent = fuel.current.toLocaleString();
      if (percentSpan) percentSpan.textContent = percentage + '%'; // ← set percentage text

     // Set height based on percentage
      barFill.style.height = percentage + '%';

      // Set color based on stock
      barFill.style.backgroundColor =
        percentage > 50 ? '#48BA6B' :
        percentage > 20 ? '#B7BA48' :
        '#F01400';                      // critical
      if (statusText) {
        statusText.textContent =
          percentage > 50 ? 'Sufficient Stock' :
          percentage > 20 ? 'Monitoring' :
          'Critical';
      }
        const statusDot = button.querySelector('.status-dot'); // find dot inside current button
      if (statusDot) {
        const statusColors = {
          "Sufficient Stock": "#48BA6B",
          "Monitoring": "#B7BA48",
          "Critical": "#F01400"
        };

        const statusTextValue = 
              percentage > 50 ? "Sufficient Stock" :
              percentage > 20 ? "Monitoring" :
              "Critical";

        statusDot.style.backgroundColor = statusColors[statusTextValue] || "#CCCCCC";
      }
    });
  })
  .catch(err => console.error('Error fetching fuel data:', err));




}

const fuelNames = {
  1: "Diesel",
  2: "Unleaded",
  3: "Premium"
};



function updateRecentOrders() {
  const container = document.getElementById('ordercontainer');
  if (!container) return;

  fetch('../config/admin/inventory.php?action=recent_orders')
    .then(res => res.json()) // directly parse JSON
    .then(orders => {
      // Clear old cards
      container.innerHTML = '';

      // Render buttons/cards
      orders.forEach(order => {
        const btn = document.createElement('button');
         btn.dataset.orderId = order.order_id;
        btn.className = "h-16 flex flex-row items-center gap-6 p-4 w-full hover:bg-[#E7E7EE] transition-colors";

        btn.innerHTML = `
          <img src="../assets/Admin/${order.notes.includes('Diesel') ? 'Droplet.png' : 'Box_blue.png'}" class="h-10 w-10">
          <div class="flex flex-col w-full text-sm gap-1 text-inter font-normal text-[#1A2F58]">
            <p class="flex flex-row justify-between w-full gap-16">
              <span>${order.supplier_name}</span>
              <span>${order.invoice_number}</span>
            </p>
            <p class="flex flex-row justify-between w-full gap-14">
              <span class="bg-[#D4FFE1] text-[#459A5F] border border-[#459A5F] rounded-md w-20">
                Received</span>
              <span>${order.date_received}</span>
            </p>
          </div>
        `;
            container.addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const orderId = btn.dataset.orderId; 
              
            fetch(`../config/admin/inventory.php?action=get_order&id=${orderId}`)
              .then(res => res.json())
              .then(data => {
                console.log(data);
                openModal('vieworder');
                populateModal(data);         
              });   
          });

          document.addEventListener("click", function(e) {
            if (e.target.closest(".close-btn")) {
              closeform();
            }
          });

        container.appendChild(btn);
        

      });

      

    })


    .catch(err => console.error('Fetch error:', err));
}

function populateModal(order) {
  document.getElementById("date_created").textContent = order.date_created;
  document.getElementById("invoice_num").textContent = order.invoice_number;
  document.getElementById("supplier_n").textContent = order.supplier_name;
  document.getElementById("fuel_n").textContent = fuelNames[order.fuel_id] || "Unknown";
  document.getElementById("ltrs").textContent = order.liters;
  document.getElementById("date_ord").textContent = order.date_ordered;
  document.getElementById("date_rec").textContent = order.date_received;
  document.getElementById("not").textContent = order.notes;
}


const tbody = document.getElementById('productTableBody');

let allProducts = [];
const searchInput = document.getElementById('productSearch');

function loadProducts() {
  fetch('../config/admin/inventory.php?action=get_product') // your PHP JSON endpoint
    .then(res => res.json())
    .then(products => {
      renderTable(products);
      allProducts = products;
      tbody.innerHTML = ''; // clear any existing rows

      products.forEach(product => {
        // Determine badge color
        let badgeClass = '';
        const status = product.status.toLowerCase();
        if (status === 'available') badgeClass = 'bg-[#48BA6B] text-white';
        else if (status === 'low') badgeClass = 'bg-[#F50505] text-white';
        else badgeClass = 'bg-[#DADADA] text-black';

        // Append row
        tbody.innerHTML += `
          <tr
          class="hover:bg-gray-100 transition duration-200 cursor-pointer"
            data-id="${product.product_id}">
            <td class="px-1 py-2 font-semibold">${product.product_name}</td>
            <td class="px-1 py-2">${product.stock}</td>
            <td class="px-1 py-2">₱${Number(product.price).toFixed(2)}</td>
            <td class="px-1 py-2">${product.restock_date}</td>
            <td class="px-1 py-2">
              <p class="flex justify-center items-center font-inter">
                <span class="${badgeClass} w-14 py-[1px] rounded-full text-[10px]">
                  ${product.status}
                </span>
              </p>
            </td>
          </tr>
        `;
      });
    })
    .catch(err => console.error('Error loading products:', err));
}

searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    const filtered = allProducts.filter(product =>
        product.product_name.toLowerCase().includes(query) ||
        product.description.toLowerCase().includes(query) ||
        product.status.toLowerCase().includes(query)
    );
    renderTable(filtered);
});


function renderTable(products) {
    const tbody = document.getElementById('productTableBody');
    tbody.innerHTML = '';

    if (products.length === 0) {
        // No results found row
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-1 py-2 text-center text-gray-500">
                    No results found
                </td>
            </tr>
        `;
        return; // exit early
    }

    products.forEach(product => {
        let badgeClass = '';
        const status = product.status.toLowerCase();
        if (status === 'available') badgeClass = 'bg-[#48BA6B] text-white';
        else if (status === 'low') badgeClass = 'bg-[#F50505] text-white';
        else badgeClass = 'bg-[#DADADA] text-black';

        tbody.innerHTML += `
            <tr>
            data-id="${product.product_id}">
            <td class="px-1 py-2">${product.product_name}</td>
            <td class="px-1 py-2">${product.stock}</td>
            <td class="px-1 py-2">₱${Number(product.price).toFixed(2)}</td>
            <td class="px-1 py-2">${product.restock_date}</td>
            <td class="px-1 py-2">
              <p class="flex justify-center items-center font-inter">
                <span class="${badgeClass} w-14 py-[1px] rounded-full text-[10px]">
                  ${product.status}
                </span>
              </p>
            </td>
          </tr>
        `;
    });

    tbody.addEventListener('click', function (e) {
    const row = e.target.closest('tr');
    if (!row) return;

    const productId = row.dataset.id;
    console.log(productId);
    openModal('viewProduct');
    fetch(`../config/admin/inventory.php?action=fetchitem&fetchitem=${productId}`)
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            document.getElementById('prod_n').textContent = data.product_name;
            document.getElementById('price').textContent = `₱${data.price}`;
            document.getElementById('stock_n').textContent = data.stock;
            document.getElementById('status').textContent = data.status;
            document.getElementById('text').textContent = data.description;

          const low = document.getElementById('low_ind');

          if (data.stock === 0) {
            low.classList.add('hidden');
          }

          if (data.status === "Unavailable".toLowerCase) {
            document.getElementById('status').classList.add('text-[#B22222]');
          }


            const imgEl = document.getElementById('prod_img');
            if (data.image) {
                imgEl.src = `../config/admin/uploads/products/${data.image}`;
                imgEl.alt = data.product_name;
            } else {
            // fallback image
                imgEl.src = `../uploads/default.png`;
                imgEl.alt = "No image available";
        }
        }
    });
    
  });

}
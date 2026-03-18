const modal = document.getElementById("modal");
const modalWrapper = document.getElementById('wrapper');
const modalOverlay = document.getElementById('overlay');
const modalContent = document.getElementById("modalContent");

function openModal1(templateId) {
  const template = document.getElementById(templateId);

  modalContent.innerHTML = "";
  modalContent.appendChild(template.content.cloneNode(true));

  // Show wrapper and overlay immediately
  modalWrapper.classList.remove('opacity-0', 'pointer-events-none');
  modalOverlay.classList.remove('opacity-0', 'pointer-events-none');

  // Animate modalContent appearance
  requestAnimationFrame(() => {
    modalContent.classList.remove('opacity-0', 'translate-y-5');
  });
}

function closeform() { 
  modalContent.classList.add('opacity-0', 'translate-y-5'); 
  modalOverlay.classList.add('opacity-0'); 
  setTimeout(() => { modalWrapper.classList.add('opacity-0', 'pointer-events-none'); 
    modalContent.innerHTML = ""; }, 500); 
  }

async function loadProducts() {
  try {
    const response = await fetch('../config/product.php?action=getproducts');
    const products = await response.json();

    const tableBody = document.getElementById('productTableBody');
    tableBody.innerHTML = ""; // clear existing rows

    products.forEach(product => {
      const row = document.createElement('tr');
      row.setAttribute('data-id', product.product_id);

      row.innerHTML = `
        <td class="px-1 py-3">${product.name}</td>
        <td class="px-1 py-3">₱${parseFloat(product.price).toFixed(2)}</td>
        <td class="px-1 py-3">${product.stock}</td>
        <td class="px-1 py-3">
          <p class="flex justify-center items-center text-white">
            <span class="w-20 py-[2px] rounded-full text-[10px]" style="background:${product.color}">
              ${product.status}
            </span>
          </p>
        </td>
      `;
      row.addEventListener('click', () => showProductDetails(product.product_id));
    
      tableBody.appendChild(row);
    });
    

  } catch (error) {
    console.error("Error loading products:", error);
  }
  searchbar();
}

async function loadFuels() {
  const response = await fetch('../config/product.php?action=getfuel');
  const fuels = await response.json();

  const container = document.getElementById('fuelContainer');
  container.innerHTML = "";

  fuels.forEach(fuel => {
    const card = document.createElement('div');

    card.className = "flex flex-col w-full h-36 gap-2 bg-[#1A2F58] rounded font-inter p-2 text-white";

    card.innerHTML = `
      <div class="flex justify-between text-base font-medium">
          <span>${fuel.name}</span>
          <span>₱${parseFloat(fuel.price).toFixed(2)}/L</span>
      </div>

      <span class="text-xl font-semibold flex items-center h-1/2">
          ${Number(fuel.stock).toLocaleString()}  / ${fuel.capacity.toLocaleString()}L
      </span>

      <div class="flex gap-2 items-center">
        <div class="w-2 h-2 rounded-full ${fuel.color}"></div>
        <div class="text-xs font-medium">${fuel.status}</div>
      </div>
    `;

    container.appendChild(card);
  });
}

async function showProductDetails(productId) {
    console.log(productId);
  try {                        
    const response = await fetch(`../config/product.php?action=getproductinfo&id=${productId}`);
    const product = await response.json();
    // Show the hidden container
    const container = document.querySelector('.product-detail-container'); // replace with actual class
    container.classList.remove('hidden');
    const placehold = document.getElementById('placeholder');
    placehold.classList.add('hidden');
    console.log(response);

    // Populate details
    container.querySelector('.product-name').textContent = product.product_name;
    container.querySelector('.unit-price').textContent = `₱${parseFloat(product.price).toFixed(2)}`;
    container.querySelector('.stock-left').textContent = Number(product.stock).toLocaleString();
    container.querySelector('.last-restock').textContent = product.last_restock;
    container.querySelector('textarea').textContent = product.description;

    // Update image
    container.querySelector('img').src = `../config/admin/uploads/products/${product.image}`;

  } catch (err) {
    console.error("Error loading product details:", err);
  }
}


function searchbar() {
const searchInput = document.getElementById('searchprod');
const tableBody = document.getElementById('productTableBody');

searchInput.addEventListener('input', function () {
  const searchValue = this.value.trim().toLowerCase();
  const rows = tableBody.querySelectorAll('tr');

  let found = false; // flag if any row matches

  rows.forEach(row => {
    const productName = row.children[0].textContent.toLowerCase();
    if (productName.includes(searchValue)) {
      row.style.display = "";
      found = true;
    } else {
      row.style.display = "none";
    }
  });

  // Check if nothing matched
  if (!found) {
    // Create "No products found" row if it doesn't exist
    if (!tableBody.querySelector('.no-product')) {
      const noRow = document.createElement('tr');
      noRow.className = 'no-product';
      noRow.innerHTML = `
        <td colspan="4" class="py-3 text-center text-[#1A2F58] font-inter font-normal">
          No products found
        </td>
      `;
      tableBody.appendChild(noRow);
    }
  } else {
    // Remove the placeholder if there are matches
    const noRow = tableBody.querySelector('.no-product');
    if (noRow) noRow.remove();
  }
});
}

loadFuels();

// call it when page loads
loadProducts();
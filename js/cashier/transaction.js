
const modalWrapper = document.getElementById('wrapper');
const modalOverlay = document.getElementById('overlay');

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

function closeform() { 
  modalContent.classList.add('opacity-0', 'translate-y-5'); 
  modalOverlay.classList.add('opacity-0'); 
  setTimeout(() => { modalWrapper.classList.add('opacity-0', 'pointer-events-none'); 
    modalContent.innerHTML = ""; }, 500); 

}

const trans = document.getElementById('transbtn');

trans.addEventListener('click', function() {
    openModal1('addtrans');
    populate();
})


function toggleView() {
  const child1 = document.getElementById("fueltab");
  const child2 = document.getElementById("prodtab");
  const button = document.getElementById('tabtxt');
  const search = document.getElementById('search');
  
  child1.classList.toggle("hidden");
  child2.classList.toggle("hidden");

    if (child1.classList.contains("hidden")) {
    button.innerText = "Add Fuel";
    search.classList.remove("hidden");
    
  } else {
    button.innerText = "Add Products";
    search.classList.add("hidden");
    
  }
}

document.addEventListener("keydown", function(event) {
  if (event.key === "F1") {
    event.preventDefault(); // prevent browser help menu
    toggleView();
  }
});

let products_array = [];  


function populate() {
  fuelpopulate();
  fetch("../config/transaction.php?action=getproducts")
.then(res => res.json())
.then(products => {

    const container = document.getElementById("prodtab");
    let html = "";
    products_array = products;
    
    products.forEach(product => {
    const price = Number(product.price).toFixed(2);
    const stock = Number(product.stock);
            let stockColor = '';
    let buttonDisabled = '';
    let buttonClasses = 'bg-[#1A2F58] flex justify-center items-center rounded-full w-7 h-7 hover:bg-[#365CA8] transition';

    if (stock === 0) {
        stockColor = 'text-gray-400'; // Out of stock text
        buttonDisabled = 'disabled';   // disable button
        buttonClasses = 'bg-gray-400 flex justify-center items-center rounded-full w-7 h-7 cursor-not-allowed'; // greyed out
    } else if (product.stock < 12) {
        stockColor = 'text-red-500'; // low stock text
    } else {
        stockColor = ''; // normal
    } 

        html += `
        <div class="w-52 p-2 gap-3 items-center h-28 flex flex-row border border-[#1F3A69]/20 shadow-md shadow-black/20">
          
          <img src="../config/admin/uploads/products/${product.image}" class="w-20 h-full">

          <div class="flex flex-col font-inter text-[#1A2F58] items-start w-full">
            <div class="flex flex-col w-full">
              
              <span class="font-semibold text-xs max-w-full">${product.product_name}</span>
              <span class="font-medium text-xs">₱ ${price}</span>
               <span class="font-medium text-xs ${stockColor}">
              ${stock === 0 ? 'Out of Stock' : `Stock: ${stock}`}
              </span>

              <div class="flex items-end justify-end w-full">
                <button onclick="addProduct(${product.product_id})" ${buttonDisabled} class="${buttonClasses}">
                <i class="fa-solid fa-plus text-xl ${product.stock === 0 ? 'text-white' : 'text-[#F8F8FF]'}"></i>
               </button>

              </div>

            </div>
          </div>

        </div>
        `;

    });

    container.innerHTML = html;

});
}

let cart = {}; 


document.addEventListener("keydown", (e) => {
    // e.code is "F3" when F3 is pressed
    if (e.code === "F3") {
        e.preventDefault(); // prevent browser default action for F3 (if any)
        

            cart = {};       // empty the cart
            renderCart();    // refresh table and summary
            updateSummary();

    }
});

function addProduct(productId) {
    document.getElementById("clear-cart-btn").addEventListener("click", () => {
    cart = {};          // empty the cart
    renderCart();       // refresh the table
    updateSummary();
  });

    const product = products_array.find(p => Number(p.product_id) === Number(productId));
    if (!product) return;

    if ((cart[product.product_id] || 0) >= Number(product.stock)) {
        alert("Cannot exceed available stock");
        return;
    }

    cart[product.product_id] = (cart[product.product_id] || 0) + 1;
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById("cart-body");
    tbody.innerHTML = ""; // clear previous rows

    let grandTotal = 0;

    for (let productId in cart) {
        const qty = cart[productId];
        const product = products_array.find(p => Number(p.product_id) === Number(productId));
        if (!product) continue;

        const total = Number(product.price) * qty;

        const unitPriceFormatted = Number(product.price).toLocaleString('en-PH', { minimumFractionDigits: 2 });
        const totalFormatted = total.toLocaleString('en-PH', { minimumFractionDigits: 2 });

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="py-2 px-2 font-inter font-semibold">${product.product_name}</td>
            <td class="py-2 px-2 font-inter font-semibold">₱ ${unitPriceFormatted}</td>
            <td class="py-2 px-2 font-inter font-semibold">${qty}</td>
           <td class="py-2 px-2 text-right whitespace-nowrap font-inter font-semibold">₱ ${totalFormatted}</td>
          
            <td>
                <button class="flex justify-center items-center w-5 h-5 bg-[#FF7676] rounded-md"
                        onclick="removeProduct(${product.product_id})">
                    <i class="fa-solid fa-minus text-white"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        updateSummary();
    }

    
}

function removeProduct(productId) {
    if (!cart[productId]) return;

    cart[productId]--;
    if (cart[productId] <= 0) delete cart[productId];

    renderCart();
} 
 
let fuels_array = []; //list of fuels
let phButtons = [];
let literButtons = [];
let customInput;
let toggleBtn;
let selectedFuel = null; // the fuel currently selected
let selectedAmount = 0; // ₱ value
let selectedLiters = 0; // liter value
let isByLiters = true; 




function fuelpopulate() {
 fetch("../config/transaction.php?action=getfuel")
  .then(res => res.json())
  .then(fuels => {
      fuels_array = fuels;
      // Loop through each fuel and populate the corresponding button
      fuels.forEach((fuel, index) => {
          let statusColor = '';
          let statusLabel = '';

          if (fuel.stock_liters === 0) {
              statusColor = 'bg-red-500';
              statusLabel = 'Out of Stock';
          } else if (fuel.stock_liters < 50) {   // warning threshold
              statusColor = 'bg-yellow-500';
              statusLabel = 'Low Stock';
          } else {
              statusColor = 'bg-green-500';
              statusLabel = 'Sufficient Stock';
          }

          const btn = document.getElementById(`fuel-${index+1}`);
          if (!btn) return;

          btn.innerHTML = `
              <div class="flex flex-col w-full h-auto text-left">
                  <span class="font-bold text-base">${fuel.fuel_type}</span>
                  <span class="font-medium text-sm">₱ ${Number(fuel.price_per_ltr).toFixed(2)} per Liter</span>
              </div>
              <div class="flex flex-row gap-3 items-center text-xs">
                 <div class="w-2 h-2 ${statusColor} rounded-full"></div>
                  <span>${statusLabel}</span>

              </div>
          `;
          btn.onclick = () => selectFuel(index);
      });
       buttons();
  })
  .catch(err => console.error(err));   
}

function selectFuel(index) {
    const fuel = fuels_array[index];
    selectedFuel = fuels_array[index];
    if (!fuel || Number(fuel.stock_liters) === 0) return; // cannot select out-of-stock fuel

    // Update your selected fuel panel
    document.getElementById("fuel-name").textContent = fuel.fuel_type;
    document.getElementById("fuel-price").textContent = `₱ ${Number(fuel.price_per_ltr).toFixed(2)}`;
    
    // You can also reset denominations/inputs here if needed
    clearDenominations();
    updateFuelTotal();
}

function buttons() {
    // Grab elements now that the DOM exists
    customInput = document.getElementById("custom-input");
    toggleBtn = document.getElementById("custom-toggle-btn");

    phButtons = document.querySelectorAll("#denominations-ph .deno-btn");
    literButtons = document.querySelectorAll("#denominations-ltr .deno-btn");

    // Toggle button listener
    toggleBtn.addEventListener("click", () => {
        isByLiters = !isByLiters;
        toggleBtn.textContent = isByLiters ? "By Liters" : "By ₱";

        if (isByLiters) selectedAmount = 0;
        else selectedLiters = 0;

        customInput.value = "";
        updateFuelTotal();
    });

    // Input listener
    customInput.addEventListener("input", () => {
        const val = Number(customInput.value) || 0;

        if (isByLiters) {
            selectedLiters = val;
            selectedAmount = 0;
        } else {
            selectedAmount = val;
            selectedLiters = 0;
        }

        updateFuelTotal();
    });

    // PH buttons
    phButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            selectedAmount = Number(btn.dataset.value);
            selectedLiters = 0;

            phButtons.forEach(b => b.classList.remove("bg-[#1A2F58]", "text-white"));
            btn.classList.add("bg-[#1A2F58]", "text-white");

            literButtons.forEach(b => b.classList.remove("bg-[#1A2F58]", "text-white"));
            document.getElementById("custom-liter-input").value = "";

            updateFuelTotal();
        });
    });

    // Liter buttons
    literButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            selectedLiters = Number(btn.dataset.value);
            selectedAmount = 0;

            literButtons.forEach(b => b.classList.remove("bg-[#1A2F58]", "text-white"));
            btn.classList.add("bg-[#1A2F58]", "text-white");

            phButtons.forEach(b => b.classList.remove("bg-[#1A2F58]", "text-white"));
            document.getElementById("custom-amount-input").value = "";

            updateFuelTotal();
        });
    });
}

function updateFuelTotal() {
    if (!selectedFuel) return;

    const totalEl = document.getElementById("fuel-total-amount");
    if (!totalEl) return; // extra safety check

    const pricePerLtr = Number(selectedFuel.price_per_ltr);
    let total = 0;
    let liters = 0;

    if (selectedAmount > 0) {
        liters = selectedAmount / pricePerLtr;
        total = selectedAmount;
    } else if (selectedLiters > 0) {
        total = selectedLiters * pricePerLtr;
        liters = selectedLiters;
    }

    totalEl.textContent = `₱ ${total.toLocaleString('en-PH', { minimumFractionDigits: 2 })} ≈ ${liters.toFixed(2)} L`;
}

function clearDenominations() {
    selectedAmount = 0;
    selectedLiters = 0;

    if (phButtons) phButtons.forEach(b => b.classList.remove("bg-[#1A2F58]", "text-white"));
    if (literButtons) literButtons.forEach(b => b.classList.remove("bg-[#1A2F58]", "text-white"));

    if (customInput) customInput.value = "";
}

function updateSummary() {
    let subtotal = 0;

    for (let productId in cart) {
        const qty = cart[productId];
        const product = products_array.find(p => Number(p.product_id) === Number(productId));
        if (!product) continue;

        subtotal += Number(product.price) * qty;
    }

    // VAT 12%
    const vat = subtotal * 0.12;

    // Total
    const total = subtotal + vat;

    // format numbers with commas and 2 decimals
    const subtotalFormatted = subtotal.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    const vatFormatted = vat.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    const totalFormatted = total.toLocaleString('en-PH', { minimumFractionDigits: 2 });

    // update HTML
    document.getElementById("summary-subtotal").textContent = `₱${subtotalFormatted}`;
    document.getElementById("summary-vat").textContent = `₱${vatFormatted}`;
    document.getElementById("summary-total").textContent = `₱${totalFormatted}`;
}
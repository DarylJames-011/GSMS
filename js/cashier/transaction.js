

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
                <button onclick="addProduct('product',${product.product_id})" ${buttonDisabled} class="${buttonClasses}">
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
const max_liters = 5000;

document.addEventListener("keydown", (e) => {
    // e.code is "F3" when F3 is pressed
    if (e.code === "F3") {
        e.preventDefault(); // prevent browser default action for F3 (if any)
        

            cart = {};       // empty the cart
            renderCart();    // refresh table and summary
            updateSummary();

    }
});

function addProduct(type,id) {
    document.getElementById("clear-cart-btn").addEventListener("click", () => {
    cart = {};          // empty the cart
    renderCart();       // refresh the table
    updateSummary();
  });

      if (type === "product") {

        const product = products_array.find(p => Number(p.product_id) === Number(id));
        if (!product) return;

        if ((cart[product.product_id] || 0) >= Number(product.stock)) {
            alert("Cannot exceed available stock");
            return;
        }

        cart[product.product_id] = (cart[product.product_id] || 0) + 1;

    }

    if (type === "fuel") {

        const fuel = fuels_array.find(f => Number(f.fuel_id) === Number(id));
        if (!fuel) return;
           const number = parseFloat(
          fuel_amt.textContent
              .replace("₱", "")
              .replace(/,/g, "")
              .trim()
      );

        const fuel_ltrs = Math.round((number / selectedprice) * 100) / 100;
        let totalLitersInCart = 0;
        for (let key in cart) {
            const item = cart[key];
            if (typeof item === "object" && item.liters) {
                totalLitersInCart += item.liters;
            }
        }

        // check transaction cap
        if (totalLitersInCart + fuel_ltrs > max_liters) {
            alert(`Cannot add fuel. Total liters per transaction cannot exceed ${max_liters} L.`);
            return;
        }
          
       if (cart[fuel.fuel_id]) {
          // already in cart, increment
          cart[fuel.fuel_id].pesos += number;
          cart[fuel.fuel_id].liters += fuel_ltrs;
      } else {
          cart[fuel.fuel_id] = {
              type: fuel.fuel_type,
              pesos: number,
              liters: fuel_ltrs
          };
      }

    }
    renderCart();

    
  }
function renderCart() {

    const tbody = document.getElementById("cart-body");
    tbody.innerHTML = ""; // clear previous rows

    for (let key in cart) {
        const item = cart[key];
        const tr = document.createElement("tr");

        // Check if the item is a product (number) or fuel (object)
        if (typeof item === "number") {
            // Product
            const productId = Number(key);
            const product = products_array.find(p => Number(p.product_id) === productId);
            if (!product) continue;

            const qty = item;
            const total = Number(product.price) * qty;
            const unitPriceFormatted = Number(product.price).toLocaleString('en-PH', { minimumFractionDigits: 2 });
            const totalFormatted = total.toLocaleString('en-PH', { minimumFractionDigits: 2 });

            tr.innerHTML = `
                <td class="py-2 px-2 font-inter font-semibold">${product.product_name}</td>
                <td class="py-2 px-2 font-inter font-semibold">₱ ${unitPriceFormatted}</td>
                <td class="py-2 px-2 font-inter font-semibold">${qty}</td>
                <td class="py-2 px-2 text-right whitespace-nowrap font-inter font-semibold">₱ ${totalFormatted}</td>
                <td>
                    <button class="flex justify-center items-center w-5 h-5 bg-[#FF7676] rounded-md"
                            onclick="removeCartItem(${product.product_id})">
                        <i class="fa-solid fa-minus text-white"></i>
                    </button>
                </td>
            `;
        } else if (typeof item === "object") {
            // Fuel
            tr.innerHTML = `
                <td class="py-2 px-2 font-inter font-semibold">${item.type} (Fuel)</td>
                <td class="py-2 px-2 font-inter font-semibold">₱ ${item.pesos.toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                <td class="py-2 px-2 font-inter font-semibold">${item.liters} L</td>
                <td class="py-2 px-2 text-right whitespace-nowrap font-inter font-semibold">₱ ${item.pesos.toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                <td>
                    <button class="flex justify-center items-center w-5 h-5 bg-[#FF7676] rounded-md"
                            onclick="removeCartItem('${key}')">
                        <i class="fa-solid fa-minus text-white"></i>
                    </button>
                </td>
            `;
        }

        tbody.appendChild(tr);
    }

    updateSummary();
}

function removeCartItem(key) {
    if (!cart[key]) return;

    if (typeof cart[key] === "number") {
        // Product
        cart[key]--;
        if (cart[key] <= 0) delete cart[key];
    } else if (typeof cart[key] === "object") {
        // Fuel
        delete cart[key];
    }

    renderCart();
}

let products_array = [];  
let fuel_amt = null;
let fuel_id = null;
let fuels_array = []; //list of fuels
let selectedFuel = null; // the fuel currently selected
let selectedprice = null;


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
       initializefuel();
  })
  .catch(err => console.error(err));   
}
function selectFuel(index) {
    const fuel = fuels_array[index];
    selectedFuel = fuels_array[index];
    if (!fuel || Number(fuel.stock_liters) === 0) return; // cannot select out-of-stock fuel
    fuel_id = Number(fuel.fuel_id);
    // Update your selected fuel panel
    document.getElementById("fuel-name").textContent = fuel.fuel_type;
    selectedprice = Number(fuel.price_per_ltr).toFixed(2);
    fuelcapacity = fuel.stock_ltrs;
    capacity = fuelcapacity;
    document.getElementById("fuel-price").textContent = `₱ ${Number(fuel.price_per_ltr).toFixed(2)}`;
    

}

let mode = "liters"; 
function initializefuel() {
    function resetButtons() {
    buttons.forEach(b => {
        b.classList.remove("bg-[#1A2F58]", "text-white");
        b.classList.add("bg-[#F3F7FF]", "hover:bg-[#b3bfd8]", "transition", "duration-200");
    });
        }
    
const toggleBtn = document.getElementById("toggle-btn");
const fuelinput = document.getElementById("custom-input");
const fuelvalue = document.getElementById("fuel-total-amount");
const buttons = document.querySelectorAll(".deno-btn");

buttons.forEach(btn => {
  btn.addEventListener("click", () => {
     if (selectedFuel === null) {
        alert("Please select fuel type first");
        return;
    }


    // remove active state from all buttons
    buttons.forEach(b => {
      b.classList.remove("bg-[#1A2F58]", "text-white");
      b.classList.add("bg-[#F3F7FF]", "hover:bg-[#b3bfd8]","transition","duration-200");
      selectedValue = Number(btn.dataset.value);
      
      const formatted = selectedValue.toLocaleString("en-PH", {
      style: "currency",
      currency: "PHP"
    });
         if (btn.closest("#deno-ph")) {
           fuelinput.value = "";
           fuelvalue.textContent = formatted;      
        }

        if (btn.closest("#deno-l")) {
            fuelinput.value = "";
            const total = selectedValue * selectedprice;
         const formattedTotal = total.toLocaleString("en-PH", {
            style: "currency",
            currency: "PHP"
        });
            fuelvalue.textContent = formattedTotal;
        }    
        
    });

    
    // activate clicked button
    btn.classList.remove("bg-[#F3F7FF]", "hover:bg-[#b3bfd8]","transition","duration-200");
    btn.classList.add("bg-[#1A2F58]", "text-white");
  });
});

 mode = mode === "liters" ? "pesos" : "liters";

  fuelinput.addEventListener("input", () => {
    if (!selectedFuel) {
      fuelinput.value = "";
        alert("Please select fuel type first");      
        return;
    }
    resetButtons();

  const value = Number(fuelinput.value); // get numeric value
  if(isNaN(value) || value <= 0) {
    fuelvalue.textContent = "₱ 0.00"; // reset if empty/invalid
    return;
  }

  if(mode === "liters") {
    // User typed liters → calculate price
    const total = value * selectedprice;
    fuelvalue.textContent = total.toLocaleString("en-PH", { style: "currency", currency: "PHP" });
  }

  if(mode === "pesos") {
    // User typed amount → calculate liters
    const liters = value;
    fuelvalue.textContent = liters.toLocaleString("en-PH", { style: "currency", currency: "PHP" });
  }

});

toggleBtn.addEventListener("click", () => {
    mode = mode === "liters" ? "pesos" : "liters";

    // Update button text & style
    toggleBtn.textContent = mode === "liters" ? "By Liters" : "By Pesos";

    if(mode === "liters") {
        toggleBtn.classList.remove("text-white", "bg-[#1A2F58]");
        toggleBtn.classList.add("text-[#1A2F58]", "bg-[#F3F7FF]");
    } else {
        toggleBtn.classList.remove("text-[#1A2F58]", "bg-[#F3F7FF]");
        toggleBtn.classList.add("text-white", "bg-[#1A2F58]");
    }

    // Reset input and output when mode changes
    fuelinput.value = "";
    fuelvalue.textContent = "₱ 0.00";
});
  
  // Update toggle button text & style
  toggleBtn.textContent = mode === "liters" ? "By Liters" : "By Pesos";
  
  if(mode === "liters") {
    toggleBtn.classList.remove("text-white", "bg-[#1A2F58]");
    toggleBtn.classList.add("text-[#1A2F58]", "bg-[#F3F7FF]");
  } else {
    toggleBtn.classList.remove("text-[#1A2F58]", "bg-[#F3F7FF]");
    toggleBtn.classList.add("text-white", "bg-[#1A2F58]");
  }
  const addOrder = document.getElementById('addFuel');
  fuel_amt = fuelvalue;
  addOrder.addEventListener("click", () => {
        addProduct('fuel',fuel_id);
    
  });

  const savebtn = document.getElementById('save-btn');
  savebtn.addEventListener("click", () => {
    savebtn.disabled = true;
    saveTransaction();
    cart = {};          // empty the cart
    renderCart();       // refresh the table
    updateSummary();  
    selectedFuel = null;

  });
}


const totaldb = null;

function updateSummary() {
    let subtotal = 0;

    for (let key in cart) {
        const item = cart[key];

        if (typeof item === "number") {
            // Product
            const productId = Number(key);
            const product = products_array.find(p => Number(p.product_id) === productId);
            if (!product) continue;

            subtotal += Number(product.price) * item;

        } else if (typeof item === "object") {
            // Fuel
            subtotal += Number(item.pesos);
        }
    }

    // Total already includes VAT
    const total = subtotal; // stays as const
    const totalToUse = totaldb !== null ? totaldb : total;

    // Format numbers
    const vat = totalToUse - totalToUse / 1.12;
    const subtotalFormatted = (totalToUse - vat).toLocaleString('en-PH', { minimumFractionDigits: 2 });
    const vatFormatted = vat.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    const totalFormatted = totalToUse.toLocaleString('en-PH', { minimumFractionDigits: 2 });

    document.getElementById("summary-subtotal").textContent = `₱${subtotalFormatted}`;
    document.getElementById("summary-vat").textContent = `₱${vatFormatted}`;
    document.getElementById("summary-total").textContent = `₱${totalFormatted}`;
}

function saveTransaction() {
  

  if (!cart || Object.keys(cart).length === 0) {
    alert('Cannot Save a Transaction if the Order is Empty.');
    return;
  }
  else {
  const paymentMethod = document.querySelector('#payment-method').value;  
  fetch('../config/transaction.php?action=saveTransaction', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        cart: cart,
        total: totaldb,
        payment_method: paymentMethod
    })
})
.then(res => res.json())
.then(data => {
    if(data.status === 'success') {
showSnackbar('Transaction Saved Successfully', 'success');
fetchTranasction();
closeform();
    } else {
        alert('Error: ' + data.message);
    }
});
}}



function fetchTranasction() {
      fetch("../config/transaction.php?action=getTransaction")
    .then(response => response.json())
    .then(data => {

        const container = document.getElementById("transactionContainer");

        container.innerHTML = "";

       data.forEach(transaction => {

    const formattedTotal = Number(transaction.total_amt).toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP'
    });

    const card = `
    <div class="transaction-card w-full p-3 flex flex-row gap-2 rounded-lg hover:bg-[#EBEBEB] transition-colors"
    data-transaction="${transaction.transaction_no}">
        <img src="../assets/Check1.png">

        <div class="flex flex-col gap-3 justify-between w-full">

            <div class="flex flex-row justify-between">
                <button class="font-semibold">
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

    container.innerHTML += card;
});

    });
    
}

function searchTransaction() {
 document.getElementById("searchTransaction").addEventListener("input", function () {

    const searchValue = this.value.toLowerCase();
    const container = document.getElementById("transactionContainer");
    const cards = container.querySelectorAll(".transaction-card");
    let anyVisible = false;

    cards.forEach(card => {
        const transactionNo = card.dataset.transaction.toLowerCase();

        if (transactionNo.includes(searchValue)) {
            card.style.display = "flex";
            anyVisible = true;
        } else {
            card.style.display = "none";
        }
    });

    // Remove previous no results message if exists
    const existingMsg = container.querySelector(".no-results");
    if (existingMsg) existingMsg.remove();

    // Add message if nothing is visible
    if (!anyVisible) {
        const msg = document.createElement("div");
        msg.className = "no-results text-center text-gray-500 p-3";
        msg.textContent = "No transaction found.";
        container.appendChild(msg);
    }

});
}

document.addEventListener("DOMContentLoaded", function () {
    fetchTranasction();
    searchTransaction();
});


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

const trans = document.getElementById('transbtn');

trans.onclick = () => {
 if (shiftStart) {
 openModal1('addtrans');
 populate();
} else {
 openModal1('shifterror');
}
}


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
                <td class="py-2 px-2 font-inter font-semibold">${Number(item.liters).toFixed(2)}L</td>
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
    if (Object.keys(cart).length === 0) {
        alert('Please Add an Order before Proceeding');
    }

    else {
        confirmPayment();
    }
    


  });
}
const totaldb = null;
let subtotal1 = null;
let ref1 = null;



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
    subtotal1 = subtotal;
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

function confirmPayment() {
    const trantab = document.getElementById('maintab');
    const confirmSection = document.getElementById('confirm');
    const save = document.getElementById('save-trns');
    const change = document.getElementById('change');
    const total_lbl = document.getElementById('totalamt');
    const input = document.getElementById("amt-rec");
    const ref = document.getElementById("ref-num");
    const return_btn = document.getElementById('return');
    const paymentSelect = document.getElementById('payment-method');

    trantab.classList.add("hidden");
    confirmSection.classList.remove("hidden");

    let formatted = new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(subtotal1);

    total_lbl.textContent = formatted;

    // RETURN BUTTON
    return_btn.onclick = () => {
        trantab.classList.remove("hidden");
        confirmSection.classList.add("hidden");
        input.value = "";
        ref.value = "";
    };

    // AMOUNT RECEIVED INPUT
    input.oninput = function () {
        let inputAmount = Number(input.value) || 0;
        let finalvalue = inputAmount - subtotal1;

        let formatted = new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(finalvalue);

        if (finalvalue < 0) {
            change.classList.add("text-[#D03939]");
        } else {
            change.classList.remove("text-[#D03939]");
        }

        change.textContent = formatted;
    };

    // PAYMENT METHOD TOGGLE
    function toggleRef() {
        if (["Credit", "Online"].includes(paymentSelect.value)) {
            ref.disabled = false;
            ref.focus();
            ref.classList.remove("cursor-not-allowed");
        } else {
            ref.disabled = true;
            ref.value = "";
            ref.classList.add("cursor-not-allowed");
        }
    }

    paymentSelect.onchange = toggleRef;
    toggleRef(); // run immediately

    // SAVE TRANSACTION
    save.onclick = function () {

        if (["Credit", "Online"].includes(paymentSelect.value) && ref.value === "") {
            alert('Please add a Reference Number before proceeding.');
            return;
        }

        let inputAmount = Number(input.value) || 0;

        if (inputAmount <= 0 || inputAmount < subtotal1) {
            alert('Insufficient amount. Please enter enough money to cover the total.');
            return;
        }

        saveTransaction(ref.value, inputAmount);


    };



}
function saveTransaction(reference,amount) {
  

  if (!cart || Object.keys(cart).length === 0) {
    alert('Cannot Save a Transaction if the Order is Empty.');
    return;
  }
  else {
  let paymentMethod = document.querySelector('#payment-method').value;  
  if (!paymentMethod) {
  paymentMethod = "Cash";
    }

  fetch('../config/transaction.php?action=saveTransaction', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        cart: cart,
        total: subtotal1,
        payment_method: paymentMethod,
        reference_num: reference,     // new field
        amt_received: amount
    })
})
.then(res => res.json())
.then(data => {
    if(data.status === 'success') {
showSnackbar('Transaction Saved Successfully', 'success');
fetchTranasction();
returnTrans();

  fetch(`../config/transaction.php?action=getReceipt&transaction_id=${data.transaction_id}`)
      .then(res => res.json())
      .then(receiptData => {    

        populateReceipt(receiptData);

        // open print
        window.print();
        cart = {};
      });


    } else {
        alert('Error: ' + data.message);
    }
});


function returnTrans() {
    const trantab = document.getElementById('maintab');
    const prompt = document.getElementById('prompt1');
    const confirmSection = document.getElementById('confirm');
    const prompt_no = document.getElementById('cancel-tr');
    const prompt_yes = document.getElementById('go-tr');
    confirmSection.classList.add('hidden');
    prompt.classList.remove('hidden');

    prompt_yes.onclick = function () {
        cart = {};
        updateSummary();
        trantab.classList.remove('hidden');
        prompt.classList.add('hidden');
    }
    prompt_no.onclick = function () {
        closeform();
    }

}
}}

function populateReceipt(data){

const t = data.transaction;
const items = data.items;

const total = Number(t.total_amt);
const subtotal = total / 1.12;
const vat = total - subtotal;

// VAT
document.getElementById("trans-vat").textContent =
"₱" + vat.toLocaleString('en-PH', {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});

// transaction info
document.getElementById("trans-number").textContent = t.transaction_no;
document.getElementById("trans-cashier").textContent = t.username;
document.getElementById("trans-date").textContent = t.date_created;

// payment
document.getElementById("trans-payment").textContent = t.payment_method;
document.getElementById("trans-reference").textContent = t.reference_num;

// total
document.querySelector("#receipt .font-bold").textContent =
"₱" + total.toLocaleString('en-PH', {minimumFractionDigits:2});
    
// items table
const tbody = document.getElementById("trans-items");
tbody.innerHTML = "";


// other
document.getElementById("amt-r").textContent =
"₱" + t.amt_received.toLocaleString('en-PH', {  minimumFractionDigits: 2,
  maximumFractionDigits: 2});


let totalItems = 0;
let subtotalSum = 0;
let change = t.amt_received - t.total_amt;
items.forEach(item => {

    const itemSubtotal = Number(item.subtotal);

    totalItems += Number(item.qty);
    subtotalSum += itemSubtotal;
    
    


    const row = document.createElement("tr");

    row.innerHTML = `
      <td>${item.name}</td>
      <td>${item.qty}</td>
      <td class="text-right">
        ₱${itemSubtotal.toLocaleString('en-PH',{minimumFractionDigits:2})}
      </td>
    `;

    tbody.appendChild(row);
});
document.getElementById("amt_ch").textContent =
"₱" + change.toLocaleString('en-PH', {minimumFractionDigits:2});
// subtotal
document.getElementById("trans-sub").textContent =
"₱" + subtotalSum.toLocaleString('en-PH', {minimumFractionDigits:2});

// item count
document.getElementById("trans-items-count").textContent = totalItems;

}


function fetchTranasction() {
    document.querySelector('[data-filter="all"]').click();
    fetch("../config/transaction.php?action=getTransaction")
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

        
        filters();

        
    });

}

updateShiftSummary();

document.getElementById("transactionContainer").addEventListener("click", function(e) {

    const btn = e.target.closest(".transopen");

    if (btn) {

        const card = btn.closest(".transaction-card");
        const id = card.dataset.transaction;

        openModal1('transview');
        viewTransaction(id);

    }

});

function formatPeso(value) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2
    }).format(value);
}

function viewTransaction(id) {
    let trans_no = null;

    fetch(`../config/transaction.php?action=viewTransaction&id=${id}`)
    .then(res => res.json())
    .then(data => {
        if (data.length === 0) return;

        const transaction = data[0];

        // Display transaction info
        document.getElementById("trans_no").textContent = "TRNS-" + String(transaction.transaction_id).padStart(3, "0");
        document.getElementById("trans_date").textContent = transaction.date_created;
        document.getElementById("trans_payment").textContent = transaction.payment_method;
        document.getElementById("ref-num").textContent = transaction.reference_num;

        // Calculate change
        trans_no = transaction.transaction_id;
      
        const amt_received = parseFloat(transaction.amt_received);
        const total_amt = parseFloat(transaction.total_amt);
        const change = amt_received - total_amt;

        // Display amounts in PHP peso
        document.getElementById("amt_r").textContent = formatPeso(amt_received);
        document.getElementById("amt_c").textContent = formatPeso(change);

        // Populate items table
        const tbody = document.querySelector("table tbody");
        tbody.innerHTML = "";
        let total = 0;

        data.forEach(item => {
            const subtotal = parseFloat(item.subtotal);
            total += subtotal;

            const row = document.createElement("tr");
            row.innerHTML = `
                <td class="py-1 text-[#1A2F58]">${item.item_name}</td>
                <td class="py-1 text-[#1A2F58]">${item.quantity}</td>
                <td class="py-1 text-[#1A2F58]">${formatPeso(subtotal)}</td>
            `;
            tbody.appendChild(row);
        });

        document.getElementById("transtotal").textContent = formatPeso(total);
          const void_tr = document.getElementById('void');
                if (transaction.status === 'Void') {
                    void_tr.disabled = true;
                    void_tr.textContent = "This Transaction has been voided";
                    void_tr.classList.add('cursor-not-allowed');
                }
                else {
                    void_tr.disabled = false;
                     void_tr.classList.remove('cursor-not-allowed');
                    
                    void_tr.onclick = () => {
                    openModal1('voidTrans');
                    const go_void = document.getElementById('go-void');
                    go_void.onclick = () => {
                        voidform(transaction.transaction_id);
                    }
                };
                 
                }
            
            const generate = document.getElementById('gen_r');
            if(transaction.status === 'Void') {
                generate.classList.add('hidden');
            }
            else {
                generate.classList.remove('hidden');
            }
            generate.onclick = () => {
                 fetch(`../config/transaction.php?action=getReceipt&transaction_id=${id}`)
                    .then(res => res.json())
                    .then(receiptData => {    

                        populateReceipt(receiptData);

                        // open print
                        window.print();
                    });

            }
       
        
    })
    .catch(err => console.error(err));

}


function voidform(id) {
    const main = document.getElementById('mainvoid');
    const confirmvoid = document.getElementById('confirmvoid');
    const form = document.getElementById("form-void");


    main.classList.add('hidden');
    confirmvoid.classList.remove('hidden');

    // Remove any previous listener to prevent duplicates
    form.replaceWith(form.cloneNode(true)); 
    const newForm = document.getElementById("form-void");
    const newInput = document.getElementById("manager_pass");
    const newErrorMsg = document.getElementById("error-msg");

    newForm.addEventListener("submit", async function(e) {
        e.preventDefault();
        const password = newInput.value.trim(); // Trim spaces

        try {
            const res = await fetch("../config/transaction.php?action=verifypass", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ password: password })
            });

            const data = await res.json();

            if(data.success){
                closeform();
                voidtrans(id);
            } else {
              
                // Show error
                newErrorMsg.classList.remove("opacity-0");
                newErrorMsg.classList.add("opacity-100");
                newInput.classList.add("border-red-700", "ring-2", "ring-red-500", "rounded");
                newInput.classList.add("animate-shake");
                setTimeout(() => newInput.classList.remove("animate-shake"), 300);
                newInput.value = '';
                newInput.focus();
            }
        } catch(err) {
            console.error(err);
        }
    });

    // Remove error effects as user types
    newInput.addEventListener("input", () => {
        newInput.classList.remove("border-red-700", "ring-2", "ring-red-500");
        newErrorMsg.classList.remove("opacity-100");
        newErrorMsg.classList.add("opacity-0");
    });
}

async function updateShiftSummary() {

    const response = await fetch('../config/transaction.php?action=getsummary');
    const data = await response.json();

    if (!data.summary) return;

    document.querySelector('.summary-transactions').textContent =
        data.summary.transactions;

    document.querySelector('.summary-liters').textContent =
        Number(data.summary.totalLiters).toFixed(2) + " L";

    document.querySelector('.summary-products').textContent =
        data.summary.totalProducts;

    document.querySelector('.summary-voids').textContent =
        data.summary.voidedTransactions;

    document.querySelector('.summary-cash').textContent =
        "₱ " + Number(data.summary.cashTotal).toFixed(2);

    document.querySelector('.summary-credit').textContent =
        "₱ " + Number(data.summary.creditTotal).toFixed(2);

    document.querySelector('.summary-online').textContent =
        "₱ " + Number(data.summary.onlineTotal).toFixed(2);

    document.querySelector('.summary-total').textContent =
    "₱ " + Number(data.summary.totalSales).toFixed(2);

    document.getElementById('total-sh').textContent = 
    "₱ " + Number(data.summary.totalSales).toFixed(2);

    const start = new Date(data.shiftStart);
const end = new Date(data.shiftEnd);

const dateOptions = { year: 'numeric', month: 'short', day: 'numeric' };
const timeOptions = { hour: 'numeric', minute: '2-digit' };

document.querySelector('.summary-date').textContent =
    start.toLocaleDateString('en-PH', dateOptions);

document.querySelector('.summary-time').textContent =
    `${start.toLocaleTimeString('en-PH', timeOptions)} - ${end.toLocaleTimeString('en-PH', timeOptions)}`;


        const currentTotal = Number(data.summary.totalSales);
        const previousTotal = Number(data.previousTotal);

        const comparisonText = document.getElementById('comparisonText'); // span in your card
        const comparisonIcon = document.getElementById('comparisonIcon'); // img in your card

        // Calculate difference
        const diff = currentTotal - previousTotal;
        const absDiff = Math.abs(diff);

        if (previousTotal === 0) {
    comparisonText.textContent = "First recorded shift";
    comparisonText.classList.remove('text-green-600', 'text-red-600');
    comparisonText.classList.add('text-gray-600'); // neutral
    comparisonIcon.src = "../assets/increase.png";
} else if (diff > 0) {
    comparisonText.textContent = `₱ ${absDiff.toLocaleString('en-PH')} more than the previous shift`;
    comparisonText.classList.remove('text-gray-600', 'text-red-600');
    comparisonText.classList.add('text-green-600'); // green for increase
    comparisonIcon.src = "../assets/increase.png";
} else if (diff < 0) {
    comparisonText.textContent = `₱ ${absDiff.toLocaleString('en-PH')} less than the previous shift`;
    comparisonText.classList.remove('text-gray-600', 'text-green-600');
    comparisonText.classList.add('text-red-600'); // red for decrease
    comparisonIcon.src = "../assets/decrease.png";
} else {
    comparisonText.textContent = "Same as previous shift";
    comparisonText.classList.remove('text-r ed-600', 'text-green-600');
    comparisonText.classList.add('text-gray-600'); // neutral
    comparisonIcon.src = "../assets/increase.png";
}

        // Update other stats in your card as needed
        document.querySelector('.summary-total').textContent = `₱ ${currentTotal.toLocaleString('en-PH')}`;

}

function voidtrans(id){
fetch("../config/transaction.php?action=voidTransaction", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ transaction_id: id })
})
.then(res => res.json())
.then(data => {
    if(data.success){
        showSnackbar('Transaction Voided Successfully', 'sucess');
    } else {
        showSnackbar('Error found', 'error');
    }
});
fetchTranasction();
}


function filters() {
    const search = document.getElementById("searchTransaction");
    const buttons = document.querySelectorAll(".filter-btn");

buttons.forEach(button => {

button.addEventListener("click", () => {
search.value = "";
const filter = button.dataset.filter;

const cards = document.querySelectorAll(".transaction-card");

const today = new Date();

cards.forEach(card => {

const date = new Date(card.dataset.date);
const diff = (today - date) / (1000 * 60 * 60 * 24);

let show = false;

if(filter === "all") show = true;
if(filter === "today") show = diff < 1;
if(filter === "yesterday") show = diff >= 1 && diff < 2;
if(filter === "week") show = diff <= 7;

card.style.display = show ? "flex" : "none";

});


/* ACTIVE BUTTON STYLE */

buttons.forEach(btn => {
btn.classList.remove("bg-[#1A2F58]", "text-white");
});

button.classList.add("bg-[#1A2F58]", "text-white");

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

});






 

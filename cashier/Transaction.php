<?php
require_once '../config/auth.php';

// Admin page
if ($_SESSION['role'] !== 'Cashier') {
    header("Location: /Alpha Stage/Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>
  
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <link href="../dist/output.css" rel="stylesheet">
  <!-- Fonts Awesome-->
  <script src="https://kit.fontawesome.com/09f8ae972d.js" crossorigin="anonymous"></script>  
  <script src="../js/cashier/dashboard.js" defer></script>
  <script src="../js/cashier/transaction.js" defer></script>

  <!-- JS Chart-->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>



<div id="wrapper" class="fixed inset-0 flex items-center justify-center z-50
            opacity-0 pointer-events-none transition-opacity duration-500">

<div id="overlay" class="fixed inset-0 bg-black/50 opacity-0 pointer-events-none z-40 transition-opacity duration-300"></div>

 <div id="modalContent"
       class="w-full h-full transform 
       translate-y-5 opacity-0 transition-all duration-300 z-50 flex justify-center items-center" >

  </div>
 
</div>


<template id="addtrans">
<div class="flex flex-row justify-between w-[80%] h-[90%] bg-white shadow-md shadow-black/20 rounded border border-[#1A2F58]/20">
            <div class="flex flex-col w-3/4 p-3 gap-3">
              <div class="flex flex-row gap-4 w-full">
                <button id="tabbtn" class="flex flex-row px-2 w-1/4 py-2 bg-[#1A2F58] rounded justify-between font-inter text-white 
                text-sm font-semibold" onclick="toggleView()">
                  <span id="tabtxt">Add Fuel</span>
                  <span>F1</span>
                </button>
                <button class="flex flex-row px-2 w-1/4 py-2 bg-[#1A2F58] rounded justify-between font-inter text-white 
                text-sm font-semibold">
                  <span>Discount</span>
                  <span>F2</span>
                </button>
                <div id="search" class="relative">
                      <input type="text" placeholder="Search..." class="w-full p-1 border border-[#1F3A69] font-inter font-normal rounded-[3px]">
                      <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-3  opacity-50"></i>
                    </div>
              </div>
              <div class="h-[550px]">
                <div id="prodtab" class="grid grid-cols-3 h-full auto-rows-[8rem] overflow-x-auto overflow-y-hidden">
                <div class="w-52 p-2 gap-3 items-center h-28  flex flex-row border border-[#1F3A69]/20 shadow-md shadow-black/20">
                  <img src="../assets/Sample.png" class="w-20 h-full">
                  <div class="flex flex-col font-inter text-[#1A2F58] items-start w-full">
                    <div class="flex flex-col w-full">
                    <span class="font-semibold text-xs max-w-full">Brake Fluid</span>
                    <span class="font-medium text-xs">Product Name</span>
                    <span class="font-medium text-xs">Quantity</span>

                    <div class="flex items-end justify-end w-full">
                      <button class="bg-[#1A2F58] flex justify-center items-center rounded-full w-7 h-7">
                        <i class="fa-solid fa-plus text-xl text-[#F8F8FF]"></i>
                      </button>
                    </div>
                  </div>
                    
                  </div>
                </div>
                <div class="w-52 p-2 gap-3 items-center h-28  flex flex-row border border-[#1F3A69]/20 shadow-md shadow-black/20">
                  <img src="../assets/Sample.png" class="w-20 h-full">
                  <div class="flex flex-col font-inter text-[#1A2F58] items-start w-full">
                    <div class="flex flex-col w-full">
                    <span class="font-semibold text-xs max-w-full">Brake Fluid</span>
                    <span class="font-medium text-xs">Product Name</span>
                    <span class="font-medium text-xs">Quantity</span>

                    <div class="flex items-end justify-end w-full">
                      <button class="bg-[#1A2F58] flex justify-center items-center rounded-full w-7 h-7">
                        <i class="fa-solid fa-plus text-xl text-[#F8F8FF]"></i>
                      </button>
                    </div>
                  </div>
                    
                  </div>
                </div>

                <div class="w-52 p-2 gap-3 items-center h-28  flex flex-row border border-[#1F3A69]/20 shadow-md shadow-black/20">
                  <img src="../assets/Sample.png" class="w-20 h-full">
                  <div class="flex flex-col font-inter text-[#1A2F58] items-start w-full">
                    <div class="flex flex-col w-full">
                    <span class="font-semibold text-xs max-w-full">Brake Fluid</span>
                    <span class="font-medium text-xs">Product Name</span>
                    <span class="font-medium text-xs">Quantity</span>

                    <div class="flex items-end justify-end w-full">
                      <button class="bg-[#1A2F58] flex justify-center items-center rounded-full w-7 h-7">
                        <i class="fa-solid fa-plus text-xl text-[#F8F8FF]"></i>
                      </button>
                    </div>
                  </div>
                    
                  </div>
                </div>
              </div>
              <!--Fuel Tab-->
              <div id="fueltab"  class="hidden h-full flex flex-row gap-5">
                <div class="w-[30%] h-full flex flex-col font-inter gap-3 text-[#1A2F58] pt-3">
                  <div class="flex flex-col mb-3">
                  <span class="text-xl font-bold">Add Fuel</span>
                  <span class="text-md font-medium tracking-tighter">
                    Select one of the following fuel
                    products below to add on your order.</span>
                  </div>
                  <button id="fuel-1" class="w-full h-28 bg-[#1A2F58] rounded shadow-md shadow-black/20
                  flex flex-col justify-between items-start text-white font-inter p-3 hover:bg-[#223B6E] transition">
                      <div class="flex flex-col w-full h-auto text-left">
                        <span class="font-bold text-base">Diesel</span> 
                        <span class="font-medium text-sm">Price Per Liter</span> 
                      </div>
                      <div class="flex flex-row gap-3 items-center text-xs">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                        <span>Label</span>
                      </div>
                  </button>
                  <button id="fuel-2" class="w-full h-28 bg-[#1A2F58] rounded shadow-md shadow-black/20
                  flex flex-col justify-between items-start text-white font-inter p-3 hover:bg-[#223B6E] transition">
                      <div class="flex flex-col w-full h-auto text-left">
                        <span class="font-bold text-base">Diesel</span> 
                        <span class="font-medium text-sm">Price Per Liter</span> 
                      </div>
                      <div class="flex flex-row gap-3 items-center text-xs">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                        <span>Label</span>
                      </div>
                  </button>
                   <button id="fuel-3" class="w-full h-28 bg-[#1A2F58] rounded shadow-md shadow-black/20
                  flex flex-col justify-between items-start text-white font-inter p-3 hover:bg-[#223B6E] transition">
                      <div class="flex flex-col w-full h-auto text-left">
                        <span class="font-bold text-base">Diesel</span> 
                        <span class="font-medium text-sm">Price Per Liter</span> 
                      </div>
                      <div class="flex flex-row gap-3 items-center text-xs">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                        <span>Label</span>
                      </div>
                  </button>

                    
                </div>
                <div class="flex flex-col p-2 gap-1 w-3/4 h-full font-inter text-[#1A2F58] bg-white border rounded border-[#1A2F58]/20 shadow-md shadow-black/20">
                  <span class="font-semibold">Selected Fuel</span>
                  <div class="flex flex-row w-full justify-between font-bold text-xl">
                    <span id="fuel-name" >No Fuel Selected</span> 
                    <span id="fuel-price">₱0.00</span> 
                  </div>
                  <div class="flex flex-row w-full items-center gap-1">
                    <span class="text-base font-semibold w-48 ">Denomination (by ₱)</span>
                    <div class="w-3/4 border-0 rounded border-t border-[#1A2F58]"></div>
                  </div>
                  <div id="deno-ph" class="grid grid-cols-3 gap-3 font-bold">
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="1000">₱1,000</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="500">₱500</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="400">₱400</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="300">₱300</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="200">₱200</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="100">₱100</button>
                  </div>
                  <div class="flex flex-row w-full items-center gap-1">
                    <span class="text-base font-semibold w-64 ">Denomination (by Liters)</span>
                    <div class="w-3/4 border-0 rounded border-t border-[#1A2F58]"></div>
                  </div>
                  <div id="deno-l" class="grid grid-cols-3 gap-3 font-bold">
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="25" >25</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="20">20</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="15">15</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="10">10</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="5">5</button>
                    <button class="deno-btn border border-[#1A2F58] bg-[#F3F7FF] py-2 px-3 rounded hover:bg-[#b3bfd8] transition duration-200" data-value="1">1</button>
                  </div>
                  <div class="flex flex-row w-full items-center gap-1">
                    <span class="text-base font-semibold w-64">Custom Amount (₱ or L)</span>
                    <div class="w-3/4 border-0 rounded border-t border-[#1A2F58]"></div>
                  </div>
                  <div class="flex flex-row gap-3">
                    <input id="custom-input" placeholder="Custom Amount" type="number" min="1" max="1000000" class="bg-[#F5F5F5] border border-[#1A2F58]/30 rounded p-2 w-1/2">
                    <button id="toggle-btn" class="font-semibold text-[#1A2F58] border border-[#1A2F58] rounded bg-[#F3F7FF] w-20 text-sm hover:bg-[#b3bfd8] transition duration-200">
                      By Liters</button>
                  </div>
                  <div class="flex flex-row items-end justify-between h-full">
                    <div class="flex flex-col">
                      <span class="font-semibold">Total Amount</span>
                      <span id="fuel-total-amount"  class="font-bold text-xl">₱ 0.00</span>
                    </div>
                    <button id="addFuel" class="h-1/2 text-sm rounded hover:bg-[#3fa75e] transition-colors px-3 bg-[#33814B] text-white font-medium">
                      Add to Order
                    </button>
                  </div>
                </div>
              </div>

              </div>

            </div>
            <div class="mb-3 p-3 flex flex-col w-[40%] h-full border bg-white border-[#173161]/20 font-inter">
              <div class="flex flex-row justify-between items-center mb-1">
                <span class="text-xl font-bold text-[#1A2F58]">Order Summary</span>
                <button onclick="closeform()" class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
              </div>
              <span class="text-[#1A2F58] font-semibold mb-2">Order Details</span>
              <div class="border-b border-[#1A2F58]/50 min-h-72    max-h-72 overflow-y-auto mb-2">
                 <table class="w-full text-left border-collapse">
              <thead class="sticky top-0 bg-[#1A2F58] shadow-md shadow-black/20 text-xs tracking-wide  text-white">
                <tr>
                  <th class="py-2 px-2 font-inter font-medium">Product Name</th>
                  <th class="py-2 px-2 font-inter font-medium">Unit Price</th>
                  <th class="py-2 px-2 font-inter font-medium">Quantity</th>
                  <th class="py-2 px-2 font-inter font-medium text-right">Total</th>
                  <th class="py-2 px-2 font-inter font-medium"></th>
                </tr>
              </thead>
              <tbody id="cart-body" class="text-xs text-[#1A2F58] divide-y divide-[#1A2F58]/20">

              </tbody>
              </table>
              </div>
              <div class="flex flex-row justify-between font-inter text-[#1A2F58] text-sm p-1">
                  <span class="font-bold">Payment Method</span>
                  <select id="payment-method" class="border border-[#1A2F58] px-2 focus:outline-[#1A2F58] rounded-sm font-bold py-1">
                    <option>Cash</option>
                      <option>Card</option>
                    <option>Online</option>
                  </select>
              </div>
              <div class="flex flex-col">
                    <div class="flex flex-row justify-between text-[#1A2F58] text-sm font-medium px-1">
                  <span>Subtotal</span>
                  <span id="summary-subtotal">₱0.00</span>
              </div>
              <div class="flex flex-row justify-between text-[#1A2F58] text-sm font-medium px-1">
                  <span>VAT (12%)</span>
                  <span id="summary-vat">₱0.00</span>
              </div>
              <div class="flex flex-row justify-between text-[#1A2F58] text-sm font-medium px-1 mb-3">
                  <div>
                    <span>Discount</span>
                     <span>(Discount Name):</span>
                  </div>
                  <span>₱0.00</span>
              </div>
              <div class="flex flex-row justify-between text-[#1A2F58] text-base font-bold   px-1 mb-3">
                  <span>TOTAL:</span>
                  <span id="summary-total">₱0.00</span>
              </div>
              </div>
              
              <div class="flex flex-row gap-2 text-[13px] px-1">
                <button id="clear-cart-btn" class="flex px-2 hover:bg-[#ff6363] transition-colors   w-1/2 flex-row gap-4 bg-[#fc1919] justify-between text-white py-3 rounded p-1">
                <span>Clear Order</span>  
                <span>F3</span>
              </button>
                <button id="save-btn" class="bg-[#33814B] w-1/2  hover:bg-[#3fa75e] transition-colors p-1 px-1 text-white rounded">Submit Transaction</button>
                
              </div>
            </div>
</div>
</template>

<body class="overflow-x-hidden overflow-y-hidden bg-gradient-to-b from-[#FFFFFF] via-[#F8F8FF] to-[#F8F8FF]">
    <nav class="w-64 bg-gradient-to-b from-[#1B2D50] via-[#1B2D50_70%] to-[#35496E] h-screen fixed top-0 left-0 shadow-[5px_0_10px_3px_rgba(0,0,0,0.25)]
    flex flex-col items-center">
        <img src="../assets/GSMS2.png" class="p-5 mb-[40px]">
        <div class="flex flex-col w-full pt-12 mb-10">
         
        <a href="dashboard1.php" class="z-10 flex items-center gap-5  w-full p-4 bg-[#1B2D50] hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <i class="fa-regular fa-window-restore text-2xl text-[#F8F8FF]"></i>
        <span class="font-medium text-lg font-poppins">Dashboard</span>
        </a>
         <div class="relative border-[0.5] bg-[#334A78] border-[#F8F8FF] w-full p-3 h-16 flex flex-row gap-5 justify-center items-center">
          <div style="position:absolute; top:0; left:0; width:5px; height:100%; background:white;"></div>
          <i class="fa-solid fa-money-bills text-2xl  text-[#F8F8FF]"></i>
          <p class="text-lg text-[#F8F8FF] font-poppins font-medium w-[70%]">Transactions</p>
          </div>
         <a href="Products.php" class="z-10 flex items-center gap-5  w-full p-4 bg-[#1B2D50] hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <i class="fa-solid fa-gas-pump text-2xl"></i>
        <span class="font-medium text-lg font-poppins">Products</span>
        </a>
        </div>
        <div class="border-t-2 w-[85%] p-5 border-[#F8F8FF]">
          <div class="flex flex-row items-center gap-3 mb-3">
            <div class="w-11 h-11 ">
              <img src="../assets/Profile.jpg" class="rounded-full w-full h-full object-cover block">
            </div>

          <div>
              <p class="font-semibold font-inter text-[#F8F8FF]">John Doe</p>
              <p class="text-[#F8F8FF] font-inter font-medium text-sm">Cashier</p>
          </div>
          </div>
          <div class="flex flex-row items-center gap-5 pl-3 mb-4 ">
            <div id="status_color" class="w-3 h-3 bg-red-500 rounded-full"></div>
            <p id="shift_status" class="font-inter text-[10px] text-[#F8F8FF]">Shift not started yet</p>
          </div>
          <div class="flex flex-row justify-between mb-5">
            <p class="font-inter text-[11px] text-[#F8F8FF] font-normal" id="date">Placeholder</p>
            <p class="font-inter text-[11px] text-[#F8F8FF] font-normal " id="time">Placeholder</p>
          </div>
          


          <button class="bg-[#1B2D50] border-[0.5px] border-[#E5EFFF] w-full h-11 font-inter text-[#F8F8FF]
          rounded-lg hover:bg-[#284379] transition-colors duration-200" 
         onclick="openModal(
                'Log Out', 
                'Are you sure you want to log out? Logging out will end your shift.', 
                () => { 
                      endShift();
                        setTimeout(() => {
                            window.location.href='../config/logout.php';
                        }, 500); // 500ms delay to give endShift time to complete
                }
            ),
            '#FF7979' , '#1A2F58', '#A00000', '#1A2F58'">
            Log Out
          </button>
        </div>

    </nav> 


</div>
    <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-col gap-5">
    <div class="flex flex-row justify-between items-center">
          <span class="text-2xl font-inter font-bold text-[#1F3A69]">Transactions</span>
       <button id="transbtn" class="p-3 bg-[#1F3A69] w-72 flex items-center flex-row gap-10 rounded-md hover:bg-[#35518B] transition">
                    <i class="fa-solid fa-plus text-xl text-[#F8F8FF] ml-2"></i>
                          <p class="text-[#F8F8FF] text-center font-inter font-semibold text-sm">Add new Transaction</p>
        </button> 
    </div>
    <div class="flex flex-row w-full gap-5">
          <div class="flex flex-col gap-3 justify-start w-[45%]">

    <div class=" p-3 bg-white h-[497px] w-full shadow-md  shadow-black/20 rounded border border-[#173161]/20">
     <div class="flex flex-row justify-between mb-3">
        <span class="text-xl font-inter font-semibold text-[#1F3A69]">Shift Summary</span>
        <div class="flex flex-col items-end">
          <span class="text-sm font-inter font-normal text-[#1F3A69]">Date</span>
          <span class="text-sm font-inter font-normal text-[#1F3A69]">Shift start & end</span>
        </div>
        
     </div>
        <div class="w-full flex p-5 flex-row items-center gap-10 bg-[#FEFFFA] h-24 mb-3 shadow-md shadow-black/20 rounded-md border border-black/10"> 
          <img src="../assets/increase.png" class="w-16 h-16" alt="increase">
          <div class="font-inter flex flex-col">
            <span class="text-[#1F3A69] font-bold text-lg">₱ 00.00</span>
            <span class="text-sm">Placeholder text</span>

        </div>
        </div>
          <div class="divide-y divide-[#1A2F58]/20 text-sm divide-dashed flex flex-col text-[#1F3A69] font-inter font-medium">
          <div class="flex flex-row justify-between py-1">
            <span>No. of Transactions </span>
            <span>Placeholder </span>
          </div>
          <div class="flex flex-row justify-between py-1">
            <span>Total Liters Sold</span>
            <span>Placeholder </span>
          </div>
           <div class="flex flex-row justify-between py-1">
            <span>Total Products Sold</span>
            <span>Placeholder </span>
          </div>
           <div class="flex flex-row justify-between py-1">
            <span class="font-semibold text-[#9E3030]">Voided Transaction </span>
            <span class="font-semibold text-[#9E3030]">Placeholder </span>
          </div>
          </div>
          <div class="border-t border-b border-[#1A2F58]/50 p-2">
            <span class="font-bold text-[#1A2F58] font-inter">Cash Details </span>
          </div>
           <div class="divide-y divide-[#1A2F58]/20 text-sm divide-dashed flex flex-col text-[#1F3A69] font-inter font-medium">
          <div class="flex flex-row justify-between py-1">
            <span>Starting Cash</span>
            <span>Placeholder </span>
          </div>
          <div class="flex flex-row justify-between py-1">
            <span>Cash Sales</span>
            <span>Placeholder </span>
          </div>
           <div class="flex flex-row justify-between py-1">
            <span>Expected Sales</span>
            <span>Placeholder </span>
          </div>
          </div>
           <div class="flex flex-row justify-between py-1 border-[#1A2F58]/20 border-dashed border-t font-inter ">
            <span class="font-bold text-[#1A2F58]">Total Cash </span>
            <span class="font-bold text-[#1A2F58]">Placeholder </span>
          </div>
          <div class="flex flex-row justify-between font-inter py-1">
            <span class="font-bold text-[#9E3030]">Cash Difference </span>
            <span class="font-bold text-[#9E3030]">Placeholder </span>
          </div>
    
    </div>
    </div>
     <div class="flex flex-col gap-3 items-end w-3/4" >
    <div class="bg-white h-[497px] w-full shadow-md p-3  shadow-black/20 rounded border border-[#173161]/20">
      <div class="flex flex-row justify-between items-center font-inter mb-3">
        <span class="text-lg text-[#1A2F58] font-bold">Transaction History</span>
            <div class="flex flex-row gap-3"> 
                      <div class="relative">
                      <input type="text" placeholder="Search..." class="w-full p-1 border border-[#1F3A69] font-inter font-normal rounded-[3px]">
                      <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-2 opacity-50"></i>
            </div>
      </div>
      </div>
       <div class="flex flex-row w-full gap-3 font-inter font-semibold text-[#1A2F58] text-sm mb-4">
        <button class="rounded-full border-2 px-4 shadow-md py-1 shadow-black/20 border-[#1A2F58]">All</button>
        <button class="rounded-full border-2 px-4 shadow-md py-1 shadow-black/20 border-[#1A2F58]">Today</button>
        <button class="rounded-full border-2 px-4 shadow-md py-1 shadow-black/20 border-[#1A2F58]">Yesterday</button>
        <button class="rounded-full border-2 px-4 shadow-md py-1 shadow-black/20 border-[#1A2F58]">Last Week</button>      
        <button class="rounded-full border-2 px-4 shadow-md py-1 shadow-black/20 border-[#1A2F58]">Last Week</button>
        <button class="rounded-full bg-[#1A2F58] text-white border-2 px-4 shadow-md shadow-black/20 border-[#1A2F58]">Active</button>
      </div>
      <div class="flex flex-col border-t h-3/4 border-b border-[#1A2F58]/50">
        <div></div>
      </div>
    </div>
    </div>

    </main>

   <div id="snackbar" style="
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%) translateY(0);
      background-color: #333;
      color: #fff;
      padding: 12px 24px;
      border-radius: 8px;
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      opacity: 0;
      pointer-events: none;
      transition: all 0.3s ease;
      z-index: 40;
  "></div>

<

</body>
</html>
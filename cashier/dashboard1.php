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
  <title>Dashboard</title>
  
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <link href="../dist/output.css" rel="stylesheet">
  <link rel="stylesheet" href="../receipt.css"> 
  <!-- Fonts Awesome-->
  <script src="https://kit.fontawesome.com/09f8ae972d.js" crossorigin="anonymous"></script>  
  <script src="../js/cashier/dashboard.js" defer></script>
  <script src="../js/cashier/general.js" defer></script>
  
</head>


<body class="overflow-visible overflow-y-hidden bg-gradient-to-b from-[#FFFFFF] via-[#  ] to-[#F8F8FF]">
<div id="receipt" class="hidden bg-white p-2 text-sm font-mono">
  <!-- Store Header -->
  <div class="text-center relative">
    <h2 class="text-base font-semibold">M Opol Gasoline Station</h2>
    <p>Luyong Bonbon, Opol, Misamis Oriental</p>
    <p>Tel: 1201-213-1201</p>
    <hr class="my-2 border-t border-black">
  </div>

  <!-- Transaction Info -->
  <div class="mb-2">
    <div class="flex flex-row justify-between">
      <span>Transaction No : </span>
      <span id="trans-number">Placeholder </span>
    </div>
    <div class="flex flex-row justify-between">
      <span>Date & Time : </span>
      <span id="trans-date">Placeholder </span>
    </div>
    <div class="flex flex-row justify-between">
      <span>Cashier : </span>
      <span id="trans-cashier">Placeholder </span>
    </div>

    <hr class="my-2 border-t border-black">
  </div>
    <div class="mb-2">
       <div class="flex flex-row justify-between">
      <span>Number of Items : </span>
      <span id="trans-items-count">Placeholder </span>
      </div>
      <div class="flex flex-row justify-between">
      <span>Subtotal : </span>
      <span id="trans-sub">Placeholder </span>
      </div>
      <div class="flex flex-row justify-between">
      <span>VAT (12%) : </span>
      <span id="trans-vat">Placeholder </span>
      </div>
      <div class="flex flex-row justify-between">
      <span>Discount : </span>
      <span>Placeholder </span>
      </div>
    <hr class="my-2 border-t border-black">
  </div>
   <div class="mb-2">
       <div class="flex flex-row justify-between">
      <span>Payment Method : </span>
      <span id="trans-payment">Placeholder </span>
      </div>
      <div class="flex flex-row justify-between">
      <span>Reference Number : </span>
      <span id="trans-reference">Placeholder </span>
      </div>

    <hr class="my-2 border-t border-black">
  </div>

  <!-- Items -->
  <table class="w-full table-fixed text-left">
    <thead>
      <tr>
        <th class="w-1/2">Product Name</th>
        <th class="w-1/4">Qty</th>
        <th class="w-1/4 text-right">Price</th>
      </tr>
    </thead>
    <tbody id="trans-items">
    </tbody>
  </table>
  <hr class="my-1 border-t border-black">

  <!-- Total -->
    <div class="flex flex-row justify-between">
      <span>Total : </span>
      <span class="font-bold">Placeholder</span>
    </div>
    <div class="flex flex-row justify-between">
      <span>Amount Received : </span>
      <span id="amt-r">Placeholder</span>
    </div>
    <div class="flex flex-row justify-between">
      <span>Change : </span>
      <span id="amt_ch">Placeholder</span>
    </div>

  <hr class="my-1 border-t border-black">
  <!-- Footer -->
  <div class="text-center mt-2">
    <p>Thank you for choosing M Fuel!</p>
    <p>-This is a Customer Copy-</p>
  </div>
</div>

<nav class="w-64 bg-gradient-to-b from-[#1B2D50] via-[#1B2D50_70%] to-[#35496E] h-screen fixed top-0 left-0 shadow-[5px_0_10px_3px_rgba(0,0,0,0.25)]
    flex flex-col items-center">
        <img src="../assets/GSMS2.png" class="p-5 mb-[40px]">
        <div class="flex flex-col w-full pt-12 mb-10">
          <div class="relative border-[0.5] bg-[#334A78] border-[#F8F8FF] w-full p-3 h-16 flex flex-row gap-5 justify-center items-center">
          <div style="position:absolute; top:0; left:0; width:5px; height:100%; background:white;"></div>
          <i class="fa-regular fa-window-restore text-2xl text-[#F8F8FF]"></i>
          <p class="text-lg text-[#F8F8FF] font-poppins font-medium w-[70%]">Dashboard</p>
          </div>
        <a href="Transaction.php" class="z-10 flex items-center gap-5  w-full p-4 bg-[#1B2D50] hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <i class="fa-solid fa-money-bills text-2xl"></i>
        <span class="font-medium text-lg font-poppins">Transactions</span>
        </a>
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
          


          <button id="log-btn" class="bg-[#1B2D50] border-[0.5px] border-[#E5EFFF] w-full h-11 font-inter text-[#F8F8FF]
          rounded-lg hover:bg-[#284379] transition-colors duration-200" 
          >
            Log Out
          </button>
        </div>

    </nav>  
    
  

  
        <!---Summary--->
<div id="shiftPanel" class="fixed flex flex-col top-0 right-0 w-[28%] h-screen bg-[#F8F8FD] border border-blue-900 rounded-tl-xl rounded-bl-xl p-5
transform translate-x-full transition-transform duration-300 ease-in-out delay-200 z-50" >
    <button class="text-[#1F3A69] font-inter text-base font-normal mb-3 text-left" onclick="closePanel()"><u>Go Back</u></button>
    <p class="font-poppins font-semibold text-3xl text-[#1F3A69]">Shift Summary</p>
    <p class="font-poppins text-sm text-[#1F3A69]">Placeholder Date</p>
    <p class="font-poppins text-sm text-[#1F3A69] mb-4">Placeholder Time</p>
    <div class="flex flex-col items-center justify-center w-full h-auto">
        <div class="w-[90%] h-full rounded-lg bg-[#EAF6FF] shadow-[0_4px_4px_1px_rgba(0,0,0,0.25)]
        flex flex-col p-3 gap-5 mb-4">
        <p class="font-poppins font-semibold text-[#1F3A69] text-base">Todays Sale</p>
        <p class="font-poppins font-semibold text-[#1F3A69] text-3xl text-center mb-5">₱00.00</p>
        <p class="font-inter font-regular text-[#1F3A69] text-[11px]">placeholder comparison</p>
        </div>

      
            <div class="border-t border-b border-[#1F3A69] font-inter 
            text-[14px] font-semibold text-[#1F3A69] p-2 w-[90%]">
                Shift Stats
            </div>
              <div class="divide-y divide-[#1A2F58] divide-opacity-50 divide-dashed w-[90%]">
            <div class="flex flex-row justify-between w-full py-1">
            <p class="font-inter font-medium text-sm text-[#1F3A69]">No. of Transactions</p>
            <p class="font-inter font-medium text-sm w-[40%] text-[#1F3A69] text-right ">Placeholder</p>
            </div>
             <div class="flex flex-row justify-between w-full py-1">
            <p class="font-inter font-medium text-sm  text-[#1F3A69]">Total Liters Sold</p>
            <p class="font-inter font-medium text-sm w-[40%] text-[#1F3A69] text-right ">Placeholder</p>
            </div>
             <div class="flex flex-row justify-between w-full py-1">
            <p class="font-inter font-medium text-sm text-left text-[#9E3030]">Voided Transactions</p>
            <p class="font-inter font-medium text-sm w-[40%] text-right text-[#9E3030] ">Placeholder</p>
            </div>
            </div>
            <div class="border-t border-b border-[#1F3A69] w-[90%] font-inter 
            text-[14px] font-semibold text-[#1F3A69] p-2">
                Payment Methods Used
            </div>
             <div class="divide-y divide-[#1A2F58] divide-opacity-50 divide-dashed w-[90%] mb-4">
            <div class="flex flex-row justify-between w-full py-1">
            <p class="font-inter font-medium text-sm text-[#1F3A69]">Cash</p>
            <p class="font-inter font-medium text-sm w-[40%] text-[#1F3A69] text-right ">Placeholder</p>
            </div>
             <div class="flex flex-row justify-between w-full py-1">
            <p class="font-inter font-medium text-sm  text-[#1F3A69]">Credit</p>
            <p class="font-inter font-medium text-sm w-[40%] text-[#1F3A69] text-right ">Placeholder</p>
            </div>
             <div class="flex flex-row justify-between w-full py-1">
            <p class="font-inter font-medium text-sm text-left text-[#1F3A69]">Online</p>
            <p class="font-inter font-medium text-sm w-[40%] text-right text-[#1F3A69] ">Placeholder</p>
            </div>
            </div>

            <p class="font-inter text-[#1F3A69] opacity-75 font-normal text-sm"><u>can view this anytime in the Transactions tab.</u> </p>

        
    </div>
</div>



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

 <div id="wrapper" class="fixed inset-0 flex items-center justify-center z-50
            opacity-0 pointer-events-none transition-opacity duration-500">

<div id="overlay" class="fixed inset-0 bg-black/50 opacity-0 pointer-events-none z-40 transition-opacity duration-300"></div>

 <div id="modalContent"
       class="w-full h-full transform 
       translate-y-5 opacity-0 transition-all duration-300 z-40 flex justify-center items-center" >

  </div>
 
</div>   


<template id="start-sh">
      <div class="w-[30%] h-40 bg-white flex flex-col rounded-sm border border-[#1A2F58]/30">
        <div class="w-full h-10 bg-[#1A2F58] shadow-sm shadow-black/20 p-2 text-white font-inter">Start your Shift</div>
        <div class="p-3 font-inter text-[#1A2F58] font-medium">
            <span>Are you sure you want to start your shift?</span>
        </div>
        <div class="w-full h-full items-end justify-end flex flex-row gap-2 p-3">
           <button id="go-sh"  class="px-2 py-2 text-white bg-[#1A2F58] transition-colors hover:bg-[#1F3A69] border border-[#1A2F58] w-20 rounded font-medium">Yes</button>
                <button onclick="closeform()" class="px-2 py-2 text-white border border-[#A00000] bg-[#FF7979] w-20 rounded">No</button>
               
        </div>
       </div>
</template>

<template id="end-sh">
        <div class="w-[30%] h-40 bg-white flex flex-col rounded-sm border border-[#1A2F58]/30">
        <div class="w-full h-10 bg-[#1A2F58] shadow-sm shadow-black/20 p-2 text-white font-inter">End your Shift</div>
        <div class="p-3 font-inter text-[#1A2F58] font-medium">
            <span>Are you sure you want to end your shift?</span>
        </div>
        <div class="w-full h-full items-end justify-end flex flex-row gap-2 p-3">
                <button id="go-end" class="px-2 py-2 text-white border border-[#A00000] bg-[#FF7979] w-20 rounded">Yes</button>
                <button onclick="closeform()" class="px-2 py-2 text-white bg-[#1A2F58] transition-colors hover:bg-[#1F3A69] border border-[#1A2F58] w-20 rounded font-medium">No</button>
        </div>
       </div>
</template>

<template id="addtrans">
<div id="maintab" class="flex flex-row justify-between w-[80%] h-[90%] bg-white shadow-md shadow-black/20 rounded border border-[#1A2F58]/20">
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
              <div class="border-b border-[#1A2F58]/50 min-h-72 max-h-72 overflow-y-auto mb-2">
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
              <div class="flex flex-col gap-1">
                    <div class="flex flex-row justify-between text-[#1A2F58] text-sm font-medium px-1">
                  <span>Subtotal</span>
                  <span id="summary-subtotal">₱0.00</span>
              </div>
              <div class="flex flex-row justify-between text-[#1A2F58] text-sm font-medium px-1">
                  <span>VAT (12%)</span>
                  <span id="summary-vat">₱0.00</span>
              </div>
              <div class="flex flex-row justify-between text-[#1A2F58] text-sm font-medium px-1 mb-8">
                  <div>
                    <span>Discount</span>
                     <span>(Discount Name):</span>
                  </div>
                  <span>₱0.00</span>
              </div>
              <div class="flex flex-row justify-between text-[#1A2F58] text-lg font-bold px-1 mb-2  ">
                  <span>TOTAL:</span>
                  <span id="summary-total">₱0.00</span>
              </div>
              </div>
              
              <div class="flex flex-row gap-2 text-[13px] px-1">
                <button id="clear-cart-btn" class="flex px-2 hover:bg-[#ff6363] transition-colors   w-1/2 flex-row gap-4 bg-[#fc1919] justify-between text-white py-3 rounded p-1">
                <span>Clear Order</span>  
                <span>F3</span>
              </button>
                <button id="save-btn" class="bg-[#33814B] w-1/2  hover:bg-[#3fa75e] transition-colors p-1 px-1 text-white rounded">Proceed</button>
                
              </div>
            </div>
</div>

<div id="confirm" class="hidden w-[30%] font-inter shadow-md shadow-black/20 rounded border border-[#1A2F58]/20 h-[53%] bg-white flex flex-col">
            <div class="flex flex-row items-center rounded-tr rounded-tl justify-between w-full bg-[#1A2F58] h-10 p-2">
              <span class=" text-white font-medium text-sm">Confirm Transaction</span>
              <button onclick="" id="return" class="text-xl text-[#B22222] pr-1"><i class="fa-solid fa-x"></i></button>

            </div>
             <div class="flex flex-col gap-3 w-full p-3 text-[#1A2F58]">
                <div class="flex flex-row justify-between items-center">
                  <span class="font-semibold">Payment Method</span>
                  <select id="payment-method" class="px-3 bg-[#F5F5F5] border border-[#1A2F58]/30 py-1 rounded">
                    <option>Cash</option>
                    <option>Credit</option>
                    <option>Online</option>
                  </select>
                </div>
                <div class="flex flex-row justify-between items-center">
                  <div class="flex flex-col">
                    <span class="font-semibold">Reference Number</span>
                    <span class="text-[10px]">(for Card/Online Only)</span>
                  </div>
                  <input id="ref-num" autocomplete="off" type="text" disabled class=" w-1/2 bg-[#F5F5F5] border border-[#1A2F58]/30 py-1 rounded cursor-not-allowed">
                </div>
                <div class="w-full border-t border-[#1A2F58]/30 "></div>
                 <div class="flex flex-row justify-between items-center">
                  <span class="font-semibold">Total Amount :</span>
                  <span id="totalamt" class="font-semibold">Placeholder</span>
                  </div>
                  <div class="flex flex-row justify-between items-center">
                  <span class="font-semibold">Amount Received :</span>
                  <input id="amt-rec" type="num" autocomplete="off" placeholder="₱ 0.00" class="bg-[#F5F5F5] py-1 px-3 text-right w-1/2 border border-[#1A2F58]/30">
                  </div>
                  <div class="flex flex-row justify-between items-center">
                  <span class="font-semibold">Change:</span>
                  <span id="change" class="font-semibold">₱ 0.00</span>
                  </div>
                  <button id="save-trns" class="w-full h-10 bg-[#459A5F] rounded text-center text-white">
                    Save Transaction
                  </button>
                
              </div>
</div>

<div id="prompt1" class="hidden w-[30%] font-inter shadow-md shadow-black/20 rounded border border-[#1A2F58]/20 h-auto bg-white flex flex-col">
      <div class="flex flex-row items-center rounded-tr rounded-tl justify-start w-full bg-[#1A2F58] shadow-sm shadow-black/20 h-10 p-2">
              <span class=" text-white font-medium text-sm">Transaction Status</span>
      </div>
      <div class="w-full p-3 text-[#1A2F58] font-medium">
        <span>Transaction completed successfully. Do you want to make another transaction?</span>
      </div>
      <div class="w-full flex flex-row justify-end p-3 gap-3 ">
        <button id="cancel-tr" class="border border-[#F1EFF7] bg-[#F1EFF7] rounded text-[#1A2F58] p-2 w-1/4">No</button>
        <button id="go-tr" class="bg-[#1A2F58] text-white p-2 w-1/4 rounded hover:bg-[#2A457C] transition-colors">Yes</button>
      </div>
      
</div>
</template>

<template id="shifterror">
  <div class="w-[30%] h-40 bg-white flex flex-col rounded-sm border border-[#1A2F58]/30">
        <div class="w-full h-10 bg-[#1A2F58] shadow-sm shadow-black/20 p-2 text-white font-inter">Error </div>

        <div class="p-3 font-inter text-[#1A2F58] font-medium text-sm w-full">
            <span>Please start your shift before making a transaction</span>
        </div>
        <div class="w-full h-3/4 items-end justify-end flex flex-row p-3">
                <button onclick="closeform()" class="px-2 py-2 text-white bg-[#1A2F58] transition-colors hover:bg-[#1F3A69] border border-[#1A2F58] w-20 rounded font-medium">OK</button>
        </div>
       </div>
</template>

<template id="transview">
  <div class="bg-white font-inter h-[80%] w-1/4 shadow-md shadow-black/20 border border-[#1A2F58]/20 flex flex-col">
         <div class="flex flex-row items-center justify-between rounded-tr rounded-tl w-full bg-[#1A2F58] shadow-sm shadow-black/20 h-10 p-2">
              <span class=" text-white font-medium text-sm">Transaction Information</span>
              <button onclick="closeform()" class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
          </div>
          <div class="flex flex-col gap-3 p-3">
            <div class="flex flex-row justify-between text-[#1A2F58] font-medium">
                <span>Transaction ID:</span>
                <span id="trans_no">Placeholder</span>
            </div>
            <div class="flex flex-row justify-between text-[#1A2F58] font-medium">
                <span>Date & Time :</span>
                <span id="trans_date">Placeholder</span>
            </div>
            <div class="flex flex-row justify-between text-[#1A2F58] font-medium">
                <span>Payment Method :</span>
                <span id="trans_payment">Placeholder</span>
            </div>
            <div class="flex flex-row justify-between items-center text-[#1A2F58] font-medium">
                <span>Reference Number :</span>
                <span id="ref-num" class="text-sm"></span>
            </div>
            
            <div class="w-full max-h-44 min-h-44 rounded border border-[#1A2F58]/30 overflow-y-auto">
            <table class="w-full text-center border-collapse font-inter"> 
            <thead class="w-full bg-[#1A2F58] border-collapse text-white text-sm font-inter font-normal tracking-wide shadow-sm">
              <tr>
            <th class="px-1 py-2">Product Name</th>
              <th class="px-1 py-2">Quantity</th>
              <th class="px-1 py-2">Amount</th>
              </tr>
            </thead>
            <tbody id="transaction-items" class="text-sm text-center">
                <tr>
                  <td class="py-1">???</td>
                  <td class="py-1">???</td>
                  <td class="py-1">???</td>
                </tr>
            </tbody>
               <tfoot>
                <tr class="sticky bottom-0 bg-white border-t border-[#1A2F58]/30 text-[#1A2F58]">
                  <td></td>
                  <td class="font-bold">Total</td>
                  <td id="transtotal" class="font-bold">0.00</td>
                </tr>
              </tfoot>
            </table> 
            </div>
             <div class="flex flex-row justify-between text-[#1A2F58] font-medium">
                <span>Amount Received:</span>
                <span id="amt_r">Placeholder</span>
            </div>
            <div class="flex flex-row justify-between text-[#1A2F58] font-medium">
                <span>Change :</span>
                <span id="amt_c">Placeholder</span>
            </div>
            <div class="flex flex-row text-[#1A2F58] font-medium justify-center gap-3">
                <button id="void" class="border-[#B22222] text-sm w-auto border bg-[#FFDDDD] py-2 px-3 rounded text-[#B22222]">Void Transaction</button>
                <button id="gen_r" class="bg-[#1A2F58] w-1/2 text-sm py-2 px-3 rounded text-white">Generate Receipt</button>
            </div>
          </div>
      </div>
</template>

<template id="voidTrans">
  
      <div id="mainvoid" class="max-w-[50%] flex flex-col font-inter h-auto rounded bg-white border border-[#1A2F58]/20 shadow-md shadow-black/20">
        <div class=" bg-[#1A2F58] w-full h-10 text-white p-2">
          Void Transaction
        </div>
        <div class="flex flex-col p-2 w-full gap-3">
          <span class="text-left py-1 px-2 text-[#1A2F58] font-semibold">     
          Are you sure you want to void the selected Transaction?
          </span>
          <div class="relative flex flex-col w-full h-auto bg-[#FFE9D9] border border-[#FA703F]/20">
            <div class="w-2 h-full bg-[#FA703F] absolute"></div>
            <div class="flex flex-col pl-4 p-3">
            <div class="flex flex-row gap-2 items-center">
               <i class="fa-solid fa-triangle-exclamation text-[#771505] text-sm"></i>
               <span class="font-bold text-sm text-[#771505]">Warning</span>
            </div>
            <div>
                <span class="text-sm text-[#C1573B]">
                  Voiding this transaction will clear all transaction details <br>
                  and cannot be undone. Do you want to proceed?
                </span>
            </div>
            </div>
            
          </div>
          <div class="flex flex-row justify-between items-center font-semibold mb-2">
            <button class="p-3 bg-[#1A2F58] text-white rounded-sm" onclick="closeform()">No, Cancel</button>
            <button id="go-void" class="p-3 border-[#1A2F58] border-2 rounded-sm text-[#1A2F58]">Continue</button>
          </div>
        </div>
      </div>

       <div id="confirmvoid" class="hidden max-w-[50%] flex flex-col font-inter h-auto rounded bg-white border border-[#1A2F58]/20 shadow-md shadow-black/20">
        <form id="form-void"> 
       <div class=" bg-[#1A2F58] w-full h-10 text-white p-2 flex flex-row justify-between">
         <span>Prompt Confirmation</span>
         <button onclick="closeform()" type="button" class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
        </div>
        <div class="flex flex-col p-2 w-full gap-3">
          <span class="text-left text-[#1A2F58] font-semibold">     
           Manager credentials required to proceed.
          </span>
          <div class="relative w-full">
            <input id="manager_pass" type="password" autocomplete="off" class="border border-[#1A2F58]/30 bg-[#F5F5F5] h-10 pl-9 w-full transition-all duration-300" placeholder="Password">
             <i class="fa-solid fa-key absolute top-0 p-3 left-0 text-[#1A2F58]"></i>   
          </div>
          
          <div class="flex flex-row justify-between items-center font-semibold mb-2">
            <span id="error-msg" class="opacity-0 transition-opacity duration-300 text-sm font-medium text-[#A00000]">Incorrect Credentials, try again.</span>
            <button type="submit" class="transition p-2 bg-[#1A2F58] rounded-sm text-white">Continue</button>
          </div>
        </div>
        </form>
      </div>
</template>

<template id="logout">
        <div class="w-[30%] h-40 bg-white flex flex-col rounded-sm border border-[#1A2F58]/30">
        <div class="w-full h-10 bg-[#1A2F58] shadow-sm shadow-black/20 p-2 text-white font-inter">Logout Confirmation</div>
        <div class="p-3 font-inter text-[#1A2F58] font-medium">
            <span>Are you sure you want to log out?</span>
        </div>
        <div class="w-full h-full items-end justify-end flex flex-row gap-2 p-3">
                <button id="log-out" class="px-2 py-2 text-white border border-[#A00000] bg-[#FF7979] w-20 rounded">Yes</button>
                <button onclick="closeform()" class="px-2 py-2 text-white bg-[#1A2F58] transition-colors hover:bg-[#1F3A69] border border-[#1A2F58] w-20 rounded font-medium">No</button>
        </div>
       </div>
</template>

<!-- Overlay -->
<div id="notifOverlay" 
     class="fixed inset-0 bg-black/50 opacity-0 pointer-events-none transition-opacity duration-300 z-40"></div>

<!-- Sliding Notification Panel -->
<div id="notifPanel" 
     class="fixed top-0 right-0 h-full w-1/4 bg-white border border-[#1F3A69]/30 p-4 
            transform translate-x-full opacity-0 pointer-events-none transition-all duration-300 z-50 flex flex-col gap-2">
  
  <div class="flex justify-between items-center">
    <span class="text-lg text-[#1F3A69] font-semibold">Notifications</span>
    <button onclick="closeNotifications()" type="button" class="text-xl text-[#B22222] pr-1">
      <i class="fa-solid fa-x"></i>
    </button>
  </div>

  <div class="w-full h-3/4 max-h-[92%] rounded flex flex-col gap-3 overflow-y-auto">
    <div class="w-full h-24 border border-[#D43131] hover:bg-[#F3F3F3] transition-colors rounded p-3 flex flex-row gap-2">
      <div class="w-24 h-full bg-red-200 rounded"></div>
      <div class="flex flex-col w-full">
        <div class="flex justify-between items-center w-full">
          <span class="text-sm text-[#1A2F58] font-semibold">Notif Title</span>
          <div class="px-1 py-1 border-[#D43131]/20 text-sm rounded-md text-[#D43131] bg-[#FFE5E5] border-[0.4px]">Alert</div>
        </div>
        <span class="text-xs text-[#1A2F58]">Notif Description</span>
        <div class="text-[9px] text-[#94A9D3] font-normal">Date & Time</div>
      </div>
    </div>
  </div>
</div>

<!--
<div class="absolute flex flex-col gap-2 h-full w-1/4 top-0 right-0 bg-white border border-[#1F3A69]/30 p-4 font-inter">
  <div class="flex flex-row justify-between items-center">
    <span class="text-lg text-[#1F3A69] font-semibold">Notifications</span>
    <button onclick="closeform()" type="button" class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
  </div>
  <div class="w-full h-3/4 max-h-[92%] flex flex-col gap-3">
    <div class="w-full h-24 border border-[#D43131] hover:bg-[#F3F3F3] transition-colors rounded p-3 flex flex-row gap-2 font-inter">
      <div class="w-24 h-full bg-red-200 rounded"></div>
      <div class="flex flex-col w-full">
        <div class="flex flex-row justify-between items-center w-full">
          <span class="text-sm text-[#1A2F58] font-semibold">Notif Title</span>
          <div class="px-1 py-1 border-[#D43131]/20 text-sm rounded-md text-[#D43131] bg-[#FFE5E5] border-[0.4px] "> Alert</div>
        </div>
        <span class="text-xs text-[#1A2F58]">Notif Description<span>
        <div class="text-[9px] text-[#94A9D3] font-normal">Date & Time</div>
      </div>
    </div>
  </div>
</div>
-->


    <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-col ">
       
    <div class="p-3 flex font-inter flex-row justify-between items-center">
            <span class="text-[#1F3A69] font-semibold text-2xl">Dashboard</span>
            <button onclick="openNotifications()" class="px-2 h-auto py-2 bg-white hover-[#EDEEFF] transition-colors text-[#1F3A69]  border border-[#1F3A69]/30 rounded flex flex-row gap-1 items-center">
                <i class="fa-solid fa-bell"></i>
                <span></span>
            </button>
        </div>
    <div class="w-full h-auto p-3 flex flex-col gap-3">
          <div class=" w-full h-auto
        flex flex-row gap-3">
        <div class="border border-[#1A2F58]/20 w-1/2 h-44 bg-white rounded
        flex flex-col p-4 font-inter gap-1">
        <span class="text-[#1A2F58] font-semibold text-xs">Previous Shift</span>
        <div id="prevShiftDiv" class="w-full h-full flex flex-row divide-x divide-[#1A2F58]/20 text-2xl">
        <div class="w-1/2 h-full flex flex-col gap-7 p-2">
            <span class="font-bold text-sm text-[#1A2F58]">Sales Made</span>
            <span class="font-bold text-[#1A2F58] text-2xl prev-sale-value">₱ 00,000.00</span>
        </div>
        <div class="w-1/2 h-full flex flex-col gap-7 p-2">
            <span class="font-bold text-sm text-[#1A2F58]">Fuel Sold</span>
            <span class="font-bold text-[#1A2F58] text-2xl prev-fuel-sold">₱ 00,000.00</span>
        </div>
        <div class="w-1/2 h-full flex flex-col gap-7 p-2">
            <span class="font-bold text-sm text-[#1A2F58]">Transactions Made</span>
            <span class="font-bold text-[#1A2F58] text-2xl text-left prev-trans-made">0</span>
        </div>
    </div>
        </div>
        
         <div class="border border-[#1A2F58]/20 w-1/2 h-44 bg-white rounded
        flex flex-col p-4 font-inter gap-1 ">
        <span class="text-[#1A2F58] font-semibold text-xs">Todays Shift</span>
        <div  id="currentShiftDiv" class="hidden w-full h-full flex flex-row divide-x divide-[#1A2F58]/20">
            <div class="w-1/2 h-full flex flex-col gap-7 p-2">
                <span class="font-bold text-sm text-[#1A2F58]">Sales Made</span>
                <span class="font-bold text-[#1A2F58] text-2xl sale-value">₱ 00,000.00</span>
            </div>
             <div class="w-1/2 h-full flex flex-col gap-7 p-2">
                <span class="font-bold text-sm text-[#1A2F58]">Fuel Sold</span>
                <span class="font-bold text-[#1A2F58] text-2xl fuel-sold">₱ 00,000.00</span>
            </div>
             <div class="w-1/2 h-full flex flex-col gap-7 p-2">
                <span class="font-bold text-sm text-[#1A2F58]">Transactions Made</span>
                <span class="font-bold text-[#1A2F58] text-2xl text-left trans-made">0</span>
            </div>

        </div>
        <div id="currentPlaceholder" class="w-full h-full flex flex-col items-center justify-center gap-1"  >
                <img src="../assets/no-trans.png" class="w-32 h-20">
                <span class="text-xs font-medium text-[#1A2F58]">You haven’t start your shift yet.</span>
        </div>
        </div>
        </div>  
        <div class=" w-full h-auto flex flex-row gap-3">
            <div class="w-[24%] h-auto flex flex-col justify-between font-inter">
                <span class="text-[#1A2F58] py-2 text-2xl mb-1 font-semibold">Quick Actions</span>
                <div class="flex flex-col gap-2">
                    <button class="rounded flex transition-colors hover:bg-[#F3F3F3]  flex-row gap-2 w-full h-20 border border-[#1A2F58]/20 bg-white p-3">
                        <div class="w-24 h-full rounded-full bg-[#1A2F58] flex flex-row items-center justify-center">
                            <i class="fa-solid fa-cart-shopping text-white text-xl"></i>
                        </div>
                        <div id="trans-btn" class="font-inter text-[#1A2F58] text-xs flex flex-col gap-1 text-left">
                            <span class="font-semibold">Add new Transaction</span>
                            <span class="font-medium text-[#506EAA]">Create a new transaction and record items sold.  </span>
                        </div>
                    </button>       
                     <button id="start-btn" class="rounded flex flex-row gap-2 w-full h-20 transition-colors hover:bg-[#F3F3F3] border border-[#1A2F58]/20 bg-white p-3">
                        <div id="start-icon" class="w-24 h-full rounded-full bg-[#B6FFAF] flex flex-row items-center justify-center">
                            <i id="icon" class="fa-solid fa-business-time text-[#55A34E] text-xl"></i>
                        </div>
                        <div class="font-inter text-[#1A2F58] text-xs flex flex-col gap-1 text-left">
                            <span id="shift_t" class="font-semibold">Start your Shift</span>
                            <span id="shift_s" class="font-medium text-[#506EAA]">Start your shift to begin recording transactions.  </span>
                        </div>
                    </button>
                     <button class="rounded transition-colors hover:bg-[#F3F3F3]  flex flex-row gap-2 w-full h-20 border border-[#1A2F58]/20 bg-white p-3">
                        <div class="w-24 h-full rounded-full bg-[#FFD4AF] flex flex-row items-center justify-center">
                            <i class="fa-solid fa-ticket text-[#B88456] text-xl"></i>
                        </div>
                        <div class="font-inter text-[#1A2F58] text-xs flex flex-col gap-1 text-left">
                            <span class="font-semibold">View Discounts</span>
                            <span class="font-medium text-[#506EAA]">Check discounts that can be applied to transactions. </span>
                        </div>
                    </button>
                </div>

            </div>

            <div class="w-1/4 p-2 font-inter bg-white h-auto rounded border-[#1A2F58]/20 border flex flex-col gap-2">
                <span class="text-[#1A2F58] font-semibold text-sm ">Stocks Monitoring</span>
                <div id="lowStockContainer"  class= "w-full h-[265px] gap-2 flex flex-col  overflow-y-auto">
                    <div class="w-full h-20 rounded flex items-center p-2 flex-row gap-2 border border-[#FF9E9E] bg-[#FFF0F0] text-[#FF5050] font-inter">
                        <img src="../assets/low_fuel.png" class="w-14 h-14">
                        <div class="flex flex-col font-semibold">
                            <span class="text-sm">Fuel Name</span>
                            <span class="text-xs">Stocks Left :</span>
                            <span class="text-xs">Placeholder</span>
                        </div>
                    </div>
                    
                    <div class="hidden w-full h-full flex flex-col gap-1 justify-center items-center">
                        <img src="../assets/restock_check.png"> 
                        <span class="text-[#1A2F58] text-sm text-center">No products that needs to be restock yet.</span>
                    </div>
                 <div>
                </div>
               
            </div>
        </div>

            <div class="w-1/2 p-2 font-inter bg-white h-auto rounded border-[#1A2F58]/20 border flex flex-col gap-2">
                <span class="text-[#1A2F58] font-semibold text-sm ">Most Recent Transaction</span>
                <div id="transactionContainer" class=" text-[#1A2F58] text-sm border-t border-b w-full h-full max-h-full overflow-y-auto border-[#1A2F58] divide-y divide-[#1A2F58]/30 flex flex-col">
                </div>
                <div class="flex items-center justify-center">
                     <a href="Transaction.php" class="bg-[#1A2F58] px-3 text-white p-2 text-sm rounded transition-colors hover:bg-[#1F3A69] ">View More Transaction</a>
                </div>
               
            </div>
            
    </div>
      
    </main>


</body>
</html>
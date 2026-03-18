<?php
require_once '../config/auth.php';

// User page
if ($_SESSION['role'] !== 'Administrator') {
    header("Location: /Alpha Stage/Login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  
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
  <script src="../js/admin/general.js" defer type="module"></script>
  <script src="../js/admin/dashboard.js" defer></script>
  <!-- JS Chart-->  

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

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
      z-index: 60;
  "></div>

 <div id="wrapper" class="fixed inset-0 flex items-center justify-center z-50
            opacity-0 pointer-events-none transition-opacity duration-500">

<div id="overlay" class="fixed inset-0 bg-black/50 opacity-0 pointer-events-none z-40 transition-opacity duration-300"></div>

 <div id="modalContent"
       class="w-full h-full transform 
       translate-y-5 opacity-0 transition-all duration-300 z-40 flex justify-center items-center" >

  </div>
 
</div>   

<template id="changeprice">
          <div class="bg-white w-[30%] h-1/2 flex flex-col font-inter gap-2 rounded">
          <div class="bg-[#1A2F58] w-full h-10 p-2 text-white font-semibold text-sm flex flex-row justify-between items-center">
          <span>Change Fuel Price</span>
          <button onclick="closeform()" class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
          </div>
          <div class="flex flex-row justify-between items-center p-2">
            <span class="text-[#1A2F58] text-lg font-semibold">Fuel Types</span>
            <span class="text-[#1A2F58] text-xs font-semibold">Current Price per L</span>
            <span class="text-[#1A2F58] text-xs font-semibold">Update Price per L</span>
          </div>
          <div class="divide-y divide-[#1A2F58] flex flex-col p-2">
            <div class="flex flex-row justify-between items-center py-2">
              <span class="text-[#1A2F58] text-lg font-semibold w-20">Diesel</span>
              <span id="diesel-price" class="text-[#1A2F58] text-lg font-semibold">00.00</span>
              <input id="diesel-input" type="number" step="0.01" min="35" max="150" class="bg-[#F5F5F5] border border-[#1A2F58]/30 w-1/4 rounded-sm h-full">
            </div>
            <div class="flex flex-row justify-between items-center py-2">
              <span class="text-[#1A2F58] text-lg font-semibold w-20">Premium</span>
              <span id="premium-price"  class="text-[#1A2F58] text-lg font-semibold">00.00</span>
              <input id="premium-input" type="number" step="0.01" min="35" max="150" class="bg-[#F5F5F5] border border-[#1A2F58]/30 w-1/4 rounded-sm h-full">
            </div>
            <div class="flex flex-row justify-between items-center py-2">
              <span class="text-[#1A2F58] text-lg font-semibold w-20">Unleaded</span>
              <span id="unleaded-price" class="text-[#1A2F58] text-lg font-semibold">00.00</span>
              <input id="unleaded-input" type="number" step="0.01" min="35" max="150" class="bg-[#F5F5F5] border border-[#1A2F58]/30 w-1/4 rounded-sm h-full">
            </div> 
            
          </div>
            <div class=" flex justify-end items-end p-2 w-full h-full">
              <button id="goconfirm" class="bg-[#1A2F58] w-24 py-2 rounded-sm text-white">Save</button>
            </div>
        </div>
</template>

<template id="confirmchg">
          <div class="bg-white w-1/4 h-[30%] flex flex-col rounded font-inter gap-2">
          <div class="bg-[#1A2F58] w-full h-10 text-white p-2">Confirm Confirmation</div>
          <div class="p-2 text-[#1A2F58] text-base">Are you Sure you want to change the price for the selected fuel?</div>
          <div class="w-full flex flex-row justify-between items-center h-full p-2">
            <button onclick="closeform()" class="border border-[#1A2F58] rounded w-20 h-10">No</button>
            <button id="savechange" class="border border-[#1A2F58] bg-[#1A2F58] rounded w-20 h-10 text-white">Yes</button>
          </div>
        </div>
</template>

<template id="addann">
          <div class="bg-white w-1/4 h-[70%] flex flex-col rounded"> 
          <div class="w-full h-10 p-2 bg-[#1A2F58] flex justify-between text-white font-inter">
           <span>Make an Announcement</span>
            <button onclick="closeform()" class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
          </div>
          <div class="flex flex-row gap-3 w-full p-4">
            <div id="announcement-icon" class="w-1/2 rounded h-24 bg-[#FAFAFF] border border-[#1A2F58] flex items-center justify-center">
                  <img id="announcement-img" src="../assets/general.png" class="w-14 h-14">
              </div>
              <div class="flex flex-col justify-between w-full font-inter">
              <select id="announcement-type" class="text-[#1A2F58] w-full py-1 bg-[#F5F5F5] border border-[#1A2F58]/30 rounded outline-[#1A2F58]">
                  <option value="general" selected>General</option>
                  <option value="alert">Alert</option>
                  <option value="update">Update</option>
              </select>
              <input id="announcement-title" placeholder="Announcement Title" type="text" class="p-1 w-full py-1 bg-[#F5F5F5] border border-[#1A2F58]/30 rounded outline-[#1A2F58]">
            </div>
            

          </div>
          <div class="w-full h-full p-4 gap-5 flex flex-col font-inter">
          <div class="w-full h-full">
            <textarea id="announcement-body"  maxlength="200" required placeholder="Announcement Body goes here..." class="text-sm border border-[#1A2F58]/30 rounded bg-[#F5F5F5] resize-none w-full h-full p-2"></textarea>
          </div>
          <button id="create-announcement" class="bg-[#1A2F58] rounded py-2 w-full text-white font-medium hover:bg-[#264177] transition-colors">Create Announcement</button>

        </div>
        </div>
</template>

<body class="overflow-y-hidden overflow-x-hidden bg-gradient-to-r from-[#DFE5F8]/50 to-[#C9D3F2]/50">

  <div id="modalContent"
       class="hidden w-full h-full transform 
       translate-y-5  transition-all duration-300 z-40 flex justify-center items-center" >

  </div>
 

    <nav class="w-64 bg-[#173161] h-screen fixed top-0 left-0 shadow-[5px_0_10px_3px_rgba(0,0,0,0.25)]
    flex flex-col items-center justify-between">
        <div>
        <img src="../assets/GSMS logo.png" class="p-5 mb-[40px]">
        <div class="relative bg-[#6284C6]/60 w-full p-6 h-14 flex flex-row items-center
        shadow-[inset_0_4px_4px_rgba(27,45,80,0.5)] gap-4">
          <div class="absolute bg-white w-1 h-full top-0 left-0"></div>
          <img src="../assets/Admin/nav/Windows.png" class="h-7 w-7">
          <span class="font-poppins text-white text-base font-medium">Dashboard</span>
        </div>
         <a href="Transaction.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Transaction.png" class="h-9 w-8">
        <span class="font-medium text-base font-poppins">Transactions</span>
        </a>
        <a href="inventory.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Trolley.png" class="h-9 w-8">
        <span class="font-medium text-base font-poppins">Inventory</span>
        </a>
        <a href="Transaction.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Adjust.png" class="h-9 w-8">
        <span class="font-medium text-base font-poppins">Announcement</span>
        </a>
        <a href="user management.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Users.png" class="h-8 w-8">
        <span class="font-medium text-base font-poppins">User Control</span>
        </a>
        </div>
        <a href="Transaction.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Settings.png" class="h-7 w-8">
        <span class="font-medium text-base font-poppins">Settings</span>
        </a>

    </nav>  

         <header class="ml-64 w-[82%] h-16 bg-[#E5EAFA] shadow-[0_5px_5px_#B7C2DF] pl-5 pr-5 flex flex-row justify-between items-center">
          <p class=" p-5 font-poppins text-xl text-[#213B62] font-semibold flex flex-row w-full items-center justify-between mr-1">
            <span>Dashboard</span>
            <span class="text-sm font-normal">Welcome Daryl, here is an overview of today’s performance!</span>
          </p>
          <div class="w-44 h-11 mr-6 relative items-center justify-center rounded-full border border-black/50 bg-[#EAEEFA] flex flex-row gap-2">
              <button class="flex items-center justify-center"><i class="fa-solid fa-bell text-lg p-1 text-[#1B2D50]"></i></button>
              <div class="border-l w-1 h-[100%] border-[#1A2F58]"></div>
              <button class="flex flex-row gap-2 items-center" id="profilebtn">
              <span class="font-inter text-base text-[#1B2D50]">Admin</span>
              <img src="../assets/Profile.jpg" class="w-7 h-7 rounded-full">
              </button>
              <div class="flex absolute top-12 right-0 w-64 p-3 gap-2 flex-col bg-white shadow-[0_4px_4px_rgba(0,0,0,0.2)] rounded-md h-[165px] border border-[#1A2F58]/20
                  opacity-0 scale-95 -translate-y-2 pointer-events-none transition duration-150 ease-out" id="dropdown">
                  <div class="flex flex-row gap-2">
                    <img src="../assets/Profile.jpg" class="w-24 h-24 rounded-full">
                    <div class="flex flex-col pt-1 font-inter text-[#213B62]">
                      <span class="text-lg font-semibold">Jonathan Joe</span>
                      <div class="w-full border-t border-[#1A2F58]"></div>
                      <span class="text-sm font-normal">Administrator</span>
                    </div>
                  </div>
                  <div class="w-full border-t border-[#1A2F58]"></div>
                  <div class="w-full flex flex-row justify-between items-center">
                    <a class="text-[#1A2F58] font-inter font-normal text-sm" href="user management.php"><u>View Profile</u></a>
                    <a href="../config/logout.php" class="flex flex-row text-sm bg-[#1A2F58] items-center py-1 px-3 gap-2 rounded-md font-inter text-white"><img src="../assets/Admin/Log in.png" class="w-5 h-5">Log Out</a>
                  </div>
              </div>
          </div>
         </header>   

        <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-col ">
           <p class="font-poppins text-[#213B62] text-[20px] font-semibold mb-3">Quick Overview</p>
            <div class="flex flex-row justify-between mb-4">
                <div class="bg-gradient-to-l from-[#1d448b] to-[#133472] w-[23%] h-24
                rounded-xl border border-[#3B4963] shadow-[0px_5px_10px_rgba(27,45,80,0.4)] p-3">
                  <p class="font-inter font-bold text-white">
                    <span class="text-[12px] font-semibold">All-time Revenue</span><br>
                    <span id="allTimeRevenue" class="text-lg">₱ Placeholder</span>
                  </p>
                </div>
                <div class="bg-gradient-to-l from-[#1d448b] to-[#133472] w-[23%] h-24
                rounded-xl border border-[#3B4963] shadow-[0px_5px_10px_rgba(27,45,80,0.4)] p-3">
                <p class="font-inter font-bold text-white">
                <span class="text-[12px] font-semibold">Todays Revenue</span><br>
                <span id="dailyRevenue" class="text-lg">₱ Placeholder</span>
              </p></div>
                <div class="bg-gradient-to-l from-[#1d448b] to-[#133472] w-[23%] h-24
                rounded-xl border border-[#3B4963] shadow-[0px_5px_10px_rgba(27,45,80,0.4)]
                flex flex-col p-3 gap-2">
                <div id="fuel-Diesel" class="font-inter text-white text-[12px] flex flex-row gap-5 items-center">
                  <span class="w-16">Diesel</span>
                  <div class="w-1/2 h-1 bg-white rounded-full">
                    <div class="bar-fill h-full bg-green-500 transition-all duration-500"></div> 
                  </div>
                  <span class="percent-label">50%</span>
                </div>
                <div id="fuel-Premium" class="font-inter text-white text-[12px] flex flex-row gap-5 items-center">
                  <span class="w-16">Premium</span>
                  <div class="w-1/2 h-1 bg-white rounded-full">
                    <div class="bar-fill h-full bg-green-500 transition-all duration-500"></div> 
                  </div>
                  <span class="percent-label">18%</span>
                </div>
                <div id="fuel-Unleaded" class="font-inter text-white text-[12px] flex flex-row gap-5 items-center">
                  <span class="w-16">Unleaded</span>
                  <div class="w-1/2 h-1 bg-white rounded-full">
                    <div class="bar-fill h-full bg-green-500 transition-all duration-500"></div> 
                  </div>
                  <span class="percent-label">10%</span>
                </div>
                </div>
                <div id="low-stock-card" class="bg-[#D4FFE1] w-[23%] h-24 p-3 items-center justify-between
                    rounded-xl border border-[#38AC5B] shadow-[0px_5px_10px_rgba(27,45,80,0.4)] flex flex-row">
                  <p class="flex flex-col h-full gap-1">
                    <span id="low-title" class="font-poppins text-[#5BA070] text-base font-semibold">Low Stock Alerts</span>
                    <span id="low-stock-text" class="font-poppins text-[#53A36C] text-[13px] font-semibold">Placeholder</span>
                  </p>
                  <img  id="low-stock-img" src="../assets/Check.png" class="w-16 h-16">
                </div>
            </div>
            <p class="text-[#213B62] font-poppins font-semibold text-[20px] mb-3">Quick Actions</p>
            <div class="flex flex-row justify-between mb-6  ">
              <button id="chn-btn" class="bg-[#F6F6FB] w-80 h-24 rounded-xl hover:bg-[#EFEFEF] transition-colors flex flex-row p-3 gap-4 shadow-[0px_4px_4px_rgba(0,0,0,0.25)]">
                  <img src="../assets/Admin/fuel_price.png" class="w-16 h-16">  
                  <p class="flex flex-col text-left">
                  <span class="text-[#213355] font-poppins text-base font-semibold">Change Fuel Price</span>
                  <span class="text-[#213B62] font-poppins text-[12px] font-medium">
                    Change the price of the fuel products.</span>
                  </p>
              </button>
              <a href="user management.php" class="bg-[#F6F6FB] w-80 h-24 rounded-xl  hover:bg-[#EFEFEF] transition-colors flex flex-row p-3 gap-4 shadow-[0px_4px_4px_rgba(0,0,0,0.25)]">
                  <img src="../assets/Admin/userm.png" class="w-16 h-16">
                  <p class="flex flex-col text-left">
                  <span class="text-[#213355] font-poppins text-base font-semibold">Manage Employees</span>
                  <span class="text-[#213B62] font-poppins text-[12px] font-medium">
                    View and manage employee access and shifts.</span>
                  </p>
              </a>
              <button id="ann-btn" class="bg-[#F6F6FB] w-80 h-24 rounded-xl flex flex-row p-3 gap-4 hover:bg-[#EFEFEF] transition-colors shadow-[0px_4px_4px_rgba(0,0,0,0.25)]">
                  <img src="../assets/Admin/megaphone.png" class="w-16 h-16">
                  <p class="flex flex-col text-left">
                  <span class="text-[#213355] font-poppins text-base font-semibold">Make an Announcement</span>
                  <span class="text-[#213B62] font-poppins text-[12px] font-medium">
                    Notify cashiers about important information.</span>
                  </p>
              </button>
            </div>
            <div class="flex flex-row gap-5">
                <div class="shadow-[0px_4px_4px_rgba(0,0,0,0.25)] w-[50%] border border-[#314C82]/20 h-[175px] bg-white rounded
                p-3 flex flex-col gap-3">
                <div class="flex flex-row justify-between items-center">
                    <p class="font-poppins text-[#213B62] text-base font-semibold">Product Sales Overview</p>
                    <select id="sales-filter" class="px-3 py-1 rounded-lg bg-[#314C82] border border-[#314C8  2]/20 text-white focus:outline-[#82A6EF] font-inter font-semibold text-sm w-max">
                      <option value="Today">Today</option>
                      <option value="Yesterday">Yesterday</option>
                      <option value="Last Week">Last Week</option>
                    </select>

                </div>
                <div class=" overflow-x-auto rounded border border-[#314C82]/20">
                    <table class="min-w-full divide-y divide-[#5C749F] divide-opacity-30">
                    <thead class=" sticky top-0 bg-[#F9F8F9] border border-[#314C82]/20 text-[#1A2F58] text-sm font-inter font-semibold tracking-wide shadow-sm ">
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Product</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Units Sold</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Revenue (₱)</th>
                    </thead>
                    <tbody id="sales-tbody" class="text-center font-inter text-[13px] text-[#1A2F58] font-normal py-1">
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                        <td class="px-1 py-1">Diesel</td>
                        <td class="px-1 py-1">124.21 L</td>
                        <td class="px-1 py-1">₱12,301</td>
                      </tr>
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                        <td class="px-1 py-1">Unleaded</td>
                        <td class="px-1 py-1">150.01 L</td>
                        <td class="px-1 py-1">₱16,130</td>
                      </tr>
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                        <td class="px-1 py-1">Premium</td>
                        <td class="px-1 py-1">95.13</td>
                        <td class="px-1 py-1">₱9,130</td>
                      </tr>
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                        <td class="px-1 py-1">Automotive Products</td>
                        <td class="px-1 py-1">13</td>
                        <td class="px-1 py-1">₱1,410</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </div>
                <div class="shadow-[0px_4px_4px_rgba(0,0,0,0.25)] w-[50%] border border-[#314C82]/20 h-[175px] bg-white rounded
                p-3 flex flex-col">
                <div class="flex flex-row justify-between items-center mb-3">
                    <p class="font-poppins text-[#213B62] text-base font-semibold">Recent Transactions</p>
                    <a href="transaction.php" class="px-5 py-1 rounded-lg bg-[#1F3A69] font-inter text-[#F8F8FF] text-sm ">See More</a>

                </div> 
                <div class=" overflow-x-auto rounded border border-[#314C82]/20">
                    <table class="min-w-full divide-y divide-[#5C749F] divide-opacity-30">
                    <thead class=" sticky top-0 bg-[#F9F8F9] border border-[#314C82]/20 text-[#1A2F58] text-sm font-inter font-semibold tracking-wide shadow-sm ">
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">ID</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Date & Time</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Cashier</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Total</th>
                    </thead>
                    <tbody id="transactions-tbody" class="text-center font-inter text-[13px] text-[#1A2F58] font-normal py-1">
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                        <td class="px-1 py-1">TRANSC-123</td>
                        <td class="px-1 py-1">02/28/26 11:21 A.M</td>
                        <td class="px-1 py-1">Jonathan Joe</td>
                        <td class="px-1 py-1">₱12,301</td>
                      </tr>
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                        <td class="px-1 py-1">TRANSC-123</td>
                        <td class="px-1 py-1">02/28/26 11:21 A.M</td>
                        <td class="px-1 py-1">Jonathan Joe</td>
                        <td class="px-1 py-1">₱12,301</td>
                      </tr>
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                       <td class="px-1 py-1">TRANSC-123</td>
                        <td class="px-1 py-1">02/28/26 11:21 A.M</td>
                        <td class="px-1 py-1">Jonathan Joe</td>
                        <td class="px-1 py-1">₱12,301</td>
                      </tr>
                      <tr class="bg-[#F5F5F6] even:bg-[#E3E7F4]">
                       <td class="px-1 py-1">TRANSC-123</td>
                        <td class="px-1 py-1">02/28/26 11:21 A.M</td>
                        <td class="px-1 py-1">Jonathan Joe</td>
                        <td class="px-1 py-1">₱12,301</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </div>  
            </div>
        </main>
</body>
</html>
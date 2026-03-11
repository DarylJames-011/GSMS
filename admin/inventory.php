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
  <!-- JS Chart-->  
    <script src="../js/admin/general.js" defer type="module"></script>
    <script src="../js/admin/inventory.js" defer  type="module"></script>
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

<body class="overflow-x-hidden bg-gradient-to-r from-[#DFE5F8]/50 to-[#C9D3F2]/50">

    

<div id="wrapper" class="fixed inset-0 flex items-center justify-center z-50
            opacity-0 pointer-events-none transition-opacity duration-500">

<div id="overlay" class="fixed inset-0 bg-black/50 opacity-0 pointer-events-none z-40 transition-opacity duration-300"></div>

 <div id="modalContent"
       class=" transform 
       translate-y-5 opacity-0 transition-all duration-300 z-50 flex justify-center items-center" >

  </div>
 
</div>

<template id="fuelform"> <!-- Fuel Form -->
  <div id="form1" class="
 bg-white flex flex-col rounded shadow-lg w-[80%] h-full border border-[#1A2F58]/50
 ">
  <div class="flex flex-row justify-between items-center p-3">
    <span class="text-[#1A2F58] font-inter text-base font-medium">Recieve a Fuel Order</span>
    <button class="text-xl text-[#B22222]"><i class="fa-solid fa-x" id="closeform"></i></button>
  </div>
  <div class="border-t border-[#1A2F58]/20"></div>
  <form class="flex flex-col p-3 gap-3" id="fuelForm">
    <div class="flex flex-row gap-2">
    <div class="flex flex-col gap-1 w-[52%]">
      <label for="invoice" class="font-inter text-sm text-[#1A2F58] font-medium">Invoice Number</label>
      <input  autocomplete="off" type="text" name="invoice" id="invoice" class="bg-[#F5F5F5] p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm" required>
      <label for="supplier" class="font-inter text-sm text-[#1A2F58] font-medium">Supplier Name</label>
      <input type="text" name="supplier" id="supplier" class="bg-[#F5F5F5] p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm" required>
      <div class="flex flex-row gap-2 ">
      <div>
      <label for="date_o" class="font-inter text-sm text-[#1A2F58] font-medium">Date Ordered</label>
      <input type="date" name="date_o" id="date_o" class="bg-[#F5F5F5] p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm" required>
      </div>
      <div>
      <label for="date_r" class="font-inter text-sm text-[#1A2F58] font-medium">Date Received</label>
      <input readonly type="date" name="date_r" id="date_r" class="bg-[#F5F5F5] p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm" required>
      </div>
      </div>
      <div class="flex flex-row gap-2 justify-between">
      <div>
      <label for="qty" class="font-inter text-sm text-[#1A2F58] font-medium">Quantity</label>
     <div class="flex items-center w-[70%]">
  <input type="number" min="500" max="20000" name="qty" id="qty"
         class="bg-gray-50 w-full p-1 border border-[#1A2F58]/30 rounded-l-sm" required>
  <span class="bg-white p-1 border border-[#1A2F58]/30 w-16 text-center font-semibold rounded-r-sm">L</span>
</div></div>
      <div>
      <label for="fuel_typ" class="font-inter text-sm text-[#1A2F58] font-medium">Fuel Type</label>
      <select id="fuel_typ" name="fuel_typ" required class="bg-[#F5F5F5] p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm">
        <option>Diesel</option>
        <option>Unleaded</option>
        <option>Premium</option>
      </select>
      </div>
    </div>
     
    </div>
     <div class="flex flex-col gap-1 w-full">
      <span class="font-inter text-sm text-[#1A2F58] font-medium">Selected Fuel</span>
      <div class="w-full bg-[#1A2F58] h-[40%] rounded-sm flex flex-col p-3 font-inter text-white gap-1">
        <div class="flex flex-row justify-between">
          <span class="font-medium text-sm" id="selectedtype">Unleaded</span>  
        </div>
        <span class="text-2xl font-semibold" id="percentage">Placeholder%</span>
        <div class="flex flex-row justify-between items-center">
            <div class="flex flex-row justify-between items-center">
              <span id="fuelLiters" class="font-normal text-xs">0L</span>
              <span class="font-normal text-xs"> / 20,000 L </span>
           </div>
            <div class="flex flex-row items-center gap-2"> 
              <div class="w-2 h-2 bg-[#48BA6B] rounded-full" id="statusdot"></div>
              <span class="text-[10px] font-medium" id="statustext">Sufficient Stock</span>
            </div>

        </div>
      </div>
      <label for="note" class="font-inter text-sm text-[#1A2F58] font-medium">Notes</label>
      <textarea name="note" id="note" maxlength="90" class="bg-[#F5F5F5] p-1 h-[40%] resize-none focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm"></textarea>
     </div>
     </div>
    <button type="submit" class="bg-[#1A2F58] w-full h-10 font-inter text-white font-semibold text-sm rounded-sm hover:bg-[#28447D] transition-all duration-200" id="fuelsave">Save</button>
  </form>
</div>
</template>

<template id="vieworder"> <!-- View Order -->
<div class="bg-white flex flex-col rounded shadow-lg w-full
border border-[#1A2F58]/50 h-full p-2">
  <div class="flex flex-row justify-between items-center p-1 mb-1">
  <div class="flex flex-col">
    <span class="font-inter text-sm text-[#1A2F58] font-semibold">Order Information</span>
    <div class="flex flex-row gap-1 text-[#1A2F58] text-opacity-60 font-inter font-light text-[13px]">
    <i>
    <span>Date Created :</span>
    <span id="date_created">Placeholder</span>
    </i>
    </div>
  </div>
  <button class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
  </div>
  <div class="w-full border-t border-[#1A2F58]/50 mb-2"></div>
  <div class="flex flex-row gap-3 p-1 justify-between items-center">
      <span class="text-[#1A2F58] font-inter font-semibold text-sm">Invoice Number</span>
      <div id="invoice_num" class="p-1 border border-[#1A2F58]/30 bg-[#F5F5F5] rounded-[3px] w-48 font-inter text-sm font-normal text-[#1A2F58]">Placeholder</div>
  </div>
  <div class="flex flex-row gap-3 p-1 justify-between items-center">
      <span class="text-[#1A2F58] font-inter font-semibold text-sm">Supplier Name</span>
      <div id="supplier_n" class="p-1 border border-[#1A2F58]/30 bg-[#F5F5F5] rounded-[3px] w-48 font-inter text-sm font-normal text-[#1A2F58]">Placeholder</div>
  </div>
  <div class="flex flex-row gap-3 p-1 justify-between items-center">
      <span class="text-[#1A2F58] font-inter font-semibold text-sm">Fuel Type</span>
      <div id="fuel_n" class="p-1 border border-[#1A2F58]/30 bg-[#F5F5F5] rounded-[3px] w-48 font-inter text-sm font-normal text-[#1A2F58]">Placeholder</div>
  </div>
  <div class="flex flex-row gap-3 p-1 justify-between items-center">
      <span class="text-[#1A2F58] font-inter font-semibold text-sm">Quantity</span>
      <div id="ltrs" class="p-1 border border-[#1A2F58]/30 bg-[#F5F5F5] rounded-[3px] w-48 font-inter text-sm font-normal text-[#1A2F58]">Placeholder</div>
  </div>
   <div class="flex flex-row gap-7 p-1">
      <div class="flex flex-col gap-1">
         <span class="text-[#1A2F58] font-inter font-semibold text-sm">Date Ordered</span>
      <div id="date_ord" class="p-1 border border-[#1A2F58]/30 bg-[#F5F5F5] rounded-[3px] w-32 font-inter text-sm font-normal text-[#1A2F58]">Placeholder</div>
      </div>
       <div class="flex flex-col gap-1">
         <span class="text-[#1A2F58] font-inter font-semibold text-sm">Date Received</span>
      <div id="date_rec" class="p-1 border border-[#1A2F58]/30 bg-[#F5F5F5] rounded-[3px] w-40 font-inter text-sm font-normal text-[#1A2F58]">Placeholder</div>
      </div>
     
  </div>
    <div class="flex flex-col gap-2 p-1 w-full">
        <span class="text-[#1A2F58] font-inter font-semibold text-sm">Note</span>
        <textarea readonly id="not" class="w-full resize-none h-24 bg-[#F5F5F5] break-words
 border border-[#1A2F58]/30 font-inter font-normal text-[#1A2F58] p-1 text-sm rounded-[3px]">
          Placeholder</textarea>
    </div>
</div>
</template>

<template id="addProduct"> <!-- Add Product -->
<div class="flex flex-col bg-white z-50 border border-[#1A2F58]/50 shadow-lg rounded h-80">
  <div class="flex justify-between items-center py-2 pl-3">
  <span class="text-[#1A2F58] font-inter font-semibold text-base text-center">Add a new Product</span>
  <button class="text-xl text-[#B22222] pr-3 close-btn"><i class="fa-solid fa-x"></i></button>
  </div>
  <div class="w-full border-t border-[#1A2F58]/50 mb-1"></div>
    <form class="flex flex-col p-3 gap-3" enctype="multipart/form-data" id="productForm">
      <div class="flex flex-row gap-3">
         <div class="flex flex-col gap-3">
          <span class="font-inter text-[#1A2F58] font-semibold text-sm">Image Preview</span>
          <div id="imgbox" class="flex justify-center items-center gap-1 flex-col border border-[#1A2F58]/20 rounded bg-[#F5F5F5] w-full h-32">
              <img src="../assets/Admin/Image_sm.png">
              <span class="text-[10px] font-inter text-[#1A2F58]/70 ">Image goes here</span>
              

          </div>
          <label class=" bg-[#DEEAFF] border border-[#1A2F58] text-[#1A2F58] font-inter text-[13px] font-semibold px-4 py-1 rounded cursor-pointer">
          Upload Image
          <input required type="file" name="prod_img"   id="imginput" accept=".jpg, .jpeg, .png, image/jpeg, image/png" class="hidden" />
        </label>
        </div>
        <div class="flex flex-col w-[70%]">
          <div class="flex flex-row gap-7">
          <div class="flex flex-col gap-3">
          <label for="prod_name" class="font-inter text-[#1A2F58] font-semibold text-sm">Product Name</label>
          <input type="text" name="prod_name" id="prod_name" autocomplete="off" id="prod_n" class=" text-[#1A2F58] bg-[#F5F5F5] w-40 p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm" required>
          </div>
          <div class="flex flex-col gap-3">
          <label for="price_pu" class="font-inter text-[#1A2F58] font-semibold text-sm">Price per unit</label>
          <div class="flex flex-row">
            <input type="number" max="2500" id="price_pu" name="price_pu" min="100" class=" text-[#1A2F58] bg-[#F5F5F5] p-1 w-[90px] focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm" required>
            <div class="flex items-center  justify-center w-8 text-center text-[#1A2F58] h-full bg-[#F5F5F5] border border-[#1A2F58]/30 font-inter font-semibold"> ₱</div>
          </div>
          
          </div>
        </div>
        <label for="desc" class="font-inter text-[#1A2F58] font-semibold text-sm py-1"> Description </label>
          <textarea name="desc" id="desc" class="bg-[#F5F5F5] border
           border-[#1A2F58]/30 text-[#1A2F58] font-inter text-sm font-normal resize-none break-words h-full p-1" maxlength="150" required></textarea>
        </div>
      </div>
          <button class="bg-[#1A2F58] text-sm font-inter text-white py-2 rounded" type="submit">Save</button>
       
    </form>

</div>
</template>

<template id="viewProduct"> <!-- View Product-->
<div class="flex flex-col w-80 bg-white z-50 border border-[#1A2F58]/50 shadow-lg rounded h-auto rounded-tl-sm rounded-tr-sm"> 
  <div class="flex flex-row justify-between p-2 bg-[#173160] items-center rounded-tl-sm rounded-tr-sm">
    <span class="text-white font-inter text-sm font-semibold">Product Information</span>
    <button class="text-xl text-white pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
  </div>
  <div class="flex flex-row pl-2 pr-2 pt-2 gap-3">
     <div class="w-[45%] h-32 shadow-md shadow-black/20 rounded-sm">
      <img id="prod_img" src="" alt="Product Image" class="w-full h-full">
      </div>
     <div class="flex flex-col gap-2">
      <span class="text-[#173160] font-inter font-bold" id="prod_n">Product Name</span>
      <div class="flex flex-row text-[13px] gap-1 text-[#173160] font-inter ">Unit Price: <span id="price">Placeholder</span></div>
      <div class="flex flex-row text-[13px] gap-1 text-[#173160] font-inter items-center">Items in Stock: <span id="stock_n">13</span> <div class=" bg-[#FB1717] px-2 text-white font-inter text-[11px] font-normal rounded-[5px]" id="low_ind">Low</div></div>
       <div class="flex flex-row text-[13px] gap-1 text-[#173160] font-inter ">Status: <span id="status">Available</span></div>
     </div>
  </div>
  <div class="p-2 flex gap-2 flex-col">
  <span class="text-[#173160] font-inter font-bold text-sm">Description</span>
  <textarea id="text" class="p-2 w-full h-32 border-[#1A2F58]/30 border bg-[#F5F5F5] resize-none text-[#173160] font-inter font-normal text-sm" readonly>This thing is a placeholder</textarea>
  </div>
  <div class="flex flex-row gap-2 p-2 mb-2">
  <button class="bg-[#1A2F58] w-1/2 p-2 text-center relative rounded-md text-white font-inter font-semibold text-xs hover:bg-[#314C82] transition-colors duration-200">Edit Product 
    <img src="../assets/Admin/Editwhite.png" class="absolute top-[6px] right-1"></button>
  <button class="text-[#B22222] border border-[#B22222] hover:bg-[#FF9E9E] transition-colors duration-200 w-1/2 p-2 text-center relative rounded-md bg-[#FFDDDD] font-inter font-semibold text-xs">
   Set to unavailable
  </button>
  </div>
</div>
</template>

<template>  
</template>

<div class="fixed w-screen h-screen flex flex-row items-center justify-center z-50 hidden">
    <div class="bg-white h-[65%] w-[50%] border border-[#1A2F58]/20 shadow-lg rounded
    flex flex-col p-2 text-[#1A2F58] font-inter">
    <div class="flex flex-row justify-between items-center py-1 mb-1">
    <span class="font-inter text-base font-semibold">Receive an Order</span>
    <button class="text-xl text-[#B22222] pr-1 close-btn"><i class="fa-solid fa-x"></i></button>
  </div>
  <div class="w-full border-t border-[#1A2F58]/50 mb-2"></div>
  <form class="flex flex-col h-full gap-2">
    <div class="flex flex-row gap-3 h-full">
          <div class="w-3/4  h-full flex flex-col">
    <div class="flex flex-row gap-3 pt-1">
      <div class="flex flex-col">
        <label>Invoice Number</label>
        <input autocomplete="off" type="text" name="invoice" id="invoice" class="bg-[#F5F5F5] w-full p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-md" required>
      </div>
      <div class="flex flex-col">
        <label>Supplier Name</label>
        <input autocomplete="off" type="text" name="invoice" id="invoice" class="bg-[#F5F5F5] w-full p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-md" required>
      </div>
    </div>
    <div class="flex flex-row gap-5 mb-2">
      <div class="flex flex-col">
        <label>Date Ordered</label>
        <input autocomplete="off" type="date" name="invoice" id="invoice" class="bg-[#F5F5F5] w-full p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-md" required>
      </div>
      <div class="flex flex-col">
        <label>Date Received</label>
        <input autocomplete="off" type="date" name="invoice" id="invoice" class="bg-[#F5F5F5] w-full p-1 focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-md" required>
      </div>
    </div>
    <span>Description</span>
    <textarea name="note" id="note" maxlength="90" class="bg-[#F5F5F5] p-1 h-[50%] resize-none focus:outline-[#1A2F58] border border-[#1A2F58]/30 rounded-sm"></textarea>
  </div>
    <div class="w-[70%] h-full gap-3 flex flex-col">
    <div class="flex flex-row justify-between items-center">
      <span>Products Ordered</span>
      <button type="button" class="p-1 bg-[#1A2F58] rounded-sm text-white">Add Products</button>
      </div>
      <div class="border border-[#1A2F58]/30 bg-[#F5F5F5] h-full  "></div>
    </div>
    </div>
    <div class="flex flex-row w-full justify-between items-center">
      <span class="text-xs">Please Double Check the information before saving.</span>
      <button class="py-2 text-white rounded bg-[#1A2F58] w-[47%]">Save</button>
    </div>

    </div>

   
  </form>
  </div>
</div>



<nav class="w-64 bg-[#173161] h-screen fixed top-0 left-0 shadow-[5px_0_10px_3px_rgba(0,0,0,0.25)]
    flex flex-col items-center justify-between">
        <div>
        <img src="../assets/GSMS logo.png" class="p-5 mb-[40px]">
         <a href="dashboard.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Windows.png" class="h-7 w-7 ">
        <span class="font-medium text-base font-poppins">Dashboard</span>
        </a>   
        <a href="transaction.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Transaction.png" class="h-9 w-8">
        <span class="font-medium text-base font-poppins">Transactions</span>
        </a>
        <div class="relative bg-[#6284C6]/60 w-full p-6 h-14 flex flex-row items-center
        shadow-[inset_0_4px_4px_rgba(27,45,80,0.5)] gap-4">
          <div class="absolute bg-white w-1 h-full top-0 left-0"></div>
          <img src="../assets/Admin/nav/Trolley.png" class="h-9 w-8">
          <span class="font-poppins text-white text-base font-medium">Inventory</span>
        </div> 
        <a href="user management.html" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Graph.png" class="h-7 w-8">
        <span class="font-medium text-base font-poppins">Reports</span>
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
            <span>Inventory Management</span>

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

        <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-col gap-4">
          <div class="w-full h-48 flex flex-row gap-4">
            <div class="w-1/2 h-full bg-white flex flex-col p-2 border border-[#1A2F58]/20 rounded-[5px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
              <div class="p-1 flex flex-row justify-between">
                <p class="text-[#1A2F58] font-inter font-semibold">
                  <span>Fuel Monitoring</span>
                </p>
                <button id="fuelopen" class="bg-[#173161] font-inter font-medium rounded-md text-white w-24 p-1 text-sm">
                  Order Fuel
                </button>
              </div>
              <div class="flex flex-row gap-4 h-full p-1">
                 <button id="tank1" class="flex flex-col w-40 bg-[#173161] rounded-md p-2 shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
  

                <p class="flex flex-row justify-between font-inter text-white text-sm font-semibold">
                  <span class="tank-label">Tank 3</span>
                  <span>Diesel</span>
                </p>

                <div class="flex flex-row h-full pl-3 p-1 gap-3">
                  
 
                  <div class="h-full w-5 bg-white/70 flex items-end">
                    <div class="bar-fill w-full h-0 bg-[#48BA6B] transition-transform duration-200"></div>
                  </div>


                  <div class="flex flex-col h-full justify-between pt-2">
                    
                    <div class="flex flex-col text-white font-inter gap-1 font-semibold text-left">
                      <span class="percent text-3xl">...</span>
                      <div class="flex flex-row text-[10px]">
                        <span class="liters">...</span>
                        <span> / 20,000 L</span>
                      </div>
                    </div>

                    <div class="flex flex-row justify-end items-center gap-2 w-full h-3">
                      <div class="status-dot w-2 h-2 bg-green-500 rounded-full"></div>
                      <span class="status-text text-white font-inter font-semibold text-[9px]">...</span>
                    </div>

                  </div>
                </div>
              </button>
                <button id="tank2" class="flex flex-col w-40 bg-[#173161] rounded-md p-2 shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
  
              <!-- Fuel type -->
              <p class="flex flex-row justify-between font-inter text-white text-sm font-semibold">
                <span class="tank-label">Tank 2</span>
                <span>Premium</span>
              </p>

              <div class="flex flex-row h-full pl-3 p-1 gap-3">
                
                <!-- Fuel bar -->
                <div class="h-full w-5 bg-white/70 flex items-end">
                  <div class="bar-fill w-full h-0 bg-[#48BA6B] transition-transform duration-200"></div>
                </div>

                <!-- Percentage and liters -->
                <div class="flex flex-col h-full justify-between pt-2">
                  
                  <div class="flex flex-col text-white font-inter gap-1 font-semibold text-left">
                    <span class="percent text-3xl">...</span>
                    <div class="flex flex-row text-[10px]">
                      <span class="liters">...</span>
                      <span> / 20,000 L</span>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="flex flex-row justify-end items-center gap-2 w-full h-3">
                    <div class="status-dot w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="status-text text-white font-inter font-semibold text-[9px]">...</span>
                  </div>

                </div>
              </div>
            </button>
             <button id="tank3" class="flex flex-col w-40 bg-[#173161] rounded-md p-2 shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
  
              <!-- Fuel type -->
              <p class="flex flex-row justify-between font-inter text-white text-sm font-semibold">
                <span class="tank-label">Tank 3</span>
                <span>Unleaded</span>
              </p>

              <div class="flex flex-row h-full pl-3 p-1 gap-3">
                
                <!-- Fuel bar -->
                <div class="h-full w-5 bg-white/70 flex items-end">
                  <div class="bar-fill w-full h-0 bg-[#48BA6B] transition-transform duration-200"></div>
                </div>

                <!-- Percentage and liters -->
                <div class="flex flex-col h-full justify-between pt-2">
                  
                  <div class="flex flex-col text-white font-inter gap-1 font-semibold text-left">
                    <span class="percent text-3xl">...</span>
                    <div class="flex flex-row text-[10px]">
                      <span class="liters">...</span>
                      <span> / 20,000 L</span>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="flex flex-row justify-end items-center gap-2 w-full h-3">
                    <div class="status-dot w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="status-text text-white font-inter font-semibold text-[9px]">...</span>
                  </div>

                </div>
              </div>
            </button>
              </div>
            </div>
            <div class="w-1/2 h-full bg-white flex flex-col p-2 border border-[#1A2F58]/20 rounded-[5px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
              <div class="p-1 flex flex-row justify-between mb-2">
                <p class="text-[#1A2F58] font-inter font-semibold">
                  <span>Quick Actions</span>
                </p>
                
              </div>
              <div class="flex flex-row gap-3 justify-center">
              <button id="prodbtn" class="flex flex-col w-36 h-full justify-between bg-[#DEEAFF] border border-[#1A2F58] rounded-md p-2 shadow-[0_4px_4px_rgba(0,0,0,0.25)]  ">
                  <span class="text-left text-xl font-inter font-semibold text-[#1A2F58]">Add a Product</span>
                  <i class="fa-solid fa-plus text-[45px] text-right text-[#173160]"></i>
                </button>
                <button class="flex flex-col w-36 h-full justify-between bg-[#1A2F58] rounded-md p-2 shadow-[0_4px_4px_rgba(0,0,0,0.25)]  ">
                  <span class="text-left text-xl font-inter font-semibold text-white">Receive an Order</span>
                  <div class="flex justify-end">
                     <img src="../assets/Admin/Box.png" class="w-10 h-10">
                  </div>
                 
                </button>
                <button class="flex flex-col w-36 h-full justify-between bg-[#DEEAFF] border border-[#1A2F58] rounded-md p-2 shadow-[0_4px_4px_rgba(0,0,0,0.25)]  ">
                  <span class="text-left text-xl font-inter font-semibold text-[#1A2F58]">Edit a Product</span>
                  <div class="flex justify-end">
                     <img src="../assets/Admin/Edit.png" class="w-10 h-10">
                  </div>  
                </button>
              </div>
                
            </div>
          </div> 
          <div class="w-full h-72 flex flex-row gap-4">
            <div class="w-[65%] h-full bg-white flex flex-col p-3 gap-3 border border-[#1A2F58]/20 rounded-[5px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
              <div class="flex flex-row justify-between items-center">
                <span class="font-inter text-base text-[#1A2F58] font-semibold">List of Products</span>
               <div class="flex flex-row gap-4">
                      <div class="relative">
                           <input id="productSearch" autocomplete="off" placeholder="Search..." class="p-1 border border-[#1F3A69]/40 rounded-sm font-inter relative text-[#1F3A69] font-normal focus:outline-[#1F3A69]" type="text">
                          <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-2 opacity-50"></i>
                      </div> 
               </div>
              </div>
              <div class=" overflow-x-auto h-60 border-t border-[#1A2F58]/20">
                    <table class="min-w-full">
                    <thead class=" sticky top-0 bg-white text-[#1A2F58]  text-sm font-inter font-semibold tracking-wide shadow-sm shadow-[#1A2F58]/20">
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Product Name</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Stock Quantity</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Unit Price</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Last Restocked</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Status</th>
                    </thead>
                    <tbody id="productTableBody" class="text-center font-inter text-[13px] text-[#1A2F58] font-normal py-1 divide-y divide-[#1A2F58]/30">
                      <tr class="">
                        <td class="px-1 py-2">Petron Brake Fluid</td>
                        <td class="px-1 py-2">  50</td>
                        <td class="px-1 py-2">₱250.00</td>
                        <td class="px-1 py-2">04/21/25</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#48BA6B] w-14 py-[1px] rounded-full text-[10px]">Available</span></p></td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2">Petron Brake Fluid</td>
                        <td class="px-1 py-2">50</td>
                        <td class="px-1 py-2">₱250.00</td>
                        <td class="px-1 py-2">04/21/25</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#F50505] w-14 py-[1px] rounded-full text-[10px]">Low</span></p></td>
                      </tr>
                     <tr class="">
                        <td class="px-1 py-2">Petron Brake Fluid</td>
                        <td class="px-1 py-2">50</td>
                        <td class="px-1 py-2">₱250.00</td>
                        <td class="px-1 py-2">04/21/25</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-black"><span class="bg-[#DADADA] px-2 py-[1px] rounded-full text-[10px]">Unavailable</span></p></td>
                      </tr>
                        <tr class="">
                        <td class="px-1 py-2">Petron Brake Fluid</td>
                        <td class="px-1 py-2">50</td>
                        <td class="px-1 py-2">₱250.00</td>
                        <td class="px-1 py-2">04/21/25</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#48BA6B] w-14 py-[1px] rounded-full text-[10px]">Available</span></p></td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2">Petron Brake Fluid</td>
                        <td class="px-1 py-2">50</td>
                        <td class="px-1 py-2">₱250.00</td>
                        <td class="px-1 py-2">04/21/25</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#F50505] w-14 py-[1px] rounded-full text-[10px]">Low</span></p></td>
                      </tr>
                     <tr class="">
                        <td class="px-1 py-2">Petron Brake Fluid</td>
                        <td class="px-1 py-2">50</td>
                        <td class="px-1 py-2">₱250.00</td>
                        <td class="px-1 py-2">04/21/25</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-black"><span class="bg-[#DADADA] px-2 py-[1px] rounded-full text-[10px]">Unavailable</span></p></td>
                      </tr>
                    </tbody>
                  </table>
                </div>  
              
              


            </div>
            <div class="bg-white w-1/2 flex flex-col p-3 gap-3 border border-[#1A2F58]/20 rounded-[5px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
              <div class="flex flex-row justify-between items-center mb-1">
                 <p class="text-[#1A2F58] font-inter font-semibold">
                  <span>Recent Order Received </span>
                </p>    
                <button class="bg-[#173161] font-inter font-medium rounded-md text-white w-24 p-1 text-sm">
                  See More
                </button> 
              </div>
              <div id="ordercontainer" class="w-full overflow-y-auto h-full border-t border-b flex flex-col border-[#1A2F58]/20 divide-y divide-[#1A2F58]/20">
                <button class="h-16 flex flex-row items-center gap-6 p-4 w-full hover:bg-[#E7E7EE] transition-colors">
                  <img src="../assets/Admin/Box_blue.png" class="h-10 w-10">
                  <div class="flex flex-col text-sm gap-1 text-inter text-[#1A2F58] font-semibold">
                    <p class="flex flex-row justify-between w-full gap-16">
                      <span>Automotive Products</span>
                      <span>ORD-1214-CM10A</span>
                    </p>
                     <p class="flex flex-row justify-between w-full gap-14">
                      <span class="bg-[#D4FFE1] text-[#459A5F] border border-[#459A5F] rounded-md w-20">
                        Received</span>
                      <span>February 21, 2026 | 06:31 PM</span>
                    </p>
                  </div>
                </button>
                <button class="h-16 flex flex-row items-center gap-6 p-4 w-full hover:bg-[#E7E7EE] transition-colors">
                  <img src="../assets/Admin/Droplet.png" class="h-10 w-10">
                  <div class="flex flex-col text-sm gap-1 text-inter text-[#1A2F58] font-normal">
                    <p class="flex flex-row justify-between w-full gap-16">
                      <span>Fuel Products</span>
                      <span>ORD-1X10-ASV13</span>
                    </p>
                     <p class="flex flex-row justify-between w-full gap-14">
                      <span class="bg-[#D4FFE1] text-[#459A5F] border border-[#459A5F] rounded-md w-20">
                        Received</span>
                      <span>February 12, 2026 | 01:36 PM</span>
                    </p>
                  </div>
                </button>
              </div>
            </div>

          </div> 
            
        </main>
</body>
</html>
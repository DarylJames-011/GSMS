<?php
require_once '../config/auth.php';

// Admin page
if ($_SESSION['role'] !== 'Cashier') {
    header("Location: /Alpha Stage/Login.php");
    exit();
}
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
  <script src="../js/dashboard.js" defer></script>

  <!-- JS Chart-->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
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
    <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-row gap-5">
      <div class="flex flex-col gap-4">
         <span class="text-[#1F3A69] font-semibold text-2xl">Product Inventory</span>
          <div class=" w-64 h-auto flex flex-col gap-6">
            <span class="text-[#1F3A69] text-lg font-semibold font-inter">Fuel Products </span>
            <div class="flex flex-col w-full h-36 gap-2 bg-[#1A2F58] rounded font-inter p-2 text-white">
              <div class="flex flex-row justify-between text-lg font-medium">
                  <span>Diesel</span>
                  <span>Price Per Ltr</span>
              </div>
              <span class="text-xl font-semibold h-1/2  flex items-center">00,000 / 20,000L</span>
              <div class="w-full h-1/4 flex flex-row gap-2 items-center">
                <div class="w-2 h-2 rounded-full bg-red-600"></div>
                <div class="text-sm font-medium">Placeholder</div>
              </div>
            </div>
            <div class="flex flex-col w-full h-36 gap-2 bg-[#1A2F58] rounded font-inter p-2 text-white">
              <div class="flex flex-row justify-between text-lg font-medium">
                  <span>Diesel</span>
                  <span>Price Per Ltr</span>
              </div>
              <span class="text-xl font-semibold h-1/2  flex items-center">00,000 / 20,000L</span>
              <div class="w-full h-1/4 flex flex-row gap-2 items-center">
                <div class="w-2 h-2 rounded-full bg-red-600"></div>
                <div class="text-sm font-medium">Placeholder</div>
              </div>
            </div>
            <div class="flex flex-col w-full h-36 gap-2 bg-[#1A2F58] rounded font-inter p-2 text-white">
              <div class="flex flex-row justify-between text-lg font-medium">
                  <span>Diesel</span>
                  <span>Price Per Ltr</span>
              </div>
              <span class="text-xl font-semibold h-1/2  flex items-center">00,000 / 20,000L</span>
              <div class="w-full h-1/4 flex flex-row gap-2 items-center">
                <div class="w-2 h-2 rounded-full bg-red-600"></div>
                <div class="text-sm font-medium">Placeholder</div>
              </div>
            </div>
            
          </div>
      </div>
      <div class="w-1/2 h-auto p-1 flex flex-col gap-2 font-inter text-[#1A2F58]">
       <div class="w-full h-auto flex flex-row justify-between items-center">
          <span class="text-xl font-semibold">Automotive Products</span>
           <div class="relative h-auto">
                  <input id="searchTransaction" type="text" placeholder="Search..." class="w-full p-1 border border-[#1F3A69] font-inter font-normal rounded-[3px]">
                 <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-2 opacity-50"></i>
            </div>
       </div>
       <div class="h-[94%] bg-white rounded  max-h-auto overflow-y-auto border border-[#1A2F58]/20 w-full">
                    <table class="min-w-full">
                    <thead class=" sticky top-0 bg-white text-[#1A2F58]  text-sm font-inter font-semibold tracking-wide shadow-sm shadow-[#1A2F58]/20">
                        <th class="font-inter text-base text-[#1A2F58] font-semibold py-2">Product Name</th>
                        <th class="font-inter text-base text-[#1A2F58] font-semibold py-2">Unit Price</th>
                        <th class="font-inter text-base text-[#1A2F58] font-semibold py-2">Stock Left</th>
                        <th class="font-inter text-base text-[#1A2F58] font-semibold py-2">Status</th>
                    </thead>
                    <tbody id="productTableBody" class=" text-center font-inter text-[13px] text-[#1A2F58] font-normal py-1 divide-y divide-[#1A2F58]/30">
                      <tr class="">
                        <td class="px-1 py-3">Petron Brake Fluid</td>
                        <td class="px-1 py-3">₱250.00</td>
                        <td class="px-1 py-3">  50</td>
                        <td class="px-1 py-3"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#48BA6B] w-14 py-[1px] rounded-full text-[10px]">Available</span></p></td>
                      </tr>
                    </tbody>
                  </table>
       </div>
      </div>
      <div class="w-1/4 h-auto gap-5 flex flex-col font-inter">
        <div class="flex flex-col w-full h-3/4 border border-[#1A2F58]/20 bg-white rounded-tr rounded-tl text-white">
          <div class="w-full h-10 p-2 bg-[#1A2F58] text-white">Product Information</div>
          <div class="p-4 flex flex-col gap-2">
            <div class="flex flex-row gap-1 text-[#1A2F58]">
              <img src="../assets/Sample.png" class="w-24 h-24">
              <div class="flex flex-col text-sm font-medium justify-between">
                <div>
                  <span>Product Name</span>
                  <span>Unit Price</span>
                </div>
                <div>
                  <span>Stock Left</span>
                  <span>Placeholder</span>
                </div>
              </div>
            </div>
          </div>
          <div class="w-full h-full text-[#1A2F58] px-4 flex flex-col gap-2">
            <div class="flex flex-row justify-between items-center">
                <span class="font-semibold text-sm">Last Restock:</span>
                <span class="font-medium text-xs">Placeholder</span>
            </div>
            <span class="font-semibold text-sm">Description</span>
            <textarea readonly class="w-full h-auto font-medium text-sm resize-none">hello</textarea>
            
          </div>
        </div>
        <div class="flex flex-col w-full h-[35%] border border-[#1A2F58]/20 bg-white p-3 gap-2">
          <span class="text-[#1A2F58] text-sm font-semibold">Recent Inventory Stocks</span>
          <div class="w-full h-3/4 p-2 border-t border-b border-[#1A2F58]">
            <div class="flex flex-row gap-2 w-full h-full items-center">
              <img src="../assets/Sample.png" class="w-20 h-20">
              <div class="flex flex-col text-[#1A2F58] text-sm  ">
                <span>Product Name</span>
                <span>Last Restocked:</span>
                <span>Date</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

</body>
</html>
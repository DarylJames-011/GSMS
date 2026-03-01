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

  <!-- JS Chart-->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="overflow-x-hidden overflow-y-hidden bg-gradient-to-r from-[#EDF4FF] via-[#DAE5FF] to-[#F8F8FF]">
     <nav class="w-64 bg-gradient-to-b from-[#1B2D50] via-[#1B2D50_70%] to-[#35496E] h-screen fixed top-0 left-0 shadow-[5px_0_10px_3px_rgba(0,0,0,0.25)]
    flex flex-col items-center">
        <img src="../assets/GSMS2.png" class="p-5 mb-[53px]">
        <div class="flex flex-col w-full pt-12 mb-24">
        <a href="dashboard1.php" class="flex flex-row gap-5 justify-center w-full p-3">
        
        <i class="fa-regular fa-window-restore text-2xl text-[#F8F8FF]"></i>
        <p class="text-lg text-[#F8F8FF] font-poppins font-medium w-[70%]">Dashboard</p>
        </a>
        <a href="Transaction.php" class="flex flex-row gap-5 justify-center w-full p-3">
        <i class="fa-solid fa-money-bills text-2xl text-[#F8F8FF]"></i>
        <p class="text-lg text-[#F8F8FF] font-poppins font-medium w-[70%]">Transactions</p>
        </a>
        <a href="Products.php"class="flex flex-row gap-5 justify-center w-full p-3">
        <i class="fa-solid fa-gas-pump text-2xl text-[#F8F8FF]"></i>
        <p class="text-lg text-[#F8F8FF] font-poppins font-medium w-[70%]">Products</p>
        </a>

        
        
        </div>
        <div class="border-t-2 w-[80%] p-5 border-[#F8F8FF]">
          <div class="flex flex-row items-center gap-3 mb-3">
            <div class="w-11 h-11 ">
              <img src="../assets/Profile.jpg" class="rounded-full w-full h-full object-cover block">
            </div>

          <div>
              <p class="font-semibold font-inter text-[#F8F8FF]">John Doe</p>
              <p class="text-[#F8F8FF] font-inter font-medium text-sm">Cashier</p>
          </div>
          </div>
          <div class="flex flex-row items-center gap-5 pl-3 mb-4">
            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
            <p class="font-inter text-[11px] text-[#F8F8FF]">Shift not started yet</p>
          </div>
          <button class="bg-[#1B2D50] border-[0.5px] border-[#E5EFFF] w-full h-11 font-inter text-[#F8F8FF]
          rounded-lg hover:bg-[#284379] transition-colors duration-200" onclick="openModal()">
            Log Out
          </button>
        </div>

    </nav>  
      <script>
        const modal = Document.getElementById("modal");
        const overlay = Document.getElementById("overlay");
      </script>
    <!---Overlay--->
    <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center">

    
    
  <!-- Overlay: dark/blur background -->
  <div id="overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

  <!-- Modal: centered content -->
  <div class="relative bg-[#F8F8FF] rounded-lg w-[90%] h-[90%] shadow-xl flex flex-row  min-w-[700px]">
    <div class="flex flex-col w-[70%]  p-6">
      <p class="font-semibold font-inter text-2xl text-[#1A2F58] mb-6">Add new Transaction</p>
      <div 
      class="h-[85%] shadow-[0_4px_4px_rgba(0,0,0,0.25)] bg-white 
      p-4">
        <div class="flex flex-row items-center gap-5 mb-3">
          <p class="font-inter text-2xl font-semibold text-[#1A2F58]">Fuel Products</p>
          <div class="border-t-2 border-[#1F3A69] w-[27%]"></div>
          <div class="flex flex-row gap-3"> 
                      <div class="relative">
                      <input type="text" placeholder="Search..." class="w-full p-1 border border-[#1F3A69] font-inter font-normal rounded-[3px]">
                      <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-2 opacity-50"></i>
                    </div>
                      <button class="bg-[#E0F1FF] text-[#1F3A69] border border-[#1F3A69] 
                      w-28 flex flex-row justify-between items-center pl-2 font-semibold text-base rounded-[3px] pr-2">
                        Filters
                        <i class="fa-solid fa-v"></i>
                      </button>
                  </div>
          
        </div>
        <div class=" flex gap-12 items-center flex-row mb-3">
        <button 
        class="bg-gradient-to-b from-[#1B2D50] to-[#35496E] 
        flex flex-col items-start p-3
        w-56 h-28 rounded-[5px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
        <p class=" text-[#F8F8FF] font-inter font-semibold">Unleaded</p>
        <p class="text-[13px] font-inter text-[#F8F8FF] mb-7">₱ 50.00 per L</p>
        <div class="flex flex-row justify-between w-[107%] items-center">
          <div class="flex flex-row items-center gap-1">
                        <div class="w-2 h-2 bg-[#38AC5B] rounded-full"></div>
                        <p class="font-inter text-[10px] text-[#F8F8FF]">Sufficient Stock</p>
          </div>
           <img src="/assets/downright.png">
       </div>
        
        </button>
        <button 
        class="bg-gradient-to-b from-[#1B2D50] to-[#35496E] 
        flex flex-col items-start p-3
        w-56 h-28 rounded-[5px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
        <p class=" text-[#F8F8FF] font-inter font-semibold">Unleaded</p>
        <p class="text-[13px] font-inter text-[#F8F8FF] mb-7">₱ 50.00 per L</p>
        <div class="flex flex-row justify-between w-[107%] items-center">
         <div class="flex flex-row items-center gap-1">
                        <div class="w-2 h-2 bg-[#F6833B] rounded-full"></div>
                        <p class="font-inter text-[10px] text-[#F8F8FF]">Reorder Soon</p>
                        </div>
           <img src="/assets/downright.png">
       </div>
        
        </button>
        <button 
        class="bg-gradient-to-b from-[#1B2D50] to-[#35496E] 
        flex flex-col items-start p-3
        w-56 h-28 rounded-[5px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
        <p class=" text-[#F8F8FF] font-inter font-semibold">Unleaded</p>
        <p class="text-[13px] font-inter text-[#F8F8FF] mb-7">₱ 50.00 per L</p>
        <div class="flex flex-row justify-between w-[107%] items-center">
          <div class="flex flex-row items-center gap-1">
                        <div class="w-2 h-2 bg-[#38AC5B] rounded-full"></div>
                        <p class="font-inter text-[10px] text-[#F8F8FF]">Sufficient Stock</p>
          </div>
           <img src="/assets/downright.png">
       </div>
        
        </button>
        </div>
        <div class="flex flex-row items-center gap-5 mb-3">
          <p class="font-inter text-2xl font-semibold text-[#1A2F58]">Automotive Products</p>
          <div class="border-t-2 border-[#1F3A69] w-[65%]"></div>
        </div>
        <div class="w-full pr-1 overflow-y-auto h-52
        grid grid-cols-1  sm:grid-cols-2 lg:grid-cols-3 gap-3
        "> 
        <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
        <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
        <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
        <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
        <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
         <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
         <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
         <button class="w-56 bg-[#F8F8FF] h-28 rounded-[3px] flex flex-row items-center shadow-[0_4px_4px_rgba(0,0,0,0.25)]">
          <img src="/assets/Sample.png" class="h-[90%] w-[45%] p-1 pl-3">
          <div class="flex flex-col justify-start items-start">
            <p class="font-bold font-inter text-[#1A2F58] text-[11px] text-left">Petron Brake Fluid</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left">₱250.00</p>
            <p class="font-semibold font-inter text-[#1A2F58] text-[10px] text-left mb-5">13 items left</p>
            <div class="flex items-center w-full justify-end">
            <div class="text-[10px] font-inter text-[#745D00] font-bold border-[0.5px] bg-[#FFE580] border-[#745D00] rounded-sm px-1">
              Low Stock</div>
          </div>
          </div>
        </button>
      </div>
      </div>
      

    </div>
    <!-- Right part of Modal -->
    <div class="flex flex-col bg-white w-full rounded-tr-[10px] rounded-br-[10px] p-6">
      <div class="flex flex-row justify-between items-center mb-5 divide-y divide-gray-200">
        <p class="font-inter text-xl text-[#1A2F58] font-semibold">Order Summary</p>
        <button class="px-3 bg-[#F02D2D] rounded-xl py-1 text-[#F8F8FF] font-semibold font-inter">Close</button>
      </div>


        <p class="text-[#1A2F58] font-semibold text-[14px] font-inter mb-3">Order Details</p>
      <div class="bg-[#DEEAFF] w-full h-10 flex items-center justify-evenly gap-7 px-2">
        <p></p>
        <p class="text-[13px] text-[#1A2F58] font-inter font-semibold">Product</p>
        <p class="text-[13px] text-[#1A2F58] font-inter font-semibold">Quantity</p>
        <p class="text-[13px] text-[#1A2F58] font-inter font-semibold">Price</p>
        <p class="text-[13px] text-[#1A2F58] font-inter font-semibold pr-3">Amount</p>
      </div>
      <div class="flex items-center py-3 px-2 font-inter font-light text-xs">
        <button class="w-6 h-6 text-white bg-[#FF7676] font-extrabold rounded-sm flex items-center justify-center">-</button>
        <p class="flex-1 px-2">Petrol Brake Fluid</p>
        <p class="w-8 text-center">1x</p>
        <p class="w-12 text-right">250.00</p>
        <p class="w-12 text-right">₱250.00</p>
      </div>

      

    </div>
  </div>

</div>
    <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-row ">
        <div class="flex flex-col h-auto w-full">
            <div class="flex flex-row justify-between w-full items-center mb-4">
                    <p class="font-inter font-bold text-[24px] text-[#1F3A69]">Transactions</p>
                    <div class="flex flex-row">
                        <button class="p-[9px] bg-[#1F3A69] w-72 flex items-center flex-row gap-10 rounded-md hover:bg-[#35518B] transition">
                          <i class="fa-solid fa-plus text-xl text-[#F8F8FF] ml-2"></i>
                          <p class="text-[#F8F8FF] text-center font-inter font-semibold text-sm">Add new Transaction</p>
                        </button>

                    </div>
            </div>
            <div class="flex flex-row gap-6">
              <div class="w-[722px]">
                <div class="w-full h-[497px] bg-[#F8F8FF] rounded-[10px] p-3 [box-shadow:0px_4px_5px_1px_rgba(0,0,0,0.25)]">
                  <div class="flex flex-row justify-between items-center mb-5">
                    <p class="font-inter font-bold text-base text-[#1F3A69]">Transaction List</p>
                    <div class="flex flex-row gap-3"> 
                      <div class="relative">
                      <input type="text" placeholder="Search..." class="w-full p-1 border border-[#1F3A69] font-inter font-normal rounded-[3px]">
                      <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-2 opacity-50"></i>
                    </div>
                      <button class="bg-[#E0F1FF] text-[#1F3A69] border border-[#1F3A69] 
                      w-28 flex flex-row justify-between items-center pl-2 font-semibold text-base rounded-[3px] pr-2">
                        Filters
                        <i class="fa-solid fa-v"></i>
                      </button>
                  </div>
                   
                  </div>
                    <table class="w-full text-center border-collapse border-[0.5px] border-[#1A2F58] border-opacity-50">
                      <thead class="bg-[#E5EFFF] text-[#1A2F58] text-sm font-inter font-semibold tracking-wide border border-[#1A2F58]">
                        <tr>
                          <th class="px2 py-3">Date and Time</th>
                          <th class="px2 py-3">Product</th>
                          <th class="px2 py-3">Quantity</th>
                          <th class="px2 py-3">Unit Price</th>
                          <th class="px2 py-3">Total</th>
                        </tr>
                      </thead>
                      <tbody class="text-[#4E6CA8] text-[12px] font-inter tracking-wide">
                        <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                        <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                        <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                        <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">02/21/26 10:30 P.M</td>
                          <td class="px-2 py-2 font-normal">Fuel</td>
                          <td class="px-2 py-2 font-normal">31.13 L</td>
                          <td class="px-2 py-2 font-normal">42.16</td>
                          <td class="px-2 py-2 font-normal">10,210.00</td>
                       </tr>
                      </tbody>
                  </table>
                  <p class="p-1 flex justify-end text-[#1A2F58] font-medium text-[13px] tracking-wide">Showing 1-10 of 10</p>
                  
                </div>
              </div>
              <div class="w-72 
              [box-shadow:0px_4px_5px_1px_rgba(0,0,0,0.25)] rounded-[10px] p-2 bg-[#F8F8FF]
              flex flex-col
              ">
                <p class="font-inter font-bold text-base text-[#1F3A69]">Transaction Info</p>
                <div class="flex justify-center items-center h-[85%] flex-col gap-3 mb-3">
                  <img src="/assets/Paper.png">
                  <p class="w-40 text-center tracking-wide font-inter font-medium text-[15px] text-black text-opacity-50">Please Select a
              Transaction to see more information</p>
                </div>
                <div class="flex flex-row gap-1 items-center justify-center">
                  <button class="bg-[#FFDDDD] border border-[#B22222] text-[#B22222] text-[15px] font-medium px-2 py-1 rounded-[5px] disabled:text-opacity-45 cursor-not-allowed" disabled>Void Transaction</button>
                  <button class="bg-[#D4E4FF] border border-[#1A2F58] text-[#1A2F58] text-[15px] font-medium px-2 py-1 rounded-[5px] disabled:text-opacity-45" disabled>Generate Receipt</button>
                </div>
              </div>
            </div>
            
        </div>
    </main>
 



</body>
</html>
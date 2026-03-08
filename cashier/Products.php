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
    <main class="ml-64 h-auto min-w-[800px] w-[81%] flex flex-row ">
       <div class=" w-[70%] p-11 flex flex-col">
            <p class="text-[#1F3A69] font-inter font-bold text-2xl mb-5">Product Inventory</p>   
            <div class=" mb-6">
            <p class="font-base text-[#1F3A69] font-inter font-bold mb-3">Fuel Products</p>
            <div class="flex flex-row justify-between">
                <div class="flex flex-col bg-gradient-to-b from-[#1A2F58] via-[#1A2F59] to-[#4471CC]
                w-52 rounded-[10px] h-32 p-3 shadow-[0_4px_5px_1px_rgba(0,0,0,0.25)]
                ">
                    <p class="font-inter font-medium text-base text-[#F8F8FF]">Tank #1</p>
                    <p class="font-inter font-medium text-[12px] text-[#F8F8FF]">Capacity : 14,000 L / 20,000 L</p>
                    <br><br>
                    <div class="flex flex-row justify-between">
                       <div class="flex flex-row items-center gap-1">
                        <div class="w-2 h-2 bg-[#38AC5B] rounded-full"></div>
                        <p class="font-inter text-[10px] text-[#F8F8FF]">Sufficient Stock</p>
                        </div>
                        <p class="font-inter font-medium text-[13px] text-[#F8F8FF]">Unleaded</p>
                        
                    </div>
                </div>
                <div class="flex flex-col bg-gradient-to-b from-[#1A2F58] via-[#1A2F59] to-[#4471CC]
                w-52 rounded-[10px] h-32 p-3 shadow-[0_4px_5px_1px_rgba(0,0,0,0.25)]
                ">
                    <p class="font-inter font-medium text-base text-[#F8F8FF]">Tank #2</p>
                    <p class="font-inter font-medium text-[12px] text-[#F8F8FF]">Capacity : 1,000 L / 20,000 L</p>
                    <br><br>
                    <div class="flex flex-row justify-between">
                       <div class="flex flex-row items-center gap-1">
                        <div class="w-2 h-2 bg-[#F6833B] rounded-full"></div>
                        <p class="font-inter text-[10px] text-[#F8F8FF]">Reorder Soon</p>
                        </div>
                        <p class="font-inter font-medium text-[13px] text-[#F8F8FF]">Premium</p>
                        
                    </div>
                </div>
               <div class="flex flex-col bg-gradient-to-b from-[#1A2F58] via-[#1A2F59] to-[#4471CC]
                w-52 rounded-[10px] h-32 p-3 shadow-[0_4px_5px_1px_rgba(0,0,0,0.25)]
                ">
                    <p class="font-inter font-medium text-base text-[#F8F8FF]">Tank #3</p>
                    <p class="font-inter font-medium text-[12px] text-[#F8F8FF]">Capacity : 9,000 L / 20,000 L</p>
                    <br><br>
                    <div class="flex flex-row justify-between">
                       <div class="flex flex-row items-center gap-1">
                        <div class="w-2 h-2 bg-[#DDE255] rounded-full"></div>
                        <p class="font-inter text-[10px] text-[#F8F8FF]">Monitor Stock</p>
                        </div>
                        <p class="font-inter font-medium text-[13px] text-[#F8F8FF]">Diesel</p>
                        
                    </div>
                </div>
            </div>
            
            </div>
            <div class="bg-[#FFFFFF] rounded-xl p-3 shadow-[0_2px_3px_1px_rgba(0,0,0,0.25)]">
                <div class="flex flex-row justify-between items-center mb-5">
                    <p class="font-inter font-bold text-base text-[#1F3A69]">Automotive Products</p>
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
                <div class="max-h-56 overflow-y-auto border-[0.5px] border-[#1A2F58] border-opacity-50 rounded-sm mb-5">
                <table class="w-full text-center border-collapse">
                      <thead class="sticky top-0 bg-[#E5EFFF] text-[#1A2F58] text-sm font-inter font-semibold tracking-wide shadow-sm ">
                        <tr>
                          <th class="px2 py-3">Product Name</th>
                          <th class="px2 py-3">Category</th>
                          <th class="px2 py-3">Price</th>   
                          <th class="px2 py-3">Stock</th>
                          <th class="px2 py-3">Status</th>
                        </tr>   
                      </thead>     
                      <tbody class="text-[#4E6CA8] text-[12px] font-inter tracking-wide">
                        <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">Petron Brake Fluid</td>
                          <td class="px-2 py-2 font-normal">Brake Fluid</td>
                          <td class="px-2 py-2 font-normal">200.00</td>
                          <td class="px-2 py-2 font-normal">14</td>
                          <td class="px-2 py-2 font-normal justify-center flex"><p class="bg-[#FFE580] px-1 w-16 text-[#745D00] font-medium rounded-md border border-[#745D00]">Low</p></td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">Engine Oil</td>
                          <td class="px-2 py-2 font-normal">Engine Oil</td>
                          <td class="px-2 py-2 font-normal">250.00</td>
                          <td class="px-2 py-2 font-normal">20</td>
                          <td class="px-2 py-2 font-normal justify-center flex"><p class="bg-[#D3FFD4   ] px-1 w-16 text-[#007431] font-medium rounded-md border border-[#007431]">In Stock</p></td>
                       </tr>
                        <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">Antifreeze</td>
                          <td class="px-2 py-2 font-normal">Antifreeze</td>
                          <td class="px-2 py-2 font-normal">300.00</td>
                          <td class="px-2 py-2 font-normal">0</td>
                          <td class="px-2 py-2 font-normal justify-center flex"><p class="bg-[#FFD3D3] px-1 w-16 text-[#740000] font-medium rounded-md border border-[#740000]">Sold Out</p></td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">Petron Brake Fluid</td>
                          <td class="px-2 py-2 font-normal">Brake Fluid</td>
                          <td class="px-2 py-2 font-normal">200.00</td>
                          <td class="px-2 py-2 font-normal">14</td>
                          <td class="px-2 py-2 font-normal justify-center flex"><p class="bg-[#FFE580] px-1 w-16 text-[#745D00] font-medium rounded-md border border-[#745D00]">Low</p></td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">Engine Oil</td>
                          <td class="px-2 py-2 font-normal">Engine Oil</td>
                          <td class="px-2 py-2 font-normal">250.00</td>
                          <td class="px-2 py-2 font-normal">20</td>
                          <td class="px-2 py-2 font-normal justify-center flex"><p class="bg-[#D3FFD4   ] px-1 w-16 text-[#007431] font-medium rounded-md border border-[#007431]">In Stock</p></td>
                       </tr>
                        <tr class="even:bg-[#DEEAFF] even:border-[0.5px] even:border-[#1A2F58] even:border-opacity-50">
                          <td class="px-2 py-2 font-normal">Antifreeze</td>
                          <td class="px-2 py-2 font-normal">Antifreeze</td>
                          <td class="px-2 py-2 font-normal">300.00</td>
                          <td class="px-2 py-2 font-normal">0</td>
                          <td class="px-2 py-2 font-normal justify-center flex"><p class="bg-[#FFD3D3] px-1 w-22 text-[#740000] font-medium rounded-md border border-[#740000]">Sold Out</p></td>
                       </tr>

                      </tbody>        
                    </table>
                </div>
            </div>
       </div>
       <div class="w-[30%] p-4 pt-11 flex flex-col
       bg-gradient-to-b from-[#F2F6FF] via-[#F2F6FF] to-[#DBE8FF]
       shadow-[0_2px_3px_1px_rgba(0,0,0,0.25)]">
        <p class="text-xl font-inter font-semibold text-[#1A2F58]">Product Information</p>
        <p class="text-[28px] font-inter font-bold text-[#1A2F58] mb-2 ">Petron Brake Fluid</p>
        <div class="flex justify-center items-center mb-4">
            <img src="../assets/Sample.png">
        </div>  
        <div class="flex flex-col gap-2">
            <div class="flex flex-row justify-between items-center">
                <p class="text-sm font-inter text-[#1F3A69] font-bold">Stock Status</p>
                <div class="flex flex-row gap-3 items-center">
                    <p class="border bg-[#FFE580] text-[12px] h-max w-max px-1 text-[#745D00] font-bold font-inter rounded-md border-[#745D00]">Low Stock</p>
                    <p class="text-[#1A2F58] font-inter text-base font-normal">13 remaining</p>
                </div>
                
            </div>
            <div class="w-full border-t border-t-[#1A2F58] border-opacity-50"></div>
            <div class="flex flex-row justify-between items-center">
                <p class="text-sm font-inter text-[#1F3A69] font-bold">Category</p>
                <p class="text-[#1A2F58] font-inter text-base font-normal">Brake Fluid</p>           
            </div>
            <div class="w-full border-t border-t-[#1A2F58] border-opacity-50"></div>
             <div class="flex flex-row justify-between items-center">
                <p class="text-sm font-inter text-[#1F3A69] font-bold">Last Stocked</p>
                <p class="text-[#1A2F58] font-inter text-base font-normal">3 days ago</p>           
            </div>
            <div class="w-full border-t border-t-[#1A2F58] border-opacity-50"></div>
            <p class="text-sm font-inter text-[#1F3A69] font-bold">Description</p>
            <p class="text-base font-inter font-normal text-[#1F3A69] text-justify">
                Designed for automotive brake systems. Not suitable for motorcycles or 2-wheeled vehicles.
            </p>
        </div>
       </div>
    </main>

</body>
</html>
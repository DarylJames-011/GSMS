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
  <!-- JS Chart-->  

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="overflow-x-hidden bg-gradient-to-r from-[#DFE5F8]/50 to-[#C9D3F2]/50">
    <nav class="w-64 bg-[#173161] h-screen fixed top-0 left-0 shadow-[5px_0_10px_3px_rgba(0,0,0,0.25)]
    flex flex-col items-center justify-between">
        <div>
        <img src="../assets/GSMS logo.png" class="p-5 mb-[40px]">
         <a href="dashboard.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Windows.png" class="h-7 w-7 ">
        <span class="font-medium text-base font-poppins">Dashboard</span>
        </a>   
        <div class="relative bg-[#6284C6]/60 w-full p-6 h-14 flex flex-row items-center
        shadow-[inset_0_4px_4px_rgba(27,45,80,0.5)] gap-4">
          <div class="absolute bg-white w-1 h-full top-0 left-0"></div>
          <img src="../assets/Admin/nav/Transaction.png" class="h-9 w-8">
          <span class="font-poppins text-white text-base font-medium">Transactions</span>
        </div> 
        <a href="inventory.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Trolley.png" class="h-9 w-8">
        <span class="font-medium text-base font-poppins">Inventory</span>
        </a>
        <a href="Transaction.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
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

        <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-row gap-4">
            
            <div class="w-[95%] h-3/4 flex flex-col gap-5">
                
                <div class="w-full flex flex-row gap-5">
                <div class="bg-[#173161] w-[50%] h-24
                rounded-xl border border-[#3B4963] shadow-[0px_5px_10px_rgba(27,45,80,0.4)] p-3">
              <div class="flex flex-row justify-between items-center">
                    <span class="text-[12px] font-semibold text-white">Todays Revenue</span>
                    <select class="rounded-md font-inter w-28 text-[#173161]">
                        <option>Today</option>
                        <option>Yesterday</option>
                        <option>Last Week</option>
                        <option>Past Month</option>
                    </select>  
                </div> 
                <p class="font-inter font-bold text-white">
                <span class="text-lg">₱ Placeholder</span>
                </p></div>

              <div class="bg-[#173161] w-[50%] h-24
                rounded-xl border border-[#3B4963] shadow-[0px_5px_10px_rgba(27,45,80,0.4)] p-3">
             
                <div class="flex flex-row justify-between items-center">
                    <span class="text-[12px] font-semibold text-white">Liters Sold Today</span>
                    <select class="rounded-md font-inter w-28 text-[#173161]">
                        <option>All</option>
                        <option>Diesel</option>
                        <option>Unleaded</option>
                        <option>Premium</option>
                    </select>  
                </div> 
                <p class="font-inter font-bold text-white">
                <span class="text-lg">₱ Placeholder</span>
                </p></div>
                
                </div>

                
                <div class="bg-[#F8F8FF] p-3 flex flex-col gap-5 border border-[#173161]/20 shadow-md shadow-blue-950/20"> 
                    <div class="flex flex-row justify-between items-center">
                            <span class="font-poppins text-[#1F3A69] font-semibold text-base">Transaction List</span>
                            <div class="flex flex-row gap-3">
                            <form>
                                <div class="relative">
                               <input placeholder="Search..." class="p-1 border border-[#1F3A69]/40 rounded-sm font-inter relative text-[#1F3A69] font-normal focus:outline-[#1F3A69]" type="text">
                                 <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-2 opacity-50"></i>
                            </div> 
                            </form>
                            <button class="border border-[#1F3A69] rounded-sm font-inter text-[#1F3A69] font-semibold w-24 p-1 bg-[#E0F1FF]">Filters</button>
                            </div>

                            
                    </div>
                        
                    <div class="h-72 overflow-y-auto border-collapse border-[0.5px] border-[#1A2F58] border-opacity-50 ">
                        <table class="w-full text-center">
                      <thead class=" sticky shadow-sm shadow-black/20 top-0 bg-[#E5EFFF] text-[#1A2F58] text-sm font-inter font-semibold tracking-wide">
                        <tr>
                          <th class="px2 py-3">ID</th>
                          <th class="px2 py-3">Date & Time</th>
                          <th class="px2 py-3">Items</th>
                          <th class="px2 py-3">Payment</th>
                          <th class="px2 py-3">Cashier</th>
                          <th class="px2 py-3">Total</th>
                          <th class="px2 py-3">Status</th>
                        </tr>
                      </thead>
                      <tbody class="text-[#4E6CA8] text-[12px] font-inter tracking-wide">
                        <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#38AC5B] px-1 rounded-full text-white text-[10px]">
                                <span>Completed</span>
                            </p>
                          </td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#F50505] px-1 rounded-full text-white text-[10px]">
                                <span>Void</span>
                            </p>
                          </td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#38AC5B] px-1 rounded-full text-white text-[10px]">
                                <span>Completed</span>
                            </p>
                          </td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#F50505] px-1 rounded-full text-white text-[10px]">
                                <span>Void</span>
                            </p>
                          </td>
                       </tr>
                        <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#38AC5B] px-1 rounded-full text-white text-[10px]">
                                <span>Completed</span>
                            </p>
                          </td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#F50505] px-1 rounded-full text-white text-[10px]">
                                <span>Void</span>
                            </p>
                          </td>
                       </tr>
                        <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#38AC5B] px-1 rounded-full text-white text-[10px]">
                                <span>Completed</span>
                            </p>
                          </td>
                       </tr>
                       <tr class="even:bg-[#DEEAFF]">
                          <td class="px-2 py-2 font-normal">TRNSX-102139</td>
                          <td class="px-2 py-2 font-normal">01/21/25 11:14 PM</td>
                          <td class="px-2 py-2 font-normal">4</td>
                          <td class="px-2 py-2 font-normal">Cash</td>
                          <td class="px-2 py-2 font-normal">Jonathan Joe</td>
                          <td class="px-2 py-2 font-normal">₱1,210.00</td>
                          <td class="px-2 py-2 font-normal">
                            <p class="bg-[#F50505] px-1 rounded-full text-white text-[10px]">
                                <span>Void</span>
                            </p>
                          </td>
                       </tr>

                      </tbody>
                  </table>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-[45%]  rounded-lg border border-[#173161]/20 shadow-md shadow-blue-950/20 ">
                <div class="w-full flex justify-center bg-[#173161] items-center h-12 rounded-tl-lg rounded-tr-lg text-center shadow-md shadow-blue-950/50">
                   <span class="font-inter font-semibold text-white">Transaction Information</span> 
                </div>
                <div class="border bg-white h-full p-3 rounded-bl-lg rounded-br-lg flex flex-col justify-between gap-3">
                    <div class="flex flex-col justify-center items-center gap-3 h-3/4">
                        <img src="../assets/Paper.png" class="w-32 h-32">
                    <span class="text-center font-inter text-black/50 text-base">Click any Transactions from the table for more information.</span>
                    </div>
                    <div class="flex justify-between gap-1 flex-row w-full"> 
                        <button class="border border-[#B22222] text-sm w-1/2 bg-[#FFDDDD] p-1 px-3 rounded-lg font-inter text-[#B22222] disabled:text-[#B22222]/50" disabled>Void Transaction</button>
                        <button class="border border-[#1A2F58] text-sm w-1/2 bg-[#D4E4FF] p-1 px-3 rounded-lg font-inter text-[#1A2F58] disabled:text-[#1A2F58]/50" disabled>Print Receipt</button>
                    </div>
                </div>
            </div>
        </main>
</body>
</html>
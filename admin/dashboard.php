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
                <span class="text-lg">₱ Placeholder</span>
              </p></div>
                <div class="bg-gradient-to-l from-[#1d448b] to-[#133472] w-[23%] h-24
                rounded-xl border border-[#3B4963] shadow-[0px_5px_10px_rgba(27,45,80,0.4)] p-3">
                <p class="font-inter font-bold text-white">
                <span class="text-[12px] font-semibold">Todays Revenue</span><br>
                <span class="text-lg">₱ Placeholder</span>
              </p></div>
                <div class="bg-gradient-to-l from-[#1d448b] to-[#133472] w-[23%] h-24
                rounded-xl border border-[#3B4963] shadow-[0px_5px_10px_rgba(27,45,80,0.4)]
                flex flex-col p-3 gap-2">
                <div class="font-inter text-white text-[12px] flex flex-row gap-5 items-center">
                  <span class="w-16">Diesel</span>
                  <div class="w-1/2 h-1 bg-white rounded-full">
                    <div class="w-1/2 h-full bg-green-500"></div> 
                  </div>
                  <span>50%</span>
                </div>
                <div class="font-inter text-white text-[12px] flex flex-row gap-5 items-center">
                  <span class="w-16">Premium</span>
                  <div class="w-1/2 h-1 bg-white rounded-full">
                    <div class="w-1/4 h-full bg-[#F5A105]"></div> 
                  </div>
                  <span>18%</span>
                </div>
                <div class="font-inter text-white text-[12px] flex flex-row gap-5 items-center">
                  <span class="w-16">Unleaded</span>
                  <div class="w-1/2 h-1 bg-white rounded-full">
                    <div class="w-[11%] h-full bg-[#F50505]"></div> 
                  </div>
                  <span>10%</span>
                </div>
                </div>
                <div class="bg-[#D4FFE1]  w-[23%] h-24 p-3 items-center justify-between
                rounded-xl border border-[#38AC5B] shadow-[0px_5px_10px_rgba(27,45,80,0.4)] flex flex-row">
                 <p class="flex flex-col h-full gap-1">
                    <span class="font-poppins text-[#5BA070] text-base font-semibold">Low Stock Alerts</span>
                    <span class="font-poppins text-[#53A36C] text-[13px] font-semibold">Placeholder</span>
                 </p>
                <img src="../assets/Check.png" class="w-16 h-16">
                  </div>
            </div>
            <p class="text-[#213B62] font-poppins font-semibold text-[20px] mb-3">Quick Actions</p>
            <div class="flex flex-row justify-between mb-6  ">
              <button class="bg-[#F6F6FB] w-80 h-24 rounded-xl flex flex-row p-3 gap-4 shadow-[0px_4px_4px_rgba(0,0,0,0.25)]">
                  <img src="../assets/Admin/paper1.png" class="w-16 h-16">
                  <p class="flex flex-col text-left">
                  <span class="text-[#213355] font-poppins text-base font-semibold">Generate Report</span>
                  <span class="text-[#213B62] font-poppins text-[12px] font-medium ">
                    View and export sales and inventory reports.</span>
                  </p>
              </button>
              <button class="bg-[#F6F6FB] w-80 h-24 rounded-xl flex flex-row p-3 gap-4 shadow-[0px_4px_4px_rgba(0,0,0,0.25)]">
                  <img src="../assets/Admin/userm.png" class="w-16 h-16">
                  <p class="flex flex-col text-left">
                  <span class="text-[#213355] font-poppins text-base font-semibold">Manage Employees</span>
                  <span class="text-[#213B62] font-poppins text-[12px] font-medium">
                    View and manage employee access and shifts.</span>
                  </p>
              </button>
              <button class="bg-[#F6F6FB] w-80 h-24 rounded-xl flex flex-row p-3 gap-4 shadow-[0px_4px_4px_rgba(0,0,0,0.25)]">
                  <img src="../assets/Admin/megaphone.png" class="w-16 h-16">
                  <p class="flex flex-col text-left">
                  <span class="text-[#213355] font-poppins text-base font-semibold">Make an Announcement</span>
                  <span class="text-[#213B62] font-poppins text-[12px] font-medium">
                    Notify cashiers about important information.</span>
                  </p>
              </button>
            </div>
            <div class="flex flex-row gap-5">
                <div class="shadow-[0px_4px_4px_rgba(0,0,0,0.25)] w-[50%] border border-[#314C82]/20 h-[175px] bg-white rounded-2xl
                p-3 flex flex-col gap-3">
                <div class="flex flex-row justify-between items-center">
                    <p class="font-poppins text-[#213B62] text-base font-semibold">Sales Overview</p>
                    <select class="px-3 py-1 rounded-lg bg-[#314C82] border border-[#314C8  2]/20 text-white focus:outline-[#82A6EF] font-inter font-semibold text-sm w-max">
                      <option value="Today">Today</option>
                      <option value="Yesterday">Yesterday</option>
                      <option value="Last Week">Last Week</option>
                    </select>

                </div>
                <div class=" overflow-x-auto rounded-xl border border-[#314C82]/20">
                    <table class="min-w-full divide-y divide-[#5C749F] divide-opacity-30">
                    <thead class=" sticky top-0 bg-[#F9F8F9] border border-[#314C82]/20 text-[#1A2F58] text-sm font-inter font-semibold tracking-wide shadow-sm ">
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Product</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Units Sold</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Revenue (₱)</th>
                    </thead>
                    <tbody class="text-center font-inter text-[13px] text-[#1A2F58] font-normal py-1">
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
                <div class="shadow-[0px_4px_4px_rgba(0,0,0,0.25)] w-[50%] border border-[#314C82]/20 h-[175px] bg-white rounded-2xl
                p-3 flex flex-col">
                <div class="flex flex-row justify-between items-center mb-3">
                    <p class="font-poppins text-[#213B62] text-base font-semibold">Recent Transactions</p>
                    <button class="px-5 py-1 rounded-lg bg-[#1F3A69] font-inter text-[#F8F8FF] text-sm ">See More</button>

                </div> 
                <div class=" overflow-x-auto rounded-xl border border-[#314C82]/20">
                    <table class="min-w-full divide-y divide-[#5C749F] divide-opacity-30">
                    <thead class=" sticky top-0 bg-[#F9F8F9] border border-[#314C82]/20 text-[#1A2F58] text-sm font-inter font-semibold tracking-wide shadow-sm ">
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">ID</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Date & Time</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Cashier</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-1">Total</th>
                    </thead>
                    <tbody class="text-center font-inter text-[13px] text-[#1A2F58] font-normal py-1">
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
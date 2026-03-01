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
  <title>User Management</title>
  
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
        <a href="transaction.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Transaction.png" class="h-9 w-8">
        <span class="font-medium text-base font-poppins">Transactions</span>
        </a>
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
        <div class="relative bg-[#6284C6]/60 w-full p-6 h-14 flex flex-row items-center
        shadow-[inset_0_4px_4px_rgba(27,45,80,0.5)] gap-4">
          <div class="absolute bg-white w-1 h-full top-0 left-0"></div>
          <img src="../assets/Admin/nav/Users.png" class="h-9 w-8">
          <span class="font-poppins text-white text-base font-medium">User Control</span>
        </div> 
        </div>
        <a href="Transaction.php" class="z-10 flex items-center gap-4  w-full p-4 pl-6 h-14 hover:bg-[#334A78] transition-colors duration-300 text-[#F8F8FF]">
        <img src="../assets/Admin/nav/Settings.png" class="h-7 w-8">
        <span class="font-medium text-base font-poppins">Settings</span>
        </a>

    </nav>  

         <header class="ml-64 w-[82%] h-16 bg-[#E5EAFA] shadow-[0_5px_5px_#B7C2DF] pl-5 pr-5 flex flex-row justify-between items-center">
          <p class=" p-5 font-poppins text-xl text-[#213B62] font-semibold flex flex-row w-full items-center justify-between mr-1">
            <span>User Management</span>
          </p>
          <div class="w-44 h-11 mr-6 items-center justify-center rounded-full border border-black/50 bg-[#EAEEFA] flex flex-row gap-2">
              <button class="flex items-center justify-center"><i class="fa-solid fa-bell text-lg p-1 text-[#1B2D50]"></i></button>
              <div class="border-l w-1 h-[100%] border-[#1A2F58]"></div>
              <button class="flex flex-row gap-2 items-center">
              <span class="font-poppins text-base text-[#1B2D50]">Admin</span>
              <img src="../assets/Profile.jpg" class="w-7 h-7 rounded-full">
              </button>
          </div>
         </header>   

        <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-row gap-2">
          <div class="w-[70%] h-[495px] border border-[#314C82]/20 bg-white shadow-[0_4px_4px_rgba(0,0,0,0.2)]">
            <div class="flex flex-row justify-between items-center p-2">
              <span class="text-base font-inter text-[#213B62] font-semibold">Users</span>
              <div class="flex flex-row gap-3">
                  <form>
                    <div class="relative">
                       <input placeholder="Search..." class="p-1 border border-[#1F3A69]/40 rounded-sm font-inter relative text-[#1F3A69] font-normal text-sm focus:outline-[#1F3A69]" type="text">
                        <i class="fa-solid fa-magnifying-glass absolute right-2 bottom-2 opacity-50"></i>
                   </div> 
                  </form>
                 <button class=" bg-[#1F3A69] rounded-md font-inter text-white font-semibold w-28 p-1 px text-sm">Add new User</button>
                </div>
            </div>
             <div class=" overflow-x-auto h-[90%] border-t border-[#1A2F58]/20">
                    <table class="min-w-full">
                    <thead class=" sticky top-0 bg-[#E9EFFF] text-[#1A2F58] text-sm font-inter font-semibold tracking-wide shadow-sm shadow-[#1A2F58]/20">
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2"></th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Full Name</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Shift</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Status</th>
                        <th class="font-inter text-[13px] text-[#1A2F58] font-semibold py-2">Phone Number</th>
                    </thead>
                    <tbody class="text-center font-inter text-[13px] text-[#1A2F58] font-normal py-1 divide-y divide-[#1A2F58]/30 ">
                      <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#F50505] w-16 py-[1px] rounded-md text-[10px]">Inactive</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#48BA6B] w-16 py-[1px] rounded-md text-[10px]">Active</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-black"><span class="bg-[#DADADA] w-16 py-[1px] rounded-full text-[10px]">Suspended</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                       <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#F50505] w-16 py-[1px] rounded-md text-[10px]">Inactive</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#48BA6B] w-16 py-[1px] rounded-md text-[10px]">Active</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-black"><span class="bg-[#DADADA] w-16 py-[1px] rounded-full text-[10px]">Suspended</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                       <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#F50505] w-16 py-[1px] rounded-md text-[10px]">Inactive</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-white"><span class="bg-[#48BA6B] w-16 py-[1px] rounded-md text-[10px]">Active</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                      <tr class="">
                        <td class="px-1 py-2 text-center"><img src="../assets/Profile.jpg" class="w-8 h-8 inline-block rounded-full"></td>
                        <td class="px-1 py-2">Jonathn Joe</td>
                        <td class="px-1 py-2">11:00AM - 10:00PM</td>
                        <td class="px-1 py-2"><p class="flex flex-row justify-center items-center font-inter text-black"><span class="bg-[#DADADA] w-16 py-[1px] rounded-full text-[10px]">Suspended</span></p></td>
                        <td class="px-1 py-2">+6391279919</td>
                      </tr>
                    </tbody>
                  </table>
                </div>  
          </div>
          <div class="w-[30%] h-[495px] border border-[#314C82]/20 bg-white shadow-[0_4px_4px_rgba(0,0,0,0.2)]">
            <div class="flex flex-row items-center justify-between p-2 mb-1">
              <span class="text-base font-inter text-[#213B62] font-semibold">User Information</span>
              <p class="border border-[#F57878] bg-[#F3D2D8] text-[12px] text-[#FB1717] font-inter font-semibold px-2 rounded-md"><span>Offline</span></p>
              <button><img src="../assets/Admin/Edit.png" class="w-6 h-6"></button>
            </div>
            <div class="w-full border-t border-[#1A2F58]/20"></div>
            <div class="flex flex-col">
              <div class="w-full flex flex-col justify-center items-center mb-5">
                <img src="../assets/Profile.jpg" class="w-36 h-36 rounded-full">
                  <span class="text-xl font-inter text-[#213B62] font-semibold">Jonathan Joe</span>
                  <div class="flex flex-row items-center justify-center gap-2">
                    <div class="w-2 h-2 bg-[#FF8B8B] rounded-full"></div>
                    <span class="text-[11px] text-[#213B62] font-inter font-normal">Active a few hours ago</span>
                  </div>
              </div>
              <div class="flex flex-col pl-7 gap-7 mb-5">
                <div class="flex flex-row gap-3 items-center">
                  <img src="../assets/Admin/User (3).png" class="w-5 h-5">
                  <div class="flex flex-col"> 
                    <span class="font-inter text-[#213B62] font-semibold text-sm">Username :</span>
                    <span class="font-inter text-[#213B62] font-normal text-sm">Jonathan</span>
                  </div>
                </div>
                <div class="flex flex-row gap-3 items-center">
                  <img src="../assets/Admin/Clock (1).png" class="w-5 h-5">
                  <div class="flex flex-col"> 
                    <span class="font-inter text-[#213B62] font-semibold text-sm">
                      Shift <span>(10 Hours)</span>
                    </span>
                    <span class="font-inter text-[#213B62] font-normal text-sm">7:00 AM - 3:00 PM</span>
                  </div>
                </div>
                <div class="flex flex-row gap-3 items-center">
                  <img src="../assets/Admin/Briefcase.png" class="w-5 h-5">
                  <div class="flex flex-col"> 
                    <span class="font-inter text-[#213B62] font-semibold text-sm">Role :</span>
                    <span class="font-inter text-[#213B62] font-normal text-sm">Cashier</span>
                  </div>
                </div>
                
              </div>
              <div class="flex flex-row w-full gap-1 justify-center h-9">
                  <button class="bg-[#1A2F58] rounded-md text-white w-36 text-sm ">
                    Attendance Logs
                  </button>
                   <button class="bg-[#A00000] rounded-md text-[white] w-36 text-sm ">
                    Suspend Account
                  </button>
                </div>
            </div>
          </div>

        </main>
</body>
</html>
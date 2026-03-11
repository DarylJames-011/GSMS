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
  <title>Dashboard</title>
  
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
  <!-- JS Chart-->  

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


<body class="overflow-visible overflow-y-hidden bg-gradient-to-b from-[#FFFFFF] via-[#F8F8FF] to-[#F8F8FF]">
   

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
    
  

    <!--Container-->  
    <div id="modal"
     class="fixed inset-0 z-50 flex items-center justify-center
     opacity-0 invisible pointer-events-none
     transition-opacity duration-300 ease-out">

    
    
  <!-- Overlay: darks background -->
  <div id="overlay"
     class="absolute inset-0 z-40 bg-black/40
     opacity-0 transition-all duration-300 pointer-events-auto"></div>

  <!-- Modal: centered content -->
  <div id="modalContent" class="relative z-50 scale-95 opacity-0 
     transition-all duration-300 ease-out shadow-[0_4px_4px_rgba(0,0,0,0.25)] 
     bg-gradient-to-r from-[#FFFFFF] to-[#F8F8FF] pt-[60px] rounded-lg w-[40%]
     flex flex-row min-w-[40%] min-h-[10rem]">
    <div class="absolute top-0 left-0 bg-gradient-to-r from-[#1B2D50] to-[#35496E] w-full rounded-tl-lg rounded-tr-lg p-3
    font-inter text-[#F8F8FF] text-base font-semibold shadow-[5px_1px_10px_3px_rgba(0,0,0,0.25)]">
    <p id="title" class="pl-3">Log Out</p>
    </div>
    <div class="flex flex-col gap-3 w-full h-10">
       <p id="desc" class="text-base font-inter font-semibold text-[#173161]  pl-5">Are you sure you want to log out?</p>
      <div class="flex flex-row gap-3 justify-end w-[100%] pr-5 mb-5">
        <button id="modalConfirm" class="bg-[#FF7979] w-max px-7 h-10 border border-[#A00000] text-white rounded-md text-inter font-semibold"
        >Yes</button>
        <button id="modalcancel" class="bg-[#1A2F58] w-max px-7 h-10 border border-[#1A2F58] text-white rounded-md text-inter font-semibold">No</button>
      </div>
    </div>
    
  </div>
    </div>
  
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



    <main class="ml-64 p-9 h-auto min-w-[800px] w-[81%] flex flex-row ">
      
        <div class="flex flex-col h-auto w-full">
            <div class="flex flex-row justify-between w-full items-center mb-4">
                    <p class="font-inter font-bold text-[24px] text-[#1F3A69]">Dashboard</p>
                    <div class="flex flex-row">
                        <p class="font-inter font-medium text-base text-[#1B2D50]">Welcome Daryl, start your shift to get started!</p>
                        <div></div>
                    </div>
            </div>
            <div class="flex flex-row">
                <div class="flex flex-col w-full flex-[2] mr-3">
                    <div class="flex flex-row justify-between mb-4 ">
                        <div class="rounded-[20px] bg-gradient-to-b from-[#1B2D50] to-[#35496E]  w-52 h-[80px] shadow-[0_4px_4px_rgba(0,0,0,0.25)] flex flex-col p-3">
                            <p class="text-[#F8F8FF] font-inter font-bold text-[13px] mb-1">Transactions this shift</p>
                            <p class="text-[#F8F8FF] font-inter font-bold text-xl ml-2">134</p>
                        </div>
                        <div class="rounded-[20px] bg-gradient-to-b from-[#1B2D50] to-[#35496E] w-52 h-[80px] shadow-[0_4px_4px_rgba(0,0,0,0.25)] flex flex-col p-3">
                            <p class="text-[#F8F8FF] font-inter font-bold text-[13px] mb-1">Sales this Shift</p>
                            <p class="text-[#F8F8FF] font-inter font-bold text-xl ml-2">12,213</p>
                        </div>
                        <div class="rounded-[20px] bg-gradient-to-b from-[#1B2D50] to-[#35496E] w-52 h-[80px] shadow-[0_4px_4px_rgba(0,0,0,0.25)]  flex flex-col p-3">
                             <p class="text-[#F8F8FF] font-inter font-bold text-[13px]">This Weeks Sales</p>
                            <p class="text-[#F8F8FF] font-inter font-bold text-xl">12,213</p>
                            <p class="text-[#F8F8FF] font-inter font-light text-[10px] ml-1">10%↑ vs last week</p>
                        </div>
                    </div>
                    <p class="text-[#1F3A69] text-2xl font-bold font-inter mb-2">Quick Actions</p>
                    <div class="flex flex-row justify-between gap-6 mb-9">
                        <button class="w-[231px] h-[136px] bg-[#EAF6FF] border border-[#1F3A69] rounded-[26px] shadow-[0_13px_20px_1px_rgba(8,70,190,0.31)]
                        flex flex-row pl-2 items-center">
                            <div class="flex flex-col">
                                <p class="text-base font-inter font-bold text-[#1B2D50] text-left pb-1">Start New Transaction</p>
                                <p class="text-[13px] font-inter font-medium text-[#5675B2] text-left">Start a new transaction <br> to place an order.</p>
                            </div>
                            <i class="fa-solid fa-plus text-6xl text-[#1B2D50] flex-1 pr-3"></i>

                        </button>
                        <button class="w-[238px] h-[136px] bg-[#D4FFE1] border border-[#459A5F] rounded-[10px] shadow-[0_13px_20px_1px_rgba(0,93,29,0.45)]
                        items-center flex flex-row pl-2 justify-between" onclick="alert('Most of your Products are at a sufficient level')">
                            <div class="flex flex-col">
                                <p class="text-base font-inter font-bold text-[#33814B] text-left pb-2">Quick <br> Monitoring</p>
                                <p class="text-[13px] font-inter font-medium text-[#53A36C] text-left">No restock needed <br>at the moment.<br>You’re all set!</p> 
                            </div>
                            <img src="../assets/Check.png" class="pr-5">
                        </button>
                        <button class="w-[228px] h-[136px] bg-[#BDE3FF] border border-[#1E5780] rounded-[10px] shadow-[0_13px_20px_1px_rgba(68,129,172,0.62)]
                        items-center flex flex-row pl-2 justify-between" id="shift_btn">
                            <div class="flex flex-col">
                                <p id="shift_ttl" class="text-base font-inter font-bold text-[#1F3A69] text-left pb-2">Start Shift</p>
                                <p id="shift_des" class="text-[13px] font-inter font-medium text-[#4F6DA1] text-left">start your shift
                                    <br> to get started!</p> 
                            </div>
                            <img src="../assets/Clock.png" class="pr-5" id="clock_img">
                            
                        </button>
                    </div>  
                    <div class="flex flex-row justify-between items-center mb-5">
                        <p class="font-inter font-bold text-2xl text-[#1B2D50]">Recent Transactions</p>
                        <button class="p-2 bg-[#1F3A69] w-28 text-[#F8F8FF] font-inter font-semibold text-base rounded-[10px]">See More</button>
                    </div>
                    <div class=" overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#5C749F] divide-opacity-30">
                        <thead class="bg-[#B7CCF0]">
                            <tr>    
                                <th class="py-2 font-inter font-bold text-base text-[#1B2D50]">Date</th>
                                <th class="py-2 font-inter font-bold text-base text-[#1B2D50]">Product</th>
                                <th class="py-2 font-inter font-bold text-base text-[#1B2D50]">Quantity</th>
                                <th class="py-2 font-inter font-bold text-base text-[#1B2D50]">Price</th>
                                <th class="py-2 font-inter font-bold text-base text-[#1B2D50]">Reciept</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#F8F8FF] divide-y-[.5px] divide-[#5C749F] divide-opacity-30">
                            <tr>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">01/21/24</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]"><p>Lubricant</p></th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Quantity</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Price</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Reciept</th>
                            </tr>
                            <tr>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#CEE0FF] text-[#1B2D50]">Date</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#CEE0FF] text-[#1B2D50]">Product</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#CEE0FF] text-[#1B2D50]">Quantity</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#CEE0FF] text-[#1B2D50]">Price</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#CEE0FF] text-[#1B2D50]">Reciept</th>
                            </tr>
                            <tr>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Date</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Product</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Quantity</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Price</th>
                                <th class="py-[2px] font-inter font-medium text-small bg-[#E5EEFF] text-[#1B2D50]">Reciept</th>
                            </tr>
                        </tbody>
                        </table> 
                </div>
                </div>
                <div class="flex-[1] flex flex-col p-2  rounded-[10px]">
                   <p class="font-inter font-bold text-base text-[#1F3A69] mb-3 text-center">Sales Performance</p>
                   <p class="font-inter font-normal text-sm text-[#6283BD] mb-3 text-center">a small description, a DIVERSION!</p>
                   <div class="bg-white p-1 pl-1 mb-2 rounded-lg shadow-[0_4px_10px_1px_rgba(0,0,0,0.25)]">
                   <p class="text-left text-[#1F3A69] font-semibold font-inter text-sm p-1">Weekly sales vs Last week</p>
                   <div class="h-44 w-64">
                    <canvas id="line"></canvas>
                   </div>
                    </div>
                    <div class="bg-white p-1 pl-1 rounded-lg shadow-[0_4px_10px_1px_rgba(0,0,0,0.25)]">
                    <p class="text-left text-[#1F3A69] font-semibold font-inter text-sm p-1">Product trends this week</p>
                   <div class="h-44 w-64">
                    <canvas id="trends"></canvas>
                   </div>
                </div>
                </div> 
            </div>
            
        </div>
    </main>


</body>
</html>
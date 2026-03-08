<?php
require_once 'config/db.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Administrator') {
        header("Location: admin/dashboard.php");
        exit;
    } else {
        header("Location: cashier/dashboard1.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    header('Content-Type: application/json');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_ID, role, password FROM user_table WHERE username = ?");

    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "type" => "database_error"
        ]);
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // User not found
    if ($result->num_rows === 0) {
        echo json_encode([
            "status" => "error",
            "type" => "user_not_found"
        ]);
        exit;
    }

    $user = $result->fetch_assoc();

    if ($password !== $user['password']) {
        echo json_encode([
            "status" => "error",
            "type" => "wrong_password"
        ]);
        exit;
    }

    $_SESSION['user_id'] = $user['user_ID'];
    $_SESSION['role'] = $user['role'];

    $redirect = ($user['role'] === 'Administrator')
        ? "admin/dashboard.php"
        : "cashier/dashboard1.php";

    echo json_encode([
        "status" => "success",
        "redirect" => $redirect
    ]);

    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gas Station Dashboard</title>
  
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="dist/output.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->

</head>

<script>


document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("loginForm");
    const errorBox = document.getElementById("errormsg");
    const usernameInput = document.getElementById("usern");
    const passwordInput = document.getElementById("passw");
    if (!form) return; // safety check


        function removeBgTint() {
        passwordInput.style.backgroundColor = "";
        usernameInput.style.backgroundColor = "";
        }


            usernameInput.addEventListener("input", removeBgTint);
            passwordInput.addEventListener("input", removeBgTint);

    form.addEventListener("submit", function(e) {
        e.preventDefault();


        let formData = new FormData(this);

        fetch("", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {

            if (data.status === "success") {
                window.location.href = data.redirect;
                return;
            }

            // Set error message
            switch (data.type) {
                case "user_not_found":
                    errorBox.textContent = "User does not exist.";
                    break;

                case "wrong_password":
                    errorBox.textContent = "Incorrect username or password.";
                    usernameInput.style.backgroundColor = "#fee2e2"; // soft red
                    passwordInput.style.backgroundColor = "#fee2e2"; // soft red
                    break;

                case "database_error":
                    errorBox.textContent = "System error. Please try again later.";
                    break;

                default:
                    errorBox.textContent = "Unexpected error.";
            }

            // Fade in
            errorBox.classList.remove("opacity-0");
            errorBox.classList.add("opacity-100");


        })
        .catch(error => {
            console.error("Fetch error:", error);
        });
    });

});
</script>

<body class="bg-[FEFFFA] flex justify-between h-screen font-inter">
    <div class="font-bold  flex-1 flex justify-center items-center p-9">
        <div class="flex h-[96%] w-[90%] flex-col gap-7">
            <img src="assets/GSMS1.png" class="w-[290px] h-auto">
            <div class="w-full max-w-[500px] min-w-[360px] font-poppins">
                    <p class="font-semibold text-3xl text-blue-950">LOGIN</p>
                    <p class="font-poppins font-light text-[14px] text-[#737373]">Please Enter your Details.</p>
                    <br>
                <form class="flex flex-col font-poppins mb-10" id="loginForm">
                    <label class="font-medium text-sm mb-1 text-[#3B3B3B]">Username</label>
                    <input id="usern" type="text" name="username" class="w-[80%] 
                    h-1/4 text-[16px] font-inter font-normal p-2 border border-1 border-black rounded-[3px] mb-3 transition-colors duration-300"
                    autocomplete="off">
                   
                    <label class="font-medium text-sm mb-1 text-[#3B3B3B]">Password</label>
                    <input id="passw" type="password" name="password" autocomplete="new-password" class="w-[80%] 
                    h-1/4 text-[16px] font-inter font-normal p-2 border border-1 border-black rounded-[3px] mb-3 transition-all duration-300" >
                
                    <div class="flex flex-row justify-between w-[80%] mb-3 items-center">
                        <div id="errormsg"
                            class="font-inter font-medium text-sm text-[#B22222] opacity-0 transition-opacity duration-300">
                            Incorrect Username or Password
                        </div>
                       <a class="w-max h-max font-medium text-sm text-[#1B2D50]" href="#"><u>Forget Password?</u></a>
                    </div>
                    
                    <button class="border p-3 font-semibold bg-[#1B2D50] text-center rounded-[3px] text-[#FEFFFA] w-[81%]" type="submit">LOGIN</button>
                </form>
                    <p class= "text-[#636363] text-sm font-light"><i>To create an account, reach out to the administrator</i></p>
            </div>
        </div>
    </div>
<div class="font-bold flex-[1.1] flex justify-center items-center">
    <div class="bg-blue-950 w-[95%] h-[95%] text-white text-2xl text-center rounded-[10px]">placeholder</div>
    
</div>  
</body>
</html>
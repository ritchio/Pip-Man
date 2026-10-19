<?php
session_start();

if(!isset($_SESSION['authenticated'])){
    header('Location: login.php');
    exit;
}else{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <script src="js/request.js"></script>
    <script src="js/divUtils.js"></script>
    <script src="js/site.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <title>Dashboard</title>
</head>
<body>
  <div class="wrapper" id="layoutWrapper">
    <aside id="sidebar" class="sidebar">

        <div class="sidebar-header">
            <button class="toggle-btn" id="toggleBtn">⏴</button>
                
            </div>
        <ul class="vertical-menu">
            <?php
            foreach($_SESSION["permissions"] as $module_name => $module_info){
                echo '<li><a href="'.$module_info['url'].'" target="content">'.$module_name.'</a></li>';
            }
            ?>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="content-wrapper">
    <div id="content" class="content">

</div>
</main>
</div>
<div id="modalFade" class="modal-fade">
    <div class="modal-wrapper">
        <div id="modal" class="modal">
        </div>
        <button class="modal-close-button" onclick="closeModal()">X</button>
    </div>
</div>
<div id="loading" class="loading">
    <p>Loading...</p>
</div>
</body>
    <script src="js/site.js"></script>

<script>
        getContent({url:"home/index",target:"content"});

        const toggleBtn = document.getElementById('toggleBtn');
        const layoutWrapper = document.getElementById('layoutWrapper');

        toggleBtn.addEventListener('click', () => {

            layoutWrapper.classList.toggle('collapsed');
            
            if(layoutWrapper.classList.contains('collapsed')) {
                toggleBtn.innerText = '⏵';
            } else {
                toggleBtn.innerText = '⏴';
            }
        });
    </script>

</html>
    <?php
}
 

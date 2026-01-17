<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login/Register Form</title>
</head>
<body class="LoginRegisterPage" style="height:100vh;">
    <div class="container">
        <div class="wrapper" style="padding-bottom: 20px;">
            <div>
                <img src="uploads\logoFiniOglasi.png" alt="Fini oglasi" style = "width: 720px; height: 150px;">
                <p class="tekstStart">
                    Dobrodošli na najbrže rastući oglasni prostor u Srbiji !
                </p>
                <button type ="button" class ="startPageBttn" onclick="location.href = 'loginPage.php';">Ulogujte se</button>
                <button type ="button" class ="startPageBttn" onclick="location.href = 'registerPage.php';">Registrujte se</button>
            </div>
            <button type ="button" class ="startPageBttn" onclick="location.href = 'guest.php';" style="width: 88%;">Pristupite kao gost</button>
        </div>
    </div>
</body>
</html>
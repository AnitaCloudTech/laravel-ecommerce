<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login Form</title>
</head>
<body class="LoginRegisterPage" style="height:100vh;">
    <div class="container" style = "background: rgba(119, 122, 121, 1)">
        <div class="wrapper">
            <div class="loginRegisterRedirect">
                <p class="loginRegisterRedirectText">Nemaš nalog?</p><button class ="loginRegisterRedirectButton" onclick="location.href = 'registerPage.php';">Registruj se</button>
            </div>
            
            <form action="login.php" method="post" class="login-form">
                <input type="text" id="loginUsername" name="username" required placeholder="Ime naloga" class="inputLogRes"><br>

                <input type="password" id="loginPassword" name="password" required placeholder="Šifra" class="inputLogRes"><br>

                <input class="startPageBttn" type="submit" value="Uloguj se" name="login">
                <input class="startPageBttn" type="reset" value="Reset">
            </form>
            <button type ="button" class ="startPageBttn" onclick="location.href = 'index.php';" style="width: 88%;">Vrati se</button>

            <script src="script.js"></script>
        </div>
    </div>
</body>
</html>
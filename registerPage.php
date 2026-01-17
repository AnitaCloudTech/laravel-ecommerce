<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register Form</title>
</head>
<body class="LoginRegisterPage" style="height:100vh;">
    <div class="container" style = "background: rgba(119, 122, 121, 1)">
        <div class="wrapper">
            <div class="loginRegisterRedirect">
                <p class="loginRegisterRedirectText">Već imaš nalog?</p><button class ="loginRegisterRedirectButton" onclick="location.href = 'loginPage.php';">Uloguj se</button>
            </div>

            <form action="register.php" method="post" class="register-form">
                
                <input type="text" id="username" name="username" required placeholder="Ime naloga" class="inputLogRes"><br>
                <input type="text" id="firstname" name="firstname" required placeholder="Ime" class="inputLogRes"><br>
                <input type="text" id="lastname" name="lastname" required placeholder="Prezime" class="inputLogRes"><br>
                <input type="email" id="email" name="email" required placeholder="Email" class="inputLogRes"><br>
                <input type="password" id="password" name="password" required placeholder="Šifra" class="inputLogRes"><br>

                <input type="radio" id="user" name="user_type" value="user" checked hidden>
                <input type="radio" id="artist" name="user_type" value="artist" hidden>
                <input type="radio" id="admin" name="user_type" value="admin" hidden>

                <input class="startPageBttn" type="submit" value="Registracija" name="register">
                <input class="startPageBttn" type="reset" value="Reset">
            </form>

            <button type ="button" class ="startPageBttn" onclick="location.href = 'index.php';" style="width: 88%;">Vrati se</button>

            <script src="script.js"></script>
        </div>
    </div>
</body>
</html>
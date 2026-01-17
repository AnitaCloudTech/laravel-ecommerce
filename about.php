<?php
session_start();
require_once "database.php";

$db = new DB;

if(!$_SESSION){ 
    echo 'Nisi ulogovan !'; 
    header("Location: guest.php"); 
} 
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O nama</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cardsWrapper {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .teamCard {
            background-color: rgba(165, 191, 221, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: #e0e0e0;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
        }

        .teamCard img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .teamCard h3 {
            color: #4a76a8;
            margin-bottom: 10px;
        }

        .teamCard p {
            font-size: 14px;
            line-height: 1.4;
        }
    </style>
</head>
<body class="LoginRegisterPage">
    <div class="navbarWrapper">
        <nav class="navbar">
            <!-- Left side (static links) -->
            <div class="navbarLeft">
                <a class="navItem" href="pocetna.php">Početna</a>
                <a class="navItem" href="about.php">O nama</a>
                <a class="navItem" href="contact.php">Kontakt</a>
            </div>

            <!-- Right side (user + actions) -->
            <div class="navbarRight">
                    <?php
                    if($_SESSION["user_type"] != "guest"){
                        $user = $db->getUserByID($_SESSION['user_id']);

                        if ($user && isset($user['user_id'])) {
                            $userID = $user['user_id'];
                            echo "<li class='navItemWrapper'>Zdravo, " . $_SESSION['firstname'] . "</li>";
                                            
                            if ($user['role'] == "user") {
                                echo "<a class='navItem' href='create_oglas.php'>Kreiraj oglas</a></li>";
                                echo "<a class='navItem' href='request_category.php'>Zatraži novu kategoriju</a></li>";
                                echo "<a class='navItem' href='profile.php'>Profil</a></li>";
                            } else if ($user['role'] == "admin") {
                                echo "<a class='navItem' href='profile.php'>Profil</a></li>";
                                echo "<a class='navItem' href='kontrolnipanel.php'>Kontrolni Panel</a></li>";
                            }
                        } else {
                            echo "<li class='navItemWrapper'>Greška pri dohvatanju korisnika</li>";
                        }
                    }
                    ?>
                    <li class="navItemWrapper"><a class="navItem" href="logout.php?logout">Izlogujte se</a></li>
            </div>
        </nav>
    </div>

    <div class="container" style="width:75%;">
        <div class="wrapper" style="margin-top:65px; width: 93%;">
            <h1 style="color: #4a76a8; margin-bottom: 15px;">O nama</h1>
            <div style="margin: 20px 0; line-height: 1.6; color: #e0e0e0;">
                <p>Dobrodošli na našu platformu, gde se inovacija susreće sa kvalitetom. Posvećeni smo tome da obezbedimo najbolje iskustvo za naše korisnike kroz modernu tehnologiju i pažljiv dizajn.</p>
                
                <p>Osnovani sa strašću za stvaranje značajnih veza, naša misija je da spojimo ljude i približimo ih jedne drugima kroz intuitivan i jednostavan interfejs.</p>
                
                <div style="margin: 25px 0; padding: 20px; border-radius: 10px;">
                    <h1 style="color: #4a76a8; margin-bottom: 15px;">Naš tim</h1>
                    <p>Sastavljen od strastvenih programera, dizajnera i stručnjaka za korisničku podršku, koji neumorno rade kako bi obezbedili vaše zadovoljstvo.</p>
                    
                    <!-- Cards section -->
                    <div class="cardsWrapper">
                        <div class="teamCard">
                            <img src="uploads\94371789.jfif" alt="Član tima">
                            <h3>Ognjen Obradović</h3>
                            <p>Frontend developer, zadužen za dizajn i korisnički interfejs.</p>
                        </div>
                        <div class="teamCard">
                            <img src="uploads\62123288.jfif" alt="Član tima">
                            <h3>Aleksandar Djokić</h3>
                            <p>Backend developer, specijalista za baze podataka i sigurnost.</p>
                        </div>
                        <div class="teamCard">
                            <img src="uploads\103903116.gif" alt="Član tima">
                            <h3>Mihajlo Spasić</h3>
                            <p>UI/UX dizajner, brine da aplikacija bude intuitivna i moderna.</p>
                        </div>
                        <div class="teamCard">
                            <img src="uploads\136701157.png" alt="Član tima">
                            <h3>Arsenije Jokić</h3>
                            <p>Menadžer projekta, povezuje tim i vodi komunikaciju sa klijentima.</p>
                        </div>
                        <div class="teamCard">
                            <img src="uploads\121518012.jfif" alt="Član tima">
                            <h3>Janko Jakovljević</h3>
                            <p>DevOps inženjer, zadužen za servere i neprekidno funkcionisanje sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="startPageBttn" onclick="location.href = 'pocetna.php';" style="width: 88%;">Nazad</button>
        </div>
    </div>
</body>
</html>
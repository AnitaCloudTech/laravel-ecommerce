<?php
session_start();


if(!$_SESSION){ echo "Nisi ulogovan !"; } else {
    require_once "database.php";
    $db = new DB;

    $dataNeeded = $_GET['id'];
    $split = explode('-', $dataNeeded);

    $oglasavacID = $split[0];
    $oglasID = $split[1];

    $queryCheckLike = "SELECT * FROM user_likes WHERE oglas_id = ? AND user_id = ?";
                    $stmt = $db->db->prepare($queryCheckLike);
                    $stmt->bind_param("ii", $oglasID, $_SESSION['user_id']);
                    $stmt->execute();
                    $resultLIKE = $stmt->get_result();

    $queryOglasi = "SELECT * FROM oglasi WHERE oglas_id =  $oglasID";
    $getOglasi = $db->db->query($queryOglasi);
    if($getOglasi->num_rows){
        $oglas = $getOglasi->fetch_assoc();

        if($oglas['user_id'] != $oglasavacID){
            echo "Dobar pokusaj -> Ovaj artist nije autor !";
            exit();
        } else {
            $queryOglasavac = "SELECT * FROM users WHERE user_id = $oglasavacID";
            $getOglasavac = $db->db->query($queryOglasavac);

            if($getOglasavac->num_rows){
                $oglasavac = $getOglasavac->fetch_assoc();

                $title = $oglas['title'] . " By " . $oglasavac['firstname'] . " " . $oglasavac['lastname'];

                $updateVisits = "UPDATE oglasi SET visits = visits + 1 WHERE oglas_id = $oglasID";
                $db->db->query($updateVisits);

                if (isset($_POST['likeSubmit'])) {
                    if ($resultLIKE->num_rows > 0) {
                        $queryDeleteLike = "DELETE FROM user_likes WHERE oglas_id = ? AND user_id = ?";
                        $stmt = $db->db->prepare($queryDeleteLike);
                        $stmt->bind_param("ii", $oglasID, $_SESSION['user_id']);
                        $deleteLikeRes = $stmt->execute();
                
                        if (!$deleteLikeRes) {
                            echo "Error: " . $db->db->error;
                        } else {
                            header("Refresh:0");
                        }
                    } else {
                        $queryLike = "INSERT INTO user_likes(user_id, oglas_id) VALUES (?, ?)";
                        $stmt = $db->db->prepare($queryLike);
                        $stmt->bind_param("ii", $_SESSION['user_id'], $oglasID);
                        $insertLikeRes = $stmt->execute();
                
                        if (!$insertLikeRes) {
                            echo "Error: " . $db->db->error;
                        } else {
                            header("Refresh:0");
                        }
                    }
                }                
            } else {
                echo "Dobar pokusaj -> Artist ovog ID-a ne postoji";
                exit();
            }
        }
        
    } else {
        echo "Dobar pokusaj -> Artwork ID ne postoji !";
        exit();
    }

    if(isset($_POST['commentSubmit'])){
        $comment = $db->db->real_escape_string($_POST['comment']);

        $query = "INSERT INTO comments(user_id, oglas_id, comment) VALUES ({$_SESSION['user_id']}, $oglasID, '$comment')";
        $result = $db->db->query($query);

        if(!$result) {
            echo "Error: " . $db->db->error;
        } else {
            header("Refresh:0");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $title; ?> </title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript">
        function posaljideletekoment(button) {
            var pozicijadugme = button.parentNode;
            var identitet = pozicijadugme.querySelector('p.identifikator');
            var praviidentitet = identitet.textContent;

            var oglasavacID = pozicijadugme.querySelector('p.identifikatorOglasavacID');
            var oglasavac = oglasavacID.textContent;
            var oglasID = pozicijadugme.querySelector('p.identifikatorOglasID');
            var oglas = oglasID.textContent;

            var form = document.createElement("form");
            form.method = "post";
            form.action = "deleteComment.php";

            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "identifikacija2";
            input.value = praviidentitet;

            var input2 = document.createElement("input");
            input2.type = "hidden";
            input2.name = "oglasavac";
            input2.value = oglasavac;

            var input3 = document.createElement("input");
            input3.type = "hidden";
            input3.name = "oglas";
            input3.value = oglas;

            var input4 = document.createElement("input");
            input4.type = "hidden";
            input4.name = "location";
            input4.value = "inspect_oglas.php?id=" + oglasavac + "-" + oglas;

            form.appendChild(input);
            form.appendChild(input2);
            form.appendChild(input3);
            form.appendChild(input4);

            document.body.appendChild(form);
            form.submit();
        }

        function posaljidelete() {
        var oglasavacID = "<?php echo $oglasavacID; ?>";
        var oglasID = "<?php echo $oglasID; ?>";

        var form = document.createElement("form");
        form.method = "post";
        form.action = "deleteOglas.php";

        var inputOglasavac = document.createElement("input");
        inputOglasavac.type = "hidden";
        inputOglasavac.name = "oglasavacID";
        inputOglasavac.value = oglasavacID;

        var inputOglas = document.createElement("input");
        inputOglas.type = "hidden";
        inputOglas.name = "oglasID";
        inputOglas.value = oglasID;

        form.appendChild(inputOglasavac);
        form.appendChild(inputOglas);

        document.body.appendChild(form);
        form.submit();
    }
    </script>
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
                if ($_SESSION["user_type"] != "guest") {
                    $user = $db->getUserByID($_SESSION['user_id']);

                    if ($user && isset($user['user_id'])) {
                        $userID = $user['user_id'];
                        echo "<span class='navItemWrapper'>Zdravo, " . $_SESSION['firstname'] . "</span>";

                        if ($user['role'] == "user") {
                            echo "<a class='navItem' href='create_oglas.php'>Kreiraj oglas</a>";
                            echo "<a class='navItem' href='request_category.php'>Zatraži novu kategoriju</a>";
                            echo "<a class='navItem' href='profile.php'>Profil</a>";
                        } else if ($user['role'] == "admin") {
                            echo "<a class='navItem' href='profile.php'>Profil</a>";
                            echo "<a class='navItem' href='kontrolnipanel.php'>Kontrolni Panel</a>";
                        }
                    } else {
                        echo "<span class='navItemWrapper'>Greška pri dohvatanju korisnika</span>";
                    }
                }
                ?>
                <a class="navItem" href="logout.php?logout">Izlogujte se</a>
            </div>
        </nav>
    </div>

    <div class="container" style="width:75%">
        <div class="wrapper" style="margin-top:65px; width: 93%;">
            <h2>Title: <?php echo $oglas['title']; ?></h2>
            <h3>Author: <?php echo $oglasavac['firstname'] . " " . $oglasavac['lastname']; ?></h3>
            <img src="<?php echo $oglas['image_url']; ?>" alt="Oglas Image" style="max-width: 80%; max-height: 80%" class="imageCard"><br>

            <?php
            if ($_SESSION['user_type'] != "guest") {
                if ($_SESSION['user_type'] === "admin" || $_SESSION['user_id'] == $oglas['user_id']) {
                    echo "<button type='button' class='adminPanelBttn' onclick='posaljidelete()'>Obriši oglas</button>";
                }
            }
            ?>

            <div class="adminWrapper">
                <div class = "adminLeft">
                    <h3>Opis: <?php echo $oglas['description']; ?></h3>
                </div>
            </div>

            <div class="adminWrapper">
                <div class = "adminLeft">
                    <h3>Kategorija: 
                        <?php
                        $categoryNameQuery = "SELECT * FROM categories WHERE category_id = {$oglas['category_id']}";
                        $categoryNameQueryRes = $db->db->query($categoryNameQuery);
                        $category = $categoryNameQueryRes->fetch_assoc();
                        echo $category['category_name'];
                        ?>
                    </h3>
                    <h3>Na prodaju: <?php echo $oglas['cost']; ?> €</h3>
                </div>

                <div class = "adminRight">
                    <form method="get" action="sellersinfo.php">
                        <input type="hidden" name="oglasavacID" value="<?php echo $oglasavacID; ?>">
                        <button type="submit" class="adminPanelBttn">Informacije</button>
                    </form>
                </div>
            </div>

            <div class="adminWrapper">
                <h4>Lajkovi: 
                    <?php 
                    $likesCountQuery = "SELECT COUNT(*) as likes_count FROM user_likes WHERE oglas_id = $oglasID";
                    $likesResult = $db->db->query($likesCountQuery);
                    $likes = $likesResult ? $likesResult->fetch_assoc()['likes_count'] : 0;
                    echo $likes;
                    ?>
                </h4>

                <?php if ($_SESSION['user_type'] != 'guest' && $_SESSION['user_id'] != $oglasavacID) { ?>
                    <form method="post" action="">
                        <button type="submit" name="likeSubmit" class="adminPanelBttn">
                            <?php echo ($resultLIKE->num_rows > 0) ? "Dislike" : "Like"; ?>
                        </button>
                    </form>
                <?php } ?>
            </div>

            <?php if ($_SESSION['user_type'] != 'guest') { ?>
                <div class="container">
                    <form method="post" action="">
                        <h3>Ostavi komentar</h3>
                        <textarea name="comment" id="comment" rows="4" required placeholder="Komentar" class="inputComment"></textarea><br>
                        <button type="submit" name="commentSubmit" class="adminPanelBttn">Postavi</button>
                    </form>
                </div>
            <?php } ?>

            <div class="container" style = "float: left;">
                <?php
                $oglasID = $oglas['oglas_id'];
                $queryComments = "SELECT * FROM comments WHERE oglas_id = $oglasID";
                $resultComments = $db->db->query($queryComments);

                if ($resultComments->num_rows) {
                    while ($comment = $resultComments->fetch_assoc()) {
                        $commentPosterID = $comment['user_id'];
                        $commentPosterQuery = "SELECT * FROM users WHERE user_id = $commentPosterID";
                        $commentPosterResult = $db->db->query($commentPosterQuery);

                        if ($commentPosterResult->num_rows) {
                            $commentPoster = $commentPosterResult->fetch_assoc();
                            $commentPosterName = $commentPoster['firstname'] . " " . $commentPoster['lastname'];
                            ?>
                            <div class="container">
                                <div class="wrapper" style="width: 720px; margin-top: 10px; border: 1px solid black; box-shadow: none; background-color: inherit;">
                                    <h5>Posted by: <?php echo $commentPosterName . " - " . $commentPoster['username']; ?></h5>
                                    <p class="tekstStart"><?php echo $comment['comment']; ?></p>
                                    <?php
                                    if ($_SESSION['user_type'] === "admin" || $_SESSION['user_id'] === $comment['user_id']) {
                                        echo "<p class='identifikator' hidden>".$comment['comment_id']."</p>";
                                        echo "<p class='identifikatorOglasavacID' hidden>".$oglasavacID."</p>";
                                        echo "<p class='identifikatorOglasID' hidden>".$oglasID."</p>";
                                        echo "<button type='button' class='adminPanelBttn' onclick='posaljideletekoment(this)'>Obriši komentar</button>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo "<h4>Nema komentara</h4>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
<script type="text/javascript">
    function posaljideletekoment(button) {
        var pozicijadugme = button.parentNode;
        var identitet = pozicijadugme.querySelector('p.identifikator');
        var praviidentitet = identitet.textContent;

        var oglasavacID = pozicijadugme.querySelector('p.identifikatorOglasavacID');
        var oglasavac = oglasavacID.textContent;
        var oglasID = pozicijadugme.querySelector('p.identifikatorOglasID');
        var oglas = oglasID.textContent;

        var form = document.createElement("form");
        form.method = "post";
        form.action = "deleteComment.php";

        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "identifikacija2";
        input.value = praviidentitet;

        var input2 = document.createElement("input");
        input2.type = "hidden";
        input2.name = "oglasavac";
        input2.value = oglasavac;

        var input3 = document.createElement("input");
        input3.type = "hidden";
        input3.name = "oglas";
        input3.value = oglas;

        var input3 = document.createElement("input");
        input3.type = "hidden";
        input3.name = "location";
        input3.value = "inspect_oglas.php?id=".<?php echo $oglasavacID; ?>."-".<?php echo $oglasID; ?>;

        form.appendChild(input);
        form.appendChild(input2);
        form.appendChild(input3);


        document.body.appendChild(form);
        form.submit();
    }

    function posaljidelete() {
        var oglasavacID = "<?php echo $oglasavacID; ?>";
        var oglasID = "<?php echo $oglasID; ?>";

        var form = document.createElement("form");
        form.method = "post";
        form.action = "deleteOglas.php";

        var inputOglasavac = document.createElement("input");
        inputOglasavac.type = "hidden";
        inputOglasavac.name = "artistID";
        inputOglasavac.value = artistID;

        var inputOglas = document.createElement("input");
        inputOglas.type = "hidden";
        inputOglas.name = "oglasID";
        inputOglas.value = oglasID;

        form.appendChild(inputOglasavac);
        form.appendChild(inputOglas);

        document.body.appendChild(form);
        form.submit();
    }
</script>

<?php
}
?>
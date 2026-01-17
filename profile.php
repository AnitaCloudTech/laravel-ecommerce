<?php
session_start();
require_once "database.php";

$db = new DB;

if(!$_SESSION){ 
    echo 'Nisi ulogovan !'; 
    header("Location: guest.php"); 
    exit();
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProdajemKupujem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="LoginRegisterPage">

    <!-- NAVBAR -->
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

    <div class="container" style="width:75%">
        <div class="wrapper" style="margin-top:65px; width: 93%;">
            <div class="search-filter-container">
                <form action="pocetna.php" method="GET">
                    <input type="text" name="search" class="inputLogRes" style="width:65%; margin-right:10px" placeholder="Pretraži oglase po nazivu ili po oglašivaču">
                    
                    <select name="category" class="inputLogRes" style="width:15%;">
                        <option value="">Sve kategorije</option>
                        <?php
                        $queryCategory = "SELECT * FROM categories";
                        $queryCategoryRes = $db->db->query($queryCategory);
                        if($queryCategoryRes->num_rows > 0){
                            while($row = $queryCategoryRes->fetch_assoc()){
                                echo "<option value='{$row['category_id']}'>{$row['category_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                    
                    <button type="submit" class="startPageBttn" style="width:15%; padding: 10px 10px;">Pretraži</button>
                </form>
            </div>
        </div>

            <!-- OGLASI -->
            <?php
            // Pagination setup
            $limit = 5;  
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            $query = "SELECT * FROM oglasi";
            $whereExists = 0;

            // Search filter
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = $db->db->real_escape_string($_GET['search']);
                $query .= $whereExists ? " AND " : " WHERE ";
                $query .= "(title LIKE '%$searchTerm%' OR user_id IN (SELECT user_id FROM users WHERE CONCAT(firstname, ' ', lastname) LIKE '%$searchTerm%'))";
                $whereExists = 1;
            }

            // Category filter
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $category = $db->db->real_escape_string($_GET['category']);
                $query .= $whereExists ? " AND " : " WHERE ";
                $query .= "category_id = $category";
                $whereExists = 1;
            }

            // Pagination setup
            $limit = 5;  
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Base query (only current user's oglasi)
            $currentUserID = (int) $_SESSION['user_id'];
            $query = "SELECT * FROM oglasi WHERE user_id = $currentUserID";
            $whereExists = 1; // because WHERE already exists

            // Search filter
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = $db->db->real_escape_string($_GET['search']);
                $query .= " AND (title LIKE '%$searchTerm%')";
            }

            // Category filter
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $category = $db->db->real_escape_string($_GET['category']);
                $query .= " AND category_id = $category";
            }

            // Pagination count
            $countQuery = str_replace("SELECT *", "SELECT COUNT(*) as total", $query);
            $countRes = $db->db->query($countQuery);
            $totalRows = $countRes->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $limit);

            // Actual fetch
            $query .= " LIMIT $limit OFFSET $offset";
            $resultOglasQuery = $db->db->query($query);


            if($resultOglasQuery->num_rows > 0){
                echo '<div class="wrapperOglasi">';
                while($oglas = $resultOglasQuery->fetch_assoc()){
                    $title = $oglas["title"];
                    $description = $oglas["description"];
                    $authorID = $oglas["user_id"];
                    $author = $db->getUserByID($authorID);
                    $authorName = $author["firstname"]. " " .$author["lastname"];
                    $imageURL = $oglas["image_url"];
                    $redirekcija = "inspect_oglas.php?id=".$authorID."-".$oglas['oglas_id'];
                    ?>
                    
                    <div class="oglasCard">
                        <div class="oglasCardContent">
                            <div class="oglasLeft">
                                <div class="oglasImage">
                                    <img src="<?php echo $imageURL ?>" alt="<?php echo $title; ?>">
                                </div>
                                <div class="oglasInfo">
                                    <h2><?php echo $title; ?></h2>
                                    <h4><?php echo $description; ?></h4>
                                </div>
                            </div>
                            <div class="oglasRight">
                                <div class="oglasAuthor">
                                    <h3><?php echo $authorName; ?></h3>
                                </div>
                                <button type="button" onclick="location.href = '<?php echo $redirekcija; ?>';" class="oglasButton">
                                    <?php echo ($_SESSION["user_type"] == 'admin') ? "Admin akcije" : "Detaljnije"; ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                echo '</div>'; // wrapperOglasi
            } else {
                echo '<div class="wrapper" style="margin-top:70px; box-shadow:0 0px 20px 0 rgba(0,0,0,0.30);"> 
                        <h1>Nema kreiranih oglasa.</h1> 
                    </div>';
            }

            // Pagination links
            if ($totalPages > 1) {
                echo '<div class="pagination">';
                for ($i = 1; $i <= $totalPages; $i++) {
                    $active = ($i == $page) ? "style='font-weight:bold; text-decoration:underline;'" : "";
                    echo "<a href='pocetna.php?page=$i' $active>$i</a> ";
                }
                echo '</div>';
            }
            ?>
    </div>
</body>
</html>

<?php } ?>
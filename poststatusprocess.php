<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" media="screen" href="style.css">
        <title>Post Status</title>
    </head>
    <body>
<!--//////////NAVIGATION BAR//////////-->
        <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html"><img src="images/logo4.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#responsiveNavlist">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse responsiveNavCollapse" id="responsiveNavlist">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">Home</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="poststatusform.php">Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="searchstatusform.html">Search</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.html">About</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
<!--//////////END NAVIGATION BAR//////////-->

    <main>
<!--//////////PHP//////////-->
        <?php
            //CONNECTING TO DATABASE
            require_once ("/home/ysh3325/conf/settings.php");
            $conn = mysqli_connect($host, $user, $pass, $dbnm);
                
            //CHECKING TO SEE IF DATABASE ALREADY EXISTS
            $create = "CREATE TABLE IF NOT EXISTS socialNetwork (scode VARCHAR(5) NOT NULL PRIMARY KEY, userstatus VARCHAR(255) NOT NULL, share VARCHAR(20), postdate DATE NOT NULL, permission VARCHAR(255))";
            $result = mysqli_query($conn, $create);

            //VARIABLES FROM POST FORM
            $sCode = $_POST["sCode"];
            $status = $_POST["status"];
            $share = $_POST["share"];
            $date = $_POST["date"];
            $permission = $_POST["permission"];

            //GETTING PERMISSION ARRAY INTO STRING AND FORMATTING
            for($i = 0; $i < count($permission); $i++){
                if($i < (count($permission)) - 1){
                    $permissionString = $permissionString . $permission[$i] . ", ";
                }
                else{
                    $permissionString = $permissionString . $permission[$i] . ".";
                }
            }

            //CHECK STATUS CONFORMS TO FORMAT "S0000"
            if(!substr($sCode, 0, -4) == "S"){
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Status code doesnt start with capital S</p>";
                echo "<a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
                return;
            }

            if(!is_numeric(substr($sCode, 1))){
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Status code doesnt contain 4 numbers after the S</p>";
                echo "<a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
                return;
            }

            if(strlen($sCode) != 5){
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Status code is not equal to 5 characters long</p>";
                echo "<a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
                return;
            }

            $sql = "select scode from socialNetwork where scode like '%$sCode%'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_affected_rows($conn) > 0){
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Status code already in database!</p>";
                echo "<a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
                return;
            }

            //CHECK STATUS NOT NULL
            if($status == NULL || $status == ""){
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Status empty or null!</p>";
                echo "<br><a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
                return;
            } 
            
            $dateExplode = explode('-', $date);
            if(!checkdate($dateExplode[1], $dateExplode[2], $dateExplode[0])){
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Date format incorrect, Must be dd/mm/yyyy</p>";
                echo "<br><a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
                return;
            }

            //CREATING SQL QUERY FROM VARIABLES
            $sql = 
            "INSERT INTO socialNetwork (scode, userstatus, share, postdate, permission)
            VALUES ('$sCode', '$status', '$share', '$date', '$permissionString')";

            if(mysqli_query($conn, $sql)) {
                echo "<p style='color:green; font-size: 20px; font-weight: bold;'>New record created successfully</p>";
                echo "<br><a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
            } 
            else{
                echo "<br><a href='poststatusform.php'>Return to Post Status Form</a>";
                echo "<br><a href='index.html'>Homepage</a>";
            }

            //CLOSING CONNECTIONS TO DATABASE
            if($conn){
                mysqli_close($conn);
            }
        ?>
<!--//////////END PHP//////////-->
    </main>

<!--//////////FOOTER//////////-->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-12">
                    <p>James Stewart - 1391333</p>
                </div>
            </div>
        </div>
    </footer>
<!--//////////END FOOTER//////////-->
    </body>
</html>

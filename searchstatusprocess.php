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
        <title>Search Status</title>
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
                        <li class="nav-item">
                            <a class="nav-link" href="poststatusform.php">Post</a>
                        </li>
                        <li class="nav-item active">
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
            $searchTerm = $_GET["searchStatus"];
            $isEmpty = false;
            require_once ("/home/ysh3325/conf/settings.php");

            //CREATING CONNECTION TO DATABSE
            $conn = mysqli_connect($host, $user, $pass, $dbnm);

            //CHECK TO SEE IF SEARCH IS EMPTY
            if(empty($searchTerm)){
                echo "<div class='container-fluid padding'>";
                echo "<div class='col-12 welcome text-center'>";
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Nothing was entered in search! Only way to get here is by changing the GET request in the address bar, sneaky!</p>";
                echo "<p><a href='searchstatusform.html'>Search Again</a></p>";
                echo "<p><a href='index.html'>Return to Home Page</a></p>";
                echo "</div>";
                echo "</div>";
                return;
                $isEmpty = true;
            }

            if(!mysqli_query($conn, "describe socialNetwork")){
                echo "<div class='container-fluid padding'>";
                echo "<div class='col-12 welcome text-center'>";
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>Table doesnt exist</p>";
                echo "<p><a href='searchstatusform.html'>Search Again</a></p>";
                echo "<p><a href='index.html'>Return to Home Page</a></p>";
                echo "</div>";
                echo "</div>";
                return;
            }

            $sql = "select * from socialNetwork where userstatus like '%$searchTerm%' order by postdate";
            $result = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0){
                if(!$isEmpty){
                    echo "<div class='container-fluid padding'>";
                    echo "<div class='col-12 welcome text-center'>";
                    echo "<h3 class='display-4'>Status Information</h1>";
                    echo "<p class='lead font-italic'>You searched: \"", $searchTerm, "\".</p>";

                    while($row = mysqli_fetch_assoc($result)) {
                        $resultNum++;
                    }
                    echo "<p class='lead font-italic'>There are: ", $resultNum, " matches.</p>";

                    $sql = "select * from socialNetwork where userstatus like '%$searchTerm%' order by postdate";
                    $result = mysqli_query($conn, $sql);

                    $counter = 0;
                    while($row = mysqli_fetch_assoc($result)) {
                        $counter++;
                        echo "<p class='lead font-weight-bold'>Search result No. ", $counter, "</p>";
                        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>";
                        echo "Code: " . $row["scode"];
                        echo "<br>";
                        echo "<p>Status: " .  $row["userstatus"], "</p>";
                        echo "<br>";
                        echo "Share: " .  $row["share"];
                        echo "<br>";
                        echo "Date Posted: " .  date("d-m-Y", strtotime($row["postdate"]));
                        echo "<br>";
                        echo "Permission: " .  $row["permission"];
                        echo "<br>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br><br><br>";
                    }
                    echo "<br><p><a href='searchstatusform.html'>Search Again</a></p>";
                    echo "<p><a href='index.html'>Return to Home Page</a></p>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            else{
                echo "<div class='container-fluid padding'>";
                echo "<div class='col-12 welcome text-center'>";
                echo "<p style='color:red; font-size: 20px; font-weight: bold;'>No matches:</p>";
                echo "<p><a href='searchstatusform.html'>Search Again</a></p>";
                echo "<p><a href='index.html'>Return to Home Page</a></p>";
                echo "</div>";
                echo "</div>";
            }
        
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
<!--//////////FOOTER//////////-->
    </body>
</html>
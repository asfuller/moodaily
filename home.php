<?php
session_start();

include("connection.php");
include("mood-connection.php");
include("functions.php");

$user_data = check_login($con);

/**Mood Input PHP */
if($_SERVER['REQUEST_METHOD'] == "POST") {

	//something was posted
	$emoji = $_POST['emoji'];
    $social = $_POST['social'];
	$hobbies = $_POST['hobbies'];
    $sleep = $_POST['sleep'];
    $food = $_POST['food'];
    $health = $_POST['health'];
    $mindfulness = $_POST['mindfulness'];
    $chores = $_POST['chores'];
    $notes = $_POST['notes'];

	if(!empty($emoji) && !empty($social) && !empty($hobbies) && !empty($sleep) && !empty($health) && !empty($mindfulness) && !empty($chores)) {


		//save to database
		$user_id = random_num(20);
		$query = "insert into mood (id,social,hobbies,food,sleep,health,mindfulness,chores,notes) values ('$id','$emoji','$social','$hobbies','$food','$sleep','$health,'$mindfulness,'$chores','$notes')";

		mysqli_query($con, $query);

		header("Location: home.php");
		die;
	}

}

/***Calendar PHP stuff****/
// Set your timezone!!
date_default_timezone_set('America/New_York');

// Get prev & next month
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // This month
    $ym = date('Y-m');
}

// Check format
$timestamp = strtotime($ym . '-01');  // the first day of the month
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

// Today (Format:2021-08-8)
$today = date('Y-m-j');

// Title (Format:April, 2021)
$title = date('F, Y', $timestamp);

// Create prev & next month link
$prev = date('Y-m', strtotime('-1 month', $timestamp));
$next = date('Y-m', strtotime('+1 month', $timestamp));

// Number of days in the month
$day_count = date('t', $timestamp);

// 1:Mon 2:Tue 3: Wed ... 7:Sun
$str = date('N', $timestamp);

// Array for calendar
$weeks = [];
$week = '';

// Add empty cell(s)
$week .= str_repeat('<td></td>', $str - 1);

for ($day = 1; $day <= $day_count; $day++, $str++) {

    $date = $ym . '-' . $day;

    if ($today == $date) {
        $week .= '<td class="today">';
    } else {
        $week .= '<td>';
    }
    $week .= $day . '</td>';

    // Sunday OR last day of the month
    if ($str % 7 == 0 || $day == $day_count) {

        // last day of the month
        if ($day == $day_count && $str % 7 != 0) {
            // Add empty cell(s)
            $week .= str_repeat('<td></td>', 7 - $str % 7);
        }

        $weeks[] = '<tr>' . $week . '</tr>';

        $week = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-----CSS Links------>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/daily-data.css">

    <!-----Fonts----->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Roboto&display=swap" rel="stylesheet"></head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@1&display=swap" rel="stylesheet">

    <!-----Bootstrap Links----->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav>
        <div class="logo">
            <h4><a href="home.php">Moodaily</a></h4>
        </div>
        <ul class="nav-links">
            <li><a href="home.html">Home</a></li>
            <li><a href="login.php">Login/Signup</a></li>
            <!--<li><a href="#AddData">Add Today's Data</a></li>-->
        </ul>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
        <div class="login">
            <a href="logout.php">Logout</a>
            Hello, <?php echo $user_data['username']; ?>
        </div>
    </nav>

    <!---Weather API-->
    <div class="container">
        <div class="app-title">
            <p>Weather</p>
        </div>
        <div class="notification"></div>
        <div class="weather-container">
            <div class="weather-icon">
                <img src="imgs/icons/unknown.png" alt="unknown icon">
            </div>
            <div class="temperature-value">
                <p>- °<span>C</span></p>
            </div>
            <div class="temperature-description">
                <p> - </p>
            </div>
            <div class="location">
                <p>-</p>
            </div>
        </div>
    </div>

    <!--------Calendar-------->
    <div class="calendar">
        <ul class="list-inline">
            <li class="list-inline-item"><a href="?ym=<?= $prev; ?>" class="btn btn-link">&lt; prev</a></li>
            <li class="list-inline-item"><span class="title"><?= $title; ?></span></li>
            <li class="list-inline-item"><a href="?ym=<?= $next; ?>" class="btn btn-link">next &gt;</a></li>
        </ul>
        <p class="text-right"><a href="home.php">Today</a></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                    <th>S</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    foreach ($weeks as $week) {
                        echo $week;
                    }
                ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddData">Add Today's Mood</button>
    </div>

    <!-- Modal
        Note: modal fade doesn't work, which is why the form pops up so suddenly-->
    <div class="modal" id="AddData" tabindex="-1" role="dialog" aria-labelledby="inputData" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inputDataTitle">How are you doing today?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                <!----Content here-->
                <!-------------Data Input------------>
                <form action="#" method="post">

                <!----Emoji Slider---->
                <div class="container">
                    <h3>How do you feel today?</h3>
                    <div class="section">
                         <ul class="emojis">
                            <li class="slide-emoji"><img src="/imgs/emojis/emoji-1.png" alt=""></li>
                            <li><img src="imgs/emojis/emoji-2.png" alt=""></li>
                            <li><img src="imgs/emojis/emoji-3.png" alt=""></li>
                            <li><img src="imgs/emojis/emoji-4.png" alt=""></li>
                            <li><img src="imgs/emojis/emoji-5.png" alt=""></li>
                        </ul>
                    </div>
                    <!---Slider-->
                    <div class="slider">
                        <div class="thumb"><span></span></div>
                            <div class="progress-bar"></div>
                                <input type="range" min="0" value="0" max="100" name="emoji"> <!--input for slider here-->
                    </div>
                </div>
                <!---End of emoji slider-->

                <!---Activities input-->
                <div class="container2">
                    <h3>What all did you do today?</h3>
                    <div class="section2">
                        <h4>Social</h4>
                            <ul class="social">
                                <li>
                                    <input type="checkbox" id="family" name="social" value="family">
                                    <label for="family">Family</label>
                                </li>

                                <li>
                                <input type="checkbox" id="friends" name="social" value="friends">
                                    <label for="friends">Friends</label>
                                </li>

                                <li>
                                <input type="checkbox" id="date" name="social" value="date">
                                <label for="date">Date</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="party" name="social" value="party">
                                    <label for="party">Party</label>
                                </li>
                                </ul>
                    </div>
                    <!----Hobbies input------>
                    <div class="section2">
                        <h4>Hobbies</h4>
                            <ul class="hobbies">
                                <li>
                                    <input type="checkbox" id="movies-tv" name="hobbies" value="movies-tv">
                                    <label for="movies-tv">Movies & TV</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="reading" name="hobbies" value="reading">
                                    <label for="reading">Reading</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="gaming" name="hobbies" value="gaming">
                                    <label for="gaming">Gaming</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="sport" name="hobbies" value="sport">
                                    <label for="sport">Sport</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="relax" name="hobbies" value="relax">
                                    <label for="relax">Relax</label>
                                </li>
                            </ul>
                    </div>

                    <!----------Sleep Input------------>
                    <div class="section2">
                        <h4>Sleep</h4>
                            <ul class="sleep">
                                <li>
                                    <input type="checkbox" id="good" name="sleep" value="good">
                                    <label for="good">Good</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="medium" name="sleep" value="medium">
                                    <label for="medium">Medium</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="bad" name="sleep" value="bad">
                                    <label for="bad">Bad Sleep</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="early" name="sleep" value="early">
                                    <label for="early">Sleep Early</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="late" name="sleep" value="late">
                                    <label for="late">Sleep Late</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="paralysis" name="sleep" value="paralysis">
                                    <label for="paralysis">Sleep Paralysis</label>
                                </li>
                            </ul>
                    </div>

                    <!------Food Input------>
                    <div class="section2">
                        <h4>Food</h4>
                            <ul class="food">
                                <li>
                                    <input type="checkbox" id="healthy" name="food" value="healthy">
                                    <label for="healthy">Eat Healthy</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="fast-food" name="food" value="fast-food">
                                    <label for="fast-food">Fast Food</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="homemade" name="food" value="homemade">
                                    <label for="homemade">Homemade</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="restaurant" name="food" value="restaurant">
                                    <label for="restaurant">Restaurant</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="delivery" name="food" value="delivery">
                                    <label for="delivery">Delivery</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="no-meat" name="food" value="no-meat">
                                    <label for="no-meat">No Meat</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="no-sweets" name="food" value="no-sweets">
                                    <label for="no-sweets">No Sweets</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="no-soda" name="food" value="no-soda">
                                    <label for="no-soda">No Soda</label>
                                </li>
                            </ul>
                    </div>

                    <!--------Health Input------->
                    <div class="section2">
                        <h4>Health</h4>
                            <ul class="health">
                                <li>
                                    <input type="checkbox" id="exercise" name="health" value="exercise">
                                    <label for="exercise">Exercise</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="drink-water" name="health" value="drink-water">
                                    <label for="drink-water">Drink Water</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="walk" name="walk" value="health">
                                    <label for="walk">Walk</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="yoga" name="yoga" value="health">
                                    <label for="yoga">Yoga</label>
                                </li>
                            </ul>
                    </div>

                    <!------Mindfulness Input------>
                    <div class="section2">
                        <h4>Mindfulness</h4>
                            <ul class="mindfulness">
                                <li>
                                    <input type="checkbox" id="meditation" name="mindfulness" value="meditation">
                                    <label for="meditation">Meditation</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="kindness" name="mindfulness" value="kindness">
                                    <label for="kindness">Kindness</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="listen" name="mindfulness" value="listen">
                                    <label for="listen">Listen</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="donate" name="mindfulness" value="donate">
                                    <label for="donate">Donate</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="give-gifts" name="mindfulness" value="give-gifts">
                                    <label for="give-gifts">Give Gifts</label>
                                </li>
                            </ul>
                    </div>

                    <!-------Chores------->
                    <div class="section2">
                        <h4>Chores</h4>
                            <ul class="chores">
                                <li>
                                    <input type="checkbox" id="shopping" name="chores" value="shopping">
                                    <label for="shopping">Shopping</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="cleaning" name="chores" value="cleaning">
                                    <label for="cleaning">Cleaning</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="cooking" name="chores" value="cooking">
                                    <label for="cooking">Cooking</label>
                                </li>

                                <li>
                                    <input type="checkbox" id="laundry" name="chores" value="laundry">
                                    <label for="laundry">Laundry</label>
                                </li>
                            </ul>
                    </div>

                    <!-------Notes-------->
                    <div class="section2">
                        <h4>Notes</h4>
                        <textarea name="notes" id="notes" cols="50" rows="3"></textarea>
                    </div>

                    <!-----Book Finder----->
                    <div class="book-finder">
                        <div id="title" class="center">
                            <h5 id="header" class="text-center mt-5">What book(s) did you read today?</h5>
                                <div class="row">
                                    <div id="input" class="input-group mx-auto col-lg-6 col-md-8 col-sm-12">
                                        <input id="search-box" type="text" class="form-control" placeholder="Search Books!...">
                                        <button id="search" class="btn btn-primary" onclick="">Search</button>
                                    </div>
                                </div>
                        </div>
                        <div class="book-list" >
                            <h2 class="text-center">Search Result</h2>
                            <div id="list-output" class="">
                                <div class="row">
                                <!-- card  -->

                                </div>

                            </div>
                        </div>
                </div>
                </div>
                </form>
                <!--End of container-->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit data</button>
                </div>
            </div>
        </div>
    </div>

    <!---Javascript-->
    <script src="js/daily-data.js"></script>
    <script src="js/app2.js"></script>
    <script src="js/app.js"></script>
</body>
</html>

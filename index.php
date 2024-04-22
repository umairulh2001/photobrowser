<?php
// Hardcoded single user authentication
$validUsername = "admin";
$validPassword = "admin";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate username and password
    if ($username == $validUsername && $password == $validPassword) {
        // Start the session
        session_start();

        // Set session variables
        $_SESSION["loggedin"] = true;

        // Redirect to the home page
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

// Check if the user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Display login form if not logged in
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    exit();
}

// User is logged in, display the home page
$currentImageIndex = isset($_GET['image']) ? (int)$_GET['image'] : 0;

$eventPath=isset($_GET['event']) ? str_replace("_","/",$_GET['event']) : "list1";

$images = file( $eventPath . '/images.txt', FILE_IGNORE_NEW_LINES);

// Ensure index is within bounds
$currentImageIndex = max(0, min($currentImageIndex, count($images) - 1));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Photo Gallery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-1">
        <div class="row justify-content-center">
            <div class="col-md-6">
				
                <div class="card">
					<div class="card-header d-flex justify-content-between">
						<a class="btn btn-primary" href="./?image=0&event=list1" style="font-size: 50%"><strong>list1</strong></a>
						<a class="btn btn-primary" href="./?image=0&event=list2" style="font-size: 50%"><strong>list2</strong></a>
					</div>

                    <div class="card-header d-flex justify-content-between">
                        <a href="?image=<?php echo max(0, $currentImageIndex - 1) . '&event=' . $eventPath;; ?>" class="btn btn-primary">Previous</a>
						<a class="btn"> Photo Gallery <strong>( <?php 
						   if ( isset($_GET['event']) )
							   echo strtoupper($_GET['event']);
						   else
							   echo 'list1';
					   ?>) </strong> </a>
					   
						<a href="?image=<?php echo min(count($images) - 1, $currentImageIndex + 1) . '&event=' . $eventPath; ?>" class="btn btn-primary">Next</a>
				
					</div>
                    <div class="card-body text-center">
                        <img src="<?php 
								echo $eventPath. '/' . $images[$currentImageIndex]; 
							?>" class="img-fluid rounded">
                    </div>
                    
					<?php
						// Uncomment below code to log ips of users accessing in requestslogs.txt
						/*
						if ( $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ){
							$txt = 'Main Page' . ',' . $_SERVER['REMOTE_ADDR'] . ',' . $images[$currentImageIndex] . ',' . $_GET['event'] . ',' . date("d/m/Y") . ',' . date("h:i:sa");
							$myfile = file_put_contents('requestslogs.txt', $txt.PHP_EOL , FILE_APPEND);
						}
						*/
					?>
					
                </div>
            </div>
        </div>
    </div>
</body>
</html>

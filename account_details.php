<?php
session_start();
require('connect_db.php');

#Redirect to login if user is not logged in.
if (!isset($_SESSION['username'])) {
    header("Location: account_login.php");
    exit();
}

#store session userID and username
$username = $_SESSION['username'];
$userID = $_SESSION['userID'];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_username'])) 
    {
        $updatedUsername = mysqli_real_escape_string($link, $_POST['new_username']);    

        $checkNameAvailability = "SELECT userID FROM user WHERE username='$updatedUsername'";
        $checkNameResult = @mysqli_query($link, $checkNameAvailability);
        #first check to see if username already exists
        if ($checkNameResult && mysqli_num_rows($checkNameResult) != 0) 
        {
            #if username exists display error alert and exit.
            echo "<script>
            alert('The username you are trying to enter is already taken. Please try again!');
            window.location.href = 'account_details.php';</script>";
            exit();        
        }
        else
        {   
            #else if username does not exist then update it in the database user table
        $updateQuery = "UPDATE user SET username='$updatedUsername' WHERE userid='$userID'";
        if (mysqli_query($link, $updateQuery)) 
        {
            $_SESSION['username'] = $updatedUsername;
            echo "<script>
            alert('Your username has successfully been updated!.');
            window.location.href = 'account_details.php';</script>";
        } 
        else 
        {
            echo "Oops. An error occured when updating your username: " . mysqli_error($link);
        }
    }
    }

    if (isset($_POST['update_password'])) {
        //store both password submissons
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        //if the passwords match then update the password
        if ($password1 === $password2) {
            $updateQuery = "UPDATE user SET password=SHA2('$password1',256) WHERE userid='$userID'";
            if (mysqli_query($link, $updateQuery)) {
                echo "<script>
                alert('Your password has successfully been updated!.');
                window.location.href = 'account_details.php';</script>";
            } 
            else {
                echo "Oops. An error occurred when updating your password: " . mysqli_error($link);
            }
        }
        //if the password dont match then echo an error alert
         else {
            echo "<script>alert('The passwords you entered do not match. Please try again!');window.location.href = 'account_details.php';</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="dropdown.js"></script>
    <script type="text/javascript" src="edit_portfolio.js"></script>
</head>
<body>
    <!--Navigation Bar Div-->
    <div class="navigation-bar">   
        <div class="logo">
            <a style="color:white; text-decoration:none;"href="dashboard.php">PortaFolio</a>
        </div>
        <div class="navigation-links">
            <a href="dashboard.php">Back to Home</a>
        </div>

        <div class="user-menu">
            <button onclick="toggleDropdown()">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?>
            </button>
            <div class="dropdown">
                <a href="account_details.php">Account Details</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <!--End of Navigation Bar Div-->
    
    <div class="account-details-container">
    <div class="account-details-box">
        <h1>Edit Account Details</h1>
        <div class="buttons">
            <button class="btn-change-username" onclick="openPopup('changeUsername')">Change Username</button>
            <button class="btn-change-password" onclick="openPopup('changePassword')">Change Password</button>
        </div>
    </div>
    </div>
    
    <!-- Change Username Popup -->
     <div id="changeUsername" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn" onclick="closePopup('changeUsername')">&times;</span>
            <h2>Change Username</h2>
            <form class="popup-form" method="post">
                <label for="new_username">New Username:</label>
                <input type="text" name="new_username" required>
                <button type="submit" class="btn" name="update_username">Update Username</button>
            </form>
        </div>
    </div>
    <!-- End of Change Username Popup Modal -->
     
    <!-- Change Password Popup -->
     <div id="changePassword" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn" onclick="closePopup('changePassword')">&times;</span>
            <h2>Change Password</h2>
            <form class="popup-form" method="post">
                <label for="password1">New Password:</label>
                <input type="password" name="password1" required>

                <label for="password2">Enter New Password Again:</label>
                <input type="password" name="password2" required>

                <button type="submit" class="btn" name="update_password">Update Password</button>
            </form>
        </div>
    </div>
    <!-- End of Change Password Popup Modal -->
</body>
</html>

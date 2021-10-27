<?php
    session_start();
    $loginFailed = "";
    use PHPMailer\PHPMailer\PHPMailer;
    $back = isset($_SESSION['returnFile']) ? $_SESSION['returnFile'] : "index.php";
    if(isset($_SESSION['user'])){
        header("Location: $back");
    }
    if(isset($_POST['email']) && isset($_POST['password'])){
        require 'phpMailer/PHPMailer.php';
        require 'phpMailer/SMTP.php';
        require 'phpMailer/Exception.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.southern.edu';
        $mail->SMTPAuth = true;
        $mail->Username = $_POST['email'];
        $mail->Password = $_POST['password'];
        // $mail->SMTPDebug = 3;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        if($mail->smtpConnect()){
            $mail->smtpClose();
           // set session variables
           //strip out the @southern.edu
            $username = explode("@", $_POST['email'])[0];
            //Verify that user exists in admin table
            require('config.php');
            $sql = "SELECT * FROM `admin` WHERE `administrator` = '$username'";
            echo $sql;
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                $_SESSION['userName'] = $row['fullname'];
                $_SESSION['user'] = $row['administrator'];
                header("Location: $back");
            }
            else{
                $loginFailed = "User not administrator.";
            }
        }
        else{
            $loginFailed = "Username or password incorrect.";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card text-center">
            <div class="card-header">
                <h1>Login</h1>
            </div>
            <div class="card-body">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="email">Southern Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="jdoe@southern.edu">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="SouthernPassword">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <?php
                        if($loginFailed != ""){
                            echo "<p class='text-danger'>$loginFailed</p>";
                        }
                    ?>
                </form>
            </div>
    </div>
</body>
</html>
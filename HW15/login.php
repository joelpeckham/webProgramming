<?php
    session_start();
    $loginFailed = false;
    use PHPMailer\PHPMailer\PHPMailer;
    if(isset($_SESSION['user'])){
        header("Location: index.php");
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
            $_SESSION['user'] = $_POST['email'];
            header("Location: index.php");
        }
        else{
            $loginFailed = true;
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
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <?php
                        if($loginFailed){
                            echo "<p class='text-danger'>Login failed</p>";
                        }
                    ?>
                </form>
            </div>
    </div>
</body>
</html>
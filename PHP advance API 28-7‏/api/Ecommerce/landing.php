<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
        }
        .btn-custom {
            width: 200px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello There!</h1>
        <p>Automatic identity verification which enables you to verify your identity</p>
        <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
        <dotlottie-player 
            src="https://lottie.host/ddd2209b-6c3b-4736-9030-19408c473f72/Jm0qJN74Jw.json" 
            background="transparent" speed="1" style="width: 300px; height: 300px;" 
            loop autoplay>
        </dotlottie-player>
        <a href="login.php" class="btn btn-primary btn-custom">Login</a>
        <a href="signup.php" class="btn btn-danger btn-custom">Sign Up</a>
    </div>
</body>
</html>

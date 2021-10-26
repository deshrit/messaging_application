<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>


<form method="POST" name="login-form">

    <div class="heading">Login</div>

    <div class="error"></div>

    <div class="input-field-container email-field"><input type="email" name="email" value="" placeholder="Email ..." autocomplete="off" class="email"></div>

    <div class="input-field-container password-field"><input type="password" name="password" value="" placeholder="Password ..." autocomplete="off" class="password"></div>

    <div class="submit-btn-container"><input type="submit" name="login"  value="Login"  class="submit-btn"></div>

</form>

<!-- css, xhr -->
<script src="js/login.js"></script>

</body>
</html>
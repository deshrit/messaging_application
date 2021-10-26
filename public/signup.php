<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>

<div id="error">
</div>

<form name="signup-form" method="POST" enctype="multipart/form-data">
    
    <div class="heading">Sign up</div>

    <div class="error"></div>

    <div class="input-field-container">
        <input type="text" class="input-field" name="username" value="" id="username" placeholder="Username...">
    </div>

    <div class="input-field-container">
        <input type="email" class="input-field" name="email" value="" placeholder="Email...">
    </div>
    
    <div class="input-field-container">
        <input type="password" class="input-field" name="password" value="" id="password" placeholder="Password...">
    </div>
    
    <div class="input-field-container">
        <input type="file" name="image" id="profile-img">
        <label for="profile-img" class="profile-photo-label">Choose profile photo</label>
    </div>
    
    <div class="submit-btn-container">
        <input type="submit" name="signup"  value="Signup" class="submit-btn">
    </div>
    
</form>

<script src="js/signup.js"></script>

</body>
</html>
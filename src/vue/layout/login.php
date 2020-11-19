<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Login & Signup Page</title>
    <link rel="stylesheet" href="../../../public/css/login.css">
</head>
<body>

<!--<div class="imgBx"><img src="../../../public/images/bg.jpg"></div>-->
<div class="wrapper">
    <div class="title-text">
        <div class="title login">Login Form</div>
        <div class="title signup">Signup Form</div>
    </div>
    <div class="form-container">
        <div class="slide-controls">
            <input type="radio" name="slider" id="login" checked>
            <input type="radio" name="slider" id="signup">
            <label for="login" class="slide login">Login</label>
            <label for="signup" class="slide signup">Signup</label>
            <div class="slide-tab"></div>
        </div>

        <div class="form-inner">
            <form action="#" class="login">
                <div class="field">
<!--                    <span>Username</span>-->
                    <input type="text" name="" placeholder="Email Address" required>
                </div>
                <div class="field">
<!--                    <span>Password</span>-->
                    <input type="password" name="" placeholder="Your password" required>
                </div>
<!--                <div class="remember">-->
<!--                    <label><input type="checkbox" name="">Remember me</label>-->
<!--                </div>-->
                <div class="pass-link"><a href="#">Forget password?</a></div>
                <div class="field">
                    <input type="submit" value="Login" name="">
                </div>
                <div class="signup-link">
                    Don't have an account? <a href="#">Sign up</a>
                </div>
            </form>
            <form action="#" class="signup">
                <div class="field">
                    <input type="text" name="" placeholder="Email Address" required>
                </div>
                <div class="field">
                    <input type="password" name="" placeholder="Your password" required>
                </div>
                <div class="field">
                    <input type="password" name="" placeholder="Confirm password" required>
                </div>
                <div class="field">
                    <input type="submit" value="Signup" name="">
                </div>
            </form>
        </div>
    </div>
    <script>
        const loginForm = document.querySelector("form.login");
        const signupForm = document.querySelector("form.signup");
        const loginBtn = document.querySelector("label.login");
        const signupBtn = document.querySelector("label.signup");
        const signupLink = document.querySelector(".signup-link a");
        const loginText = document.querySelector(".title-text .login");
        const signupText = document.querySelector(".title-text .signup");
        signupBtn.onclick = (() => {
            loginForm.style.marginLeft = "-50%";
            loginText.style.marginLeft = "-50%";
        });
        loginBtn.onclick = (() => {
            loginForm.style.marginLeft = "0%";
            loginText.style.marginLeft = "0%";
        });
        signupLink.onclick = (() => {
            signupBtn.click();
            return false;
        });
    </script>

    <div class="h3">Login with social media</div>
<!--    <h3>Login with social media</h3>-->
    <ul class="sci">
        <li><img class="imgBx1" src="../../../public/images/facebook.png"></li>
        <li><img class="imgBx2" src="../../../public/images/instagram.png"></li>
        <li><img class="imgBx3" src="../../../public/images/twitter.png"></li>
    </ul>
    <script>
        const img1 = document.querySelector(".imgBx1");
        const img2 = document.querySelector(".imgBx2");
        const img3 = document.querySelector(".imgBx3");
        img1.onclick = (() => {
            signupBtn.click();
            return false;
        });
        img2.onclick = (() => {
            signupBtn.click();
            return false;
        });
        img3.onclick = (() => {
            signupBtn.click();
            return false;
        });
    </script>

</div>

</body>
</html>

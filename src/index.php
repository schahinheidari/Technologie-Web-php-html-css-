<!DOCTYPE html>
<html>
<head>
    <title>Lego</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, Initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/footer.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/slider.css">
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css"
    />
</head>
<body>
<div class="allweb">
    <div class="menu">
        <ul class="nav-links">
            <li><a href="#">home</a></li>
            <li><a href="#">publication</a></li>
            <li>
                <a href="#">list of object</a>
                <ul class="drop-menu">
                    <li><a href="">Drop menu 1</a></li>
                    <li><a href="">Drop menu 2</a></li>
                    <li><a href="">Drop menu 3</a></li>
                </ul>

            </li>
            <li><a href="#" ><img src="../public/images/phone.png" height="20" width="20">contact us</a></li>
        </ul>
        <ul>
            <li1><b href="#"><img src="../public/images/lego.png" height="50" width="120" alt="logo"></b></li1>
        </ul>
    </div><!-- menu -->
    <!--    logo-->
    <div class="ads">
        <ul>
            <li><a href="#"><img src="../public/images/twitter.png"></a></li>
            <li><a href="#"><img src="../public/images/facebook.png"></a></li>
            <li><a href="#"><img src="../public/images/instagram.png"></a></li>
            <!--            <li><a href="#"><img src="../../../public/images/php.jpg"></a></li>-->
        </ul>
        <ul style="direction: rtl">
            <li><img src="../public/images/account.png" width="20" height="20"><a href="../src/vue/layout/login.php">login</a> | <a href="../src/vue/layout/login.php">registration</a></li>
        </ul>

    </div><!-- ads -->
<!--search-->
    <label for="site-search">Search the site:</label>
    <input type="search" id="site-search" name="q"
           aria-label="Search through site content">
    <button><img src="../public/images/search-solid.svg" width="15" height="15" alt="">Search</button>
        <!-- Insert to your webpage where you want to display the slider -->
        <section class="slider">
            <div class="slides">
                <div class="item  active" id="slide1">
                    <img src="../public/images/harry-potter.jpg" height="500" width="500" alt="" />
                    <span>Harry Potter is a series of seven fantasy novels written by British author J. K. Rowling</span>
                </div>
                <div class="item " id="slide2">
                    <img src="../public/images/batman.jpg" height="500" width="500" alt="" />
                    <span>Batman is a fictional superhero appearing in American comic books published by DC Comics</span>
                </div>
                <div class="item " id="slide3">
                    <img src="../public/images/spyderman.jpg" height="500" width="500" alt="" />
                    <span>Spider-Man is a fictional superhero created by writer-editor Stan Lee and writer-artist Steve Ditko</span>
                </div>
            </div>
            <div class="buttons">
                <i onclick="setSlide('slide1' , 1)" class="fas fa-circle"></i>
                <i onclick="setSlide('slide2' , 2)" class="fas fa-circle"></i>
                <i onclick="setSlide('slide3' , 3)" class="fas fa-circle"></i>
            </div>
        </section>
    <script src="../public/js/main.js"></script>

    <!-- slider -->

    <div class="content-wrapp">
        <div class="content">
            <div class="content-title">
                <p>lego 1</p>
            </div><!-- content-title -->
            <div class="content-thumb">
                <img src="../public/images/lgo.jpg">
            </div><!-- content-thumb -->
            <div class="content-txt">
                <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise
                    en page avant impression. </p>
            </div><!-- content-txt -->
            <!--            button-->
            <div class="btn">
                <a href="#">Detail</a>
            </div>
        </div><!-- content -->
        <div class="content">
            <div class="content-title">
                <p>lego 2</p>
            </div><!-- content-title -->
            <div class="content-thumb">
                <img src="../public/images/lgo.jpg">
            </div><!-- content-thumb -->
            <div class="content-txt">
                <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise
                    en page avant impression. </p>
            </div><!-- content-txt -->
            <!--            button-->
            <div class="btn">
                <a href="#">Detail</a>
            </div>
        </div><!-- content -->
        <div class="content">
            <div class="content-title">
                <p>lego 3</p>
            </div><!-- content-title -->
            <div class="content-thumb">
                <img src="../public/images/lgo.jpg">
            </div><!-- content-thumb -->
            <div class="content-txt">
                <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise
                    en page avant impression. </p>
            </div><!-- content-txt -->
            <!--            button-->
            <div class="btn">
                <a href="#">Detail</a>
            </div>
        </div><!-- content -->
    </div><!-- content-wrapp -->

    <div class="big-content">
        <div class="big-content-thumb">
            <img src="../public/images/lgo.jpg">
        </div><!-- big-content-thumb -->
        <div class="big-content-title">
            <p>lego 4</p>
        </div><!-- big-content-title -->

        <div class="big-content-txt">
            <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise
                en page avant impression. </p>
        </div><!-- big-content-txt -->
        <div class="big-content-footer">
            <a href="#">more detail</a>
        </div><!-- big-content-footer -->
    </div><!-- big-content -->
    <div class="big-content">
        <div class="big-content-thumb">
            <img src="../public/images/lgo.jpg">
        </div><!-- big-content-thumb -->
        <div class="big-content-title">
            <p>lego 5</p>
        </div><!-- big-content-title -->

        <div class="big-content-txt">
            <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise
                en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis
                les années 1500, quand un imprimeur anonyme assembla
                ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. </p>        </div><!-- big-content-txt -->
        <div class="big-content-footer">
            <a href="#">more detail..</a>
        </div><!-- big-content-footer -->
    </div><!-- big-content -->
</div><!-- ads -->
<div class="footer">
    <div class="footermnu">
        <ul>
            <li><a href="#">home</a></li>
            <li><a href="#">publication</a></li>
            <li><a href="#">about us</a></li>
            <li><a href="#">contact me</a></li>
        </ul>
    </div><!-- footermenu -->
    <div class="socialpage">
        <ul>
            <li><a href="#"><img src="../public/images/facebook.png"></a></li>
            <li><a href="#"><img src="../public/images/instagram.png"></a></li>
            <li><a href="#"><img src="../public/images/twitter.png"></a></li>
        </ul>
        <p>you agree to have read and accepted our terms of use, cookie and privacy policy.</p>
    </div><!-- socialpage -->

</div><!-- footer -->


</div><!-- allweb -->
</body>
</html>
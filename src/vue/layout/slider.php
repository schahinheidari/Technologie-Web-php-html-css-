<!DOCTYPE html>
<html>
<head>
    <title>Lego</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, Initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/slider.css">
</head>
<body>
<section class="slider">
    <div class="slides">
        <div class="item  active" id="slide1">
            <img src="../../../public/images/harry-potter.jpg" width="300" height="200" alt="" />
            <span>Harry Potter is a series of seven fantasy novels written by British author J. K. Rowling.</span>
        </div>
        <div class="item " id="slide2">
            <img src="../../../public/images/batman.jpg" width="300" height="200" alt="" />
            <span style="color: white;">Batman is a fictional superhero appearing in American comic books published by DC Comics</span>
        </div>
        <div class="item " id="slide3">
            <img src="../../../public/images/spyderman.jpg" width="300" height="200" alt="" />
            <span>Spider-Man is a fictional superhero created by writer-editor Stan Lee and writer-artist Steve Ditko</span>
        </div>
    </div>
    <div class="buttons">
        <i onclick="setSlide('slide1' , 1)" class="fas fa-circle"></i>
        <i onclick="setSlide('slide2' , 2)" class="fas fa-circle"></i>
        <i onclick="setSlide('slide3' , 3)" class="fas fa-circle"></i>
    </div>
</section>
<script src="../../../public/js/main.js"></script>
</body>
</html>
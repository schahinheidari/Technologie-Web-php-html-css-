<!DOCTYPE html>
<html>
<head>
	<title>Send Post</title>
	<meta charset="utf-8">
    <link rel="stylesheet" href="../../../../public/css/admin/admin.css">
</head>
<body>
<div class="menu">
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Manage special posts</a></li>
        <li><a href="#">Manage of posts</a>
            <ul>
                <li><a href="#">Send a new post</a></li>
                <li><a href="#">Manage previous posts</a></li>
            </ul>
        </li>
        <li><a href="#">setting</a>
            <ul>
                <li><a href="#">setting of slider</a></li>
                <li><a href="#">Top ad settings</a></li>
                <li><a href="#">Below ad settings</a></li>
                <li><a href="#">Top menu settings</a></li>
                <li><a href="#">Below menu settings</a></li>
            </ul>
        </li>
    </ul>
</div><!-- menu -->

<div class="sendpostBox">
	<div class="lastpostTitle">
			<p>send post</p>
		</div><!-- lastpostTitle -->

		<form>
			<label>title</label>
			<input type="text" name="title">
			<label>id picture</label>
			<input type="text" name="thumb">
			<label>text</label>
			<textarea name="content"></textarea>
			<input type="submit" name="sendpostbtn" value="send post">
		</form>
			<a href="#" class="uploadlink">upload file </a>
</div><!-- sendpostBox -->



</body>
</html>
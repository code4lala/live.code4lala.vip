<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <!--    bootstrap-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!--    dplayer-->
    <link class="dplayer-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dplayer/dist/DPlayer.min.css">
    <script src="https://cdn.jsdelivr.net/npm/hls.js/dist/hls.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dplayer/dist/DPlayer.min.js"></script>
    <title>欢乐白给直播间</title>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-light navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="https://live.code4lala.vip">直播</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="https://code4lala.vip">博客</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admin.php">管理</a>
        </li>
    </ul>
</nav>
<br>
<div class="container">
    <div id="dplayer"></div>
</div>
<br>
<script>
    const dp = new DPlayer({
        container: document.getElementById('dplayer'),
        live: true,
        video: {
            url: 'hls/.m3u8',
            type: 'hls'
        }
    });
</script>
</body>
</html>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <!--bootstrap-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>欢乐白给直播间</title>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-light navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="https://live.code4lala.vip">直播</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="https://code4lala.vip">博客</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="admin.php">管理</a>
        </li>
    </ul>
</nav>
<br>
<div class="container">
    <?php
    function controlLive($control, $key, $conn, $ip)
    {
        $password = str_replace(' ', '', str_replace(PHP_EOL, '',
            trim(file_get_contents("private/key"))
        ));
        if ($key == $password) {
            if ($control == "start") {
                if (file_exists("/var/www/html/127/rtmp_auth.php")) {
                    echo "<div class=\"alert alert-success\"><strong>成功! </strong>直播已打开，不必重复开启</div>";
                } else if (rename("/var/www/html/127/rtmp_auth.php.bak",
                    "/var/www/html/127/rtmp_auth.php")) {
                    echo "<div class=\"alert alert-success\"><strong>成功! </strong>开启直播成功</div>";
                } else {
                    echo "<div class=\"alert alert-danger\"><strong>失败! </strong>开启直播失败</div>";
                }
            } else if ($control == "stop") {
                if (!file_exists("/var/www/html/127/rtmp_auth.php")) {
                    echo "<div class=\"alert alert-success\"><strong>成功! </strong>直播已关闭，不必重复关闭</div>";
                } else if (rename("/var/www/html/127/rtmp_auth.php",
                    "/var/www/html/127/rtmp_auth.php.bak")) {
                    echo "<div class=\"alert alert-success\"><strong>成功! </strong>关闭直播成功</div>";
                } else {
                    echo "<div class=\"alert alert-danger\"><strong>失败! </strong>关闭直播失败</div>";
                }
            }
        } else {
            echo "<div class=\"alert alert-danger\"><strong>失败! </strong>管理员key不匹配</div>";
        }
        // 记录操作
        $nowTime = time();
        $sql = "update ip_limit set last_submit_time='{$nowTime}' where ip='{$ip}'";
        mysqli_query($conn, $sql);
    }

    $key = $_POST['key'];
    $control = $_POST['control'];
    if (empty($key) || empty($control)) {
        echo "<div class=\"alert alert-info\"><strong>呦! </strong>来看后台啊</div>";
    } else {
        // 限制每ip每分钟只能操作一次，防止恶意攻击
        $ip = $_SERVER["REMOTE_ADDR"];
        $dbhost = 'localhost';
        $dbuser = 'live.code4lala.vip';
        $dbpass = str_replace(' ', '', str_replace(PHP_EOL, '',
            trim(file_get_contents("private/mysql_password"))
        ));
        $dbname = 'live';
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die(mysqli_error($conn));
        mysqli_select_db($conn, $dbname);
        $sql = "select * from ip_limit where ip='{$ip}'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows == 1) {
            $lastTime = $result->fetch_assoc()['last_submit_time'];
            // 以前尝试过，检查时间差
            $minute = 3;
            if (time() - $lastTime < $minute * 60) {
                // 两次操作时差小于3分钟
                echo "<div class=\"alert alert-danger\"><strong>失败! </strong>两次操作间隔小于{$minute}分钟</div>";
            } else {
                controlLive($control, $key, $conn, $ip);
            }
        } else {
            // 以前没有尝试过，直接插入数据库
            $time = time();
            $sql = "insert into ip_limit (ip, last_submit_time) values ('{$ip}', '{$time}')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                //成功
                controlLive($control, $key, $conn, $ip);
            } else {
                //失败
                echo "<div class=\"alert alert-danger\"><strong>失败! </strong>查询数据库失败</div>";
            }
        }
        mysqli_close($conn);
    }
    ?>
    <form method="post">
        <div class="form-group">
            <label for="key">管理员key</label>
            <input type="password" class="form-control" id="key" name="key">
        </div>
        <div>
            <label class="radio-inline">
                <input type="radio" name="control" value="start" checked>开启直播
            </label>
            <label class="radio-inline">
                <input type="radio" name="control" value="stop">关闭直播
            </label>
        </div>
        <button type="submit" class="btn btn-primary">提交</button>
    </form>
</div>
<br>
</body>
</html>
<?php
require_once "function.php";
$conf = parse_ini_file("config.ini", TRUE);
$platform = isset($_GET["platform"]) ? $_GET["platform"] : "shuashuake";
//url login
define("UL", $conf[$platform]["login"]);
//url money
define("UM", $conf[$platform]["money"]);
//url index
define("UI", $conf[$platform]["index"]);
//get account
define("PLATFORM",$platform);
if(isset($_GET["account"])) {
    $account = $_GET["account"];
} else {
    $account = "x";
}
//get password
if(isset($_GET["password"])) {
    $password = $_GET["password"];
} else {
    $password = "x";
}
//main
if(!login(UL, $account, $password)) exit("登陆失败");
//get row
$row  = [];
$tmp  = [];
$page = 1;
do {
    $tmp = getXID(UI, $page);
    if(empty($tmp)) {
        $page = 0;
    } else {
        $row = array_merge($row, $tmp);
        $page++;
    }
} while($page != 0);
foreach($row as $k => &$p) {
    $p = explode(":", $p);
}
//get money
$money = [];
$tmp   = [];
$page  = 1;
if(isset($_GET["picker"])) {
    $picker = $_GET["picker"];
} else {
    $picker = date("Y-m-d");
}
do {
    $tmp = getMoney(UM, $page, strtotime($picker));
    if(empty($tmp)) {
        $page = 0;
    } else {
        $money = array_merge($money, $tmp);
        $page++;
    }
} while($page != 0);
//补money
foreach($row as &$pp) {
    foreach($money as $vv) {
        if($pp[0] == $vv[0]) {
            $pp[] = $vv[1];
        }
    }
    if(3 != count($pp)) {
        $pp[] = 0;
    }
}
//sort
$sort = [];
foreach($row as $k => $v) {
    $sort[$k] = $v[2];
}
array_multisort($sort, SORT_ASC, $row);
//total
$total = 0;
foreach($row as $v) {
    $total += $v[2];
}
$count = count($row);
$total_slash_count = @round($total/$count,2);

?>
<!doctype html>
<html lang="en">
<head>
    <title>pax</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="My97DatePicker/WdatePicker.js"></script>
</head>
<body>
<div class="container">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <caption style="text-align: center">
                <form action="index.php">
                    <input type="text" value="<?= $picker ?>" name="picker" onfocus="WdatePicker({})">
                    <input type="text" name="account" value="<?= $account ?>">
                    <input type="text" name="password" value="<?= $password ?>">
					<select name ="platform" style="height: 30px">
                        <?php if($platform == "x"){ ?>
                            <option value ="x" selected>x</option>
                        <?php }else{ ?>
                            <option value ="x">x</option>
                        <?php } ?>
                        <?php if($platform == "x"){ ?>
                            <option value ="x" selected>x</option>
                        <?php }else{ ?>
                            <option value ="x">x</option>
                        <?php } ?>
                        <?php if($platform == "x"){ ?>
                            <option value ="x" selected>x</option>
                        <?php }else{ ?>
                            <option value ="x">x</option>
                        <?php } ?>
					</select>
                    <input type="submit" value="select">
					<span><?= $total . "/" .  $count . "=" . $total_slash_count ?></span>
                </form>
            </caption>
            <tr>
                <th>row</th>
                <th>id</th>
                <th>nickname</th>
                <th>money</th>
            </tr>
            <?php $i= 1;foreach($row as $v) { ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $v[0] ?></td>
                    <td><?= $v[1] ?></td>
                    <td><?= $v[2] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>

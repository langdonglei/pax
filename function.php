<?php
function login($url, $account, $password) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_COOKIEJAR      => "cookie",
        CURLOPT_HTTPHEADER     => [
            "User-Agent: ua",
            "X-Requested-With:XMLHttpRequest"
        ],
        CURLOPT_POSTFIELDS     => http_build_query([
            'action'    => 'login',
            'password'  => $password,
            'telephone' => $account
        ]),
        CURLOPT_RETURNTRANSFER => 1
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    if(strpos($html, "登陆成功")) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function getXID($url, $page = 1) {
    $url = "$url?vtype=1&page=$page";
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_COOKIEFILE     => "cookie",
        CURLOPT_HTTPHEADER     => [
            "User-Agent: uae",
        ],
        CURLOPT_RETURNTRANSFER => 1,
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    //container
    $flag1 = '<div style="overflow: hidden;">';
    switch(PLATFORM){
        case "shuashuake":
            $flag2 = '<div id="page" style="margin-top: 10px;text-align: center;"></div>';
            break;
        case "xiaowei":
            $flag2 = '<div id="page" style="margin-top: 10px;"></div>';
            break;
        default:
            $flag2 = '<div id="page" style="margin-top: 10px;"></div>';
    }

    preg_match_all("|$flag1(.+)$flag2|is", $html, $m);
    //filter
    $tmp = @preg_replace('|title=".+"|isU', '', $m[1][0]);
    $tmp = preg_replace('|style=".+"|isU', '', $tmp);
    $tmp = preg_replace('|class=".+"|isU', '', $tmp);
    $tmp = preg_replace('|onclick=".+"|isU', '', $tmp);
    $tmp = preg_replace('|<input.+>|isU', '', $tmp);
    $tmp = preg_replace('|<div\s+>|isU', '<div>', $tmp);
    $tmp = preg_replace('|<span\s+>|isU', '<span>', $tmp);
    $tmp = preg_replace('|<a.*>|isU', '', $tmp);
    $tmp = preg_replace('|</a>|isU', '', $tmp);
    $tmp = preg_replace("/(\r\n|\n|\r|\t)/isU", '', $tmp);
    //span
    $flag1 = '<span>';
    $flag2 = '</span>';
    preg_match_all("|$flag1(.+)$flag2|isU", $tmp, $mm);
    $arr = array_chunk($mm[1], 2);
    $ret = [];
    foreach($arr as $k => $v) {
        if("在线" == $v[1]) {
            $ret[] = $v[0];
        }
    }
    return $ret;
}

function getMoney($url, $page, $picker) {
    $url = "$url?task_date=$picker&page=$page";
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_COOKIEFILE     => "cookie",
        CURLOPT_HTTPHEADER     => [
            "User-Agent: ua",
        ],
        CURLOPT_RETURNTRANSFER => 1,
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    //container
    $flag1 = '<tbody class="mobile">';
    $flag2 = '</tbody>';
    preg_match_all("|$flag1(.+)$flag2|isU", $html, $m);
    //td
    $flag1 = '<td>';
    $flag2 = '</td>';
    @preg_match_all("|$flag1(.+)$flag2|isU", $m[1][0], $mm);
    //chunk
    $ret = array_chunk($mm[1], 3);
    //    var_dump($arr);die;
    //    $ret = [];
    //    foreach($arr as $v) {
    //        $ret[$v[0]] = $v[1];
    //    }
    return $ret;
}
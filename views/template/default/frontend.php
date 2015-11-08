<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/7/2015
 * Time: 2:23 PM
 */

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Truvisco</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/static/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/static/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/static/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/static/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/static/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/static/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/static/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/static/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/static/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/static/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/static/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/static/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/static/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/static/manifest.json">
    <link rel="mask-icon" href="/static/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/static/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">

</head>

<body>
<?php
echo isset($main) ? View::factory($main)->render() : '$main content not set';
?>
</body>
</html>
<!DOCTYPE html>
<html>
<?php use App\Util\UrlUtils; ?>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Forum</title>

    <link href="<?= UrlUtils::getAssetsUrl() ?>../Media/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= UrlUtils::getAssetsUrl() ?>../Media/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?= UrlUtils::getAssetsUrl() ?>../Media/css/animate.css" rel="stylesheet">
    <link href="<?= UrlUtils::getAssetsUrl() ?>../Media/css/style.css" rel="stylesheet">

</head>

<body>

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg" style="margin:0px;">
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">

                    <div class="ibox-content m-b-sm border-bottom">
                        <div class="p-xs">
                            <div class="pull-left m-r-md">
                                <i class="fa fa-globe text-navy mid-icon"></i>
                            </div>
                            <h2><?= $title ?></h2>
                            <span><?= $description ?></span>
                        </div>
                    </div>

                    <div class="ibox-content forum-container forum-post-container">
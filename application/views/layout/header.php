<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <title>시스넷서비스</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GS25 asset management system">
    <meta name="author" content="Jang KwangHee">

    <!-- Le styles -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="/assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="/assets/ico/favicon.png">

    <link rel="stylesheet" href="/assets/css/colorbox.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.colorbox.js"></script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">GS25 재고관리</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">관리 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="/users">사용자</a></li>
                  <li><a href="/admin/office">사무소</a></li>
                  <li><a href="/admin/inventory">재고창고</a></li>
                  <li><a href="/admin/customer">고객사</a></li>
                  <li><a href="/admin/store">점포</a></li>
                </ul>
              </li>

              <li><a href="#about">일정</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">입고 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="/orders/request_form">입고요청</a></li>
                  <li><a href="/orders/lists">입고요청 확인</a></li>
                </ul>
              </li>

              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">설치 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="/install/new">신규</a></li>
                  <li><a href="/install/hujum_s">휴점S(매장 장비)</a></li>
                  <li><a href="/install/hujum_c">휴점C</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>

              <li><a href="">철수</a></li>
            </ul>

            <form class="navbar-form pull-right">
              <input class="span2" type="text" placeholder="Email">
              <input class="span2" type="password" placeholder="Password">
              <button type="submit" class="btn">Sign in</button>
            </form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <!-- start of div.container -->
    <div class="container">
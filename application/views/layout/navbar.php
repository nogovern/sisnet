    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=site_url()?>">GS25 재고관리</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">관리자 <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="/admin/user">사용자</a></li>
                <li><a href="/admin/office">사무소</a></li>
                <li><a href="/admin/inventory">창고</a></li>
                <li><a href="/admin/company">거래처</a></li>
                <li><a href="/admin/part">장비</a></li>
                <li><a href="/admin/store">점포</a></li>
              </ul>
            </li>

            <li><a href="/schedule">일정</a></li>
            <li><a href="/stock">재고</a></li>

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
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> John Smith <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-inbox"></span> Inbox <span class="badge">7</span></a></li>
                <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                <li class="divider"></li>
                <li><a href="#"><span class="glyphicon glyphicon-off"></span> Log Out</a></li>
              </ul>
            </li>
          </ul>
          
          <!--
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        -->
          
        </div><!--/.nav-collapse -->
      </div>
    </div>

    
    <style type="text/css">
    #breadcrumbs {
        color: #737373;
        background-color: #eeeef6;
        border:1px solid #d4d4e8;
        border-top:0;
        margin:0 auto;
        font-size:.875em;
        line-height:1.71428571428571;
    }
    #breadcrumbs ul {
        padding:.5em .75em;
        margin:.5em 0;
    }
    #breadcrumbs div {
        padding:.5em .75em;
    }
    #breadcrumbs li {
        display:inline-block;
    }
    #breadcrumbs li+li:before {
        padding:0 .5em 0;
        content:"\203A";
    }
    #breadcrumbs a:link,
    #breadcrumbs a:visited {
        border-width:0;
    }

    #breadcrumbs .prev {
        float: left;
        min-width: 23.4043%;
        margin-right: 2.1276%;
        box-sizing: border-box;
    }
    #breadcrumbs .next {
        float: right;
    }
    #breadcrumbs .breadcrumbs-container {
        /* Prevent the breadcrumbs from wrapping around the previous link. */
        overflow: hidden;
    }
    </style>

    <div id="breadcrumbs" class="container" style="position:relative;top:-10px;">
      <div class="next" style="margin-top:5px;">
        <sapn class="label label-danger"> 예시 입니다 </sapn>
      </div>
      <ul class="breadcrumbs-container">
        <li><a href='/'>홈</a></li>
        <li><a href='/admin'>관리자</a></li>
        <li><a href='/admin/user'>사용자 리스트</a></li>    
      </ul>
    </div>
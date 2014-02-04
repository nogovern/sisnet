<?php
if(0) {
  echo '<pre>';
  var_dump($this->session->userdata);
  echo '</pre>';
}
?>
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
            <li class="dropdown" id="page-admin">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">관리자 <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li id="page-admin-user"><a href="<?=site_url('/admin/user')?>">사용자</a></li>
                <li id="page-admin-office"><a href="<?=site_url('/admin/office')?>">사무소</a></li>
                <li id="page-admin-company"><a href="<?=site_url('/admin/company')?>">거래처</a></li>
                <li id="page-admin-store"><a href="<?=site_url('/admin/store')?>">점포</a></li>
                <li class="divider"></li>
                <li id="page-admin-part"><a href="<?=site_url('/admin/part')?>">장비</a></li>
                <li id="page-admin-serialpart"><a href="<?=site_url('/admin/part/serial')?>">시리얼관리장비</a></li>
                <li id="page-admin-category"><a href="<?=site_url('/admin/category')?>">장비 카테고리</a></li>
              </ul>
            </li>

            <li id="page-schedule"><a href="<?=site_url('/schedule')?>">일정</a></li>
            <li id="page-stock"><a href="<?=site_url('/stock')?>">재고</a></li>
            <li id="page-enter"><a href="<?=site_url('/work/enter')?>">입고</a></li>
            <li id="page-install"><a href="<?=site_url('/work/install')?>">설치</a></li>
            <li id="page-close"><a href="<?=site_url('/work/close')?>">철수</a></li>
            <li id="page-change"><a href="<?=site_url('/work/change')?>">상태변경</a></li>
            <li id="page-replace"><a href="<?=site_url('/work/replace')?>">교체</a></li>
            <li id="page-move"><a href="<?=site_url('/work/move')?>">이동</a></li>
            <li id="page-destroy"><a href="<?=site_url('/work/destroy')?>">폐기</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>
<?php 
if($this->auth->isLoggedIn()) {
  echo $this->session->userdata('user_name') ;
  echo ' (';
  echo  $this->session->userdata('office_name');
  echo  $this->session->userdata('company_name');
  echo ')';

} else {
  echo 'John Smith';
}
?>
                <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-inbox"></span> Inbox <span class="badge">7</span></a></li>
                <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
<?php
if($this->auth->isLoggedIn()):
?>                
                <li class="divider"></li>
                <li><a href="<?=site_url('main/logout')?>"><span class="glyphicon glyphicon-off"></span> Log Out</a></li>
<?php
endif;
?>                
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
  
    <!--
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
    -->
    
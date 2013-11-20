    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">GS25 재고관리</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">관리자 <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="/users">사용자</a></li>
                <li><a href="/admin/office">사무소</a></li>
                <li><a href="/admin/inventory">창고</a></li>
                <li><a href="/admin/customer">고객사</a></li>
                <li><a href="/admin/equipment">장비</a></li>
                <li><a href="/admin/store">점포</a></li>
              </ul>
            </li>

            <li class="active"><a href="#">Home</a></li>
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

          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
          
        </div><!--/.nav-collapse -->
      </div>
    </div>
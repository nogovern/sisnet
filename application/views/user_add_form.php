    <!-- start of div.container -->
    <div class="container">
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">  
            <form role="form">
              <h2>사용자 추가 양식</h2>

              <div class="form-group has-success">
                <lable for="type" class="control-label">Select User Type</lable>
                  <select id="type" class="form-control">
                    <option>--선택하세요--</option>
                    <option>시스네트</option>
                    <option>GS</option>
                    <option>납품처</option>
                  </select>
              </div>

              <div class="form-group">
                <lable for="office" class="control-label">사무소 선택</lable>
                  <select id="office" class="form-control">
                    <option>--선택하세요--</option>
                    <option>가산</option>
                    <option>대전</option>
                    <option>부산</option>
                    <option>제주</option>
                  </select>
              </div>

              <!-- 외부 업체 선택-->
              <div class="form-group">
                <lable for="customer" class="control-label">외부 업체 선택</lable>
                  <select id="customer" class="form-control">
                    <option>--선택하세요--</option>
                  </select>
              </div>

              <div class="form-group">
                <lable for="username" >사용자 ID 입력</lable>
                <input type="text" class="form-control" id="username" placeholder="Enter ID">
              </div>

              <div class="form-group">
                <label for="name">이름</label>
                <input type="text" class="form-control" id="name" placeholder="이름을 입력하세요">
              </div>

              <div class="form-group has-error">
                <label for="password" class="control-label">패스워드</label>
                <input type="password" class="form-control" id="password" placeholder="패스워드를 입력하세요">
              </div>

              <div class="form-group has-success">
                <label for="re_password" class="control-label">패스워드 재입력</label>
                <input type="password" class="form-control" id="re_password" placeholder="패스워드를 입력하세요">
              </div>
              
              <p class="form-actions">
                <button class="btn btn-primary" type="button">입력완료</button>
                <button id="ajax" class="btn btn-default" type="button">팝업 띄우기</button>
                <button id="ajax" class="btn btn-danger" type="button">다른 종류</button>
              </p>
            </form>
          </div>

        </div>
      </div>
      <!-- start of div.container -->
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          
        <form>
          <fieldset>
            <legend>사용자 추가 양식</legend>

            <label>사용자 구분</label>
            <label class="radio">
              <input type="radio" name="gubun" id="gubun_1" checked>
              시스넷관리자
            </label>
            <label class="radio">
              <input type="radio" name="gubun" id="gubun_2">
              GS25유저
            </label>

            <label>사용자 ID</label>
            <input type="text" name="username" placeholder="ID를 입력하세요...">

            <label>이름</label>
            <input type="text" name="name" placeholder="성함을 입력하세요...">

            <label>패스워드</label>
            <input type="password" name="password" placeholder="패스워드를 입력하세요...">

            <label>패스워드 확인</label>
            <input type="password" name="re_password" placeholder="패스워드를 다시 입력하세요...">
          </fieldset>

          <p>
            <button class="btn btn-primary" type="button">입력완료</button>
          </p>

          <p class="submit">
            <input type="submit" vlaue="Send md the activation link">
          </p>
        </form>

        </div>
      </div>
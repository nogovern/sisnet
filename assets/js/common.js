/*!
 * gs25 자산관리시스템 공통 javascript functions
 * 
 * @author	JangKwangHee
 * @Date	2014.01.27
 *
 * 이 파일 안에서는 CI 함수를 사용 못한다.
 * header 파일 최상단에 필요한 변수를 미리 선언해야 한다.
 *
 * 전역 변수는 '_' 로 시작함
 */

// 먼저 jquery.validate 가 import 되어야 함
// 기본 에러 메세지 재정의
jQuery.extend(jQuery.validator.messages,{
  required: "필수 항목입니다",
  email: "올바른 이메일 형식이 아닙니다",
  number: "숫자 형식만 가능합니다",
  min: "필수 항목입니다. 올바른 값을 선택하세요."
});

// 디버깅용 출력 함수
function gs2_console(msg) {
	if(window.console) {
		console.log(msg);
	}
}

// 값이 숫자인지 검사
function gs2_is_number(val) {
	var regex = /[0-9]|\./;

	// return regex.text(val) ? true : false;
	return (val - 0) == val && val.length > 0;
}

// 업무 상세보기 내  로그 출력 
function gs2_display_memo(where, op_id) {
  if(op_id === undefined)
    op_id = operation.id;

  // _ajax_log_url 는 header.php 공통 view 안에 정의 됨
  var load_url = _ajax_log_url + op_id;
  $(where).load(load_url);
}

// 업무 요청 취소 후 삭제 처리
// ret_url : 처리 후 이동할 url
function gs2_cancel_operation(ret_url) {
  if(ret_url === undefined) {
    ret_url = _base_url + "schedule";
  }

  var res = confirm('업무 요청을 취소하고 삭제합니다\n진행하시겠습니까?');
  if(!res) {
    return false;
  }

  $.ajax({
      url: _base_url + "work/ajax/remove_operation",
      type: "POST",
      data: {
        id : operation.id,
        "csrf_test_name": $.cookie("csrf_cookie_name")
      },
      dataType: "html",
    })
      .done(function(html) {
        if(html == 'success') {
          alert('해당 업무를 취소하였습니다\n리스트 페이지로 이동합니다');
          location.href = ret_url;
        } else {
          alert('취소 과정에서 오류 발생');
          return false;
        }         
      })
      .fail(function(xhr, textStatus){
        alert("Request failed: " + textStatus);
      });
}

// 한글 입력 검사
//  입력값에 한글이 있으면 true 를 리턴함
function gs2_is_hangul(str) {
  if(str.length == 0)
    return false;

  var re = /[^A-Za-z0-9_\`\~\!\@\#\$\%\^\&\*\(\)\-\=\+\\\{\}\[\]\'\"\;\:\<\,\>\.\?\/\s]/gm;
  
  return re.test(str);
}

// 페이지 이동
function gs2_go_page(page_url) {
  location.href = page_url;
}
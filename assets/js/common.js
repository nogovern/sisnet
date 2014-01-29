/*!
 * gs25 자산관리시스템 공통 javascript functions
 * 
 * @author	JangKwangHee
 * @Date	2014.01.27
 */

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
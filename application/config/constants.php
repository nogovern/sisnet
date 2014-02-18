<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */

// Define Ajax Request
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

// 파일 업로드
define('GS2_UPLOAD_BASEPATH', FCPATH . 'assets/files/');		// 실제 파일 저장 폴더 위치
define('GS2_UPLOAD_BASEURL', 'assets/files/');						// 파일 접근 URL
define('GS2_MAX_FILE_SIZE', 2);

// 회원 타입
define('GS2_USER_TYPE_1',	'시스네트');
define('GS2_USER_TYPE_2',	'GS25');
define('GS2_USER_TYPE_3',	'거래처');
define('GS2_USER_TYPE_9',	'관리자');

// 회원권한 (실제 기능 제한에 사용)
define('GS2_USER_LEVEL_COMPANY',	'1');			// 거래처
define('GS2_USER_LEVEL_GS25',		'2');
define('GS2_USER_LEVEL_SISNET',		'5');			// 기본 시스네트 유저
define('GS2_USER_LEVEL_ADMIN',		'8');
define('GS2_USER_LEVEL_SUPERADMIN',	'9');			// 슈퍼 관리자

// 위치 종류
define('GS2_LOCATION_TYPE_OFFICE', 	'O');			// 사무소
define('GS2_LOCATION_TYPE_COMPANY', 'C');			// 거래처
define('GS2_LOCATION_TYPE_STORE', 	'S');			// 점포

//////////////
// 작업 관려
//////////////
define('GS2_OP_TYPE_ENTER', 	'100');		// 입고

define('GS2_OP_TYPE_INSTALL', 	'200');		// 설치
define('GS2_OP_TYPE_INSTALL_1', '201');		// 설치 - 신규
define('GS2_OP_TYPE_INSTALL_2', '202');		// 설치 - 서비스
define('GS2_OP_TYPE_INSTALL_3', '203');		// 설치 - 개점C (보관)
define('GS2_OP_TYPE_INSTALL_4', '204');		// 설치 - 개점S (점검)
define('GS2_OP_TYPE_INSTALL_5', '205');		// 설치 - 교체
define('GS2_OP_TYPE_INSTALL_6', '206');		// 설치 - 리뉴얼

define('GS2_OP_TYPE_CLOSE',		'300');		// 철수
define('GS2_OP_TYPE_CLOSE_1',	'301');		// 철수 - 폐점
define('GS2_OP_TYPE_CLOSE_2',	'302');		// 철수 - 서비스
define('GS2_OP_TYPE_CLOSE_3',	'303');		// 철수 - 휴점C (보관)
define('GS2_OP_TYPE_CLOSE_4',	'304');		// 철수 - 휴점S (점검)
define('GS2_OP_TYPE_CLOSE_5',	'305');		// 철수 - 교체
define('GS2_OP_TYPE_CLOSE_6',	'306');		// 철수 - 리뉴얼

define('GS2_OP_TYPE_REPLACE', 	'400');		// 교체
define('GS2_OP_TYPE_REPAIR', 	'500');		// 수리
define('GS2_OP_TYPE_DESTROY', 	'600');		// 폐기
define('GS2_OP_TYPE_MOVE', 		'700');		// 이동
define('GS2_OP_TYPE_TRANS', 	'800');		// 이관 (정확한 개념???)
define('GS2_OP_TYPE_CHANGE',	'900');		// (상태변경)점검

// 입고
define('GS2_OP_ENTER_STATUS_1', 	'요청');		// 요청
define('GS2_OP_ENTER_STATUS_2', 	'확정');		// 요청접수 (납품처 장비 등록)
define('GS2_OP_ENTER_STATUS_3', 	'입력');		// 장비 스캔 & 확인
define('GS2_OP_ENTER_STATUS_4', 	'완료');		// 완료

// 설치
define('GS2_OP_INSTALL_STATUS_1', 	'요청'); 
define('GS2_OP_INSTALL_STATUS_2', 	'확정');
define('GS2_OP_INSTALL_STATUS_3', 	'점포완료');
define('GS2_OP_INSTALL_STATUS_4', 	'완료');
define('GS2_OP_INSTALL_STATUS_5', 	'승인');

// 철수
define('GS2_OP_CLOSE_STATUS_1', 	'요청'); 
define('GS2_OP_CLOSE_STATUS_2', 	'확정');
define('GS2_OP_CLOSE_STATUS_3', 	'점포완료');
define('GS2_OP_CLOSE_STATUS_4', 	'완료');
define('GS2_OP_CLOSE_STATUS_5', 	'승인');

// 이동
define('GS2_OP_MOVE_STATUS_1', 	'요청');		// 요청 - 장비등록
define('GS2_OP_MOVE_STATUS_2', 	'입력');		// 장비 스캔 & 확인
define('GS2_OP_MOVE_STATUS_3', 	'완료');		// 완료


// 상태변경
define('GS2_OP_CHANGE_STATUS_1', 	'생성'); 
define('GS2_OP_CHANGE_STATUS_2', 	'완료');

// 장비 관련
define('GS2_PART_TYPE_1', 	'시리얼');				// 시리얼관리장비
define('GS2_PART_TYPE_2', 	'수량');				// 수량관리장비
define('GS2_PART_TYPE_3', 	'소모품');				// 소모품

// 시리얼장비 상태
define('GS2_SERIAL_STATUS_OK', 		'1');
define('GS2_SERIAL_STATUS_NOTOK', 	'0');			
define('GS2_SERIAL_STATUS_INSTALL',	'2');			// 설치중
define('GS2_SERIAL_STATUS_FIX', 	'5');			// 수리대기		
define('GS2_SERIAL_STATUS_DESTORY', '6');			// 폐기대기
define('GS2_SERIAL_STATUS_MOVE', '7');			// 이동
define('GS2_SERIAL_STATUS_INSPECT', '9');			// 점검중
define('GS2_SERIAL_STATUS_LOST', 	'L');

// 점포 상태
define('GS2_STORE_STATUS_0',	'폐점');
define('GS2_STORE_STATUS_1',	'정상운영');
define('GS2_STORE_STATUS_2',	'휴점C');
define('GS2_STORE_STATUS_3',	'휴점S');
define('GS2_STORE_STATUS_4',	'리뉴얼');


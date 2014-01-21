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

// 회원 타입
define('GS2_USER_TYPE_1',	'시스네트');
define('GS2_USER_TYPE_2',	'GS25');
define('GS2_USER_TYPE_3',	'거래처');
define('GS2_USER_TYPE_9',	'관리자');

define('GS2_LOCATION_TYPE_OFFICE', 	'O');			// 사무소
define('GS2_LOCATION_TYPE_COMPANY', 'C');			// 거래처
define('GS2_LOCATION_TYPE_STORE', 	'S');			// 점포

//////////////
// 작업 관려
//////////////
define('GS2_OP_TYPE_ENTER', 	100);		// 입고
define('GS2_OP_TYPE_INSTALL', 	200);		// 설치
define('GS2_OP_TYPE_INSTALL_1', 201);		// 설치 - 신규
define('GS2_OP_TYPE_INSTALL_2', 202);		// 설치 - 개점S
define('GS2_OP_TYPE_INSTALL_3', 202);		// 설치 - 개점C
define('GS2_OP_TYPE_INSTALL_4', 203);		// 설치 - 리뉴얼

define('GS2_OP_TYPE_CLOSE',		300);		// 철수
define('GS2_OP_TYPE_CLOSE_1',	301);		// 철수 - 폐점
define('GS2_OP_TYPE_CLOSE_2',	302);		// 철수 - 휴점S
define('GS2_OP_TYPE_CLOSE_3',	303);		// 철수 - 휴점C

define('GS2_OP_TYPE_REPAIR', 	400);		// 수리
define('GS2_OP_TYPE_DESTROY', 	500);		// 폐기
define('GS2_OP_TYPE_REPLACE', 	600);		// 교체
define('GS2_OP_TYPE_MOVE', 		700);		// 이동
define('GS2_OP_TYPE_TRANS', 	800);		// 이관 (정확한 개념???)
define('GS2_OP_TYPE_CHANGE',	900);		// (상태변경)점검

// 입고
define('GS2_OP_ENTER_STATUS_1', 	'요청');		// 요청
define('GS2_OP_ENTER_STATUS_2', 	'확정');		// 요청접수 (납품처 장비 등록)
define('GS2_OP_ENTER_STATUS_3', 	'입력');		// 장비 스캔 & 확인
define('GS2_OP_ENTER_STATUS_4', 	'완료');		// 완료

// 설치
define('GS2_OP_INSTALL_STATUS_1', 	'요청'); 
define('GS2_OP_INSTALL_STATUS_2', 	'확정');
define('GS2_OP_INSTALL_STATUS_3', 	'점포완료');
define('GS2_OP_INSTALL_STATUS_4', 	'입력');
define('GS2_OP_INSTALL_STATUS_5', 	'완료');
define('GS2_OP_INSTALL_STATUS_6', 	'승인');

// 철수
define('GS2_OP_CLOSE_STATUS_1', 	'요청'); 
define('GS2_OP_CLOSE_STATUS_2', 	'확정');
define('GS2_OP_CLOSE_STATUS_3', 	'점포완료');
define('GS2_OP_CLOSE_STATUS_4', 	'입력');
define('GS2_OP_CLOSE_STATUS_5', 	'완료');
define('GS2_OP_CLOSE_STATUS_6', 	'승인');

// 장비 관련
define('GS2_PART_TYPE_1', 	'시리얼');				// 시리얼관리장비
define('GS2_PART_TYPE_2', 	'수량');				// 수량관리장비
define('GS2_PART_TYPE_3', 	'소모품');				// 소모품



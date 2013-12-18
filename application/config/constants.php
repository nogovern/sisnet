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

define('GS2_LOCATION_TYPE_OFFICE', 	'O');			// 사무소
define('GS2_LOCATION_TYPE_COMPANY', 'C');			// 거래처
define('GS2_LOCATION_TYPE_STORE', 	'S');			// 점포

define('GS2_OPERATION_STATUS_ENTER', 	100);			// 입고
define('GS2_OPERATION_STATUS_INSTALL', 	200);			// 설치
define('GS2_OPERATION_STATUS_CHANGE', 	300);			// 교체
define('GS2_OPERATION_STATUS_WITHDRAW',	400);			// 철수
define('GS2_OPERATION_STATUS_REPAIR', 	500);			// 장비 수리
define('GS2_OPERATION_STATUS_DESTROY', 	510);			// 장비 폐기
define('GS2_OPERATION_STATUS_MOVE', 	520);			// 장비 이동
define('GS2_OPERATION_STATUS_TRANS', 	530);			// 장비 이관
define('GS2_OPERATION_STATUS_ETC', 		900);			// 상태 변경


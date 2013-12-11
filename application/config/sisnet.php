<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 장비 카테고리
|--------------------------------------------------------------------------
| 관리성 때문에 
| DB 를 이용하기로 함
|
| 자주 참조되므로 배열로 캐싱 해 놓으면 좋겠다
*/
$config['gs2_categories'] = array(
	0	=>	'전체',
	1	=>	'POS스캐너',
	2	=>	'고정스캐너',
	3	=>	'모니터 CRT 공용',
	4	=>	'모니터 LCD 공용',
	5	=>	'서버(NT)',
	6	=>	'프린터 점포용',
	7	=>	'AP',
	8	=>	'GOT',
	9	=>	'HUB(Network)',
	10	=>	'POS 일체형',
	11	=>	'Scan Terminal',
	12	=>	'VPN',
	13	=>	'통합동글이',
	14	=>	'SC스캐너',
	15	=>	'BGM',
	16	=>	'소모품',
); 

/* End of file sisnet.php */
/* Location: ./application/config/sisnet.php */
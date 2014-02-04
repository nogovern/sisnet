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
$config['gs2']['category'] = array();

// 업무 형태 (대분류)
$config['gs2']['op_type'] = array(
	'100'	=> '입고',
	'200'	=> '설치',
	'201'	=> '설치-신규',
	'202'	=> '설치-서비스',
	'203'	=> '설치-휴점 보관',
	'204'	=> '설치-휴점 점검',
	'205'	=> '설치-교체',
	'206'	=> '설치-리뉴얼',
	'300'	=> '철수',
	'301'	=> '철수-일반',
	'302'	=> '철수-휴점 보관',
	'303'	=> '철수-휴점 점검',
	'304'	=> '철수-리뉴얼',
	'305'	=> '철수-교체',
	'309'	=> '철수-서비스',
	'400'	=> '교체',
	'500'	=> '수리',
	'600'	=> '폐기',
	'700'	=> '이동',
	'800'	=> '이관',
	'900'	=> '변경',
	'901'	=> '변경-점검',
);

// 업무 형태 (약어)
$config['gs2']['op_type_short'] = array(
	'100'	=> '입고',
	'200'	=> '설치',
	'201'	=> '신규',
	'202'	=> '서설',
	'203'	=> '휴설C',
	'204'	=> '휴설S',
	'205'	=> '교설',
	'206'	=> '리설',
	'300'	=> '철수',
	'301'	=> '철수',
	'302'	=> '서철',
	'303'	=> '휴철C',
	'304'	=> '휴철S',
	'305'	=> '교철',
	'306'	=> '리철',
	'400'	=> '교체',
	'500'	=> '수리',
	'600'	=> '폐기',
	'700'	=> '이동',
	'800'	=> '이관',
	'900'	=> '변경',
	'901'	=> '점검',
);

// 업무 link base url
$config['gs2']['op_url'] = array(
	'100'	=> 'work/enter/view',
	'200'	=> 'work/install/view',
	'201'	=> 'work/install/view',
	'202'	=> 'work/install/view',
	'203'	=> 'work/install/view',
	'204'	=> 'work/install/view',
	'205'	=> 'work/install/view',
	'206'	=> 'work/install/view',
	'300'	=> 'work/close/view',
	'301'	=> 'work/close/view',
	'302'	=> 'work/close/view',
	'303'	=> 'work/close/view',
	'304'	=> 'work/close/view',
	'305'	=> 'work/close/view',
	'309'	=> 'work/close/view',
	'400'	=> 'work/replace/view',
	'500'	=> 'work/repair/view',
	'600'	=> 'work/destroy/view',
	'700'	=> 'work/move/view',
	'800'	=> 'work/transfer/view',
	'900'	=> 'work/change/view',
	'901'	=> 'work/change/view',
);

// 점포 가맹 타입 (DB 에서 불러와야 함)
$config['gs2']['store_join_type'] = array(
	'1' => '가맹1종A(순수)',
	'2' => '가맹1종C(전대)',
	'3' => '직영A (일반)',
	'4' => '직영B',
	'5' => 'C타입',
	'6' => 'G타입',
	'7' => 'K타입',
	'8' => 'S타입',
);

$config['gs2']['close'] = array(
		
);

/* End of file sisnet.php */
/* Location: ./application/config/sisnet.php */
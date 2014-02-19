<?php
/**
 * Codeigniter Helper
 *
 * 이 사이트 에서 전역적으로 쓰이는 helper
 * 함수명은 'gs2_' 로 시작한다
 * -------------------------------------------------------------------
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

////////////////
// custom dump
////////////////
if (!function_exists('gs2_dump')) {
    function gs2_dump($var, $return=FALSE) {
        $output = "<pre>";
        $output .= print_r($var, TRUE);
        $output .= "</pre>";

        if(!$return)
            echo $output;
        else 
            return $output;
    }
}

// 작업종류 명을 리턴
if (!function_exists('gs2_get_work_name')) {
	function gs2_get_work_name($type) {
		$CI =& get_instance();

        $_config = $CI->config->item('gs2');

        if(array_key_exists($type, $_config['op_type'])) {
            return $_config['op_type'][$type];
        } else {
            return NULL;
        }
		
		exit;
	}
}

// select-option 용 array로 변환 
if(!function_exists('gs2_convert_for_dropdown')) {
    function gs2_convert_for_dropdown($rows) {
        $array = array();
        $array[0] = '-- 선택하세요 --';

        foreach($rows as $row) {
            $array[$row->id] = $row->name;
        }

        return $array;
    }
}

// 객체를 location 문자열로 변환하여 반환
// location 문자열 형태로 DB에 저장함
if(!function_exists('gs2_encode_location')) {
    function gs2_encode_location($obj) {
        if(!is_object($obj)){
            trigger_error('인자는 object 여야 합니다.');
            return FALSE;
        }
        $prefix = '';

        if($obj instanceof Entity\Office) {
            $prefix = 'O';
        } elseif ($obj instanceof Entity\Company) {
            $prefix = 'C';
        } elseif ($obj instanceof Entity\Store) {
            $prefix = 'S';
        } else {
            return FALSE;
        }

        return $prefix . '@' . $obj->id;
    }
}


// location 해석 (DB -> 화면)
if(!function_exists('gs2_decode_location')) {
    function gs2_decode_location($string)
    {
        if(is_null($string) || strlen($string) < 1) {
            log_message('error', '인자는 location 형식(S@1) 의 문자열이어야 합니다.');
            return NULL;
        }

        $CI =& get_instance();

        $arr = explode('@', $string);
        $instance = NULL;

        switch ($arr[0]) {
            case GS2_LOCATION_TYPE_COMPANY:
                $instance = $CI->doctrine->em->getReference('Entity\Company', intval($arr[1]));
                break;
            
            case GS2_LOCATION_TYPE_OFFICE:
                $instance = $CI->doctrine->em->getReference('Entity\Office', intval($arr[1]));
                break;
            
            case GS2_LOCATION_TYPE_STORE:
            default:
                $instance = $CI->doctrine->em->getReference('Entity\Store', intval($arr[1]));
                break;
        }

        return $instance;
    }
}

// 점포 - 가맹 타입
if(!function_exists('gs2_store_join_type')) {
    function gs2_store_join_type($type) {
        if(!$type)
            return NULL;
        
        $CI =& get_instance();
        $_config = $CI->config->item('gs2');

        return $_config['store_join_type'][$type];
    }
}

// 점포 - 상태
if(!function_exists('gs2_store_status')) {
    function gs2_store_status($type) {
        $CI =& get_instance();
        $_config = $CI->config->item('gs2');

        return $_config['store_status'][$type];
    }
}

// 업무 타입
if(!function_exists('gs2_op_type')) {
    function gs2_op_type($type=NULL) {
        $CI =& get_instance();

        $_config = $CI->config->item('gs2');
        $op_types = $_config['op_type'];

        return $op_types[$type];
    }
}

// 일반적인 업무 타입명 반환
if(!function_exists('gs2_op_general_type')) {
    function gs2_op_general_type($type=NULL) {

        // 303 인 경우 300 으로 변경 
        $type = intval($type);
        $type = floor($type / 100) * 100;

        return gs2_op_type($type);
    }
}

// 짧은 업무 타입명 반환
if(!function_exists('gs2_op_short_type')) {
    function gs2_op_short_type($type=NULL) {

        $CI =& get_instance();

        $_config = $CI->config->item('gs2');
        $op_types = $_config['op_type_short'];

        return $op_types[$type];
    }
}

// 시리얼 장비 상태
if(!function_exists('gs2_serial_part_status')) {
    function gs2_serial_part_status($type=NULL) {

        $CI =& get_instance();

        $_config = $CI->config->item('gs2');
        $op_types = $_config['serial_part_status'];

        return $op_types[$type];
    }
}  

// 업무 type 으로 상세보기 link 얻기
if(!function_exists('gs2_hover')) {
    function gs2_hover($type=NULL) {

        $CI =& get_instance();

        $_config = $CI->config->item('gs2');
        $url = $_config['op_url'];

        return base_url() . $url[$type] . '/';
    }
}  
 
// 장비 카테고리-장비 배열
if(!function_exists('gs2_category_parts')) {
    function gs2_category_parts($type=NULL) {
        $CI =& get_instance();

        $categories = array();

        $CI->load->model('category_m');
        $CI->load->model('part_m');
        $rows = $CI->category_m->getSubCategories(1);

        $em = $CI->category_m->getEntityManager();
        foreach($rows as $row) {
            $categories[$row->id] = array();
            $parts = $em->getRepository('Entity\Part')->findBy(array('category' => $row));
            foreach($parts as $part) {
                $categories[$row->id][$part->id] = $part->name;
            }
        }

        return $categories;
    }
}

// 재고 표시 변환 0 => "-" 로 변경
if(!function_exists('gs2_zero_to_dash')) {
    function gs2_zero_to_dash($num) {
        return ($num == 0) ? '-' : $num;
    }
}

// 장비 타입 문구
if(!function_exists('gs2_part_type')) {
    function gs2_part_type($idx) {
        $CI =& get_instance();

        $_config = $CI->config->item('gs2');
        $arr = $_config['part_type'];

        return $arr[$idx];
    }
}

 




<?php
/**
 * Codeigniter Helper
 *
 * 이 사이트 에서 전역적으로 쓰이는 helper
 * 함수명은 'gs2_' 로 시작한다
 * -------------------------------------------------------------------
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
        if(is_null($string) || !is_string($string)) {
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
if(!function_exists('gs2_get_store_join_type')) {
    function gs2_get_store_join_type($type) {
        if(!$type)
            return NULL;
        
        $CI =& get_instance();
        $_config = $CI->config->item('gs2');

        return $_config['store_join_type'][$type];
    }
}

// 점포 - 가맹 타입 리스트
if(!function_exists('gs2_get_store_join_types')) {
    function gs2_get_store_join_types() {

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
 




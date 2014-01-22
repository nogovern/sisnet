<?php
/**
 * Codeigniter Aler Helper
 * -------------------------------------------------------------------
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 경고메세지를 경고창으로
if (!function_exists('alert')) {
	function alert($msg='', $url='') {
		$CI =& get_instance();

		if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
		echo "<script type='text/javascript'>alert('".$msg."');";
	    if ($url)
	        echo "location.replace('".$url."');";
		else
			echo "history.go(-1);";
		echo "</script>";
		exit;
	}
}

// 경고메세지 출력후 창을 닫음
if (!function_exists('alert_close')) {
	function alert_close($msg) {
		$CI =& get_instance();

		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
		echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
		exit;
	}
}

// 경고메세지만 출력
if (!function_exists('alert_only')) {
	function alert_only($msg) {
		$CI =& get_instance();

		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
		echo "<script type='text/javascript'> alert('".$msg."'); </script>";
		exit;
	}
}

if (!function_exists('alert_continue')) {
	function alert_continue($msg){
		alert_only($msg);
		exit;
	}
}

// color popup 창에서 닫기 
if (!function_exists('alert_colorbox_close')) {
    function alert_colorbox_close($msg){
        $CI =& get_instance();

        echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
        echo "<script type='text/javascript'> alert('".$msg."'); parent.jQuery.fn.colorbox.close(); </script>";
        exit;
    }
}

//////////////////////////////////////////////////////
/// bootstrap element 를 이용한 메세지 출력           
//////////////////////////////////////////////////////
if (!function_exists('message_box')) {
    function message_box($message_type, $close_button = TRUE)
    {
        $CI =& get_instance();
        $message = $CI->session->flashdata($message_type);
        $retval = '';
        
        if($message){
            switch($message_type){
                case 'success':
                    $retval .= '<div class="alert alert-success">';
                    break;
                case 'error':
                    $retval .= '<div class="alert alert-error">';
                    break;
                case 'info':
                    $retval .= '<div class="alert alert-info">';
                    break;
                case 'warning':
                    $retval .= '<div class="alert">';
                    break;
            }

            if($close_button)
                $retval .= '<a class="close" data-dismiss="alert" href="#">&times;</a>';

            $retval .= $message;
            $retval .= '</div>';
            return $retval;
        }
    }
}

if (!function_exists('set_message')){
    function set_message($type, $message)
    {
        $CI =& get_instance();
        $CI->session->set_flashdata($type, $message);
    }
}
?>
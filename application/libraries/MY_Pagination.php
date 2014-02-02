<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Pagination extends CI_Pagination {
    
    public function __construct()
    {
        parent::__construct();
        
        $config['full_tag_open'] = '<ul class="pagination pagination-sm pagination">';
        $config['full_tag_close'] = '</ul><!--pagination-->';
         
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
         
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
         
        $config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
         
        $config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
         
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
         
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';
         
        $config['anchor_class'] = 'class="follow_link"';

        // init these vars
        $this->initialize($config);
    }
}
// END Pagination Class
 
/* End of file MY_Pagination.php */
/* Location: ./application/libraries/MY_Pagination.php */
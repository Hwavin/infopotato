<?php
/**
 * Sort an array by the given key in the given order
 *
 * @param array $list the input array to be sorted
 * @param string $field the key by which the array to be sorted
 * @param string $sortby order (asc, desc, nat)
 * @return	sorted list
 */
function sort_by($list, $field, $sortby = 'asc') {
   if (is_array($list)) {
       $refer = $result_set = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 
                asort($refer);
                break;
           case 'desc':// reverse order
                arsort($refer);
                break;
           case 'nat': // natural order
                natcasesort($refer);
                break;
       }
       foreach ($refer as $key=> $val) {
           $result_set[] = &$list[$key];
       }
	   return $result_set;
   }
   return FALSE;
}

// End of file: ./system/scripts/sort_by/sort_by_script.php

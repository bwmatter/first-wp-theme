<?php 

// =========================================================================
// Disable YOAST Json
// =========================================================================
function bybe_remove_yoast_json($data){
    $data = array();
    return $data;
  }
add_filter('wpseo_json_ld_output', 'bybe_remove_yoast_json', 10, 1);
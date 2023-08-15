<?php 

/**
  |
  | Function for debugging
  | @Args   :-  1) `var` variable (could be String, Number, Array or Object)
  |             2) `true` or `false` (if true then it will exit) (by default its true)
  | @Returns:- Printed Variable
  |
 */
function pr($var, $bool = true) {
    if (is_array($var) || is_object($var)) {
        echo "<pre>";
        print_r($var);
    } else {
        echo $var;
    }
    if ($bool == true) {
        exit;
    }
}

function get($dataArray,$code="GMB402",$status=true) {
    if(!is_array($dataArray)) {
        pr("First arg needs to be array.");
    }
    $array['status'] = $status;
    $array['code'] = $code;
    foreach ($dataArray as $key => $value) {
        $array[$key] = $value;
    }
    return $array;
}

?>
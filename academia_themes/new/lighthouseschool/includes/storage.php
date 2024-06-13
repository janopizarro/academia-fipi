<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('lighthouseschool_storage_get')) {
	function lighthouseschool_storage_get($var_name, $default='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		return isset($LIGHTHOUSESCHOOL_STORAGE[$var_name]) ? $LIGHTHOUSESCHOOL_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('lighthouseschool_storage_set')) {
	function lighthouseschool_storage_set($var_name, $value) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		$LIGHTHOUSESCHOOL_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('lighthouseschool_storage_empty')) {
	function lighthouseschool_storage_empty($var_name, $key='', $key2='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key]);
		else
			return empty($LIGHTHOUSESCHOOL_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('lighthouseschool_storage_isset')) {
	function lighthouseschool_storage_isset($var_name, $key='', $key2='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key]);
		else
			return isset($LIGHTHOUSESCHOOL_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('lighthouseschool_storage_inc')) {
	function lighthouseschool_storage_inc($var_name, $value=1) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (empty($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = 0;
		$LIGHTHOUSESCHOOL_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('lighthouseschool_storage_concat')) {
	function lighthouseschool_storage_concat($var_name, $value) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (empty($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = '';
		$LIGHTHOUSESCHOOL_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('lighthouseschool_storage_get_array')) {
	function lighthouseschool_storage_get_array($var_name, $key, $key2='', $default='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key]) ? $LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key][$key2]) ? $LIGHTHOUSESCHOOL_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('lighthouseschool_storage_set_array')) {
	function lighthouseschool_storage_set_array($var_name, $key, $value) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if ($key==='')
			$LIGHTHOUSESCHOOL_STORAGE[$var_name][] = $value;
		else
			$LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('lighthouseschool_storage_set_array2')) {
	function lighthouseschool_storage_set_array2($var_name, $key, $key2, $value) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key])) $LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$LIGHTHOUSESCHOOL_STORAGE[$var_name][$key][] = $value;
		else
			$LIGHTHOUSESCHOOL_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('lighthouseschool_storage_merge_array')) {
	function lighthouseschool_storage_merge_array($var_name, $key, $value) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if ($key==='')
			$LIGHTHOUSESCHOOL_STORAGE[$var_name] = array_merge($LIGHTHOUSESCHOOL_STORAGE[$var_name], $value);
		else
			$LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] = array_merge($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('lighthouseschool_storage_set_array_after')) {
	function lighthouseschool_storage_set_array_after($var_name, $after, $key, $value='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if (is_array($key))
			lighthouseschool_array_insert_after($LIGHTHOUSESCHOOL_STORAGE[$var_name], $after, $key);
		else
			lighthouseschool_array_insert_after($LIGHTHOUSESCHOOL_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('lighthouseschool_storage_set_array_before')) {
	function lighthouseschool_storage_set_array_before($var_name, $before, $key, $value='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if (is_array($key))
			lighthouseschool_array_insert_before($LIGHTHOUSESCHOOL_STORAGE[$var_name], $before, $key);
		else
			lighthouseschool_array_insert_before($LIGHTHOUSESCHOOL_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('lighthouseschool_storage_push_array')) {
	function lighthouseschool_storage_push_array($var_name, $key, $value) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($LIGHTHOUSESCHOOL_STORAGE[$var_name], $value);
		else {
			if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key])) $LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] = array();
			array_push($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('lighthouseschool_storage_pop_array')) {
	function lighthouseschool_storage_pop_array($var_name, $key='', $defa='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($LIGHTHOUSESCHOOL_STORAGE[$var_name]) && is_array($LIGHTHOUSESCHOOL_STORAGE[$var_name]) && count($LIGHTHOUSESCHOOL_STORAGE[$var_name]) > 0) 
				$rez = array_pop($LIGHTHOUSESCHOOL_STORAGE[$var_name]);
		} else {
			if (isset($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key]) && is_array($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key]) && count($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('lighthouseschool_storage_inc_array')) {
	function lighthouseschool_storage_inc_array($var_name, $key, $value=1) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if (empty($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key])) $LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] = 0;
		$LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('lighthouseschool_storage_concat_array')) {
	function lighthouseschool_storage_concat_array($var_name, $key, $value) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if (!isset($LIGHTHOUSESCHOOL_STORAGE[$var_name])) $LIGHTHOUSESCHOOL_STORAGE[$var_name] = array();
		if (empty($LIGHTHOUSESCHOOL_STORAGE[$var_name][$key])) $LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] = '';
		$LIGHTHOUSESCHOOL_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('lighthouseschool_storage_call_obj_method')) {
	function lighthouseschool_storage_call_obj_method($var_name, $method, $param=null) {
		global $LIGHTHOUSESCHOOL_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($LIGHTHOUSESCHOOL_STORAGE[$var_name]) ? $LIGHTHOUSESCHOOL_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($LIGHTHOUSESCHOOL_STORAGE[$var_name]) ? $LIGHTHOUSESCHOOL_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('lighthouseschool_storage_get_obj_property')) {
	function lighthouseschool_storage_get_obj_property($var_name, $prop, $default='') {
		global $LIGHTHOUSESCHOOL_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($LIGHTHOUSESCHOOL_STORAGE[$var_name]->$prop) ? $LIGHTHOUSESCHOOL_STORAGE[$var_name]->$prop : $default;
	}
}
?>
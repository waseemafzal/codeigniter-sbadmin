<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
CREATE BY : WASEEM AFZAL
EMAIL     : waseemafzal31@gmail.com

|-------------------------------------------------------
| 
get_by_status($tableName)
select_from_where($this,$table,$field,$val)
get_table_by_user_id($tableName,$id) 
get_field_where($id,$fields,$tableName)
get_where($id,$tableName)
get_All_by_fieldName($field,$tableName)
get_title_by_fieldName($id,$field,$tableName)
get_title($id,$tableName)
|-------------------------------------------------------
*/
function getFormFields($arr)
{
	foreach ($arr as $key => $val) {
		$html .= '<input name="' . $key . ' value="' . $val . '""';
	}
	return $html;
}
function lasturi()
{
	$CI = &get_instance();
	return end($CI->uri->segment_array());
}
function prelasturi()
{
	$refrerlink = $_SERVER['HTTP_REFERER'];
	$link_array = explode('/', $refrerlink);
	return end($link_array);
}
function html_cut($text, $max_length)
{
	$tags   = array();
	$result = "";

	$is_open   = false;
	$grab_open = false;
	$is_close  = false;
	$in_double_quotes = false;
	$in_single_quotes = false;
	$tag = "";

	$i = 0;
	$stripped = 0;

	$stripped_text = strip_tags($text);

	while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length) {
		$symbol  = $text{
		$i};
		$result .= $symbol;

		switch ($symbol) {
			case '<':
				$is_open   = true;
				$grab_open = true;
				break;

			case '"':
				if ($in_double_quotes)
					$in_double_quotes = false;
				else
					$in_double_quotes = true;

				break;

			case "'":
				if ($in_single_quotes)
					$in_single_quotes = false;
				else
					$in_single_quotes = true;

				break;

			case '/':
				if ($is_open && !$in_double_quotes && !$in_single_quotes) {
					$is_close  = true;
					$is_open   = false;
					$grab_open = false;
				}

				break;

			case ' ':
				if ($is_open)
					$grab_open = false;
				else
					$stripped++;

				break;

			case '>':
				if ($is_open) {
					$is_open   = false;
					$grab_open = false;
					array_push($tags, $tag);
					$tag = "";
				} else if ($is_close) {
					$is_close = false;
					array_pop($tags);
					$tag = "";
				}

				break;

			default:
				if ($grab_open || $is_close)
					$tag .= $symbol;

				if (!$is_open && !$is_close)
					$stripped++;
		}

		$i++;
	}

	while ($tags)
		$result .= "</" . array_pop($tags) . ">";
	if (strlen($result) < $max_length) {
		return strip_tags($result);
		exit;
	}
	return strip_tags($result) . ' ...';
}

function getYoutubeImage($e)
{
	//GET THE URL
	$url = $e;
	$queryString = parse_url($url, PHP_URL_QUERY);
	parse_str($queryString, $params);
	$v = $params['v'];
	//DISPLAY THE IMAGE
	if (strlen($v) > 0) {
		$src = 'http://i3.ytimg.com/vi/' . $v . '/default.jpg';
		return $src;
	}
}

function getHead()
{
	$CI = &get_instance();
	return $CI->load->view("admin/header");
}
function adminAvatar()
{
	$CI = &get_instance();
	$avatar = 'dist/img/user2-160x160.jpg';

	$image = $CI->db->select('image')->where('id', get_session('user_id'))->get(TBL_USER)->row()->image;
	if ($image != '') {
		$image = 'uploads/' . $image;
		if (file_exists($image) == true) {
			$avatar = $image;
		}
	}
	return $avatar;
}
function adminName()
{
	$CI = &get_instance();
	$row = $CI->db->select('name,email')->where('id', get_session('user_id'))->get(TBL_USER)->row();
	if ($row->name != '') {
		return ucfirst($row->name);
	} else {
		return $row->email;
	}
}
function role()
{
	$CI = &get_instance();
	return $CI->db->select('group_title as role')->where('id', get_session('user_type'))->get('users_rights')->row()->role;
}
function roleID()
{
	$CI = &get_instance();
	return $CI->db->select('id as role')->where('id', get_session('user_type'))->get('users_rights')->row()->role;
}

function hello()
{

	return ("admin/header");
}
function getFooter()
{
	$CI = &get_instance();
	return $CI->load->view("admin/footer");
}
function getMyscript()
{
	$CI = &get_instance();
	return $CI->load->view("admin/myscript");
}


function if_active($url)
{
	if (strpos($_SERVER['REQUEST_URI'], $url) !== false) {
		echo  'active';
	}
}
if (!function_exists('view_loader')) {
	function view_loader($view, $vars = array(), $output = false)
	{
		$CI = &get_instance();
		return $CI->load->view($view, $vars, $output);
	}
}


/****/
function reset_pasword_if_email_exist($email)
{
	$CI = &get_instance();
	$arr = array('email' => $email);
	$data = $CI->db->select('*')->where($arr)->get('users');

	if ($data->num_rows() > 0) {

		$key = randomKey(6);
		$password = md5($key);
		$data_array = array('password' => $password);
		$CI->db->where('email', $email);
		$result = $CI->db->update('truth_users', $data_array);
		if ($result) {
			return $key;
		}
	} else {
		return 0;
	}
}
function randomKey($length)
{
	$pool = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
	$key = '';
	for ($i = 0; $i < $length; $i++) {
		$key .= $pool[mt_rand(0, count($pool) - 1)];
	}
	return $key;
}



/**************************getting data from tables *******************************/
function get_table($tableName)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM " . $tableName . " ");
	return $query;
}
function checkExist($table, $where)
{
	$CI = &get_instance();
	$CI->db->select('*');
	$CI->db->from($table);
	$CI->db->where($where);
	$data = $CI->db->get();

	if ($data->num_rows() > 0) {
		return 1;
	} else {
		return 0;
	}
}
function get_All_by_fieldName($field, $tableName)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT " . $field . " as title FROM " . $tableName . " ");
	return $query->row()->title;
}

function select_from_where($fields, $table, $field, $val)
{
	$CI = &get_instance();
	$CI->db->select($fields)->from($table)->where($field, $val);
	$data = $CI->db->get();
	if ($data->num_rows() > 0) {
		return $data;
	} else {
		return 0;
	}
}



function select_this($val, $table)
{
	$CI = &get_instance();
	$CI->db->distinct();
	$CI->db->select($val)->from($table);
	$data = $CI->db->get();

	if ($data->num_rows() > 0) {
		return $data;
	} else {
		return 0;
	}
}
function get_where($id, $tableName)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM " . $tableName . " WHERE id ='" . $id . "'");
	return $query->row();
}
function get_last_record($tableName)
{
	$CI = &get_instance();
	$last_row = $CI->db->select('*')->order_by('id', "desc")->limit(1)->get($tableName)->row();
	return $last_row;
}
function count_tbl_where($tableName, $condition, $value)
{
	$CI = &get_instance();
	return $CI->db->where($condition, $value)->count_all_results($tableName);
}

function getcount($tableName)
{
	$CI = &get_instance();
	return $CI->db->from($tableName)->count_all_results();
}




function get_field_where($id, $fields, $tableName)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT " . $fields . " FROM " . $tableName . " WHERE id ='" . $id . "'");
	return $query->row();
}
function get_by_where_array($array, $table)
{
	$CI = &get_instance();
	$query = $CI->db->select('*')->where($array)->get($table);
	return $query;
}
function get_field_by_where_array($field, $array, $table)
{
	$CI = &get_instance();
	$query = $CI->db->select($field)->where($array)->get($table);
	return $query;
}


function get_random_field_by_where_array($field, $array, $table)
{
	$CI = &get_instance();
	$query = $CI->db->select($field)->where($array)->order_by('rand()')->get($table);
	return $query;
}


/*********************************************************************************************/
function get_tbl_users_rights()
{
	$CI = &get_instance();
	$query = $CI->db->select('*')->get('users_rights');
	return $query;
}

function get_usersType_title($id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT group_title FROM  users_rights WHERE id='" . $id . "' ");
	return $query->row()->group_title;
}

function noData($colspan)
{
	$row .= '<tr class="noData_row">';
	$row .= '<td>' . this_lang("record not found ") . '!</td>';
	for ($i = 1; $i < $colspan; $i++) {
		$row .= '<td>&nbsp;</td>';
	}
	$row .= '</tr>';

	$row;
}
function get_session($variable)
{
	$CI = &get_instance();
	return $CI->session->userdata($variable);
}
function this_lang($variable)
{
	$CI = &get_instance();
	// making constant eg. From User Management to USER_MANAGEMENT
	$constant = $variable;
	$constant = strtoupper($constant); // constant to uppercase
	$constant = str_replace(' ', '_', $constant); // replace spaces with underscore
	$ucfirst_constant = ucfirst($constant); // constant in lo
	/********************************************/
	if ($CI->session->userdata($variable)) {
		// checking if constant exist in session as passed eg. USER_MANAGEMENT
		return $CI->session->userdata($variable);
	} else if ($CI->session->userdata($constant)) {
		// checking if created constant exist in session 
		return $CI->session->userdata($constant);
	} else if ($CI->session->userdata($ucfirst_constant)) {
		// checking if created constant exist in session 
		return $CI->session->userdata($ucfirst_constant);
	} else {
		// if nothing available then return in the form of text like 
		// USER_MANAGEMENT to User Management 
		$str = str_replace('_', ' ', $variable);
		$str = strtolower($str);
		$str =  ucwords($str);
		return $str;
	}
}
function get_unused_id($table, $field)
{
	// Create a random user id unique_id_number


	// Make sure the random user_id isn't already in use
	$CI = &get_instance();
	$random_unique_int = izrand(6);
	$CI->db->where($field, $random_unique_int);
	$query = $CI->db->get_where($table);
	if ($query->num_rows() > 0) {
		$query->free_result();

		// If the random user_id is already in use, get a new number
		$this->get_unused_id();
	}

	return $random_unique_int;
}

function izrand($length = 10)
{

	$random_string = "";
	while (strlen($random_string) < $length && $length > 0) {
		$randnum = mt_rand(0, 61);
		$random_string .= ($randnum < 10) ?
			chr($randnum + 48) : ($randnum < 36 ?
				chr($randnum + 55) : $randnum + 61);
	}
	return $random_string;
}



function get_title($id, $tableName)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT title FROM " . $tableName . " WHERE id ='" . $id . "'");
	return $query->row()->title;
}
function get_user_type_title($id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT group_title FROM users_rights WHERE id ='" . $id . "'");
	return $query->row()->group_title;
}

function get_cat_title($id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT cat_name FROM categories WHERE id ='" . $id . "'");
	return $query->row()->cat_name;
}

function get_cat()
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM categories");
	return $query;
}
function get_page_content_image($id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT image FROM " . TBL_PAGES_CONTENT_IMAGES . " WHERE page_content_id ='" . $id . "'");
	return $query->row()->image;
}
function get_page_content_images($id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM " . TBL_PAGES_CONTENT_IMAGES . " WHERE page_content_id ='" . $id . "'");
	return $query;
}



function get_title_by_fieldName($id, $field, $tableName)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT " . $field . " as title FROM " . $tableName . " WHERE id ='" . $id . "'");
	//echo  $CI->db->last_query(); die('L');
	return $query->row()->title;
}

function get_table_by_user_id($tableName, $id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM " . $tableName . " WHERE user_id =" . $id . "");
	return $query;
}



function get_by_status($tableName)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM " . $tableName . " where status = 1 ");
	return $query;
}





/*
	---------------
	Debuging
	*/
function pre($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	die('<===========>');
}

function lq()
{
	$CI = &get_instance();
	echo $CI->db->last_query();
	die(' <=====Last query exe======> ');
}

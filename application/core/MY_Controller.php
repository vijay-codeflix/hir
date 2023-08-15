<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('common_model');
		$this->load->model('api/Api');
		$this->load->helper('directory');
	}

	public function getLogSession($key)
	{
		if (isset($this->session->userdata['logged_in'][$key])) {
			return $this->session->userdata['logged_in'][$key];
		} else {
			return FALSE;
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(BASE_URL . 'admin', 'refresh');
	}

	public function checkSess()
	{
		if ($this->session->userdata('logged_in') != '') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkPermission($action = '')
	{
		$className = strtolower(get_called_class()); // get class name where function is called
		$urole = $this->session->userdata['logged_in']['usertype']; // gey logged in user type
		// This is use for check permission
		/* if ($this->acl->has_permission($className, $urole, $action)){ */
		if (true) {
			return TRUE;
		} else {
			redirect(CURRENT_MODULE . 'login');
			//return FALSE;

		}
	}

	public function viewAdmin($view, $data = array())
	{
		// echo "<pre>";
		// print_r($view);
		// exit();
		$data['scriptload'] = $view;

		$this->load->view('includes/admin_header', $data); // header admin

		$this->load->view($view, $data); // load main content view

		$this->load->view('includes/admin_footer', $data); // load footer
	}

	public function viewLogin($view, $data = array())
	{

		$data['scriptload'] = $view;

		$this->load->view('header', $data); // header admin

		$this->load->view($view, $data); // load main content view

		$this->load->view('footer', $data); // load footer
	}

	public function get_user_type()
	{

		$result = $this->common_model->get_usertype();

		return $result;
	}

	public function createDir($path)
	{
		if (!file_exists($path)) {
			mkdir($path);
			chmod($path, 0777);
		}
	}

	public function viewCurrent($view, $data = array())
	{
		$this->load->view($view, $data); // load main content view
	}

	public function viewCurrentFooter($view, $data = array())
	{
		$this->load->view($view, $data); // load main content view
		$this->load->view('includes/page_footer', $data); // load footer
	}

	public function checkid($id, $tablename, $editid)
	{
		$this->db->select($id);
		$this->db->from($tablename);
		$this->db->where($id, $editid, TRUE);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkValidDomain($email)
	{

		$domain = substr($email, strpos($email, '@') + 1);

		if (checkdnsrr($domain) !== FALSE) {
			return true;
		} else {
			$this->form_validation->set_message('checkValidDomain', 'Please enter email with correct domain name !! ');
			return false;
		}
	}

	function alpha_dash_backsplash($str, $msg)
	{
		$validate = (!preg_match("/^([-0-9-a-z_\/])+$/i", $str)) ? FALSE : TRUE;

		if ($validate) {

			return true;
		} else {
			// custom error message
			$this->form_validation->set_message('alpha_dash_backsplash', 'Allow only alphanumeric characters, back splash and hyphen');
			return false;
		}
	}

	function alpha_dash_space($str, $msg)
	{
		$validate = (!preg_match("/^([-0-9-a-z_\/ ])+$/i", $str)) ? FALSE : TRUE;

		if ($validate) {

			return true;
		} else {
			// custom error message
			$this->form_validation->set_message('alpha_dash_space', 'Allow only alphanumeric characters, white spaces and hyphen');
			return false;
		}
	}

	function alpha_space($str, $msg)
	{
		$validate = (!preg_match("/^([-a-z ])+$/i", $str)) ? FALSE : TRUE;

		if ($validate) {

			return true;
		} else {
			// custom error message
			$this->form_validation->set_message('alpha_space', 'Allow only alphabetical characters & white spaces');
			return false;
		}
	}

	function alpha($str, $msg)
	{
		$validate = (!preg_match("/^([-a-z])+$/i", $str)) ? FALSE : TRUE;

		if ($validate) {

			return true;
		} else {
			// custom error message
			$this->form_validation->set_message('alpha', 'Allow only alphabetical characters');
			return false;
		}
	}

	function alpha_numeric($str, $msg)
	{
		$validate = (!preg_match("/^([0-9a-z])+$/i", $str)) ? FALSE : TRUE;

		if ($validate) {

			return true;
		} else {
			// custom error message
			$this->form_validation->set_message('alpha', 'Allow only alphanumeric characters');
			return false;
		}
	}

	function alpha_numeric_special($str, $msg)
	{
		$validate = (!preg_match("/^([0-9 a-z,.-])+$/i", $str)) ? FALSE : TRUE;

		if ($validate) {

			return true;
		} else {
			// custom error message
			$this->form_validation->set_message('alpha', 'Allow only alphanumeric characters');
			return false;
		}
	}

	public function changeCase($str = NULL, $case = 'lower')
	{

		if ($str != NULL) {

			switch ($case) {
				case 'lower':
					return strtolower($str);
					break;

				case 'upper':
					return strtoupper($str);
					break;

				case 'ucfirst':
					return ucfirst($str);
					break;

				case 'ucfirstLower':
					return ucfirst(strtolower($str));
					break;

				default:
					# code...
					break;
			}
		}
	}

	public function pr($data)
	{
		echo "<pre>";
		print_r($data);
		exit;
	}

	public function isMobile()
	{
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	public function hex2rgb($hex)
	{

		$hex = str_replace("#", "", $hex);

		if (strlen($hex) == 3) {
			$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
		} else {
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}

	public function ForceDownloadImage($fileSource = NULL, $outputFileDest = NULL)
	{

		$contentOrFalseOnFailure   = file_get_contents($fileSource);

		$byteCountOrFalseOnFailure = file_put_contents($outputFileDest, $contentOrFalseOnFailure);

		chmod($outputFileDest, 0777);
	}

	public function deleteUnusedImages()
	{
		$this->db->select('image');
		$this->db->from('prod_variants');
		$this->db->where('image !=', PRODUCT_THUMB);

		$res = $this->db->get();

		if ($res->num_rows() > 0) {

			$result = $res->result();

			$AllImages = array();

			foreach ($result as $value) {
				$AllImages[] = $value->image;
				$img = explode('thumb_', $value->image);
				$AllImages[] = 'full_' . $img[1];
			}

			$AllImages[] = PRODUCT_FULL;
			$AllImages[] = PRODUCT_THUMB;

			$map = directory_map(PRODUCT_DIR);

			if (!empty($map)) {
				$i = 0;
				foreach ($map as $fileName) {

					if (!in_array($fileName, $AllImages)) {
						unlink(PRODUCT_DIR . $fileName);
					}
				}
				echo "done";
			}
		} else {
			return false;
		}
	}
}

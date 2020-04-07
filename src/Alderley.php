<?php

class Alderley {

	public static function authentication_required()
	{
			session_start();
			if (empty($_SESSION['auth'])) {
					http_response_code(401);
			}
	}


	public static function authenticate()
	{
			session_start();
			$_SESSION['auth'] = true;
	}


	public static function deauthenticate()
	{
			session_start();
			unset($_SESSION['auth']);
	}


	public static function generate_password(int $password_length)
	{

			$keyspace = array(
					'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
					'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
					's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A',
					'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
					'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
					'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2',
					'3', '4', '5', '6', '7', '8', '9', '0', '!',
					'@', '#', '$', '%', '^', '&', '*', '(', ')');
					
			$password = '';
			for ($i = 0; $i < $password_length; $i++) {
					$password .= $keyspace[random_int(0, count($keyspace) - 1)];
			}
			return $password;
	}


	public static function get_configuration(string $cfg_file_path)
	{
			return parse_ini_file($cfg_file_path);
	}


	public static function new_csrf_token()
	{
			session_start();
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}


	public static function get_csrf_token()
	{
			session_start();
			return $_SESSION['csrf_token'];
	}


	public static function check_csrf_token(string $token)
	{
			session_start();
			return hash_equals($_SESSION['csrf_token'], $token);
	}


	public static function get_database(string $db_uri, string $db_user)
	{
			return new PDO($db_uri, $db_user);
	}


	public static function resize_image(string $image_in, string $image_out, int $cols, int $rows)
	{
			$image = new Imagick($image_in);
			$image->adaptiveResizeImage($cols, $rows, true);
			$image->writeImage($image_out);
			$image->destroy();
	}


	public static function thumbnail_image(string $image_in, string $image_out, int $cols, int $rows)
	{
			$image = new Imagick($image_in);
			$image->thumbnailImage($cols, $rows, true);
			$image->writeImage($image_out);
			$image->destroy();
	}


	public static function sanitize_input(string $i)
	{
			return htmlspecialchars(stripslashes(trim($i)));
	}


	public static function sanitize_string(string $s)
	{
			return filter_var(sanitize_input($s), FILTER_SANITIZE_STRING);
	}


	public static function sanitize_integer(int $i)
	{
			return filter_var(sanitize_input($i), FILTER_SANITIZE_NUMBER_INT);
	}


	public static function sanitize_email(string $e)
	{
			return filter_var(sanitize_input($e), FILTER_SANITIZE_EMAIL);
	}


	public static function contact_mail(string $mail_to, string $mail_from, string $subject, array $fields)
	{
			$body = '';
			foreach ($fields as $field) {
					list($label, $value) = $field;
					$body .= $label.': '.$value.PHP_EOL;
			}
			$headers = 'From: '.$mail_from;
			mail($mail_to, $subject, $body, $headers);
	}


	public static function get_pagination_offset(int $page, int $limit = 9)
	{
			return ($page - 1) * $limit;
	}


	public static function redirect(string $uri)
	{
			header('Location: '.$uri);
	}


	public static function x_accel_redirect(string $uri)
	{
			header('X-Accel-Redirect: '.$uri);
	}


	public static function create_slug(string $s)
	{
			return str_replace(
					" ",
					"-",
					strtolower(preg_replace("/[^0-9a-zA-Z ]/", "", $s))
			);
	}
}

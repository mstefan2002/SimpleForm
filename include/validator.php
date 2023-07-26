<?php 

class Validator
{
	/**
	 * Check if the mail is valid(under 100 len and pass the php filter validate email)
	 *
	 * @param string $email
	 * 
	 * @return bool
	 * 
	 */
	public static function email(string $email) : bool
	{
		if(strlen($email) > 100||!filter_var($email, FILTER_VALIDATE_EMAIL))
			return false;

		return true;
	}
	/**
	 * Check if the name is valid(len is `higher then 2` and `lower then 65` and all the chars is like `A-Za-z` or `space`[not all])
	 * Another alternative could be using regex to check for Unicode characters, commas, apostrophes, and periods.
	 *
	 * @param string $name
	 * 
	 * @return bool
	 * 
	 */
	public static function name(string $name) : bool
	{
		$len = strlen($name);
		if($len < 3 || $len > 64)
			return false;

		if(trim($name) < 2)
			return false;

		for($i=0;$i<$len;++$i)
		{ 
			$aux = $name[$i];
			if($aux == ' ' || 'A' <= $aux &&  $aux <= 'Z' || 'a' <= $aux &&  $aux <= 'z')
				continue;
			return false;
		}
		return true;
	}

	/**
	 * Check if the image is valid
	 *
	 * @param string $name
	 * 
	 * @return bool
	 * 
	 */

	public static function image(string $imageField) : bool
	{
		if(empty($imageField))
			return false; // Empty imagefield
		if(!isset($_FILES[$imageField]))
			return false; // No file was uploaded

		$file = $_FILES[$imageField];
		if(!isset($file['tmp_name']))
			return false; // No file was uploaded
		
		$image = $file['tmp_name'];
		if(empty($image))
			return false; // No file was uploaded

		$fileInfo = getimagesize($image);
		if($fileInfo === false)
			return false; // File is not an image

		$allowedExtensions = array('jpg', 'jpeg', 'png', 'bmp');
		$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

		if(!in_array(strtolower($fileExtension), $allowedExtensions))
			return false; // Invalid file format. Only JPG, JPEG, PNG, and BMP files are allowed

		return true;
	}
}
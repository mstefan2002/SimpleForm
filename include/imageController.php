<?php

class ImageController
{
	public static function resize(string $imageField, int $maxPixels) : string
	{
		$file = $_FILES[$imageField];
		$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$uniqueFileName = uniqid() . '.' . $fileExtension;

		$uploadDirectory = 'assets/images/uploads/';
        	$targetPath = $uploadDirectory . $uniqueFileName;

		if (!move_uploaded_file($file['tmp_name'], $targetPath))
			Output::error("Error uploading the file.");

        	list($width, $height) = getimagesize($targetPath);
		if ($width > $maxPixels || $height > $maxPixels)
		{
			$aspectRatio = $width / $height;

			if ($width > $height)
			{
				$newWidth = $maxPixels;
				$newHeight = $maxPixels / $aspectRatio;
			}
			else
			{
				$newHeight = $maxPixels;
                		$newWidth = $maxPixels * $aspectRatio;
            		}

            		$resizedImage = imagecreatetruecolor($newWidth, $newHeight);
           		$sourceImage = imagecreatefromstring(file_get_contents($targetPath));

           		imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            		imagedestroy($sourceImage);

            		imagepng($resizedImage, $targetPath);
            		imagedestroy($resizedImage);
        	}
		return $targetPath;
	}
}
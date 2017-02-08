<?php
Class Captcha_model extends CI_Model
{
	/**
	 * Returns all classes from DB
	 *
	 * @return array
	 */
	public function getRandomWord()
	{
		$words = array('audiovisuel', 'mandarine','tungstene', 'perche', 'cinch', 'minijack', 'microphone',
						'mixage', 'montage', 'masques', 'calques', 'codec', 'fresnel', 'halogene',
						'audition', 'chromatique', 'fondu');

		// Note: array_rand uses the libc generator, which is slower and less-random than Mersenne Twister.
		return  strtoupper($this->_generateRandomString(1) . $words[mt_rand(0, count($words) - 1)]);
	}

	private function _generateRandomString($length = 10)
	{
	    $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++)
		{
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
	}
}
?>

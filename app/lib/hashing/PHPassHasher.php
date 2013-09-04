<?php namespace StickyNotes\Hashing;

use Illuminate\Hashing\HasherInterface;

class PHPassHasher implements HasherInterface {

	/**
	 * Hash the given value.
	 *
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function make($value, array $options = array())
	{
		return \PHPass::make()->create($value, NULL);
	}

	/**
	 * Check the given plain value against a hash.
	 *
	 * @param  string  $value
	 * @param  string  $hashedValue
	 * @param  array   $options
	 * @return bool
	 */
	public function check($value, $hashedValue, array $options = array())
	{
		return \PHPass::make()->check('hasher', $value, NULL, $hashedValue);
	}

	/**
	 * Check if the given hash has been hashed using the given options.
	 *
	 * @param  string  $hashedValue
	 * @param  array   $options
	 * @return bool
	 */
	public function needsRehash($hashedValue, array $options = array())
	{
		return FALSE;
	}

}

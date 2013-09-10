<?php

class SiteTest extends TestCase {

	/**
	 * Sets up the test case data.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * A basic functional test for the site.
	 *
	 * @return void
	 */
	public function testSite()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());
	}

}

<?php

use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\Response;

class ApiControllerTest extends TestCase {

	public function testFormatJsendAcceptsArrayData() {
		$data = array(1,2,3);

		/*
		 * Note: we cannot mock Response as a facade because it
		 * doesn't extend Facade. So, we have to inspect the result
		 */
		$response = ApiController::formatJsend($data);

		$actual = json_decode($response->getContent(), true)['data'];

		$this->assertEquals($data, $actual);
	}

	public function testFormatJsendAcceptsCollectionData() {
		$data = array(1,2,3);

		$collection = Collection::make($data);

		$response = ApiController::formatJsend($collection);

		$actual = json_decode($response->getContent(), true)['data'];

		$this->assertEquals($data, $actual);
	}

}
<?php

use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\Response;

use Judge\Controllers\ApiController;

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
		$data = Collection::make([1, 2, 3]);

		$response = ApiController::formatJsend($data);

		$actual = json_decode($response->getContent(), true)['data'];

		$this->assertEquals([1, 2, 3], $actual);
	}

}

<?php

class JSend4LaravelTests extends Orchestra\Testbench\TestCase {
	
	protected function getPackageProviders()
	{
		return array('Vena\JSend4Laravel\JSend4LaravelServiceProvider');
	}

	public function testJSendSuccess()
	{
		$response = \Response::jsendSuccess((object) [ 'mydata' => 'thedata']);
		$this->assertEquals('success', $response->getData()->status);
		$this->assertEquals('thedata', $response->getData()->data->mydata);
	}

	public function testJSendFail()
	{
		$response = \Response::jsendFail((object) [ 'mydata' => 'thedata']);
		$this->assertEquals('fail', $response->getData()->status);
		$this->assertEquals('thedata', $response->getData()->data->mydata);
	}

	public function testJSendError()
	{
		$thrownException = FALSE;
		try {
			$response = \Response::jsendError();
		} catch (Exception $e) {
			$thrownException = $e;
		}
		$this->assertInstanceOf('BadMethodCallException', $thrownException, 'JSend errors without messages should throw a BadMethodCallException.');

		$response = \Response::jsendError('mymessage', 123, (object) [ 'mydata' => 'thedata']);
		$this->assertEquals('error', $response->getData()->status);
		$this->assertEquals('mymessage', $response->getData()->message);
		$this->assertEquals(123, $response->getData()->code);
		$this->assertEquals('thedata', $response->getData()->data->mydata);
	}
}
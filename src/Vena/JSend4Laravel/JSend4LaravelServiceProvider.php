<?php namespace Vena\JSend4Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response as Response;
use Illuminate\Support\Facades\Input as Input;

class JSend4LaravelServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('vena/jsend4laravel', 'jsend4laravel');

		/**
		 * Wrapper for sending a JSON response with automatic JSONP support
		 * @param  string        $message  (required) Message describing the error.
		 * @param  string|array  $data     (optional) Data to include in the response.
		 * @param  int           $status   (optional) HTTP status code of the return response.
		 * @param  array         $headers  (optional) Request headers to include in the response.
		 * @return Response
		 */
		Response::macro('jsend', function($data = NULL, $httpStatus = 200, array $headers = array())
		{
			$response = Response::json($data, $httpStatus, $headers);
			if (Input::has('callback')) {
				$response->setCallback(Input::get('callback'));
			}
			return $response;
		});

		/**
		 * Return a JSend success response
		 * 
		 * @param  string        $message  (required) Message describing the error.
		 * @param  string|array  $data     (optional) Data to include in the response.
		 * @param  int           $status   (optional) HTTP status code of the return response.
		 * @param  array         $headers  (optional) Request headers to include in the response.
		 * @return Response
		 */
		Response::macro('jsendSuccess', function($data = NULL, $httpStatus = 200, array $headers = array())
		{
			return Response::jsend(
				(object) array(
					'status' => 'success',
					'data' => $data
				),
				$httpStatus,
				$headers
			);
		});

		/**
		 * Return a JSend fail response
		 *
		 * @param  string        $message  (required) Message describing the error.
		 * @param  string|array  $data     (optional) Data to include in the response.
		 * @param  int           $status   (optional) HTTP status code of the return response.
		 * @param  array         $headers  (optional) Request headers to include in the response.
		 * @return Response
		 */
		Response::macro('jsendFail', function($data, $httpStatus = 400, array $headers = array())
		{
			$responseData = (object) array(
				'status' => 'fail',
				'data' => $data
			);
			return Response::jsend(
				$responseData,
				$httpStatus,
				$headers
			);
		});

		/**
		 * Return a JSend error response
		 *
		 * @param  string        $message  (required) Message describing the error.
		 * @param  int           $code     (optional) An internal error code, if applicable
		 * @param  string|array  $data     (optional) Data to include in the response.
		 * @param  int           $status   (optional) HTTP status code of the return response.
		 * @param  array         $headers  (optional) Request headers to include in the response.
		 * @return Response
		 */
		Response::macro('jsendError', function($message = NULL, $code = NULL, $data = NULL, $httpStatus = 400, array $headers = array())
		{
			if (is_null($message)) {
				throw new \BadMethodCallException($this->app['translator']->get('jsend4laravel::messages.jsend_errors_must_have_messages'));
			}
			$responseData = (object) array(
				'status' => 'error',
				'message' => $message
			);
			if ( ! is_null($code)) {
				$responseData->code = $code;
			}
			if ( ! is_null($data)) {
				$responseData->data = $data;
			}
			return Response::jsend(
				$responseData,
				$httpStatus,
				$headers
			);
		});

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}

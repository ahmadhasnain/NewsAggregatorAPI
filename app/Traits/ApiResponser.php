<?php

namespace App\Traits;

trait ApiResponser
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	protected function success($data, string $message = null, int $code = 200)
	{
        $executionTime = microtime(true) - LARAVEL_START;
		return response()->json([
			'status' => true,
			'message' => $message,
			'data' => $data,
            'tt' => $executionTime
		], $code);
	}

	/**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error(string $message = null, int $code, $data = null)
	{
        $executionTime = microtime(true) - LARAVEL_START;
		return response()->json([
			'status' => false,
			'message' => $message,
			'error' => $data,
            'tt' => $executionTime
		], $code);
	}
}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ApiExceptionHandlerTrait
{
    /**
     * @var \Exception
     */
    private $exception;

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        $this->exception = $e;

        switch(true) {
            case $e->getCode() == 404:
            case $this->isModelNotFoundException($e):
            case $this->getStatusExceptionIfExist($e) == 404:
                $retval = $this->setResponse('Not found', 404);
                break;
            case $this->isMethodNotAllowed($e):
                $retval = $this->setResponse("Method not allowed", 404);
                break;
            case $this->getStatusExceptionIfExist($e) == 403:
                $retval = $this->setResponse("Forbidden", 403);
                break;
            case $this->isAuthException($e):
                $retval = $this->setResponse("Unauthorized", 401);
                break;
            default:
                $retval = $this->setResponse('Bad request', 400);
        }

        return $retval;
    }

    /**
     * @param Exception $e
     * @return int|null
     */
    private function getStatusExceptionIfExist(\Exception $e) : ?int
    {
        if (!method_exists ($e, 'getStatusCode')) return null;
        return $e->getStatusCode();
    }

    private function setResponse($message, $statusCode)
    {
        return $this->jsonResponse($message, $statusCode);
    }

    public function handleException(\Exception $e, $statusCode)
    {
        return $this->jsonResponse($e->getMessage(), $statusCode, $e);
    }

    /**
     * Returns json response.
     *
     * @param $message
     * @param int $statusCode
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($message, $statusCode, \Exception $e = null)
    {
        $exception = $e ?? $this->exception;

        $errorMsg = empty($exception->getMessage()) ? $message : $exception->getMessage();

        $result = [
            'statusCode' => $statusCode,
            'code' => $exception->getCode(),
            'message' => $errorMsg,
            'type' => get_class($exception),
        ];

        if (env('APP_DEBUG')) {
            // TODO: add additions debug info if need
            $result = array_merge($result, ['stackTrace' => $exception->getTraceAsString()]);
        }

        return response()->json(["error" => $result], $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isMethodNotAllowed(Exception $e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isAuthException(Exception $e)
    {
        if ($e instanceof UnauthorizedException) return true;
        if ($e instanceof AuthorizationException) return true;

        return false;
    }

}
<?php

namespace App\Exceptions;

use App\Http\Controllers\AppBaseController;
use App\Jobs\SendReminderEmail;
use App\Models\System\ReminderEmail;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return mixed
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException && $response = $exception->validator->getMessageBag()->count() > 0){
            if($request->ajax()){
                $errorMessages = $exception->validator->getMessageBag()->toArray();
                $key = array_keys($errorMessages)[0];
                return AppBaseController::sendError([
                    'field' => $key,
                    'message'=> $errorMessages[$key][0]
                ],403);
            }
        }
        else if ($exception instanceof NotFoundHttpException){
            if(\WinwinAuth::isCurrentMemberAdminSystem() || \WinwinAuth::isCurrentMemberFrontEndSystem()){
                return response(\WTemplate::renderWebNotFoundPage());
            }
            if(\WinwinAuth::isCurrentCarrierAdminSystem()){
                return AppBaseController::sendError(['message' => '404 NOT FOUND',404]);
            }
        }
        else if($exception instanceof ModelNotFoundException && $request->ajax()){
            return AppBaseController::sendError('找不到数据',404);
        }
        $response = parent::render($request, $exception);
        if($response->isServerError()){
            if(\App::environment() != 'local'){
                dispatch(new SendReminderEmail(new ReminderEmail($exception)));
            }
        }
        return $response;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

}

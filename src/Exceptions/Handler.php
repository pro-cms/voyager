<?php

namespace Voyager\Admin\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $e
     */
    public function render($request, Throwable $e): \Symfony\Component\HttpFoundation\Response
    {
        $response = parent::render($request, $e);

        $status = 500;
        if (method_exists($response, 'status')) {
            $status = $response->status();
        }

        // If this is an AJAX request let original handler handle the JSON response
        if ($request->expectsJson()) {
            return $this->prepareJsonResponse($request, $e);
        }

        // TODO: Don't pass everything when not in development mode
        return Inertia::render('Error', [
            'exception' => [
                'status'    => $status,
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => json_decode(json_encode($e->getTrace()) ?: '[]'),
                'exception' => get_class($e),
            ],
        ])
        ->withViewData('title', __('voyager::generic.error', [ 'code' => $status ]))
        ->toResponse($request)
        ->setStatusCode($status);
    }
}

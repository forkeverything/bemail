<?php


namespace App\Translation\Exceptions;


class FailedCreatingMessageException extends \Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::error("Failed Creating New Message Models", [
            'exception' => $this
        ]);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view('errors.create-new-message-fail', [], 500);
    }
}
<?php


namespace App\Translation\Exceptions;


class FailedCreatingNewMessageException extends \Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::error("FAILED_CREATING_NEW_MESSAGE_MODELS", [
            'code' => $this->code,
            'msg' => $this->message,
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
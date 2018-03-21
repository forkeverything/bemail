<?php

namespace App\Contracts\InboundMail;

use Illuminate\Http\Request;

interface InboundMailRequest
{

    /**
     * Instantiate InboundMail\PostmarkInboundMailRequest from a normal HTTP request.
     *
     * @param HttpRequest $request
     */
    public function __construct(Request $request);

    /**
     * The name of person who sent the email.
     *
     * @return string
     */
    public function fromName();

    /**
     * The email address that sent the email.
     *
     * @return mixed
     */
    public function fromAddress();

    /**
     * The email subject.
     *
     * @return mixed
     */
    public function subject();

    /**
     * Only the reply (without previous emails or headers) in plain text.
     *
     * @return string
     */
    public function strippedReplyBody();

    /**
     * Email attachments.
     *
     * @return array
     */
    public function attachments();

    /**
     * RecipientEmails in standard 'to' field.
     *
     * @return array
     */
    public function standardRecipients();

    /**
     * RecipientEmails in 'cc' field.
     *
     * @return array
     */
    public function ccRecipients();

    /**
     * RecipientEmails in 'bcc' field.
     *
     * @return mixed
     */
    public function bccRecipients();

    /**
     * The action that this email is trying to perform.
     *
     * ie. 'reply'
     *
     * @return string
     */
    public function action();

    /**
     * The target that this email is intended for.
     *
     * @return string
     */
    public function target();
}

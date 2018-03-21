<?php

namespace App\InboundMail\Postmark;

use App\Contracts\InboundMail\InboundMailRequest;
use App\Translation\Utilities\EmailReplyParser;
use Illuminate\Http\Request;

class PostmarkInboundMailRequest implements InboundMailRequest
{

    /**
     * The name of person who sent the email.
     *
     * @return string
     */
    private $fromName;
    /**
     * The email address that sent the email.
     *
     * @return string
     */
    private $fromAddress;
    /**
     * The email subject.
     *
     * @return string
     */
    private $subject;
    /**
     * Only the reply (without previous emails or headers) in plain text.
     *
     * @return string
     */
    private $strippedReplyBody;
    /**
     * Email attachments.
     *
     * @var array
     */
    private $attachments;
    /**
     * The emails in standard 'to' field.
     *
     * @var array
     */
    private $standardRecipients;
    /**
     * The emails in 'cc' field.
     *
     * @var array
     */
    private $ccRecipients;
    /**
     * The emails in 'bcc' field.
     *
     * @var array
     */
    private $bccRecipients;
    /**
     * The email address it was intended for.
     *
     * @var string
     */
    private $inboundAddress;

    /**
     * Create PostmarkInboundMailRequest instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {

        // Set fields explicitly without first setting $request as a
        // property so that it won't be passed along with this
        // class. Prevents serialization of closure error.

        $this->setFromName($request)
             ->setFromAddress($request)
             ->setSubject($request)
             ->setStrippedReplyBody($request)
             ->setAttachments($request)
             ->setStandardRecipients($request)
             ->setCcRecipients($request)
             ->setBccRecipients($request)
             ->setInboundAddress($request);
    }

    /**
     * Set fromName.
     *
     * @param Request $request
     * @return $this|string
     */
    public function setFromName(Request $request)
    {
        $this->fromName = $request["FromName"];
        return $this;
    }

    /**
     * Get fromName.
     *
     * @return string
     */
    public function fromName()
    {
        return $this->fromName;
    }

    /**
     * Set sender email.
     *
     * @param Request $request
     * @return $this
     */
    public function setFromAddress(Request $request)
    {
        $this->fromAddress = $request["From"];
        return $this;
    }

    /**
     * Get sender email.
     *
     * @return mixed
     */
    public function fromAddress()
    {
        return $this->fromAddress;
    }


    /**
     * Set message subject.
     *
     * @param Request $request
     * @return $this
     */
    public function setSubject(Request $request)
    {
        $this->subject = $request["Subject"];
        return $this;
    }

    /**
     * Get message subject.
     *
     * @return string
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * Set the reply message body.
     *
     * @param Request $request
     * @return $this
     */
    public function setStrippedReplyBody(Request $request)
    {
        $this->strippedReplyBody = EmailReplyParser::parse($request["TextBody"]);
        return $this;
    }

    /**
     * Get the reply message body.
     *
     * @return string
     */
    public function strippedReplyBody()
    {
        return $this->strippedReplyBody;
    }

    /**
     * Set attachments.
     *
     * @param Request $request
     * @return $this
     */
    public function setAttachments(Request $request)
    {
        $this->attachments = $request["Attachments"];
        return $this;
    }

    /**
     * Get attachments.
     *
     * @return array
     */
    public function attachments()
    {
        return $this->attachments;
    }

    /**
     * The recipients for a given field.
     *
     * @param Request $request
     * @param $field
     * @return array
     */
    private function recipientsForField($field, Request $request)
    {
        $recipients = [];
        $jsonCollection = $request[$field];

        foreach ($jsonCollection as $json) {
            $recipient = new PostmarkInboundMailRecipient($json);
            array_push($recipients, $recipient);
        }

        return $recipients;
    }

    /**
     * Set standard recipients.
     *
     * @param Request $request
     * @return $this
     */
    public function setStandardRecipients(Request $request)
    {
        $this->standardRecipients = $this->recipientsForField('ToFull', $request);
        return $this;
    }

    /**
     * Get standard recipients.
     *
     * @return array
     */
    public function standardRecipients()
    {
        return $this->standardRecipients;
    }

    /**
     * Set cc recipients.
     *
     * @param Request $request
     * @return $this
     */
    public function setCcRecipients(Request $request)
    {
        $this->ccRecipients = $this->recipientsForField("CcFull", $request);
        return $this;
    }

    /**
     * Get cc recipients.
     *
     * @return array
     */
    public function ccRecipients()
    {
        return $this->ccRecipients;
    }

    /**
     * Set bcc recipients.
     *
     * @param Request $request
     * @return PostmarkInboundMailRequest
     */
    public function setBccRecipients(Request $request)
    {
        $this->bccRecipients = $this->recipientsForField("BccFull", $request);
        return $this;
    }

    /**
     * get bcc recipients.
     *
     * @return array
     */
    public function bccRecipients()
    {
        return $this->bccRecipients;
    }

    /**
     * Set inbound address.
     *
     * @param Request $request
     * @return $this
     */
    public function setInboundAddress(Request $request)
    {
        $this->inboundAddress = $request["OriginalRecipient"];
        return $this;
    }


    /**
     * Turns the inbound address into an array.
     *
     * Inbound Address Convention:
     * - snake_case for incoming mail address
     * - first part specifies the type of email
     * - ie. reply_s0m3h4$h@in.bemail.io, for replies to a specific Message
     *
     * @return array
     */
    private function inboundAddressArray()
    {
        return explode("_", $this->inboundAddress);
    }

    /**
     * The action that this email is trying to perform.
     *
     * ie. 'reply'
     *
     * @return string
     */
    public function action()
    {
        return $this->inboundAddressArray()[0];
    }

    /**
     * The target that this email is intended for.
     *
     * @return string
     */
    public function target()
    {
        preg_match("/.*(?=@)/", $this->inboundAddressArray()[1], $matches);
        return $matches[0];
    }

}
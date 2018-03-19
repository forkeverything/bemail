<?php


namespace App\Translation\Factories\RecipientFactory;


use App\Http\Requests\CreateMessageRequest;
use App\Translation\RecipientType;

class RecipientEmails
{
    /**
     * Normal direct email recipients.
     *
     * @var array
     */
    private $standard = [];
    /**
     * CC
     *
     * @var array
     */
    private $cc = [];
    /**
     * BCC
     * @var array
     */
    private $bcc = [];

    /**
     * New instance of RecipientEmails.
     *
     * @return static
     */
    public static function new()
    {
        return new static();
    }

    /**
     * Adds list a comma separated emails as a specific type of recipient.
     *
     * @param RecipientType $type
     * @param string $emailList
     */
    private function addListToType($emailList, RecipientType $type)
    {
        $emails = explode(',', $emailList);
        foreach ($emails as $email) {
            $this->addEmailToType($email, $type);
        }
    }

    /**
     * Adds a list of emails to 'standard' key.
     *
     * @param $emails
     * @return $this
     */
    public function addListOfStandardEmails($emails)
    {
        $this->addListToType($emails, RecipientType::standard());
        return $this;
    }

    /**
     * Adds a list of emails to 'cc' key.
     *
     * @param $emails
     * @return $this
     */
    public function addListOfCcEmails($emails)
    {
        $this->addListToType($emails, RecipientType::cc());
        return $this;
    }

    /**
     * Adds a list of emails to 'bcc' key.
     *
     * @param $emails
     * @return $this
     */
    public function addListOfBccEmails($emails)
    {
        $this->addListToType($emails, RecipientType::bcc());
        return $this;
    }

    /**
     * Add an email to type.
     *
     * @param $email
     * @param RecipientType $type
     * @return $this
     */
    public function addEmailToType($email, RecipientType $type)
    {
        if (!$this->isValidEmail($email)) {
            return $this;
        }
        $typeName = $type->name;
        $this->$typeName[] = $email;
        return $this;
    }

    /**
     * Any of bemail's service emails or the inbound address
     * are all invalid recipients.
     *
     * @param $email
     * @return bool
     */
    public function isValidEmail($email)
    {
        $domain = explode('@', $email)[1];
        return $domain !== 'in.bemail.io' && $domain !== 'bemail.io';
    }

    /**
     * All of the recipient emails.
     *
     * @return array
     */
    public function all()
    {
        return [
            'standard' => $this->standard,
            'cc' => $this->cc,
            'bcc' => $this->bcc
        ];
    }

    /**
     * Standard emails.
     *
     * @return array
     */
    public function standard()
    {
        return $this->standard;
    }

    /**
     * CC emails.
     *
     * @return array
     */
    public function cc()
    {
        return $this->cc;
    }

    /**
     * BCC emails.
     *
     * @return array
     */
    public function bcc()
    {
        return $this->bcc;
    }
}
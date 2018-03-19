<?php


namespace App\Translation\Factories\MessageFactory;


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
     * Adds list a comma separated emails as a specific type of recipient.
     *
     * @param RecipientType $type
     * @param string $emailList
     * @return $this
     */
    public function addListToType($emailList, RecipientType $type)
    {
        $emails = explode(',', $emailList);
        $typeName = $type->name;
        foreach ($emails as $email) {

            if (!$this->isValidEmail($email)) {
                continue;
            }

            $this->$typeName[] = $email;
        }
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
    protected function isValidEmail($email)
    {
        $domain = explode('@', $email)[1];
        return $domain !== 'in.bemail.io' && $domain !== 'bemail.io';
    }

}
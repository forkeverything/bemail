<?php

namespace Tests\Unit\Translation\Factories\RecipientFactory;

use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\RecipientType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipientEmailsTest extends TestCase
{

    /**
     * @var RecipientEmails
     */
    private $recipientEmails;

    protected function setUp()
    {
        parent::setUp();
        $this->recipientEmails = RecipientEmails::new();
    }


    /**
     * @test
     */
    public function it_adds_list_of_emails_to_standard_type()
    {
        $emails = 'john@example.com,sarah@example.com,steve@example.com';
        $this->assertCount(0, $this->recipientEmails->standard());
        $this->recipientEmails->addListOfStandardEmails($emails);
        $this->assertCount(3, $this->recipientEmails->standard());
    }

    /**
     * @test
     */
    public function it_adds_list_of_emails_to_cc_type()
    {
        $emails = 'john@example.com,sarah@example.com,steve@example.com';
        $this->assertCount(0, $this->recipientEmails->cc());
        $this->recipientEmails->addListOfCcEmails($emails);
        $this->assertCount(3, $this->recipientEmails->cc());
    }

    /**
     * @test
     */
    public function it_adds_list_of_emails_to_bcc_type()
    {
        $emails = 'john@example.com,sarah@example.com,steve@example.com';
        $this->assertCount(0, $this->recipientEmails->bcc());
        $this->recipientEmails->addListOfBccEmails($emails);
        $this->assertCount(3, $this->recipientEmails->bcc());
    }

    /**
     * @test
     */
    public function it_adds_single_email_of_type()
    {
        $this->assertCount(0, $this->recipientEmails->standard());
        $this->recipientEmails->addEmailToType('mike@example.com', RecipientType::standard());
        $this->assertCount(1, $this->recipientEmails->standard());
    }

    /**
     * @test
     */
    public function it_invalidates_bemail_emails()
    {
        $this->assertTrue($this->recipientEmails->isValidEmail('mike@example.com'));
        $this->assertFalse($this->recipientEmails->isValidEmail('mike@bemail.io'));
        $this->assertFalse($this->recipientEmails->isValidEmail('mike@in.bemail.io'));
    }

    /**
     * @test
     */
    public function it_returns_all_emails()
    {
        $this->assertTrue(is_array($this->recipientEmails->all()));
        $this->assertTrue(array_key_exists('standard', $this->recipientEmails->all()));
        $this->assertTrue(array_key_exists('cc', $this->recipientEmails->all()));
        $this->assertTrue(array_key_exists('bcc', $this->recipientEmails->all()));
    }

    /**
     * @test
     */
    public function it_returns_standard_emails()
    {
        $this->assertTrue(is_array($this->recipientEmails->standard()));
    }

    /**
     * @test
     */
    public function it_returns_cc_emails()
    {
        $this->assertTrue(is_array($this->recipientEmails->cc()));
    }


    /**
     * @test
     */
    public function it_returns_bcc_emails()
    {
        $this->assertTrue(is_array($this->recipientEmails->bcc()));
    }

}

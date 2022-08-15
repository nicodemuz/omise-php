<?php

use PHPUnit\Framework\TestCase;
use Traits\ChargeTrait;

class OmiseDisputeTest extends TestCase
{
    use ChargeTrait;

    public $chargeId;

    public $disputeId;

    public function init()
    {
        $charge = $this->createCharge(true);
        $this->chargeId = $charge['id'];
        $dispute = OmiseDispute::create($charge, ['message' => '2 time charge']);
        $this->disputeId = $dispute['id'];
    }
    /**
     * @test
     * OmiseDispute class must be contain some method below.
     */
    public function method_exists()
    {
        $this->assertTrue(method_exists('OmiseDispute', 'retrieve'));
        $this->assertTrue(method_exists('OmiseDispute', 'reload'));
        $this->assertTrue(method_exists('OmiseDispute', 'update'));
        $this->assertTrue(method_exists('OmiseDispute', 'accept'));
        $this->assertTrue(method_exists('OmiseDispute', 'getUrl'));
    }

    /**
     * @test
     * Assert that a list of dispute object is returned after a successful retrieve.
     */
    public function retrieve_omise_dispute_object()
    {
        $this->init();
        $dispute = OmiseDispute::retrieve();
        $dispute->reload();

        $this->assertArrayHasKey('object', $dispute);
        $this->assertEquals('list', $dispute['object']);
        if (isset($dispute['data'][0])) {
            $this->assertEquals('dispute', $dispute['data'][0]['object']);
        }
    }

    /**
     * @test
     * Assert that a dispute object is returned after a successful retrieve.
     */
    public function retrieve_omise_dispute_object_with_key()
    {
        $this->init();
        $dispute = OmiseDispute::retrieve($this->disputeId);
        $this->assertArrayHasKey('object', $dispute);
        $this->assertEquals('dispute', $dispute['object']);
    }

    /**
     * @test
     * Assert that a dispute object is returned after a successful retrieve with 'open' status.
     */
    public function retrieve_omise_dispute_object_that_open()
    {
        $this->init();
        $dispute = OmiseDispute::retrieve('open');
        $this->assertArrayHasKey('object', $dispute);
        if (isset($dispute['data'][0])) {
            $this->assertEquals('dispute', $dispute['data'][0]['object']);
            $this->assertEquals('open', $dispute['data'][0]['status']);
        }
    }

    /**
     * @test
     * Assert that a dispute object is returned after a successful retrieve with 'pending' status.
     */
    public function retrieve_omise_dispute_object_that_pending()
    {
        $this->init();
        $dispute = OmiseDispute::retrieve('pending');
        $this->assertArrayHasKey('object', $dispute);
        if (isset($dispute['data'][0])) {
            $this->assertEquals('dispute', $dispute['data'][0]['object']);
            $this->assertEquals('pending', $dispute['data'][0]['status']);
        }
    }

    /**
     * @test
     * Assert that a dispute object is returned after a successful retrieve with 'closed' status.
     */
    public function retrieve_omise_dispute_object_that_closed()
    {
        $this->init();
        $dispute = OmiseDispute::retrieve('closed');
        $this->assertArrayHasKey('object', $dispute);
        if (isset($dispute['data'][0])) {
            $this->assertEquals('dispute', $dispute['data'][0]['object']);
            $this->assertTrue(in_array($dispute['data'][0]['status'], ['closed', 'lost']));
        }
    }

    /**
     * @test
     * Assert that a dispute is successfully updated with the given parameters set.
     */
    public function update()
    {
        $this->init();
        $dispute = OmiseDispute::retrieve($this->disputeId);
        $dispute->update([
            'message' => 'New Message...'
        ]);
        $this->assertArrayHasKey('object', $dispute);
        $this->assertEquals('dispute', $dispute['object']);
    }

    /**
     * @test
     * Assert that the dispute is successfully accepted.
     */
    public function accept()
    {
        $this->init();
        $dispute = OmiseDispute::retrieve($this->disputeId);
        $dispute->accept();
        $this->assertArrayHasKey('object', $dispute);
        $this->assertEquals('dispute', $dispute['object']);
        $this->assertEquals('lost', $dispute['status']);
    }

    /**
     * @test
     * Assert that OmiseDispute can search for disputes.
     */
    public function search()
    {
        $this->init();
        $result = OmiseDispute::search('demo')
            ->filter(['card_last_digits' => '5454']);
        $this->assertArrayHasKey('object', $result);
        $this->assertEquals('search', $result['object']);
        foreach ($result['data'] as $item) {
            $this->assertArrayHasKey('object', $item);
            $this->assertEquals('dispute', $item['object']);
        }
    }
}

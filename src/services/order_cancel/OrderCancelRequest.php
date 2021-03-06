<?php

namespace Platron\Connectum\services\order_cancel;

use Platron\Connectum\services\BasePostRequest;

class OrderCancelRequest extends BasePostRequest {
    
    /** @var int */
    private $id;
    /** @var string */
    protected $amount;
    
    /**
     * {@inheritdoc}
     */
    public function getRequestUrl() {
        return '/orders/'.$this->id.'/cancel';
    }
    
    /**
     * @param int $id
     * @param float $amount
     */
    public function __construct($id, $amount) {
        $this->id = $id;
        $this->amount = (string) round($amount, 2);
    }

}

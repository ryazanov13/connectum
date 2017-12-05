<?php

namespace Platron\Connectum\services\order_authorize;

use Platron\Connectum\services\BaseResponse;
use Platron\Connectum\services\order_info\OrderInfoResponse;
use Platron\Connectum\data_objects\Form3dData;

class OrderAuthorizeResponse extends BaseResponse {
    /** @var OrderInfoResponse */
    public $order;
    /** @var Form3dData */
    public $form3d;
    public $form3d_html;
    
    public function __construct(\stdClass $response) {
        parent::__construct($response);
        if(!empty($response->orders[0])){
            $this->order = $response->orders[0];
        }
    }
}

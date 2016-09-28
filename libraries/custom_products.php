<?php

namespace Addon\CustomProducts\Libraries;

class CustomProducts
{
    public function createService($purchase_id)
    {
        return array(
            'result' => 1
        );
    }

    public function renewService($purchase_id)
    {
        return array(
            'result' => true
        );
    }

    public function suspendService($purchase_id)
    {
        return array(
            'result' => true
        );
    }

    public function terminateService($purchase_id)
    {
        return array(
            'result' => true
        );
    }

    public function getNextRenewalDate($purchase_id)
    {
        return null;
    }
}

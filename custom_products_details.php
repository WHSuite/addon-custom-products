<?php
namespace Addon\CustomProducts;

class CustomProductsDetails extends \App\Libraries\AddonDetails
{
    /**
     * addon details
     */
    protected static $details = array(
        'name' => 'Custom Products',
        'description' => 'Addon for WHSuite that provides ability to create custom products',
        'author' => array(
            'name' => 'WHSuite Dev Team',
            'email' => 'info@whsuite.com'
        ),
        'website' => 'http://www.whsuite.com',
        'version' => '1.0.0',
        'license' => 'http://whsuite.com/license/ The WHSuite License Agreement',
        'type' => 'addon'
    );
}

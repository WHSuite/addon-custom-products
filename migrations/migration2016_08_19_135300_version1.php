<?php
namespace Addon\CustomProducts\Migrations;

use \App\Libraries\BaseMigration;

class Migration2016_08_19_135300_version1 extends BaseMigration
{
    /**
     * migration 'up' function to install items
     *
     * @param   int     addon_id
     */
    public function up($addon_id)
    {
        // Add product type
        $product_type = new \ProductType();
        $product_type->name = 'Custom Product';
        $product_type->slug = 'custom-product';
        $product_type->description = 'Custom Products within WHSuite that have no service or action attached.';
        $product_type->is_hosting = 0;
        $product_type->is_domain = 0;
        $product_type->addon_id = $addon_id;
        $product_type->sort = 3;
        $product_type->save();
    }

    public function down($addon_id)
    {
        // drop product type row
        \ProductType::where('addon_id', '=', $addon_id)->delete();
    }
}

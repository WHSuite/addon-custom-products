<?php
namespace Addon\CustomProducts\Libraries;

class ProductTypesHelper
{
    /**
     * load custom tabs handled by the addon
     *
     * @param   array   the product type model so the addon can handle multiple product types based on their slug
     * @return  array   returns an array containing the tab names and view paths
     */
    public function loadProductTabs($product_type)
    {
        return array(
            array(
                'name' => 'Pricing',
                'view' => 'custom_products::admin/products/tabs/pricing.php'
            )
        );
    }

    /**
     * load custom view data
     *
     * @param   array   the product type model so the addon can handle multiple product types based on their slug
     * @param   array  array of data used in the product just incase the addon needs it
     * @return  array  array of view data to inject
     */
    public function loadViewData($product_type, $data = array())
    {
        return array();
    }

    /**
     * load custom form data to populate fields
     *
     * @param   array   the product type model so the addon can handle multiple product types based on their slug
     * @param   object  the product object
     * @return  array  array of view data to inject
     */
    public function loadFormData($product_type, $product = null)
    {
        if (empty($product)) {
            return array();
        }

        $pricing = $product->ProductPricing()->get();

        $pricing_data = array();
        $Money = \App::get('money');

        $currency_ids = array_unique(
            array_pluck(
                $pricing->toArray(),
                'currency_id'
            )
        );

        $Currencies = \Currency::whereIn('id', $currency_ids)->get();
        $currencyCodes = array_pluck(
            $Currencies->toArray(),
            'code',
            'id'
        );

        foreach ($pricing as $price) {
            $pricing_data[$price->billing_period_id][$price->currency_id] = array(
                'price' => $Money->format(
                    $price->price,
                    $currencyCodes[$price->currency_id],
                    true
                ),
                'renewal_price' => $Money->format(
                    $price->renewal_price,
                    $currencyCodes[$price->currency_id],
                    true
                ),
                'setup' => $Money->format(
                    $price->setup,
                    $currencyCodes[$price->currency_id],
                    true
                ),
                'allow_in_signup' => $Money->format(
                    $price->allow_in_signup,
                    $currencyCodes[$price->currency_id],
                    true
                )
            );
        }
        \Whsuite\Inputs\Post::set('Pricing', $pricing_data);

        return array();
    }

    /**
     * allow validation on any of the fields added by this addon
     *
     * @param   array   the product type model so the addon can handle multiple product types based on their slug
     * @param   array   complete array of all post data to allow the addon to handle it as it sees fit
     * @return   array   array of validation errors returned by the addon (or an empty array if there were none)
     */
    public function validateAddonProductData($product_type, $data)
    {
        return array();
    }

    /**
     * save custom tab data
     *
     * @param   array   the product type model so the addon can handle multiple product types based on their slug
     * @param   array   complete array of all post data to allow the addon to handle it as it sees fit
     * @param   array   the saved product model data to allow the addon to alter and reference back to it
     * @return   boolean   returns true / false to let whsuite know if the addon successfully added its custom data
     */
    public function saveAddonProductData($product_type, $data, $product)
    {
        if (! empty($product->id)) {
            \ProductPricing::where('product_id', '=', $product->id)->delete();
        }

        $pricing_data = null;
        if (isset($data['Pricing'])) {
            $pricing_data = $data['Pricing'];
        }

        // Insert product pricing
        foreach ($pricing_data as $billing_period_id => $data) {
            foreach ($data as $currency_id => $price_data) {
                if (! empty($price_data['price'])) {
                    if (isset($pricing_status) && $pricing_status == false) {
                        // An error occurred at some point when adding
                        // one of the pricing data records, skip over
                        // the rest so we can show an unexpected error.
                        continue;
                    }

                    $pricing_record = new \ProductPricing();
                    $pricing_record->product_id = $product->id;
                    $pricing_record->currency_id = $currency_id;
                    $pricing_record->billing_period_id = $billing_period_id;
                    $pricing_record->price = $price_data['price'];
                    $pricing_record->renewal_price = $price_data['renewal_price'];
                    $pricing_record->setup = $price_data['setup'];
                    $pricing_record->allow_in_signup = $price_data['allow_in_signup'];

                    $pricing_status = $pricing_record->save();
                }
            }
        }

        return array(
            'result' => 'success'
        );
    }

    public function updateOrderFormData($product_type, $form_data)
    {
        $billingPeriod = \Whsuite\Inputs\Post::get('billing_period');
        if (! empty($billingPeriod)) {
            $form_data['billing_period'] = $billingPeriod;
        }

        return $form_data;
    }

    /**
     * frontend order form validation for when this product type is purchased by a client
     *
     * @param   array   the product type model so the addon can handle multiple product types based on their slug
     * @param   array   complete array of all post data to allow the addon to handle it as it sees fit
     * @return   array   array of validation errors returned by the addon (or an empty array if there were none)
     */
    public function validateAddonProductOrderData($product_type, $data)
    {
        return array();
    }
}

<table class="table table-striped" id="pricingTable">
<?php foreach($currencies as $currency): ?>
    <thead>
        <tr>
            <th><?php echo $currency->code; ?></th>
            <th><?php echo $lang->get('price'); ?></th>
            <th><?php echo $lang->get('setup'); ?></th>
            <th><?php echo $lang->get('renewal_price'); ?></th>
            <th><?php echo $lang->get('enabled'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($billing_periods as $period): ?>
        <tr>
            <td><?php echo $period->name; ?></td>
            <td><?php echo $forms->input('Pricing.'.$period->id.'.'.$currency->id.'.price', null, array('placeholder' => '0.00')); ?></td>
            <td><?php echo $forms->input('Pricing.'.$period->id.'.'.$currency->id.'.setup', null, array('placeholder' => '0.00')); ?></td>
            <td><?php echo $forms->input('Pricing.'.$period->id.'.'.$currency->id.'.renewal_price', null, array('placeholder' => '0.00')); ?></td>
            <td><?php echo $forms->checkbox('Pricing.'.$period->id.'.'.$currency->id.'.allow_in_signup', null); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
<?php endforeach; ?>
</table>

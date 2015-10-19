<table class="price-items">
    <?php if ($price_items) { ?>
        <thead>
            <tr>
                <th>
                    <?php $this->echo_text('Name of item'); ?>
                </th>
                <th>
                    <?php $this->echo_text('Price'); ?>
                </th>
                <th>
                    <?php $this->echo_text('Required?'); ?>
                </th>
                <th>
                    <?php $this->echo_text('Can change quantity?'); ?>
                </th>
                <th>
                    <?php $this->echo_text('Delete item'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $key = 1;
            foreach ($price_items as $item) {
            $checked_quantity = (isset($item['quantity']));
            $checked_required = (isset($item['required']));?>
                <tr class="price-item price-item-<?php echo $key; ?>" data-id="<?php echo $key; ?>">
                    <td>
                        <input type="text" name="price_item[<?php echo $key; ?>][name]" value="<?php echo $item['name']; ?>" />
                    </td>
                    <td>
                        <input type="text" name="price_item[<?php echo $key; ?>][price]" value="<?php echo $item['price']; ?>" />
                    </td>
                    <td>
                        <input type="checkbox" name="price_item[<?php echo $key; ?>][required]" value="1" <?php if ($checked_required) echo 'checked="checked"'; ?> />
                    </td>
                    <td>
                        <input type="checkbox" name="price_item[<?php echo $key; ?>][quantity]" value="1" <?php if ($checked_quantity) echo 'checked="checked"'; ?> />
                    </td>
                    <td>
                        <button class="button btn-delete" type="button" title=" <?php $this->echo_text('Delete this item'); ?>">x</button>
                    </td>
                </tr>
            <?php 
            $key++;
            } ?>
          
        </tbody>
    <?php } ?>
</table>

<button class="button btn-add-item" type="button">+ <?php $this->echo_text('Add New Item'); ?></button>



<script type="text/javascript">

    jQuery(document).ready(function () {


        jQuery('.price-item button').live('click', function (event) {

            jQuery(this).parents('tr.price-item').remove();

            event.preventDefault();
        });
        
        jQuery('.btn-add-item').click(function (event) {
            var key = (jQuery('table.price-items tr:last').attr('data-id') * 1) + 1;
            var html = '<tr class="price-item price-item-' + key + '" data-id="' + key + '"><td><input type="text" name="price_item[' + key + '][name]" value="" /></td><td><input type="text" name="price_item[' + key + '][price]" value="" /></td> <td><input type="checkbox" name="price_item[' + key + '][required]" value="1" /></td><td><input type="checkbox" name="price_item[' + key + '][quantity]" value="1" /></td><td><button class="button btn-delete" type="button" title="<?php $this->echo_text('Delete this item'); ?>">x</button></td></tr>';
            jQuery('.price-items tbody').append(html);

            event.preventDefault();
        });



    });

</script>
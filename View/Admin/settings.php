<div class="wrap">
    <h1><?php _e('Simple Payments Settings', $this->get_textdomain()); ?></h1>

    <form method="post" action="options.php"> 

        <?php settings_fields($this->get_settings_group()); ?>
        <?php do_settings_sections($this->get_settings_group());
        ?>
        <table class="form-table">

            <?php
            foreach ($settings as $key => $item) {
                $name = $this->get_text($item['name']);
                $option = get_option($name);
                ?>
                <tr valign="top">
                    <th scope="row"><?php $this->echo_text($item['title']); ?></th>
                    <td>
                        <?php if ($item['type'] == 'text') { ?>
                            <input type="text" name="<?php echo $name; ?>" value="<?php echo esc_attr($option); ?>" />
                        <?php
                        } else if ($item['type'] == 'checkbox_group') {
                            foreach ($item['values'] as $key => $value) {
                                $checked = in_array($key, $option);
                                
                                ?>
                                <div>
                                    <label>
                                        <input type="checkbox" name="<?php echo $name; ?>[]" value="<?php echo $key; ?>" 
                                               <?php if ($checked) echo 'checked="checked"'; ?>
                                               />
            <?php $this->echo_text($value); ?>
                                    </label>
                                </div>
                            <?php } ?>
    <?php } ?>
                    </td>
                </tr>
<?php } ?>


        </table>

<?php submit_button(); ?>
    </form>
</div>
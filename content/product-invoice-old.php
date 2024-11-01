<style>
	#yabi-data th
	{
		text-align: right;
	}
</style>

<table width="100%" border="0" cellpadding="5" cellspacing="5" id="yabi-data">

	<table width="100%" border="0" cellpadding="5" cellspacing="5" id="yabi-data">

    <tr>
        <th><?php echo __('Name','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['name'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Last Name','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['lastname'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Type of document','yabi-wc'); ?></th>
        <td><?php echo esc_attr( $thetypes[ $person['type'] ] ); ?></td>
    </tr>

    <tr>
        <th><?php echo __('Identifier','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['identifier'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Email','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['email'] ); ?>
        </td>
    </tr>	
    
    <tr>
        <th><?php echo __('Telephone','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['telephone'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Address','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['address'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('City Code','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['citycode'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Observations','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['observations'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Note Name','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['note_name'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Note Description','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person['note_value'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Invoice Number','yabi-wc'); ?></th>
        <td>
           <?php echo esc_attr( $yabi_invoice['number'] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Invoice Serial','yabi-wc'); ?></th>
        <td>
           <?php echo esc_attr( $yabi_invoice['serial'] ); ?>
        </td>
    </tr>

</table>

</table>
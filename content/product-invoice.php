<style>
	#yabi-data th
	{
		text-align: right;
	}
</style>

<table width="100%" border="0" cellpadding="5" cellspacing="5" id="yabi-data">

	<?php if( 'LEGAL_ENTITY' == $person[ 'type_person' ] ): ?>
    
    	<tr>
            <th><?php echo __( 'Commercial Name','yabi-wc' ); ?></th>
            <td>
                <?php echo esc_attr( $person[ 'commercialname' ] ); ?>
            </td>
        </tr>
    
    <?php endif; ?>

	<tr>
        <th><?php echo __('Name','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'name' ] ); ?>
        </td>
    </tr>

	<?php if( !empty( $person[ 'second_name' ] ) ): ?>

        <tr>
            <th><?php echo __( 'Second Name','yabi-wc' ); ?></th>
            <td>
                <?php echo esc_attr( $person[ 'second_name' ] ); ?>
            </td>
        </tr>
    
    <?php endif; ?>
    
    <tr>
        <th><?php echo __('Last Name','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'lastname' ] ); ?>
        </td>
    </tr>
    
    <?php if( !empty( $person[ 'second_last_name' ] ) ): ?>
    
        <tr>
            <th><?php echo __( 'Second Last Name','yabi-wc' ); ?></th>
            <td>
                <?php echo esc_attr( $person[ 'second_last_name' ] ); ?>
            </td>
        </tr>
    
    <?php endif; ?>
    
    <tr>
        <th><?php echo __( 'Type of document','yabi-wc' ); ?></th>
        <td><?php echo esc_attr( $person[ 'type_document' ] ); ?></td>
    </tr>

    <tr>
        <th><?php echo __( 'Identifier','yabi-wc' ); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'identifier' ] ); ?><?php if( 'LEGAL_ENTITY' == $person[ 'type_person' ] ): ?>-<?php echo esc_attr( $person[ 'identifier_digit' ] ); ?><?php endif; ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Email','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'email' ] ); ?>
        </td>
    </tr>	
    
    <tr>
        <th><?php echo __('Telephone','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'telephone' ] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Address','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'address' ] ); ?>, <?php echo esc_attr( yabi_generate_values( $person[ 'city' ] ) ); ?>,  <?php echo esc_attr( $person[ 'citycode' ] ); ?>
        </td>
    </tr>
    
    <?php if( !empty( $person[ 'observations' ] ) ): ?>
    
    <tr>
        <th><?php echo __('Observations','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'observations' ] ); ?>
        </td>
    </tr>
    
    <?php endif; ?>
    
    <?php if( !empty( $person[ 'note_name' ] ) ): ?>
    
    <tr>
        <th><?php echo __('Note Name','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'note_name' ] ); ?>
        </td>
    </tr>
    
    <?php endif; ?>
    
    <?php if( !empty( $person[ 'note_value' ] ) ): ?>
    
    <tr>
        <th><?php echo __('Note Description','yabi-wc'); ?></th>
        <td>
            <?php echo esc_attr( $person[ 'note_value' ] ); ?>
        </td>
    </tr>
    
    <?php endif; ?>
    
    <tr>
        <th><?php echo __('Invoice Number','yabi-wc'); ?></th>
        <td>
           <?php echo esc_attr( $yabi_invoice[ 'number' ] ); ?>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Invoice Serial','yabi-wc'); ?></th>
        <td>
           <?php echo esc_attr( $yabi_invoice[ 'serial' ] ); ?>
        </td>
    </tr>

</table>

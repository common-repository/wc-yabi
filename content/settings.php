<?php 
	$yabi_settings = get_option( 'yabi_settings' ); 
	$invoice_number = get_option( 'yabi_invoice_number' ); 
	
	if( empty( $yabi_settings ) )
	{
		$yabi_settings = array(
			'payment_type'		=> 'IN_CASH',
			'credit_days'		=> 30,
			'modified_checkout'	=> 'No',
			'invoice_type'		=> 'Manual',
			'invoice_name'		=> '',
			'owner'				=> 'person',
			'businessunituuid'	=> '',
			'token'				=> '',
			'url_client'		=> 'https://api.yabi.co/co/einvoices/v2/',
		);
	}
?>
<style>
	table th
	{
		text-align: right;	
	}

	.card
	{
		max-width: 100%;
	}
</style>
<script>

jQuery( document ).ready(function(){
	
	jQuery( '#payment_type' ).change(function(){
	
		if( 'CREDIT' == jQuery( this ).val() )
		{
			jQuery( '#option_credit' ).show();
		}
		else
		{
			jQuery( '#option_credit' ).hide();
		}
		
	});
	
	jQuery( '#modified_checkout' ).change(function(){
	
		setting_automatic();
		
	});
	
	setting_automatic();
});

function setting_automatic()
{
	if( 'Yes' == jQuery( '#modified_checkout' ).val() )
	{
		jQuery( '#automatic_option' ).show();
	}
	else
	{
		jQuery( '#automatic_option' ).hide();
		jQuery( '#invoice_type' ).val( 'Manual' );
	}
}

</script>
<div class="wrap">

	<h1><?php echo __('Settings','yabi-wc'); ?></h1>

	<form name="thesettings" method="post">
    
    	<div class="card">
        
        	<h2><?php echo __('Settings','yabi-wc'); ?></h2>
            <hr />
            
            <table width="100%" cellpadding="10" cellspacing="10" border="0">
            
            	<tr>
                	<th width="20%"><label for="payment_type"><?php echo __('Payment type','yabi-wc'); ?>:</label></th>
                    <td>
                    	<select id="payment_type" name="payment_type">
                        	<option value="IN_CASH" <?php if( 'IN_CASH' == $yabi_settings[ 'payment_type' ] ) echo 'selected'; ?>><?php echo __( 'In cash','yabi-wc' ); ?></option>
                        	<option value="CREDIT" <?php if( 'CREDIT' == $yabi_settings[ 'payment_type' ] ) echo 'selected'; ?>><?php echo __( 'Credit','yabi-wc' ); ?></option>                             
                        </select>
                    </td>
               	</tr>
                
                <tr id="option_credit" <?php if( 'CREDIT' != $yabi_settings[ 'payment_type' ] ) echo 'style="display: none;"'; ?>>
                	<th><label for="credit_days"><?php echo __('Credit days','yabi-wc'); ?>:</label></th>
                    <td>
                		<input type="number" class="small-text" id="credit_days" name="credit_days" value="<?php echo esc_attr( $yabi_settings[ 'credit_days' ] ); ?>" /><br/>
                        <span class="description">30</span>
                    </td>
               	</tr>
                
                <tr>
                	<th><label for="modified_checkout"><?php echo __( 'I agree to modify the checkout','yabi-wc' ); ?>:</label></th>
                    <td>
                    	<select id="modified_checkout" name="modified_checkout">
                        	<option value="No" <?php if( 'No' == $yabi_settings[ 'modified_checkout' ] ) echo 'selected'; ?>><?php echo __( 'No','yabi-wc' ); ?></option>
                        	<option value="Yes" <?php if( 'Yes' == $yabi_settings[ 'modified_checkout' ] ) echo 'selected'; ?>><?php echo __( 'Yes','yabi-wc' ); ?></option>                             
                        </select>
                    </td>
               	</tr>
                
                <tr id="automatic_option" style="display: none;">
                	<th><label for="invoice_type"><?php echo __('Invoice type','yabi-wc'); ?>:</label></th>
                    <td>
                    	<select id="invoice_type" name="invoice_type">
                        	<option value="Manual" <?php if( 'Manual' == $yabi_settings[ 'invoice_type' ] ) echo 'selected'; ?>><?php echo __( 'Manual','yabi-wc' ); ?></option>
                        	<option value="Automatic" <?php if( 'Automatic' == $yabi_settings[ 'invoice_type' ] ) echo 'selected'; ?>><?php echo __( 'Automatic','yabi-wc' ); ?></option>                             
                        </select>
                    </td>
               	</tr>
            
            </table>
    
            <h2><?php echo __( 'Invoice','yabi-wc' ); ?></h2>    
            <hr />    
            
            <table width="100%" cellpadding="10" cellspacing="10" border="0">
            	
                <tr>
                	<th width="20%"><label for="invoice_name"><?php echo __( 'Invoice Name','yabi-wc' ); ?>:</label></th>
                    <td>
                    	<input type="text" class="small-text" id="invoice_name" name="invoice_name" value="<?php echo esc_attr( $yabi_settings[ 'invoice_name' ] ); ?>" />
                    </td>
               	</tr>
                
                <tr>
                	<th><label for="invoice_number"><?php echo __( 'Invoice Number','yabi-wc' ); ?>:</label></th>
                    <td>
                    	<input type="number" class="small-text" id="invoice_number" name="invoice_number" value="<?php echo esc_attr( $invoice_number ); ?>" />
                    </td>
               	</tr>
            
            </table>            
    
            <h2><?php echo __('Client','yabi-wc'); ?></h2>    
            <hr />      
            
            <table width="100%" cellpadding="10" cellspacing="10" border="0">
            
            	<tr>
                	<th width="20%"><label for="owner"><?php echo __( 'Type of Owner','yabi-wc' ); ?>:</label></th>
                    <td>
                    	<select id="owner" name="owner">
                        	<option value="company" <?php if( 'company' == $yabi_settings[ 'owner' ] ) echo 'selected'; ?>><?php echo __( 'Company','yabi-wc' ); ?></option>
                        	<option value="person" <?php if( 'person' == $yabi_settings[ 'owner' ] ) echo 'selected'; ?>><?php echo __( 'Person','yabi-wc' ); ?></option>                             
                        </select> 
                    </td>
               	</tr>
            
            	<tr>
                	<th><label for="businessunituuid"><?php echo __('Business Unit Uuid','yabi-wc'); ?>:</label></th>
                    <td>
                    	<input type="text" class="large-text" id="businessunituuid" name="businessunituuid" value="<?php echo esc_attr( $yabi_settings[ 'businessunituuid' ] ); ?>" /><br/>
                        <span class="description">xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</span>
                    </td>
               	</tr>
            
            	<tr>
                	<th><label for="token"><?php echo __('Token','yabi-wc'); ?>:</label></th>
                    <td>
                    	<input type="text" class="large-text" id="token" name="token" value="<?php echo esc_attr( $yabi_settings[ 'token' ] ); ?>" /><br/>
                        <span class="description">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</span>
                    </td>
               	</tr>
                
                <tr>
                	<th><label for="yabi_url_client"><?php echo __('URL client','yabi-wc'); ?>:</label></th>
                    <td>
                    	<input type="text" class="large-text" id="url_client" name="url_client" value="<?php echo esc_attr( $yabi_settings[ 'url_client' ] ); ?>" /><br/>
                         <span class="description">https://api.yabi.co/co/einvoices/v2/</span>
                    </td>
               	</tr> 
            	
                <tr>
                	<th></th>
                    <td><input class="button-primary" type="submit" name="Save" value="Save" /></td>
               	</tr>
            
            </table>      
            
        </div>
    
    </form>

</div>
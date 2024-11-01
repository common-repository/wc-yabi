<?php global $post; ?>
<style>
	#yabi-data th
	{
		text-align: right;
	}
	
	<?php if( $person[ 'accountid' ] == 'NATURAL' ): ?>
	
		.data-commercialname, #identifier-instruction
		{
			display: none;
		}
	
	<?php endif; ?>
	
</style>
<script type="text/javascript">

function yabiCommercial()
{
	var type_person = jQuery( '#type_person' ).val();
	
	if( type_person == 'LEGAL_ENTITY' )
	{
		jQuery( '.data-commercialname' ).show( 500 );
		jQuery( '#identifier_digit' ).show();
	}
	else
	{
		jQuery( '.data-commercialname' ).hide();
		jQuery( '#identifier_digit' ).hide();
	}
}

function yabiMessage( type, message, show )
{
	jQuery( '#thespiner' ).removeClass( 'is-active' );
	jQuery( '#response' ).html( '<div class="notice notice-'+ type +' inline"><p>'+ message +'</p></div>' );
	
	if( show )
	{
		jQuery( '#button-yabi-1' ).attr( 'disabled', false );
		jQuery( '#button-yabi-2' ).attr( 'disabled', false );
	}
}

function yabiSave( option )
{
	jQuery( '#thespiner' ).addClass( 'is-active' );
	jQuery( '#button-yabi-1' ).attr( 'disabled', true );
	jQuery( '#button-yabi-2' ).attr( 'disabled', true );
	jQuery( '#response' ).html(' ');
	
	jQuery.post( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', jQuery( '#post' ).serialize() )
		.done(function( data ) {			
			
			if( 'GOOD' == data )
			{
				if( parseInt( option ) == 0 )
				{
					yabiMessage( 'success', 'Saved!', true );
				}
				else
				{
					jQuery.post( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', { action : 'yabi_generate_invoice', post_id : <?php echo esc_attr( $post->ID ); ?> } )
						.done(function( datainvoice ) {
							
							if( 'FAIL' == datainvoice )
							{
								yabiMessage( 'error', 'Saved data!. Unexpected error on invoice, try again later.!', true );
							}
							else
							{
								var obj = JSON.parse( datainvoice );
								
								if( parseInt( obj.error ) == 1 )
								{
									yabiMessage( 'error', obj.message, true );
								}
								else
								{
									yabiMessage( 'success', obj.message, false );
								}
							}
							
						});
			
				}
			}
			else
			{
				yabiMessage( 'error', 'Unexpected error, try again later.!', true );
			}
			
		});
}

jQuery( document ).ready(function(){

	yabiCommercial();
	
});

</script>
	
<input type="hidden" name="post_id" value="<?php echo esc_attr( $post->ID ); ?>" />
<input type="hidden" name="action" value="yabi_save_data" /> 

<table width="100%" border="0" cellpadding="5" cellspacing="5" id="yabi-data">

	<tr>
        <th><?php echo __( 'Type of person','yabi-wc' ); ?>*:</th>
        <td>
            <select name="type_person" id="type_person" onchange="yabiCommercial()">
                
                <?php foreach($typesperson as $id => $name): ?>
                
                    <option <?php if( $person[ 'type_person' ] == $id ): ?>selected="selected"<?php endif; ?> value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
                
                <?php endforeach; ?>
                
            </select>
        </td>
    </tr>    
    
    <tr class="data-commercialname">
        <th><?php echo __( 'Commercial Name','yabi-wc' ); ?>*:</th>
        <td>
             <input class="regular-text" type="text" id="commercialname" name="commercialname" value="<?php echo esc_attr( $person[ 'commercialname' ] ); ?>"  />
        </td>
    </tr>

    <tr>
        <th><?php echo __( 'Name','yabi-wc' ); ?>*:</th>
        <td>
            <input class="regular-text" type="text" id="name" name="name" value="<?php echo esc_attr( $person[ 'name' ] ); ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Second Name','yabi-wc' ); ?>:</th>
        <td>
            <input class="regular-text" type="text" id="second_name" name="second_name" value="<?php if( !empty( $person[ 'second_name' ] ) ): echo esc_attr( $person[ 'second_name' ] ); endif; ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Last Name','yabi-wc' ); ?>*:</th>
        <td>
            <input class="regular-text" type="text" id="lastname" name="lastname" value="<?php echo esc_attr( $person[ 'lastname' ] ); ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Second Last Name','yabi-wc' ); ?>:</th>
        <td>
            <input class="regular-text" type="text" id="second_last_name" name="second_last_name" value="<?php if( !empty( $person[ 'second_last_name' ] ) ): echo esc_attr( $person[ 'second_last_name' ] ); endif; ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Type of document','yabi-wc' ); ?>*:</th>
        <td>
            <select name="type_document" id="type_document">
                
                <?php global $yabi_thetypes; foreach( $yabi_thetypes as $id => $name ): ?>
                
                    <option <?php if( $person[ 'type_document' ] == $id ): ?>selected="selected"<?php endif; ?> value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
                
                <?php endforeach; ?>
                
            </select>
        </td>
    </tr>

    <tr>
        <th><?php echo __( 'Identifier','yabi-wc' ); ?>*:</th>
        <td>
            <input class="regular-text" type="text" id="identifier" name="identifier" value="<?php echo esc_attr( $person[ 'identifier' ] ); ?>"  />
            <input class="small-text" type="text" id="identifier_digit" name="identifier_digit" value="<?php echo esc_attr( $person[ 'identifier_digit' ] ); ?>"  />            
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Email','yabi-wc' ); ?>*:</th>
        <td>
            <input class="regular-text" type="text" id="email" name="email" value="<?php echo esc_attr( $person[ 'email' ] ); ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Telephone','yabi-wc' ); ?>:</th>
        <td>
            <input class="regular-text" type="text" id="telephone" name="telephone" value="<?php echo esc_attr( $person[ 'telephone' ] ); ?>"  />
        </td>
    </tr>	
    
    <tr>
        <th><?php echo __('Address','yabi-wc'); ?>:</th>
        <td>
            <input class="regular-text" type="text" id="address" name="address" value="<?php echo esc_attr( $person[ 'address' ] ); ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('City','yabi-wc'); ?>:</th>
        <td>
        	<select name="city" id="city">
                
                <?php foreach( $diancodes as $id => $name ): ?>
                
                    <option <?php if( $person[ 'city' ] == $id ): ?>selected="selected"<?php endif; ?> value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
                
                <?php endforeach; ?>
                
            </select>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __('Postcode','yabi-wc'); ?>:</th>
        <td>
            <input class="regular-text" type="text" id="citycode" name="citycode" value="<?php echo esc_attr( $person[ 'citycode' ] ); ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Observations','yabi-wc' ); ?>:</th>
        <td>
        	<textarea class="regular-text" id="observations" name="observations" rows="3"><?php if( !empty( $person[ 'observations' ] ) ): echo esc_attr( $person[ 'observations' ] ); endif; ?></textarea>
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Note Name','yabi-wc' ); ?>:</th>
        <td>
            <input class="regular-text" type="text" id="note_name" name="note_name" value="<?php if( !empty( $person[ 'note_name' ] ) ): echo esc_attr( $person[ 'note_name' ] ); endif; ?>"  />
        </td>
    </tr>
    
    <tr>
        <th><?php echo __( 'Note Description','yabi-wc' ); ?>:</th>
        <td>
            <textarea class="regular-text" id="note_value" name="note_value" rows="3"><?php if( !empty( $person[ 'note_value' ] ) ): echo esc_attr( $person[ 'note_value' ] ); endif; ?></textarea>
        </td>
    </tr>
    
    <tr>
        <th></th>
        <td>
            <input id="button-yabi-1" class="button-primary" type="button" name="submit" value="<?php echo __( 'Save','yabi-wc' ); ?>" onClick="yabiSave(0)" />
            
            <input id="button-yabi-2" class="button-primary" type="button" name="submit" value="<?php echo __( 'Save & Generate','yabi-wc' ); ?>" onClick="yabiSave(1)" />
            
            <div id="thespiner" class="spinner" style="float:none;width:auto;height:auto;padding:10px 0 10px 
30px;background-position:0;"></div>
        </td>
    </tr>
    
    <?php $yabi_response = get_post_meta( $post->ID, 'yabi_response', true ); ?>
	
	<?php if( !empty( $yabi_response ) ): ?>
    
        <tr>
            <th>Last error:</th>
            <td>
            
            	<?php echo $yabi_response[ 'data' ][ 'createInvoice' ][ 'errors' ][ 0 ][ 'message' ]; ?><br/>
            	
                <small><?php echo $yabi_response[ 'data' ][ 'createInvoice' ][ 'errors' ][ 0 ][ 'helpText' ]; ?></small>
            
            </td>
            
        </tr>
    
    <?php endif; ?>

</table>

<div id="response"></div> 
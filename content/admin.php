<?php if( $ok ): ?>

<div class="notice notice-success is-dismissible inline">
	<p><?php echo __('Setting saved!','yabi-wc'); ?></p>
</div>

<?php endif; ?>
 
<div class="wrap">	   	
    
    <div class="postbox">	
    			
        <div class="inside">
        
        	<h1><?php echo __('Yabi for WooCommerce','yabi-wc'); ?></h1>
        
        	<nav class="nav-tab-wrapper">
      			<a href="admin.php?page=yabi" class="nav-tab <?php if( empty( $tab ) ):?>nav-tab-active<?php endif; ?>"><?php echo __('Information','yabi-wc'); ?></a>
      			<a href="admin.php?page=yabi&tab=settings" class="nav-tab <?php if( $tab == 'settings'):?>nav-tab-active<?php endif; ?>"><?php echo __('Settings','yabi-wc'); ?></a>      			
    		</nav>
            
            <div class="tab-content">
    			<?php switch( $tab ) :
      				case 'settings':
       					require_once( YABI_PLUGIN_PATH . 'content/settings.php' );
        			break;
      				default:
        				require_once( YABI_PLUGIN_PATH . 'content/information.php' );
        			break;
    			endswitch; ?>
    		</div>
        
        </div>
        
   	</div>
    
</div>
<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php echo __('Information','yabi-wc'); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">
                    
                    	<h2><span><?php echo __('Instructions for settings','yabi-wc'); ?></span></h2>
                        
                         <div class="inside">
                        
                        	<p><?php echo __('Invoices can be defined in Cash or In Credit','yabi-wc'); ?></p>              
                            
                            <ul>
                            	<li><?php echo __('Cash: The payment is made immediately.','yabi-wc'); ?></li>
                                <li><?php echo __('In Credit: The payment is credited and you can select the number of days of the credit, generally it is 30 days','yabi-wc'); ?></li>
                           	</ul>   
							
                            <p><?php echo __('I agree to modify the checkout','yabi-wc'); ?></p>   
                            
                            <ul>
                            	<li><?php echo __('This option allows you to modify the default Woocommerce form to allow the custom fields that Yabi uses, such as IDs or NIT. It also displays the departments and municipalities of Colombia with their respective DIAN codes. It also generates a link so that people can generate the address as necessary for the DIAN.','yabi-wc'); ?></li>
                            </ul>   
                            
                            <p><?php echo __('Invoice type','yabi-wc'); ?></p>
                            
                            <ul>
                            	<li><?php echo __('Automatic: Generates automatic invoices, this depends on whether the data has been filled out correctly.','yabi-wc'); ?></li>
                                <li><?php echo __('Manual: Generate invoices manually in order administration','yabi-wc'); ?></li>
                           	</ul>
                            
						</div>
                    
                    	<p>&nbsp;</p>
                        <hr/>
                        <p>&nbsp;</p>
                    
                    	<h2><span><?php echo __('Instructions for invoice','yabi-wc'); ?></span></h2>
                        
                         <div class="inside">
                        
                        	<p><?php echo __('The fields of the invoice are the name and the consecutive number of the invoice, before the DIAN the requirement for the numbering had to be passed. Please fill in the corresponding data. Example: SETT-26.','yabi-wc'); ?></p>                 
							
                            <ol>                            	
                            	<li><?php echo __('Go to','yabi-wc'); ?> <a href="<?php echo admin_url('/admin.php?page=yabi&tab=settings'); ?>"><?php echo __('settings page','yabi-wc'); ?></a>.</li>
                            	<li><?php echo __('Fill the text field "Invoice Name" with the invoice name. Example: "SETT-", "SE"','yabi-wc'); ?></li>
                            	<li><?php echo __('Fill the text field with the consecutive invoice. Example: if the consecutive number is 25, please fill the text field with 26.','yabi-wc'); ?></li>
                            	<li><?php echo __('Click in button &quot;Save&quot;.','yabi-wc'); ?></li>
                                
                            </ol>
                            
						</div>	
                    
                    	<p>&nbsp;</p>
                        <hr/>
                        <p>&nbsp;</p>

						<h2><span><?php echo __('Instructions for client','yabi-wc'); ?></span></h2>

						<div class="inside">
							
                            <ol>
                            	<li><?php echo __('To get started, contact Yabi to send you the personalized Token of your account by email.','yabi-wc'); ?></li>
                            	<li><?php echo __('Enter the Yabi system','yabi-wc'); ?> <a href="https://einvoices.yabi.co/public/login" target="_blank">https://einvoices.yabi.co/public/login</a> <?php echo __('with your username and password.','yabi-wc'); ?></li>
                            	<li><?php echo __('Click on &quot;Business Units&quot;.','yabi-wc'); ?></li>
                            	<li><?php echo __('Click on a business unit, this displays the information of the business unit.','yabi-wc'); ?></li>
                            	<li><?php echo __('Copy the code of the business unit.','yabi-wc'); ?></li>
                            	<li><?php echo __('Go to','yabi-wc'); ?> <a href="<?php echo admin_url('/admin.php?page=yabi&tab=settings'); ?>"><?php echo __('settings page','yabi-wc'); ?></a>.</li>
                            	<li><?php echo __('Paste the previously copied code in the text field &quot;Business Unit Uuid&quot;.','yabi-wc'); ?></li>
                            	<li><?php echo __('Copy and paste  the personalized Token in the text field &quot;Token&quot;.','yabi-wc'); ?></li>
                            	<li><?php echo __('Copy the example URL client in the text field &quot;URL client&quot;.','yabi-wc'); ?></li>
                            	<li><?php echo __('Click in button &quot;Save&quot;.','yabi-wc'); ?></li>
                                
                            </ol>
                            
						</div>
						<!-- .inside -->
                        
                        <p>&nbsp;</p>
                        <hr/>
                        <p>&nbsp;</p>

						<h2><span><?php echo __('How to generate an invoice?','yabi-wc'); ?></span></h2>

						<div class="inside">
                        
                        	<p><?php echo __('The process to generate an invoice.','yabi-wc'); ?></p>
                            
                            <p><?php echo __('The WooCommerce purchase must be in a completed state, in order to generate the electronic invoice. (Automatic or Manual)','yabi-wc'); ?></p>
                            
                            <ol>
                            	<li><?php echo __('Click on WooCommerce orders','yabi-wc'); ?></li>
                                <li><?php echo __('Find the order in the completed state for which you want to generate an invoice','yabi-wc'); ?></li>
                                <li><?php echo __('Click on the order to open the details','yabi-wc'); ?></li>
                                <li><?php echo __('When displaying the order information, the Yabi module will be displayed at the bottom','yabi-wc'); ?></li>
                                <li><?php echo __('You must select the type of person','yabi-wc'); ?></li>
                                <li><?php echo __('Depending on the type of person, you must complete the additional information','yabi-wc'); ?></li>
                                <li><?php echo __('Click on the "Save" button to save the information of the fields','yabi-wc'); ?></li>
                                <li><?php echo __('Click on the button "Save and generate" for when you are sure to generate the invoice, after the invoice is generated you will not be able to edit the fields again.','yabi-wc'); ?></li>
                                <li><?php echo __('To download the invoice you have to enter the Yabi platform','yabi-wc'); ?></li>
                            </ol>
                        
                        </div>

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h2><span><?php echo __('About the plugin','yabi-wc'); ?></span></h2>

						<div class="inside">
							
                            <p>
                            	<?php echo __('Support this plugin','yabi-wc'); ?> 
                                
                                <form action="https://www.paypal.com/donate" method="post" target="_top">
<input type="hidden" name="hosted_button_id" value="NY5NTM46C5PS4" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_CO/i/scr/pixel.gif" width="1" height="1" />
</form>

                            </p>
                            <hr/>
                            <p>
                            	<?php echo __('Code QR','yabi-wc'); ?><br/><br/>
                                <img src="<?php echo YABI_PLUGIN_URL; ?>/images/CodigoQR.png" width="128" height="128" />
                            </p>
                            
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
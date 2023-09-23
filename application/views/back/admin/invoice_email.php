<!--=== Content Part ===-->
<table width="100%" style="background:rgba(212, 224, 212, 0.17);">
	<?php
	$sale_details = $this->db->get_where( 'sale', array( 'sale_id' => $sale_id ) )->result_array();
	foreach ( $sale_details as $row ) {
		?>
        <!--Invoice Header-->
        <tr>
            <td style="padding:10px;">
                <img src="<?php echo $this->crud_model->logo( 'home_top_logo' ); ?>" alt=""
                     style="width:100%;max-width:200px;">
            </td>
            <td style="vertical-align: top;">
                <table>
                    <tr>
                        <td>
                            <h1 class="text-center"><?php echo translate( 'sales_invoice' ); ?></h1>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php echo translate( 'invoice_no' ); ?></strong> : <?php echo $this->crud_model->get_sale_code($row['sale_id']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php echo translate( 'date' ); ?></strong>
                            : <?php echo date( 'd M, Y', $row['sale_datetime'] ); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <!--End Invoice Header-->

        <!--Invoice Detials-->
        <tr>
            <td style="padding:20px;">
                <div class="tag-box tag-box-v3">
					<?php
					$info = json_decode( $row['shipping_address'], true );
					?>
                    <h2><?php echo translate( 'client_information:' ); ?></h2>
                    <table>
                        <tr>
                            <td>
                                <strong><?php echo translate( 'first_name:' ); ?></strong> <?php echo $info['firstname']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong><?php echo translate( 'last_name:' ); ?></strong> <?php echo $info['lastname']; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="tag-box tag-box-v3">
                    <h2><?php echo translate( 'payment_details_:' );
						$payment_status = $this->crud_model->sale_payment_status( $row['sale_id'] );
						?></h2>
                    <table>
                        <tr>
                            <td><strong><?php echo translate( 'payment_status_:' ); ?></strong>
                                <i><?php echo translate( $payment_status ); ?></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong><?php echo translate( 'payment_method_:' ); ?></strong> <?php echo ucfirst( str_replace( '_', ' ', $info['payment_type'] ) ); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <!--End Invoice Details-->

        <!--Invoice Table-->
        <tr>
            <td style="padding:10px 5px 0px; background:rgb(34, 110, 203); color:white; text-align:center;" colspan="2">
                <h3><?php echo translate( 'products_in_invoice' ); ?></h3>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding:0px;">
                <table width="100%">
                    <thead>
                    <tr>
                        <th style="padding: 5px;background:rgba(128, 128, 128, 0.30)"><?php echo translate( 'no' ); ?></th>
                        <th style="padding: 5px;background:rgba(128, 128, 128, 0.30)"><?php echo translate( 'item' ); ?></th>
                        <th style="padding: 5px;background:rgba(128, 128, 128, 0.30)"><?php echo translate( 'option' ); ?></th>
                        <th style="padding: 5px;background:rgba(128, 128, 128, 0.30)"><?php echo translate( 'quantity' ); ?></th>
                        <th style="padding: 5px;background:rgba(128, 128, 128, 0.30)"><?php echo translate( 'unit_cost' ); ?></th>
                        <th style="padding: 5px;background:rgba(128, 128, 128, 0.30)"><?php echo translate( 'total' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$product_details = json_decode( $row['product_details'], true );
					$i               = 0;
					$total           = 0;
					foreach ( $product_details as $row1 ) {
						$i ++;
						?>
                        <tr>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo $i; ?></td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo $row1['name']; ?>
                                <br/> <?php echo $row1['sku_code']; ?></td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)">
								<?php
								$all_o = json_decode( $row1['option'], true );
								if ( isset( $all_o['color'] ) && ! empty( $all_o['color'] ) ) {
									$color = $all_o['color']['value'];
									if ( $color ) {
										$titlecolorname = '<span style="display:inline-block;"> Blue  </span>';
										echo $titlecolorname;
										?>
                                        <div style="background:<?php echo $color; ?>; height:25px; width:25px;display:inline-block;"></div>
										<?php
									}
								}
								?>
								<?php
								foreach ( $all_o as $l => $op ) {
									if ( $l !== 'color' && $op['value'] !== '' && $op['value'] !== null ) {
										?>

                                        <br/>
										<?php echo $op['title'] ?> :
										<?php
										if ( is_array( $va = $op['value'] ) ) {
											echo $va = join( ', ', $va );
										} else {
											echo $va;
										}
										?>
                                        <br>
										<?php
									}
								}
								?>
                            </td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo $row1['qty']; ?></td>
                            <td style="padding: 5px;text-align:center;background:rgba(128, 128, 128, 0.18)"><?php echo currency() . $this->cart->format_number( $row1['price'] ); ?></td>
                            <td style="padding: 5px;text-align:right;background:rgba(128, 128, 128, 0.18)"><?php echo currency() . $this->cart->format_number( $row1['subtotal'] );
								$total += $row1['subtotal']; ?></td>
                        </tr>
						<?php
					}
					?>
                    </tbody>
                </table>
            <td>
        </tr>
        <!--End Invoice Table-->

        <!--Invoice Footer-->
        <tr>
            <td width="50%" style="">
                <table>
                    <tr>
                        <td style="padding:10px 20px;"><h2><?php echo translate( 'delivery_address' ); ?></h2></td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
							<?php echo $info['address1']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
							<?php echo $info['address2']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
							<?php echo translate( 'zipcode' ); ?> : <?php echo $info['zip']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
							<?php echo translate( 'phone' ); ?> : <?php echo $info['phone']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:3px 20px;">
							<?php echo translate( 'e-mail' ); ?> : <?php echo $info['email']; ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align:right;">
                <table width="100%">
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%; ">
                            <h3><?php echo translate( 'sub_total_amount' ); ?> :</h3></td>
                        <td style="text-align:right;padding:3px">
                            <h3><?php echo currency() . $this->cart->format_number( $total ); ?></h3></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%;"><h3><?php echo translate( 'tax' ); ?>
                                :</h3></td>
                        <td style="text-align:right;padding:3px">
                            <h3><?php echo currency() . $this->cart->format_number( $row['vat'] ); ?></h3></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%;"><h3><?php echo translate( 'shipping' ); ?>
                                :</h3></td>
                        <td style="text-align:right;padding:3px">
                            <h3><?php echo currency() . $this->cart->format_number( $row['shipping'] ); ?></h3></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding:3px; width:80%;">
                            <h2><?php echo translate( 'grand_total' ); ?> :</h2></td>
                        <td style="text-align:right;padding:3px">
                            <h2><?php echo currency() . $this->cart->format_number( $row['grand_total'] ); ?></h2></td>
                    </tr>
                </table>

            </td>
        </tr>
		<?php if ( $payment_status != 'fully_paid' && $payment_status != 'failed' ) { ?>
            <tr>
                <td style="padding:20px;">
                    <h2>Payment Instructions</h2>
                    <strong>REFERENCE: <?php echo $this->crud_model->get_sale_code($row['sale_id']); ?></strong><br/>
					<?php
					if ( $info['payment_type'] == 'fnb' ) {
						?>
						<?php echo translate( 'payment URL' ); ?>: <?php echo $url; ?>
						<?php
					} ?>

					<?php
					if ( $info['payment_type'] == 'eft' ) {
						?>
						<?php echo translate( 'eft_instructions' ); ?>
                        Reference: <?php echo $this->crud_model->get_sale_code($row['sale_id']); ?>
						<?php
					} ?>
					<?php
					if ( $info['payment_type'] == 'cash_deposit' ) {
						?>
						<?php echo translate( 'cash_deposit_instructions' ); ?>
                        Reference: <?php echo $this->crud_model->get_sale_code($row['sale_id']); ?>
						<?php
					} ?>

                    <br/>
                    If you are having issues with your payment, please review other payment options by <a href="https://www.tvmall.co.za/index.php/home/payment_options">clicking here</a><br/>
                </td>
            </tr>
		<?php } ?>

	<?php } ?>
</table><!--/container-->
<!--=== End Content Part ===-->
<?php /* ?>
    <h4>
        ** You can download purchased (fully paid) digital products form your profile.
    </h4>
     */
?>
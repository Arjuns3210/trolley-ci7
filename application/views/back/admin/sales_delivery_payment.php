<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/sales/delivery_payment_set/' . $sale_id, array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'delivery_payment',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
			<?php
                if($_SESSION['login_type'] != 'warehouse'){
                if($payment_status !== ''){
            ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('payment_status'); ?></label>
                        <div class="col-sm-6">
                        <?php
                            if($payment_type == 'payInCash' || $payment_type == 'payInCard' || $payment_type == 'ePaymentCard' || $payment_type == 'ePaymentWallet' || $payment_type == 'trolleyCredit'){
                        ?>
                            <?php
//                                $from = array('due','failed','paid','refund_pending','refund_successful');
                                $from = array('pending','paid','failed');
                                echo $this->crud_model->select_html($from,'payment_status','','edit','demo-chosen-select',$payment_status);
                            ?>	
                        <?php
                            } 
                        ?>
                        </div>
                </div>
            <?php
            	}
            ?>
            <?php
                if($payment_status !== ''){
            ?>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('payment_details'); ?></label>
                <div class="col-sm-6">
                    <textarea name="payment_details" class="form-control" <?php if($payment_type == 'ePayment'){ ?>readonly<?php } ?> rows="10"><?php echo $payment_details; ?></textarea>
                </div>
            </div>
            <?php
                }else{
            ?>
            <center>
                <h3><?php echo translate('no_product_from_admin'); ?></h3>
            </center>
            <script>
                $(document).ready(function(e) {
                    $('.btn-purple').hide();
                });
            </script>
            <?php
                }
                }
            ?>
			<?php
                if($delivery_status !== ''){
            ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('delivery_status'); ?></label>
                    <div class="col-sm-6">
                        <?php
                            $from = array('pending','process','delivered');
                            echo $this->crud_model->select_html($from,'delivery_status','','edit','demo-chosen-select',$delivery_status,'','','getCheckBoxOption');
                        ?>
                    </div>
                </div>
                <span id="showStockHandlingCheckbox" style="display: none;">
                       <div class="form-group">
                            <label class="col-sm-4 control-label" for="demo-hor-44"><?php echo translate('Remove_user_quantity_from_current_stock');?></label>
                            <div class="col-sm-6">
                                <input type="checkbox"  id="stock_handled"  name="stock_handled" value="yes" <?php if(!empty($stock_handled) && $stock_handled== 'yes') { echo 'checked  disabled'; }  ?>>
                            <p style="color:red;font-weight: bold">Note : once checked this cannot be edited.</p>
                            </div>
                    </div>
                </span>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('details_on_delivery_status'); ?></label>
                    <div class="col-sm-6">
                        <textarea class="form-control textarea" name="comment"><?php echo $comment; ?></textarea>
                    </div>
                </div>
            <?php
            	}
            ?>

        </div>
    </form>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        total();
         <?php /*if( $stock_handled == 'yes' || $delivery_status == 'collected') {?>
            $('#showStockHandlingCheckbox').show();
        <?php }*/ ?>  
    });
    

    function total(){
        var total = Number($('#quantity').val())*Number($('#rate').val());
        $('#total').val(total);
    }

    $(".totals").change(function(){
        total();
    });
	
	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
                <?php //added by sagar: 05-10
                if(isset($payment_status) && $payment_status == 'failed') { ?>
                        $('.enterer').hide();
                <?php } ?>
	});
        
    function getCheckBoxOption(id){
//        console.log("SELECTED ID : "+id);
        /* NOT In Use
         * if(id == 'collected'){
            $('#showStockHandlingCheckbox').show();
        }else{
            $('#showStockHandlingCheckbox').hide();
        } */
    }
</script>
<div id="reserve"></div>


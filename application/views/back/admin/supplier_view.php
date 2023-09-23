<!--CONTENT CONTAINER-->
<?php 
		foreach($product_data as $row)
        { 
?>

<h4 class="modal-title text-center padd-all"><?php echo translate('details_of');?> <?php echo $row['supplier_name'];?></h4>
	<hr style="margin: 10px 0 !important;">
    <div class="row">
    <div class="col-md-12">
        <div class="text-center pad-all">
            <center>
            <div class="col-md-12">   
                <table class="table table-striped" style="border-radius:3px;">
                    <tr>
                        <th class="custom_td"><?php echo translate('name');?></th>
                        <td class="custom_td"><?php echo $row['supplier_name']?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('mobile_number');?></th>
                        <td class="custom_td"><?php echo $row['mobile_number']?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('email_address');?></th>
                        <td class="custom_td"><?php echo $row['email_address']?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('address');?></th>
                        <td class="custom_td"><?php echo $row['address']?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('company_name');?></th>
                        <td class="custom_td"><?php echo $row['company_name']; ?></td>
                    </tr>
                     <tr>
                        <th class="custom_td"><?php echo translate('payment_terms');?></th>
                        <td class="custom_td">
                               <?php echo $row['payment_terms']; ?>
                       </td>
                    </tr>
                   
                    <tr>
                        <th class="custom_td"><?php echo translate('bank_details');?></th>
                        <td class="custom_td"><?php echo $row['bank_details']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('billng_address');?></th>
                        <td class="custom_td"><?php echo $row['billing_address']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('other_details');?></th>
                        <td class="custom_td"><?php echo $row['other_details']; ?></td>
                    </tr>
                </table>
            </div>
          </center>
            
        </div>
    </div>
</div>				

<?php 
	}
?>
            
<style>
.custom_td{
border-left: 1px solid #ddd;
border-right: 1px solid #ddd;
border-bottom: 1px solid #ddd;
}
</style>

<script>
	$(document).ready(function(e) {
		proceed('to_list');
	});
</script>
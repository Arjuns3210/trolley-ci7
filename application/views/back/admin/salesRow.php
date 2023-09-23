<tr id="row_id_<?php echo ($counter+1); ?>" read_row_no="<?php echo ($counter); ?>" class="row-top row_id_<?php echo ($counter+1); ?>">
    <td><span class="srno"><?php echo ($counter+1); ?></span>
    <br>
    <span style="cursor: pointer;font-size: 15px;color: blue;" title="Delete this row" onclick="remove_this_row('row_id_<?php echo ($counter+1); ?>');"><i class="fa fa-trash" aria-hidden="true"></i></span>
    </td>
   
    <td rowspan="4">
		
                            <span style="width:100%;" ><?php if(isset($prdt[0]) && !empty($prdt[0]['title'])){ echo $prdt[0]['title']; }else{ echo ' '; } ?></span>
                             <br>
                            <span style="width:100%;" >Product Code : <?php if(isset($prdt[0]) && !empty($prdt[0]['product_code'])){ echo $prdt[0]['product_code']; }else{ echo ' '; } ?></span>
                            <span id="product_select<?php echo $counter; ?>"></span>
                            <span id="prod_id_here<?php echo $counter; ?>">
                                <input class="product_ids" msg_id="<?php echo $counter; ?>" type="hidden"  id="product_id<?php echo $counter; ?>"  name="product_id[<?php echo $counter; ?>]" value="<?php if(isset($prdt[0]) && !empty($prdt[0]['product_id'])){echo $prdt[0]['product_id'];}else{ if(isset($data['product_id']) && !empty($data['product_id'])){echo $data['product_id']; } }?>">
                            </span>
                            <br>
                            <br>
                            <br>
                            SKU Code : <span id='sku_code<?php echo $counter; ?>'></span>
                            
		
    </td>
	
    
    <td rowspan="4">
       <span id='sale_price<?php echo $counter; ?>' ></span>
       <br/>
       
    </td>
    
    <td rowspan="4">
        
        <?php
        
        $variation = $this->crud_model->getVariationAttributeValueMapping($prdt[0]['product_id']);

        ?>
        
        <?php
        if($prdt[0]['product_type']=='variation'){
            ?>
        <table>
        <?php
        
        $default_variation_to_select = 'variation'.$counter.$variation[0]['variation_id'];
        foreach($variation as $vk => $vv){
            $checked = '';
            if($vv['is_default'] == 'yes'){
                $checked = 'checked';
                $default_variation_to_select = 'variation'.$counter.$vv['variation_id'];
            }
            $attributes = explode('|', $vv['group_attribute_name']);
            $attributesvalue = explode('|', $vv['group_att_value']);
            $price_after_discount = $this->crud_model->get_variant_product_price($prdt[0]['product_id'],$vv['variation_id']);
            $discount_price = $vv['variation_price']-$price_after_discount;
            $tax_price = $this->crud_model->get_product_tax($prdt[0]['product_id'],$vv['variation_price']);
            $variation_stock = $vv['variation_stock']
        ?>
        
            <tr>
                <td style="padding: 5px;"><input type="radio" <?php echo $checked; ?> data-tax="<?php echo $tax_price; ?>" data-stock="<?php echo $variation_stock; ?>" data-discount="<?php echo $discount_price; ?>"  data-price-after-discount="<?php echo $price_after_discount; ?>" data-saleprice="<?php echo $vv['variation_price']; ?>" data-skucode="<?php echo $vv['sku_code']; ?>" name="variation[<?php echo $counter; ?>]" value="<?php echo $vv['variation_id']; ?>" id="variation<?php echo $counter.$vv['variation_id']; ?>"></td>
                <td style="padding: 5px;">
                    <label for="variation<?php echo $counter.$vv['variation_id']; ?>" style="cursor: pointer;">
                        <?php if(is_array($attributes) && is_array($attributesvalue)){
                            foreach($attributes as $attk => $attv){

                                echo $attv.':'.$attributesvalue[$attk].'<br>';
                            }

                        } ?>
                    </label>
                </td>
            </tr>
        
        
        <?php }
        ?>
        </table>
        <?php
        }else{
            echo 'No Variation Available';
           
        
        $default_variation_to_select = 'variation'.$counter.$variation[0]['variation_id'];
        foreach($variation as $vk => $vv){
            $checked = '';
            if($vv['is_default'] == 'yes'){
                $checked = 'checked';
                $default_variation_to_select = 'variation'.$counter.$vv['variation_id'];
            }
            $attributes = explode('|', $vv['group_attribute_name']);
            $attributesvalue = explode('|', $vv['group_att_value']);
            $price_after_discount = $this->crud_model->get_variant_product_price($prdt[0]['product_id'],$vv['variation_id']);
            $discount_price = $vv['variation_price']-$price_after_discount;
            $tax_price = $this->crud_model->get_product_tax($prdt[0]['product_id'],$vv['variation_price']);
            $variation_stock = $vv['variation_stock']
        ?>
        <input style="display: none;" type="radio" <?php echo $checked; ?> data-tax="<?php echo $tax_price; ?>" data-stock="<?php echo $variation_stock; ?>" data-discount="<?php echo $discount_price; ?>"  data-price-after-discount="<?php echo $price_after_discount; ?>" data-saleprice="<?php echo $vv['variation_price']; ?>" data-skucode="<?php echo $vv['sku_code']; ?>" name="variation[<?php echo $counter; ?>]" value="<?php echo $vv['variation_id']; ?>" id="variation<?php echo $counter.$vv['variation_id']; ?>">
             
        
        
        <?php }
        
        }
        ?>
        
        <?php // added by dev -- End?>
        
    </td>
    
    
    
    <td>
        <input style="width:50%;" class="input-xlarge form-control quantity" type="text" autocomplete="off"  onfocus="this.select();"  name="quantity[<?php echo $counter; ?>]"  id="quantity<?php echo $counter; ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="1" onkeyup="startCalculation(<?php echo $counter; ?>);"><span ></span>
    </td>
   
    
    
    <td>
      
        
      <span id='current_stock<?php echo $counter; ?>'></span>
       
    </td>
    
    <td>
         
          
        <input style="width:100%;" class="input-xlarge form-control taxable_value" type="text" autocomplete="off"  onfocus="this.select();"  name="total[<?php echo $counter; ?>]" id="total<?php echo $counter; ?>" readonly="" data-calculatedValue="<?php  ?>" value="" >
                       
        
     <span ></span>
    </td>
    
</tr>
<tr class="row_id_<?php echo ($counter+1); ?>">
    <td></td>
    <td></td>
    
    <td>Discount</td>
    
    <td>
        
        
        <input style="width:100%;" class="input-xlarge form-control discount_amt" type="text" autocomplete="off"  onfocus="this.select();"  name="discount_amt[<?php echo $counter; ?>]"   id="discount_amt<?php echo $counter; ?>" onblur="" data-discountValue="<?php ?>" value="" readonly=""  onkeydown=""><span ></span>
    </td>
</tr>
<tr class="row_id_<?php echo ($counter+1); ?>">
    <td></td>
    <td></td>
    

    <td>TAX</td>
    
    <td>
         <?php 
         $tax_price = 0;
         if($prdt[0]['tax'] > 0){ ?> 
                       <?php  
                           $tax_price =  $this->crud_model->get_product_tax($prdt[0]['product_id']);
                            }
                       ?>
        <input style="width:100%;" class="input-xlarge form-control tax" type="text" autocomplete="off"  onfocus="this.select();"  name="tax[<?php echo $counter; ?>]"   id="tax<?php echo $counter; ?>" onblur="" data-taxValue="<?php echo $tax_price; ?>" value="<?php echo $tax_price; ?>" readonly="" onkeydown=""><span ></span>
    </td>
</tr>
<tr class="row_id_<?php echo ($counter+1); ?>">
    <td></td>
    <td></td>
    
    <td>Shipping</td>
   
    <td>
        <?php 
         $shipping_price = 0;
         if($prdt[0]['shipping_cost'] > 0){ ?> 
                       <?php  
                           $shipping_price =  $this->crud_model->get_shipping_cost($prdt[0]['product_id']);
                        }
                       ?>
        <input style="width:100%;" class="input-xlarge form-control shipping" type="text" autocomplete="off"  onfocus="this.select();"  name="shipping[<?php echo $counter; ?>]"  id="shipping<?php echo $counter; ?>" onblur="" data-shippingValue="<?php echo $shipping_price; ?>" value="<?php echo $shipping_price; ?>" readonly="" onkeydown=""><span ></span>
    </td>
</tr>

<?php //if(isset($type) && $type=='update'){ ?>
<script>
    $(document).ready(function(){
        
        $('#<?php echo $default_variation_to_select ; ?>').prop('checked',true);
        change_values(<?php echo $counter; ?>);
        $('input[name="variation[<?php echo $counter; ?>]"]').change(function(){
            change_values(<?php echo $counter; ?>);
        });
    });
   
</script>
<?php// } ?>


<style>
    .colors{
        width: 20px;
        height: 20px;
        border-radius: 50%;
    }
</style>
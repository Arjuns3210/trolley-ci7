<?php 
//if($prod_type  == 'simple') { 
//    $dummy_counter = $counter;
 $variation = $this->crud_model->getVariationAttributeValueMapping($prdt[0]['product_id']);
 if(is_array($variation) && !empty($variation)) {
   
     foreach($variation as $row => $val) { 
  
        $attributes = explode('|', $val['group_attribute_name']);
        $attributesvalue = explode('|', $val['group_att_value']);
?>
    <tr id="row_id_<?php echo $val['product_id'].'_'.$counter ; ?>" read_row_no="<?php echo $val['product_id'].'_'.$counter ; ?>" class="<?php if($row == '0') { echo 'row-top'; } ?> row_id_<?php echo $val['product_id'].'_'.$counter ; ?>">
        <td>
            <input type="hidden" name="variation_id[<?php echo $val['product_id'].'_'.$counter ; ?>]" value="<?php echo $val['variation_id']; ?>">
            <input type="hidden" name="product_id[<?php echo $val['product_id'].'_'.$counter ; ?>]" value="<?php echo $val['product_id']; ?>">
            <span class="srno"><?php echo ($row); ?></span>
            <br>
            <span style="cursor: pointer;font-size: 15px;color: blue;" title="Delete this row" onclick="remove_this_row('row_id_<?php echo $val['product_id'].'_'.$counter ; ?>');"><i class="fa fa-trash" aria-hidden="true"></i></span>
        </td>
        <td>
            <span style="width:100%;" ><?php if(isset($prdt[0]) && !empty($prdt[0]['title'])){ echo $prdt[0]['title']; }else{ echo ' '; } ?></span>
            <br>
            <span style="width:100%;" >Product Code : <?php if(isset($prdt[0]) && !empty($prdt[0]['product_code'])){ echo $prdt[0]['product_code']; }else{ echo ' '; } ?></span>
        </td>
        <td>
            <span style="width:100%;" >Title : <?php if(isset($val['varaiation_title']) && !empty($val['varaiation_title'])){ echo $val['varaiation_title']; }else{ echo ' '; } ?></span>
            <br>
            <span style="width:100%;" >SKU Code : <?php if(isset($val['sku_code']) && !empty($val['sku_code'])){ echo $val['sku_code']; }else{ echo ' '; } ?></span>
        </td>    
        <td>
            <input style="width:100%;" class="input-xlarge form-control quantity" type="text" autocomplete="off"  onfocus="this.select();"  name="price[<?php echo $val['product_id'].'_'.$counter ; ?>]"  id="price<?php echo $val['product_id'].'_'.$counter ; ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="<?php if(isset($val['variation_price'])){ echo $val['variation_price']; }?>" ><span ></span>
        </td> 
        <td style="padding: 5px;">
            <label for="variation<?php echo $counter.$val['variation_id']; ?>" style="cursor: pointer;">
                <?php if(is_array($attributes) && is_array($attributesvalue) && $prod_type  == 'variation'){
                    foreach($attributes as $attk => $attv){

                        echo $attv.':'.$attributesvalue[$attk].'<br>';
                    }

                }else{ 
                    echo '<center>No Variation Available</center>';
                }
                ?>

            </label>
        </td>
        <td>
            <input style="width:100%;" class="input-xlarge form-control quantity" type="text" autocomplete="off"  onfocus="this.select();"  name="in_stock[<?php echo $val['product_id'].'_'.$counter ; ?>]"   onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="0" ><span ></span>
        </td> 
    </tr>
<?php 
        $counter++;
        } 
    }
?>

<script>
//    $(document).ready(function(){
//        
//        $('#<?php //echo $default_variation_to_select ; ?>').prop('checked',true);
//        change_values(<?php //echo $counter; ?>);
//        $('input[name="variation[<?php //echo $counter; ?>]"]').change(function(){
//            change_values(<?php //echo $counter; ?>);
//        });
//    });   
counter = <?php echo $counter; ?>;
//   console.log(counter);
</script>


<style>
    .colors{
        width: 20px;
        height: 20px;
        border-radius: 50%;
    }
</style>
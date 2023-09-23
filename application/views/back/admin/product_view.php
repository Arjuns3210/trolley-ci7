<!--CONTENT CONTAINER-->
<?php
foreach ($product_data as $row) {
        $supplierDetails =  array();
        if($row['supplier'] != 0) { 
            $supplierDetails = $this->db->select('supplier_name')->get_where('supplier',array('supplier_id'=>$row['supplier']))->row_array();
        }
    ?>

    <h4 class="modal-title text-center padd-all"><?php echo translate('details_of'); ?><?php echo ' ' . $row['title']; ?></h4>
    <hr style="margin: 10px 0 !important;">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center pad-all">
                <div class="col-md-3">
                    <div class="col-md-12">
                        <?php  /* $image_ids = explode(",,", $row['product_drive_ids']); ?> 
                        <img class="img-responsive thumbnail" alt="Profile Picture"
                             src="<?php if(isset($image_ids[0]) && !empty($image_ids[0])) { echo IMAGE_DRIVE_URL . '' . $image_ids[0]; } else{ echo base_url().'uploads/product_image/default.jpg' ; } ?>">
                            */ ?>  
                               <img class="img-responsive thumbnail" alt="Profile Picture"
                               src="<?php echo $this->crud_model->file_view( 'product', $row['product_id'], '', '', 'no', 'src', 'multi', 'one' ); ?>"> 
                                  
                    </div>
                    <div class="col-md-12" style="text-align:justify;">
                        <p><?php echo $row['description']; ?></p>
                    </div>
                </div>
                <div class="col-md-9">
                    <table class="table table-striped" style="border-radius:3px;">
                        <tr>
                            <th class="custom_td"><h3><?php echo translate('Product_type'); ?></h3></th>
                            <td class="custom_td"><b>
                                    <h3><?php echo $row['product_type'] == 'simple' ? 'Simple Product' : 'Variation Product' ?></h3>
                                </b></td>
                        </tr>
                        <tr>
                            <th class="custom_td"><?php echo translate('name'); ?></th>
                            <td class="custom_td"><?php echo $row['title'] ?></td>
                        </tr>
                        <tr>
                            <th class="custom_td"><?php echo translate('category'); ?></th>
                            <td class="custom_td">
                                <?php echo $this->crud_model->get_type_name_by_id('category', $row['category'], 'category_name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="custom_td"><?php echo translate('sub-category'); ?></th>
                            <td class="custom_td">
                                <?php echo $this->crud_model->get_type_name_by_id('sub_category', $row['sub_category'], 'sub_category_name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="custom_td"><?php echo translate('brand'); ?></th>
                            <td class="custom_td">
                                <?php echo $this->crud_model->get_type_name_by_id('brand', $row['brand']); ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="custom_td"><?php echo translate('unit'); ?></th>
                            <td class="custom_td"><?php echo $row['unit']; ?></td>
                        </tr>
                      
                        <tr>
                            <th class="custom_td"><?php echo translate('discount'); ?></th>
                            <td class="custom_td"><?php echo $row['discount'] . ' %'; ?></td>
                        </tr>
                        <tr>
                            <th class="custom_td"><?php echo translate('supplier_name'); ?></th>
                            <td class="custom_td"><?php echo isset($supplierDetails['supplier_name']) ? $supplierDetails['supplier_name'] : ' - '; ?></td>
                        </tr>
                    
                        <?php if ($row['product_type'] == 'simple') { ?>
                            <tr>
                                <th class="custom_td"><?php echo translate('supplier_price'); ?></th>
                                <td class="custom_td"><?php
                                    if (is_array($simple_variation_data) && count($simple_variation_data) > 0) {
                                        echo DEF_CURR . $simple_variation_data[0]['supplier_price'];
                                    }
                                    ?> / <?php echo $row['unit']; ?></td>
                            </tr>
                            <tr>
                                <th class="custom_td"><?php echo translate('sale_price'); ?></th>
                                <td class="custom_td"><?php
                                    if (is_array($simple_variation_data) && count($simple_variation_data) > 0) {
                                        echo DEF_CURR . $simple_variation_data[0]['sale_price'];
                                    }
                                    ?> / <?php echo $row['unit']; ?></td>
                            </tr>
                            <tr>
                                <th class="custom_td"><?php echo translate('sku_code'); ?></th>
                                <td class="custom_td"><?php
                            if (is_array($simple_variation_data) && count($simple_variation_data) > 0) {
                                echo $simple_variation_data[0]['sku_code'];
                            }
                                    ?></td>
                            </tr>

    <?php } ?>

    <?php /* if ($row['shipping_cost'] != '') { ?>
                            <tr>
                                <th class="custom_td"><?php echo translate('shipping_cost'); ?></th>
                                <td class="custom_td"><?php echo $row['shipping_cost']; ?>
                                    / <?php echo $row['unit']; ?></td>
                            </tr>
                                <?php }
                                if ($row['tax'] != '') {
                                    ?>
                            <tr>
                                <th class="custom_td"><?php echo translate('tax'); ?></th>
                                <td class="custom_td">
                                    <?php echo $row['tax']; ?>
                                        <?php
                                        if ($row['tax_type'] == 'percent') {
                                            echo '%';
                                        } elseif ($row['tax_type'] == 'amount') {
                                            echo currency('', 'def');
                                        }
                                        ?>
                                    / <?php echo $row['unit']; ?>
                                </td>
                            </tr>
                                <?php }
                                if ($row['discount'] != '') {
                                    ?>
                            <tr>
                                <th class="custom_td"><?php echo translate('discount'); ?></th>
                                <td class="custom_td">
                            <?php echo $row['discount']; ?>
                            <?php
                            if ($row['discount_type'] == 'percent') {
                                echo '%';
                            } elseif ($row['discount_type'] == 'amount') {
                                echo currency('', 'def');
                            }
                            ?>
                                    / <?php echo $row['unit']; ?>
                                </td>
                            </tr>
                                <?php } */  ?>
                     
                        <?php /*
                        <tr>
                            <th class="custom_td"><?php echo translate('tag'); ?></th>
                            <td class="custom_td">
                    <?php foreach (explode(',', $row['tag']) as $tag) { ?>
                        <?php echo $tag; ?>
                    <?php } ?>
                            </td>
                        </tr>
                        */ ?>
                        <tr>
                            <th class="custom_td"><?php echo translate('status'); ?></th>
                            <td class="custom_td"><?php echo $row['status'] == 'ok' ? 'Published' : 'unpublished'; ?></td>
                        </tr>

                    </table>
    <?php
    if ($product_data[0]['product_type'] == 'variation') {
        ?>
        <?php
        if (is_array($variation_data)) {
            ?>
                            <h3><b><?php echo translate('Product Variation'); ?></b></h3>
                            <table id="demo-table" class="table table-striped" data-pagination="true"
                                   data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true"
                                   data-show-columns="false" data-search="true">

                                <thead>
                                    <tr>
                                        <th><?php echo translate('SKU'); ?></th>
                                        <th><?php echo translate('Title'); ?></th>
                                        <th><?php echo translate('Supplier Sale Price'); ?></th>
                                        <th><?php echo translate('Sale Price'); ?></th>
                                        <th><?php echo translate('is_default?'); ?></th>
                                        <th><?php echo translate('Status'); ?></th>
                                        <th><?php echo translate('Attribute & Value'); ?></th>
                                    </tr>
                                </thead>

                                <tbody>

            <?php
            $i = 0;
            foreach ($variation_data as $row) {
                $i ++;
                ?>
                                        <tr>

                                            <td><?php echo $row['sku_code']; ?></td>
                                            <td><?php echo $row['title']; ?></td>
                                            <td><?php echo DEF_CURR .$row['supplier_price']; ?></td>
                                            <td><?php echo DEF_CURR .$row['sale_price']; ?></td>
                                            <td><?php echo translate($row['is_default']); ?></td>

                                            <td>
                <?php
                if ($row['status'] == 'Active') {
                    echo '<span class="label label-success">' . translate('Active') . '</span>';
                } else {
                    echo '<span class="label label-danger">' . translate('Disabled') . '</span>';
                }
                ?>

                                            </td>

                                            <td>
                                                <?php
                                                $query = $this->db->query('Select a.attribute_name,av.value
                                                                    From attribute_mapping am
                                                                    Left Join attribute a ON (am.attribute_id = a.attribute_id)
                                                                    Left Join attributevalue av ON (am.attributevalue_id = av.attributevalue_id)
                                                                    Where am.variation_id = ' . $this->db->escape($row['variation_id']) . '
                                                                    ');
                                                $res = $query->result_array();
                                                if (is_array($res) && count($res) > 0) {
                                                    foreach ($res as $keya => $vala) {
                                                        echo $vala['attribute_name'] . ' : ' . $vala['value'] . '<br>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                <?php
                            }
                            ?>
                                </tbody>
                            </table>


            <?php
        }
    }
    ?>
                </div>
                <hr>
            </div>
        </div>
    </div>

    <?php
}
?>

<style>
    .custom_td {
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
    }
</style>

<script>
    $(document).ready(function (e) {
        proceed('to_list');
    });
</script>
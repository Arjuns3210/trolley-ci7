<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<!--<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-table/bootstrap-table.js"></script>-->
<?php

    $product_edit_permission = $this->crud_model->admin_permission('product_edit');
    $product_variation_permission = $this->crud_model->admin_permission('product_variation');
    $product_view_permission = $this->crud_model->admin_permission('product_view');
    $stock_add_permission = $this->crud_model->admin_permission('stock_add');
    $stock_remove_permission = $this->crud_model->admin_permission('stock_edit');
    ?>

<div class="panel-body" id="demo_s">
    <table id="events-table" class="table table-striped"  data-url="<?php echo base_url(); ?>admin/product/list_data" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"  data-show-refresh="true" data-search="true"  data-show-export="true" >
        <thead>
            <tr>

                <th data-field="image" data-align="center" data-sortable="false">
                    <?php echo translate('image');?>
                </th>
                <th data-field="title" data-align="left" data-sortable="true">
                    <?php echo translate('title');?>
                </th>
                <th data-field="product_code"  data-sortable="true">
                    <?php echo translate('product_code');?>
                </th>
                <?php /*
                <th data-field="current_stock" data-sortable="true">
                    <?php echo translate('current_quantity');?>
                </th>
                 */ ?>
                <?php if($product_edit_permission){?>
                
                <th data-field="publish" data-sortable="false">
                    <?php echo translate('publish');?>
                </th>
                <?php /*
                <th data-field="deal" data-sortable="false">
                    <?php echo translate("today's_deal");?>
                </th>
                */ ?>
                <th data-field="featured" data-sortable="false">
                    <?php echo translate('is_new_product');?>
                </th>
              
                    <th data-field="price" data-align="center" data-sortable="false">
		                <?php echo translate('price');?>
                    </th>
                    <th data-field="id" data-align="center" data-sortable="true">
		                <?php echo translate('id');?>
                    </th>
                    <?php /* 
                    <th data-field="live_from" data-align="center" data-sortable="false">
		                Live from date
                    </th>
                    <th data-field="live_to" data-align="center" data-sortable="false">
		                Expiry Date Time
                    </th>
                     */ ?>
                <?php } ?>
                <?php if($product_edit_permission || $product_variation_permission || $product_view_permission || $stock_add_permission || $stock_remove_permission){ ?>   
                <th data-field="options" data-sortable="false" data-align="right">
                    <?php echo translate('options');?>
                </th>
                <?php } ?>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    // var LAST_KNOWN_PAGE = 0;
    $(document).ready(function(){
        $('#events-table').bootstrapTable({
            /*
            onAll: function (name, args) {
                console.log('Event: onAll, data: ', args);
            }
            onClickRow: function (row) {
                $result.text('Event: onClickRow, data: ' + JSON.stringify(row));
            },
            onDblClickRow: function (row) {
                $result.text('Event: onDblClickRow, data: ' + JSON.stringify(row));
            },
            onSort: function (name, order) {
                $result.text('Event: onSort, data: ' + name + ', ' + order);
            },
            onCheck: function (row) {
                $result.text('Event: onCheck, data: ' + JSON.stringify(row));
            },
            onUncheck: function (row) {
                $result.text('Event: onUncheck, data: ' + JSON.stringify(row));
            },
            onCheckAll: function () {
                $result.text('Event: onCheckAll');
            },
            onUncheckAll: function () {
                $result.text('Event: onUncheckAll');
            },
            onLoadSuccess: function (data) {
                $result.text('Event: onLoadSuccess, data: ' + data);
            },
            onLoadError: function (status) {
                $result.text('Event: onLoadError, data: ' + status);
            },
            onColumnSwitch: function (field, checked) {
                $result.text('Event: onSort, data: ' + field + ', ' + checked);
            },
            onPageChange: function (number, size) {
                $result.text('Event: onPageChange, data: ' + number + ', ' + size);
            },
            onSearch: function (text) {
                $result.text('Event: onSearch, data: ' + text);
            }
            */
        }).on('all.bs.table', function (e, name, args) {
            //alert('1');
            //set_switchery();
        }).on('click-row.bs.table', function (e, row, $element) {
            
        }).on('dbl-click-row.bs.table', function (e, row, $element) {
            
        }).on('sort.bs.table', function (e, name, order) {
            
        }).on('check.bs.table', function (e, row) {
            
        }).on('uncheck.bs.table', function (e, row) {
            
        }).on('check-all.bs.table', function (e) {
            
        }).on('uncheck-all.bs.table', function (e) {
            
        }).on('load-success.bs.table', function (e, data) {
            set_switchery();

        }).on('load-error.bs.table', function (e, status) {
            
        }).on('column-switch.bs.table', function (e, field, checked) {
            
        }).on('page-change.bs.table', function (e, size, number) {
            //alert('1');
            // $('#events-table').attr('data-page-number',number);
            LAST_KNOWN_PAGE=number;
            // console.log(LAST_KNOWN_PAGE);
            // set_switchery();
        }).on('search.bs.table', function (e, text) {
            
        });
    });
$('.page-header').html('Manage Product');

</script>


    <div class="panel-body" id="demo_s">
    <?php 
    $sale_invoice_view = $this->crud_model->admin_permission('sale_invoice');
    $Sales_Order_Status_Update = $this->crud_model->admin_permission('Sales_Order_Status_Update');
    ?>
    <div class="row" hidden>
        <div class="col-sm-3 form-group">
            <label for="sale_id"><?php echo translate('order_id');?></label>
            <input type="text" name="sale_id" class="form-control" id="sale_id">
        </div>
        <div class="col-sm-3 form-group">
            <label for="customer_name"><?php echo translate('customer_name');?></label>
            <input type="text" name="customer_name" class="form-control" id="customer_name">
        </div>
        <div class="col-sm-3 form-group">
            <label for="delivery_status"><?php echo translate('delivery_status');?></label>
            <select class="form-control" name="delivery_status" id="delivery_status">
                <option><?php echo translate('select');?></option>
                <option><?php echo translate('pending');?></option>
                <option><?php echo translate('delivered');?></option>
                <option><?php echo translate('cancelled');?></option>
            </select>
        </div>
        <div class="col-sm-3 form-group">
            <label for="payment_status"><?php echo translate('payment_status');?></label>
            <select class="form-control" name="payment_status" id="payment_status">
                <option><?php echo translate('select');?></option>
                <option><?php echo translate('cancelled');?></option>
                <option><?php echo translate('paid');?></option>
            </select>
        </div>
    </div>
    <table id="events-table" class="table table-striped"  data-url="<?php echo base_url(); ?>index.php/admin/sales/list_data" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"  data-show-refresh="true" data-search="true"  data-show-export="true" >
        <thead>
        <tr>
                <th data-field="sale_id" data-align="right" data-sortable="true">
                    <?php echo translate('id');?>
                </th>
                <th data-field="sale_code" data-align="right" data-sortable="false">
                    <?php echo translate('order_code');?>
                </th>
                <th data-field="shipping_address" data-align="center" data-sortable="true">
                    <?php echo translate('customer');?>
                </th>
                
                <th data-field="sale_datetime"  data-sortable="true">
                    <?php echo translate('date');?>
                </th>
                <th data-field="invoice_amount" data-align="right"  data-sortable="true">
                    <?php echo translate('invoice_amount');?>
                </th>
                <th data-field="grand_total" data-align="right"  data-sortable="true">
                    <?php echo translate('Total');?>
                </th>
                <th data-field="delivery_status"  data-sortable="true">
                    <?php echo translate('Delivery Status');?>
                </th>
                <th data-field="payment_status"  data-sortable="true">
                    <?php echo translate('payment_status');?>
                </th>
                <th data-field="delivery_date"  data-sortable="true">
                    <?php echo translate('delivery_date');?>
                </th>
                <?php if($sale_invoice_view || $Sales_Order_Status_Update ){ ?>
                <th data-field="options" data-sortable="false" data-align="right">
                    <?php echo translate('options');?>
                </th>
                <?php } ?>
        </tr>
        </thead>
    </table>
                        </div>

<script type="text/javascript">
    // $('#sale_id').on('keyup',function(){
    //     var sale_id = $(this).val();

    //     console.log(sale_id);
    //     // var bootstrapTable = $('#events-table').data('bootstrap.table');
    //     // var dataSearchValue = bootstrapTable.searchText;
    //     // console.log(dataSearchValue);

    //     $.ajax({
    //         url: '<?php echo base_url(); ?>index.php/admin/sales/list_data',
    //         type: 'get',
    //         data : {sale_id:sale_id},
    //         success: function (response) {
    //             console.log(response);
    //         }

    //     })
    // })
    // var LAST_KNOWN_PAGE = 0;
    $(document).ready(function(){
        $('#events-table').bootstrapTable({

        }).on('all.bs.table', function (e, name, args) {
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
            LAST_KNOWN_PAGE=number;
            // console.log(LAST_KNOWN_PAGE);
            // set_switchery();
        }).on('search.bs.table', function (e, text) {

                });
                });
$('.page-header').html('Manage Orders');

</script>




<div class="panel-body" id="demo_s">
    <?php 
    $sale_invoice_view = $this->crud_model->admin_permission('sale_invoice');
    ?>
    <table id="events-table" class="table table-striped"  data-url="<?php echo base_url(); ?>index.php/admin/storeSales/list_data" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"  data-show-refresh="true" data-search="true"  data-show-export="true" >
        <thead>
        <tr>
                <th data-field="sale_id" data-align="left" data-sortable="false">
                    <?php echo translate('id');?>
                </th>
                <th data-field="sale_code" data-align="left" data-sortable="false">
                    <?php echo translate('sale_code');?>
                </th>
                
                <th data-field="sale_datetime"  data-sortable="false">
                    <?php echo translate('date');?>
                </th>
          
                <?php if($sale_invoice_view ){ ?>
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
$('.page-header').html('Manage Sale');

</script>




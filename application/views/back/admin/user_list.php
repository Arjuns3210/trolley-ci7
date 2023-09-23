<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<?php 
 $user_view = $this->crud_model->admin_permission('user_view');
 /*
 $user_add_wallet_balance = $this->crud_model->admin_permission('user_add_wallet_balance');
 $user_reduce_wallet_balance = $this->crud_model->admin_permission('user_reduce_wallet_balance');
 $user_wallet_type = $this->crud_model->admin_permission('user_wallet_type');
 */
 $user_add_wallet_balance = false;
 $user_reduce_wallet_balance = false;
 $user_wallet_type = false;
?>
<div class="panel-body" id="demo_s">
    <table id="events-table" class="table table-striped"  data-url="<?php echo base_url(); ?>index.php/admin/user/list_data" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"  data-show-refresh="true" data-search="true"  data-show-export="true" >
        <thead>
            <tr>
                 <th data-field="no" data-sortable="true">
                    <?php echo translate('ID');?>
                </th>
                <th data-field="phone" data-sortable="false">
                    <?php echo translate('phone');?>
                </th>
                <th data-field="full_name" data-align="center" data-sortable="true">
                    <?php echo translate('Name');?>
                </th>
                <?php /*
               <th data-field="first_name" data-align="center" data-sortable="true">
                    <?php echo translate('firstname');?>
                </th>
                <th data-field="second_name" data-align="center" data-sortable="true">
                    <?php echo translate('secondname');?>
                </th>
                <th data-field="third_name" data-align="center" data-sortable="true">
                    <?php echo translate('thirdname');?>
                </th>
                <th data-field="fourth_name" data-align="center" data-sortable="true">
                    <?php echo translate('fourthname');?>
                </th>
                */ ?>
                <th data-field="email" data-sortable="false">
                    <?php echo translate("email");?>
                </th>
                <?php /*
                <th data-field="langlat" data-sortable="false">
                    <?php echo translate("address_location");?>
                </th>
                */ ?>
                <th data-field="status" data-sortable="false">
                    <?php echo translate('status');?>
                </th>
                <?php if($user_view || $user_add_wallet_balance || $user_reduce_wallet_balance || $user_wallet_type) { ?>
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
//$('.page-header').html('Manage Product');

</script>


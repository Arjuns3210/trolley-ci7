<!--<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>-->

<div class="panel-body" id="demo_s">
    <table id="events-table" class="table table-striped"  data-url="<?php echo base_url(); ?>index.php/admin/contact_us/list_data" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"  data-show-refresh="true" data-search="true"  data-show-export="true" >
        <thead>
            <tr>
                <th data-field="name" data-align="center" data-sortable="false">
                    <?php echo translate('Name');?>
                </th>
                <th data-field="email" data-align="center" data-sortable="false">
                    <?php echo translate('Email');?>
                </th>
                <th data-field="mobile" data-align="center" data-sortable="false">
                    <?php echo translate('Mobile');?>
                </th>
                <th data-field="msg" data-align="center" data-sortable="false">
                    <?php echo translate('Message');?>
                </th>
                <th data-field="date" data-align="center" data-sortable="false">
                    <?php echo translate('added_on');?>
                </th>
                <th data-field="options" data-align="center" data-sortable="false">
                    <?php echo translate("options");?>
                </th>
                
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

</script>
 





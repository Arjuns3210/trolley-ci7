<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<?php
 $enquiry_view = $this->crud_model->admin_permission('enquiry_view');
 $enquiry_delete = $this->crud_model->admin_permission('enquiry_delete');
?>
<div class="panel-body" id="demo_s">
    <table id="events-table" class="table table-striped"  data-url="<?php echo base_url(); ?>index.php/admin/enquiries/list_data" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"  data-show-refresh="true" data-search="true"  data-show-export="true" >
        <thead>
            <tr>
                <th data-field="no" data-align="right" data-sortable="false">
                    <?php echo translate('no');?>
                </th>
                <th data-field="from" data-align="left" data-sortable="false">
                    <?php echo translate('from');?>
                </th>
                <th data-field="name" data-sortable="false">
                    <?php echo translate('name');?>
                </th>
                <th data-field="mobile" data-sortable="false">
                    <?php echo translate('mobile');?>
                </th>
                <th data-field="email" data-sortable="false">
                    <?php echo translate('email');?>
                </th>
                <th data-field="subject" data-sortable="false">
                    <?php echo translate('subject');?>
                </th>
                <th data-field="date" data-sortable="false">
                    <?php echo translate("date");?>
                </th>
                <th data-field="status" data-sortable="false">
                    <?php echo translate("status");?>
                </th>
                <?php  if($enquiry_view || $enquiry_delete) { ?>
                <th data-field="options" data-sortable="false" data-align="right">
                    <?php echo translate('options');?>
                </th>
                <?php } ?>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
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
            //set_switchery();
        }).on('search.bs.table', function (e, text) {
            
        });
    });

</script>


<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/sales/cancel_order/' . $sale_id, array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'cancel_order',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="demo-hor-2"><?php echo translate('comment'); ?></label>
                <div class="col-sm-7">
                    <textarea class="form-control textarea required" rows="5" name="comment"></textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    });
	
    $(document).ready(function() {
        $("form").submit(function(e){
                event.preventDefault();
        });
    });
  
</script>
<div id="reserve"></div>


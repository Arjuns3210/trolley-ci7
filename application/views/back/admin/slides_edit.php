<?php
	foreach($slides_data as $row){
?>
    <div>
        <?php
			echo form_open(base_url() . 'index.php/admin/slides/update/' . $row['slides_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'slides_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('banners_logo');?></label>
                    <div class="col-sm-6">
                        <span class="pull-left btn btn-default btn-file">
                            <?php echo translate('select_slide_banner');?>
                            <input type="file" name="img" id='imgInp' accept="image">
                        </span>
                        <br><br>
                        <p style="color:blue;font-weight: bold">Note : Upload file size <?php echo SIZE_DIMENSIONS['slides']; ?></p>
                        <span id='wrap' class="pull-left" >
                        <?php
                            if(!empty($row['button_link'])){
                        ?>
                        <img src="<?php echo base_url(); ?>uploads/slides_image/<?php echo $row['button_link']; ?>" width="100%" id='blah' />  
                        <?php
                            } else {
                        ?>
                        <img src="<?php echo base_url(); ?>uploads/slides_image/default.jpg" width="100%" id='blah' />
                        <?php
                            }
                        ?> 
                    </span>
                        
                    </div>
                </div>
                <?php // added by sagar : START 22-10 banner based on app language ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label"
                           for="demo-hor-115"><?php echo translate( 'show_for_language' ); ?></label>
                    <div class="col-sm-6">
                        <select class="demo-chosen-select" name="slides_lang" id="slides_lang"  >
                                    <?php 
                                    $from = array('en'=>'English','ar'=>'Arabic' );
                                    foreach($from as $key => $val){
                                        $sel = "";
                                        if($key == $row['slides_lang']){
                                            $sel = 'Selected';
                                        }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $sel; ?> ><?php echo $val; ?></option>
                                    <?php } ?>
                        </select>
                    </div>
                </div>
                <?php // added by sagar : END 22-10 banner based on app language ?>
                
                <?php /*
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo translate('button_color:');?></label>
                    <div class="col-sm-6">
                        <div class="input-group demo2">
                           <input type="text" value="<?php echo $row['button_color'];?>" name="color_button" class="form-control" />
                           <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo translate('button_text_color:');?></label>
                    <div class="col-sm-6">
                        <div class="input-group demo2">
                           <input type="text" value="<?php echo $row['text_color'];?>" name="color_text" class="form-control" />
                           <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('button_text');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="button_text" id="demo-hor-3" value="<?php echo $row['button_text'];?>"
                            placeholder="<?php echo translate('button_text'); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-4"><?php echo translate('button_link');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="button_link" id="demo-hor-4" value="<?php echo $row['button_link'];?>"
                            placeholder="<?php echo translate('button_link'); ?>" class="form-control">
                    </div>
                </div>
                */ ?>
            </div>
        </form>
    </div>

<?php
	}
?>

<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>
<script>
$(document).ready(function() {
	createColorpickers();
});

function createColorpickers(){

	$('.demo2').colorpicker({
		format: 'rgba'
	});
	
}
</script>


<div>
    <?php
    
    
		echo form_open(base_url() . 'index.php/admin/attribute/do_valueedit/'.$attributevalue_data[0]['attributevalue_id'], array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'attributevalue_edit',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <input type="hidden" value="<?php echo $attribute_data[0]['attribute_id']; ?>" name="attribute_id">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('attribute_value');?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="attribute_value" id="demo-hor-1"  value="<?php echo $attributevalue_data[0]['value']; ?>"
                    	class="form-control required" placeholder="<?php echo translate('attribute_value');?>" >
                </div>
            </div>
            <?php if($attribute_data[0]['is_color'] == 'ok'){ ?>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('Select_color');?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group demo2">
                        <input type="text" name="color" value="<?php echo $attributevalue_data[0]['rgb']; ?>" class="form-control required" />
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
                
            </div>
            <?php } ?>
            
        </div>
	</form>
</div>

<script>
	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
                createColorpickers();
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
	
			reader.onload = function(e) {
				$('#wrap').hide('fast');
				$('#blah').attr('src', e.target.result);
				$('#wrap').show('fast');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imgInp").change(function() {
		readURL(this);
	});
        function createColorpickers() {
	
		$('.demo2').colorpicker({
			format: 'rgba'
		});
		
	}
</script>
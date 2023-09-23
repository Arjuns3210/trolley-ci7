<?php
	foreach($attribute_data as $row){
?>
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/attribute/update/' . $row['attribute_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'attribute_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('attribute_name');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="attribute_name"  
                        	value="<?php echo $row['attribute_name'];?>" id="demo-hor-1" 
                            	class="form-control required" placeholder="<?php echo translate('attribute_name');?>" >
					</div>
				</div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="demo-hor-1">
                                            <?php echo translate('is_color?');?>
                                    </label>
                                    <div class="col-sm-6">
                                        <select name="is_color" id="is_color" 
                                            class="form-control required" >
                                            <option value="no" <?php if($row['is_color'] == 'no'){ echo 'selected'; } ?>>No</option>
                                            <option value="yes" <?php if($row['is_color'] == 'ok'){ echo 'selected'; } ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                
			</div>
		</form>
	</div>
<?php
	}
?>

<script>
	$(document).ready(function() {
	    $("form").submit(function(e) {
	        return false;
	    });
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
	
</script>
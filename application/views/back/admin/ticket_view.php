<?php 
    foreach($message_data as $row)
    { 
?>
<div style="padding-top: 30px;">
    <h4 class="modal-title text-center padd-all">
    	<?php echo translate('Enquiry_from');?> 
		<?php 
            $from = json_decode($row['from_where'],true);
            if($from['type'] == 'user'){
        ?>
        <a class="btn btn-mint btn-xs btn-labeled fa fa-location-arrow" data-toggle="tooltip" 
        onclick="ajax_modal('view_user','<?php echo translate('view_profile'); ?>','<?php echo translate('successfully_viewed!'); ?>','user_view','<?php echo $from['id']; ?>')" data-original-title="View" data-container="body">
            <?php echo $this->db->get_where('user',array('user_id'=>$from['id']))->row()->first_name; ?>
        </a>
        <?php	
            } else {
        ?>
            <?php echo translate('admin');?> 
        <?php
            }
        ?>
        
    </h4>
</div>
    
    <hr style="margin-top: 10px !important;">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center pad-all">
                <div class="col-md-12"> 
                    <table class="table table-striped" style="border-radius:3px;">
                        
                        <tr>
                            <th class="custom_td"><?php echo translate('subject');?></th>
                            <td class="custom_td">
                                <?php echo $row['subject']?>
                            </td>
                        </tr>
                        
                        <tr>
                            <th class="custom_td"><?php echo translate('date_&_time');?></th>
                            <td class="custom_td">
                                <?php echo date('d M,Y h:i:s',$row['time']); ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr>
            </div>
            <script>
				$('.overer').click(function(){
					var now = $(this);
					if(now.hasClass('collapsed')){
						now.css('background-color','#fff');
						now.css('border-color','#fff');
						now.find('.reth').css('color','#fff');
					} else {
						now.css('background-color','#F5F5F5');
						now.css('border-color','#D8D8D8');
						now.find('.reth').css('color','black');
					}
				});
			</script>
			<?php
            
            $msgs=$this->db->get_where('ticket_message',array(' ticket_id'=>$row['ticket_id']))->result_array();
            foreach ($msgs as $row1){
				$from1 = json_decode($row1['from_where'],true);
            ?>
              <div class="col-md-12" >
                  <div class="col-md-12 btn btn-md btn-default overer" data-toggle="collapse" data-target="#demo<?php echo $row1['ticket_message_id']; ?>" style="cursor:pointer; background-color: #F5F5F5; border-color: #D8D8D8; margin-top: 5px;">
                  	<div class="col-md-1 text-left">
                        <div class="row" style="padding:5px;">
                            <?php
                                if($from1['type'] == 'admin'){
                            ?>
                                <img src="<?php echo $this->crud_model->logo('admin_login_logo'); ?>" class="img-sm img-border" />
                            <?php
                                } else {
                            ?>
                            <img   src="<?php echo base_url(); ?>uploads/user_image/default.jpg"  class="img-sm img-border" alt="user logo">
                          
                            <?php
                                }
                            ?>
                        </div>
                     </div>
                  	 <div class="col-md-9 text-left">
                        <div class="row">
                            <b><i>
							<?php
                                if($from1['type'] == 'admin'){
                                    echo translate('admin');
                                } else if($from1['type'] == 'user'){
                                    echo 'user';
                                }
                                ?>
                            </i></b>
                            <?php if($row1['num_of_files'] > 0) { ?>
                                <i class="fa fa-paperclip" aria-hidden="true"></i>
                            <?php } ?>
                         </div>
                        <div class="row reth" style="padding:5px;">
							<?php echo limit_chars($row1['message'],160); ?>
                         </div>
                      </div>
                  	 <div class="col-md-2 text-left">
                        <div class="row">
                            <b>
                            <?php echo date('d F, Y h:i:s A',$row1['time']); ?>
                            </b>
                         </div>
                      </div>
                  </div>
                  
                  <div id="demo<?php echo $row1['ticket_message_id']; ?>" class="collapse" style="text-align:justify; border-bottom:1px solid #D8D8D8">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-10" style="padding:20px; background:white; font-size:12px;">
                        <?php echo $row1['message']; ?> 
                    </div>
                    <div>
                        <?php
                            $image_sources = array();
                            if ($row1['num_of_files'] > 0) {
                                $count = $row1['num_of_files'];
                                for ($x = 0; $x < $count; $x++) {
                                    $image_files = glob('uploads/enquiries_docs/enquiries_' . $row1['ticket_message_id'] . '_' . $x . '.*');

                                    if (!empty($image_files)) {
                                        $image_path = $image_files[0];
                                        $image_extension = pathinfo($image_path, PATHINFO_EXTENSION);
                                        $image_sources[] = base_url() . $image_path . '?d=' . refreshedImage();
                                    }
                                }
                            }
                             foreach ($image_sources as $val) { 
                                $file_name_with_extension = basename(parse_url($val, PHP_URL_PATH));

                                $file_extension = pathinfo($file_name_with_extension, PATHINFO_EXTENSION);
                                ?>
                             
                                <div class="row col-md-12">
                                    <div class="col-md-1 mt-1">
                                    </div>
                                    <div class="col-md-3 mt-1">
                                        <input type="text" class="form-control" placeholder="File Name" value="<?=$file_name_with_extension?>">
                                    </div>
                                    <div class="col-md-3 mt-1">
                                        <a href="<?=$val?>" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                    </div>
                                </div>
                            <?php } ?>
                    </div>
                  </div>
              </div>
            <?php
                }
            ?>
        </div>
    </div>
    <div>
        <?php
                $enquiry_reply = $this->crud_model->admin_permission('enquiry_reply'); 
			echo form_open(base_url() . 'index.php/admin/enquiries/reply/'.$row['ticket_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'ticket_reply',
				'enctype' => 'multipart/form-data'
			));
		?>
            <div class="panel-body">
                 <?php   if($enquiry_reply) { ?>
                <div class="form-group">
                <div class="col-sm-12" for="demo-hor-1">
                	<?php echo translate('reply_message');?>
                </div>
                <div class="col-sm-12">
                   <textarea  class="col-md-12 required" rows="9" cols="120" data-height="200" name="reply" style="max-width:100%"></textarea>
                </div>
                <?php
                /*
                    // uncomment below code to unlock files upload by admin side -- start
                    <div class="form-group btm_border">
                        <label class="col-sm-1 control-label" for="demo-hor-12"><?php echo translate('Upload'); ?></label>
                        <div class="col-sm-6">
                            <span class="pull-left btn btn-default btn-file"> <?php echo translate('choose_file'); ?>
                                <input type="file" multiple name="images[]" onchange="preview(this);" id="demo-hor-12" class="form-control">
                            </span>
                            <br><br>
                            <span id="previewImg"></span>
                        </div>
                    </div>
                    // uncomment above code to unlock files upload by admin side -- end
                */
                ?>

                 <?php } ?>
            </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    
                    <div class="col-md-6 col-md-offset-6">
                       <?php   if($enquiry_reply) { ?>
                        <span class="btn btn-success btn-md btn-labeled fa fa-reply pull-right" 
                            onclick="form_submit('ticket_reply','<?php echo translate('successfully_replied!'); ?>')" >
                                <?php echo translate('reply');?>
                        </span>
                        <?php } ?>
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right" 
                            onclick="ajax_set_full('view','<?php echo translate('view_ticket'); ?>','<?php echo translate('successfully_viewed!'); ?>','ticket_view','<?php echo $row['ticket_id']; ?>');">
                                <?php echo translate('refresh');?>
                        </span>
                    </div>
                </div>
            </div>
		</form>
	</div>    
<?php 
	}
?>        
<style>
	.custom_td{
		border-left: 1px solid #ddd;
		border-right: 1px solid #ddd;
		border-bottom: 1px solid #ddd;
	}
        
        
    .overer{
        display: inline-block;
        white-space: nowrap;
    }
    @media only screen and (max-width: 600px) {
         .overer{
            display: block !important;
            white-space: unset !important;
        }
    }
</style>

<script>
	
    $(document).ready(function(e) {
        proceed('to_list');
    });
    window.preview = function (input) {
        if (input.files && input.files[0]) {
            $("#previewImg").html('');
            $(input.files).each(function () {
                var reader = new FileReader();
                reader.readAsDataURL(this);
                reader.onload = function (e) {
                    $("#previewImg").append("<div style='float:left;border:4px solid #303641;padding:5px;margin:5px;'><img height='80' src='" + e.target.result + "'></div>");
                }
            });
        }
    }
	$("form").submit(function(e){
			return false;
		});
</script>
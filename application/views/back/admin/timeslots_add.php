<div class="row">
    <div class="col-md-12">
		<?php
                
		echo form_open( base_url() . 'index.php/admin/timeslots/do_add/', array(
			'class'   => 'form-horizontal',
			'method'  => 'post',
			'id'      => 'timeslots_add',
			'enctype' => 'multipart/form-data'
		) );
		?>
        <!--Panel heading-->
        <div class="panel-body">
            
            <div class="form-group btm_border">
                    <h4 class="text-thin text-center"><?php echo translate('Add TimeSlots'); ?></h4>
            </div>
            <div class="form-group btm_border">
                <label class="col-sm-4 control-label" > <?php echo translate('Days'); ?></label>
                <div class="col-sm-1">
                    <span class="">Monday</span>
                    <input type="checkbox"  id="demo_mon"  value="monday" name="days[]">
                </div>
                <div class="col-sm-1">
                    <span class="">Tuesday</span>
                    <input type="checkbox"  id="demo_tue"  value="tuesday" name="days[]">
                </div>
                <div class="col-sm-1">
                    <span class="">Wednesday</span>
                    <input type="checkbox"  id="demo_wed"  value="wednesday" name="days[]" >
                </div>
                <div class="col-sm-1">
                    <span class="">Thursday</span>
                    <input type="checkbox"  id="demo_thus"  value="thursday" name="days[]" >
                </div>
                <div class="col-sm-1">
                    <span class="">Friday</span><br>
                    <input type="checkbox"  id="demo_fri"  value="friday" name="days[]" >
                </div>
                <div class="col-sm-1">
                    <span class="">Saturday</span>
                    <input type="checkbox"  id="demo_sat"  value="saturday" name="days[]" >
                </div>
                <div class="col-sm-1">
                    <span class="">Sunday</span><br>
                    <input type="checkbox"  id="demo"  value="sunday" name="days[]" >
                </div>
            </div>
            <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="demo-hor-14"><?php echo translate('Timeslots'); ?></label>
                    
                    <div class="col-sm-6"  id="more_times">
                      <div class="col-md-12" style="margin-bottom:8px;">
                              <div class="col-md-3">
                                      <div class="input-group demo2" style="margin-bottom:8px;">
                                            <span class="">Start Time</span>
                                            <input type="time" value="" name="start_time[]" size="35" placeholder="start_time" class="form-control" required />
                                     </div>
                              </div>
                              <div class="col-md-3">
                                      <div class="input-group demo2" style="margin-bottom:8px;">
                                            <span class="">End Time</span>
                                            <input type="time" value="" name="end_time[]" size="35" placeholder="end_time" class="form-control" required />
                                     </div>
                              </div>
                              <div class="col-md-3">
                                      <div class="input-group demo2" style="margin-bottom:8px;">
                                            <span class="">Order limit</span>
                                            <input type="number" value="" name="order_limit[]" size="35" value="0" class="form-control" required />
                                     </div>
                              </div>
                              <span class="col-md-2">
                                      <span class="remove_it_v rmc btn btn-danger btn-icon icon-lg fa fa-trash" ></span>
                              </span>
                      </div>
                    </div>

                    <div class="col-sm-2">
                            <div id="more_time_btn" class="btn btn-primary  fa fa-plus">
                            </div>
                    </div>
            </div>
        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-md-12">
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer"
                          onclick="form_submit('timeslots_add','<?php echo translate( 'Timeslots_has_been_uploaded!' ); ?>');proceed('to_add');"><?php echo translate( 'upload' ); ?></span>
               
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right "
                              onclick="ajax_set_full('add','<?php echo translate( 'add_timeslots' ); ?>','<?php echo translate( 'successfully_added!' ); ?>','timeslots_add',''); "><?php echo translate( 'reset' ); ?>
                        </span>
                </div>
            </div>
        </div>

        </form>
    </div>
</div>
<script>
    $("#more_time_btn").click(function () {

        $("#more_times").append(''
            + '      <div class="col-md-12" style="margin-bottom:8px;">'
            + '          <div class="col-md-3">'
            + '              <div class="input-group demo2" style="margin-bottom:8px;">'
            + '		     	   <span class="">Start Time</span>'
            + '		     	   <input type="time" value="" name="start_time[]" size="35" placeholder="placename" class="form-control" required /> '
            + '		        </div>'
            + '          </div>'
            + '          <div class="col-md-3">'
            + '              <div class="input-group demo2" style="margin-bottom:8px;">'
            + '		     	   <span class="">End Time</span>'
            + '		     	   <input type="time" value="" name="end_time[]" size="35" placeholder="placename" class="form-control" required /> '
            + '		        </div>'
            + '          </div>'
            + '          <div class="col-md-3">'
            + '              <div class="input-group demo2" style="margin-bottom:8px;">'
            + '		     	   <span class="">Order Limit</span>'
            + '		     	   <input type="number" value="" name="order_limit[]" size="35" value="0" class="form-control" required /> '
            + '		        </div>'
            + '          </div>'
            + '          <span class="col-md-2">'
            + '              <span class="remove_it_v rmc btn btn-danger btn-icon icon-lg fa fa-trash" ></span>'
            + '          </span>'
            + '      </div>'
        );
    });
    
    function delete_row(e)
    {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    } 
    $('body').on('click', '.rmc', function () {
        $(this).parent().parent().remove();
    });


    $(document).ready(function () {
        $("form").submit(function (e) {
            event.preventDefault();
        });
       
    });
</script>

<style>
    .btm_border {
        border-bottom: 1px solid #ebebeb;
        padding-bottom: 15px;
    }
</style>



<!--Bootstrap Tags Input [ OPTIONAL ]-->


<!-- BEGIN: main -->
<!-- BEGIN: view -->
<div class="well">
<form action="{NV_BASE_SITEURL}index.php" method="get">
    <input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
    <input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
    <div class="row">
        <div class="col-xs-24 col-md-6">
            <div class="form-group">
                <input class="form-control" type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.search_title}" />
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
            </div>
        </div>
    </div>
</form>
</div>
<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    
                    <th>{LANG.title}</th>
                    <th>{LANG.frame_image}</th>
                    <th class="w100 text-center">{LANG.active}</th>
                    <th class="w150">&nbsp;</th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="6">{NV_GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    
                    <td> {VIEW.title} </td>
                    <td> <img src="{VIEW.frame_image}" width="70px" height="70px" /> </td>
                    <td class="text-center"><input type="checkbox" name="status" id="change_status_{VIEW.fid}" value="{VIEW.fid}" {CHECK} onclick="nv_change_status({VIEW.fid});" /></td>
                    <td class="text-center"><!-- BEGIN: action --><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{ULINK.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{ULINK.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a><!-- END: action --></td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-body">
<form id="frame" class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="fid" value="{ROW.fid}" />
    <input type="hidden" name="userid" value="{ROW.userid}" />
    <div class="form-group">
        <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.title}</strong> <span class="red">(*)</span></label>
        <div class="col-sm-19 col-md-20">
            <input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity(nv_required)" oninput="setCustomValidity('')" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.frame_image}</strong> <span class="red">(*)</span></label>
        <div class="col-sm-19 col-md-20">
            <div class="input-group">
          <span class="input-group-btn">
				<input type="text" name="imgdata" id="imgdata" value="" >
                <input type="text" name="frame_image" id="frame_image" >
                <input type="file" name="frame_file" id="file-input">
            </span>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.groups_view}</strong></label>
        <div class="col-sm-19 col-md-20">
            <input class="form-control" type="checkbox" name="groups_view" value="1" /> {ROW.groups_view}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.status}</strong></label>
        <div class="col-sm-19 col-md-20">

                <label><input class="form-control" type="checkbox" name="status" value="{OPTION.key}" {OPTION.checked}>{OPTION.title} </label> 
        </div>
    </div>
    <div class="form-group" style="text-align: center"></div>
	<input type="submit" name="submit" id="framesubmit" style="display:none" value="Save">
</form>
<button class="btn btn-primary" id="save-frame">{LANG.save}</button>
</div></div>

<script type="text/javascript">
//<![CDATA[
    

    


    function nv_change_status(id) {
        var new_status = $('#change_status_' + id).is(':checked') ? true : false;
        if (confirm(nv_is_change_act_confirm[0])) {
            var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=myframe&nocache=' + new Date().getTime(), 'change_status=1&fid='+id, function(res) {
                var r_split = res.split('_');
                if (r_split[0] != 'OK') {
                    alert(nv_is_change_act_confirm[2]);
                }
            });
        }
        else{
            $('#change_status_' + id).prop('checked', new_status ? false : true);
        }
        return;
    }


//]]>
</script>
<!-- END: main -->
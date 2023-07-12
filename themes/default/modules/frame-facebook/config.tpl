<!-- BEGIN: main -->


<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-body">
<form id="config" class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="savesetting" value="1" />
   Cấu hình frame mặc định : <br>
    <div class="form-group">
        <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.frame_image_0}</strong> <span class="red">(*)</span></label>
        <div class="col-sm-19 col-md-20">
            <div class="input-group">
          <span class="input-group-btn">
				<input type="text" name="imgdata0" id="imgdata0" value="" style="display:none">
                <input type="text" name="frame_image0" id="frame_image0" value="{IMAGE_0}">
                <input type="file" name="frame_file0" id="file-input0">
            </span>
        </div>
        </div>
    </div>
   <div class="form-group">
        <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.frame_image_1}</strong> <span class="red">(*)</span></label>
        <div class="col-sm-19 col-md-20">
            <div class="input-group">
          <span class="input-group-btn">
				<input type="text" name="imgdata1" id="imgdata1" value="" style="display:none">
                <input type="text" name="frame_image1" id="frame_image1" value="{IMAGE_1}">
                <input type="file" name="frame_file1" id="file-input1">
            </span>
        </div>
        </div>
    </div>
	<div class="form-group">
        <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.frame_image_2}</strong> <span class="red">(*)</span></label>
        <div class="col-sm-19 col-md-20">
            <div class="input-group">
          <span class="input-group-btn">
				<input type="text" name="imgdata2" id="imgdata2" value="" style="display:none">
                <input type="text" name="frame_image2" id="frame_image2" value="{IMAGE_2}">
                <input type="file" name="frame_file2" id="file-input2">
            </span>
        </div>
        </div>
    </div>
    <div class="form-group" style="text-align: center"></div>
	<input type="submit" name="submit" id="framesubmit" style="display:none" value="Save">
</form>
<button class="btn btn-primary" id="save-frame">{LANG.save}</button>
</div></div>
<!-- END: main -->
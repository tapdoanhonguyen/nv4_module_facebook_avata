<!-- BEGIN: main -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/croppie.css" rel="stylesheet" async="async" />
    <script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/croppie.min.js" async="async"></script>
<div id="wrapper">
      <div id="content">
      <div id="main-header">
<div id="designs">
<!-- BEGIN: view -->


                <!-- BEGIN: loop -->
					<img class="design {VIEW.active}" src="{VIEW.frame_image}" data-design="{VIEW.frame}" data-userid="{USERID}"/>


                <!-- END: loop -->
				<!-- BEGIN: guest -->
					<img class="design " src="{GUEST.frame_image_0}" data-design="{GUEST.frame_0}" data-userid="{USERID}"/>
					<img class="design " src="{GUEST.frame_image_1}" data-design="{VIEW.frame_1}" data-userid="{USERID}"/>
					<img class="design " src="{GUEST.frame_image_2}" data-design="{GUEST.frame_2}" data-userid="{USERID}"/>


                <!-- END: guest -->

<!-- END: view -->
        </div>
        <div id="preview">
          <div id="crop-area">
            <img src="{IMAGEPERSON}" id="profile-pic" />
          </div>
          <img src="{VIEW.frame_image}" id="fg" data-design="{VIEW.frame}" data-userid="{USERID}" />
        </div>
        <div id="designs" style="max-width: 350px;margin: auto;">  
          <div style="/* display: inline-flex; */">
			<div style="float: left;">
			<button id="download" disabled>Download ảnh</button>
			</div>
			<div style="/* float: right; */">
                         <button id="fb-set-pic" disabled>Thay <b>Facebook</b> Avata</button>
		        </div>
		  </div>

            <div>   
            
            Lưu avata từ Facebook/Zalo và Chọn ảnh để chèn vào khung hình<input type="file" name="file" onchange="onFileChange(this)">
</div>
        </div> 
		
		 <div>   
            <!-- BEGIN: share -->
            Share link : <a href="{NV_MY_DOMAIN}{NV_BASE_SITEURL}{MODULE_NAME}/{USER_LINK}"  >{NV_MY_DOMAIN}{NV_BASE_SITEURL}{MODULE_NAME}/{USER_LINK} </a>
			<!-- END: share -->
</div>

</div> 
</div> 
</div> 
<!-- END: main -->
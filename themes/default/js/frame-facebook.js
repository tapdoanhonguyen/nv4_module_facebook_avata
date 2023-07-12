/**
 * @Project NUKEVIET 4.x
 * @Author Tập Đoàn Họ Nguyễn <adminwmt@gmail.com>
 * @Copyright (C) 2023 Tập Đoàn Họ Nguyễn. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 24 Jun 2023 13:16:47 GMT
 */

function dataURItoBlob(dataURI) {
    // convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], {type:mimeString});
}

window.uploadPicture = function(callback){
  croppie.result({
    size: "viewport"
  }).then(function(dataURI){
    var formData = new FormData();
    formData.append("design", $(".factive").data("design"));
    formData.append("image", dataURItoBlob(dataURI));
    formData.append("userid", $(".factive").data("userid"));
	var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    formData.append("screenWidth", screenWidth);
	$.ajax({
      url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&op=upload&nocache=' + new Date().getTime(),
      data: formData,
      type: "POST",
      contentType: false,
      processData: false,
      success: callback,
      error: function(){
        document.getElementById("download").innerHTML = "Download Profile Picture";
      },
      xhr: function() {
        var myXhr = $.ajaxSettings.xhr();
        if(myXhr.upload){
            myXhr.upload.addEventListener('progress', function(e){
              if(e.lengthComputable){
                var max = e.total;
                var current = e.loaded;

                var percentage = Math.round((current * 100)/max);
                document.getElementById("download").innerHTML = "Uploading... Please Wait... " + percentage + "%";
              }
            }, false);
        }
        return myXhr;
      },
    });
  });
}
if (window.innerWidth <= 400) {
		
window.updatePreview = function(url) {
  document.getElementById("crop-area").innerHTML = "";
	
		window.croppie = new Croppie(document.getElementById("crop-area"), {
			"url": url,
			boundary: {
			  height: 350,
			  width: 350
			},
			viewport: {
			  width: 350,
			  height: 350
			},
		  });
	


  

  $(".frame").on('mouseover touchstart', function(){
    document.getElementById("fg").style.zIndex = -1;
  });
  $(".cr-boundary").on('mouseleave touchend', function(){
    document.getElementById("fg").style.zIndex = 10;
  });

  document.getElementById("download").onclick = function(){
    this.innerHTML = "Uploading... Please wait...";
    uploadPicture(function(r){
      document.getElementById("download").innerHTML = "Uploaded";
      window.location = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&op=download&i=' + r + '&nocache=' + new Date().getTime();
    });
  };
  document.getElementById("download").removeAttribute("disabled");
};
}else{
	window.updatePreview = function(url) {
  document.getElementById("crop-area").innerHTML = "";
	

	
		window.croppie = new Croppie(document.getElementById("crop-area"), {
			"url": url,
			boundary: {
			  height: 350,
			  width: 350
			},
			viewport: {
			  width: 350,
			  height: 350
			},
		  });

  

  $(".frame").on('mouseover touchstart', function(){
    document.getElementById("fg").style.zIndex = -1;
  });
  $(".cr-boundary").on('mouseleave touchend', function(){
    document.getElementById("fg").style.zIndex = 10;
  });

  document.getElementById("download").onclick = function(){
    this.innerHTML = "Uploading... Please wait...";
    uploadPicture(function(r){
      document.getElementById("download").innerHTML = "Uploaded";
      window.location = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&op=download&i=' + r + '&nocache=' + new Date().getTime();
    });
  };
  document.getElementById("download").removeAttribute("disabled");
};
	}	
window.onFileChange = function(input){
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      image = new Image();
      image.onload = function() {
		
			var width = this.width;
			var height = this.height;
        if(width >= 400 && height >= 400)
          updatePreview(e.target.result);
        else
          alert("Image should be atleast have 400px width and 400px height");
      };
      image.src = e.target.result;
		
    }

    reader.readAsDataURL(input.files[0]);
  }
}


$(document).ready(function(){
  $(".design").on("click", function(){
    var image = document.getElementById('fg');
    //$(".active").attr("src", $(this).attr("src")).data("design", $(this).data("design"));
    $(".design.factive").removeClass("factive");
    $(this).addClass("factive");
	image.src=$(this).attr("src");
  });
  
  $('#save-frame').click(function(){
            
				$('#framesubmit').click();
				
				
          });
	var fileInput = document.getElementById('file-input');
	if(fileInput != null){
		fileInput.addEventListener("change", function(event) {
			var file = event.target.files[0]; // Lấy tệp tin từ sự kiện onchange

			var reader = new FileReader();

			// Xử lý khi đọc tệp tin hoàn tất
			reader.onload = function(e) {
				var imageData = e.target.result; // Dữ liệu ảnh đã đọc

			 

				$('#imgdata').val('');
				$('#imgdata').val(imageData);
			}
			reader.readAsDataURL(file);
			$('#frame_image').val('');
			$('#frame_image').val(file.name);
			  // Hiển thị thông tin về tệp tin đã chọn (ví dụ)
			  console.log("File Name: " + file.name);
			  console.log("File Size: " + file.size + " bytes");
			});
	}
	var fileInput0 = document.getElementById('file-input0');
	if(fileInput0 != null){
		fileInput0.addEventListener("change", function(event) {
			var file = event.target.files[0]; // Lấy tệp tin từ sự kiện onchange

			var reader = new FileReader();

			// Xử lý khi đọc tệp tin hoàn tất
			reader.onload = function(e) {
				var imageData = e.target.result; // Dữ liệu ảnh đã đọc

			 

				$('#imgdata0').val('');
				$('#imgdata0').val(imageData);
			}
			reader.readAsDataURL(file);
			$('#frame_image0').val('');
			$('#frame_image0').val(file.name);
			  // Hiển thị thông tin về tệp tin đã chọn (ví dụ)
			  console.log("File Name: " + file.name);
			  console.log("File Size: " + file.size + " bytes");
			});
	}
	var fileInput1 = document.getElementById('file-input1');
	if(fileInput1 != null){
		fileInput1.addEventListener("change", function(event) {
			var file = event.target.files[0]; // Lấy tệp tin từ sự kiện onchange

			var reader = new FileReader();

			// Xử lý khi đọc tệp tin hoàn tất
			reader.onload = function(e) {
				var imageData = e.target.result; // Dữ liệu ảnh đã đọc

			 

				$('#imgdata1').val('');
				$('#imgdata1').val(imageData);
			}
			reader.readAsDataURL(file);
			$('#frame_image1').val('');
			$('#frame_image1').val(file.name);
			  // Hiển thị thông tin về tệp tin đã chọn (ví dụ)
			  console.log("File Name: " + file.name);
			  console.log("File Size: " + file.size + " bytes");
			});
	}
	var fileInput2 = document.getElementById('file-input2');
	if(fileInput2 != null){
		fileInput2.addEventListener("change", function(event) {
			var file = event.target.files[0]; // Lấy tệp tin từ sự kiện onchange

			var reader = new FileReader();

			// Xử lý khi đọc tệp tin hoàn tất
			reader.onload = function(e) {
				var imageData = e.target.result; // Dữ liệu ảnh đã đọc

			 

				$('#imgdata2').val('');
				$('#imgdata2').val(imageData);
			}
			reader.readAsDataURL(file);
			$('#frame_image2').val('');
			$('#frame_image2').val(file.name);
			  // Hiển thị thông tin về tệp tin đã chọn (ví dụ)
			  console.log("File Name: " + file.name);
			  console.log("File Size: " + file.size + " bytes");
			});
	}
});
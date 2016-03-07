(function($){var public_methods={init:function(options){return this.each(function(){var wrapper=$(this);var wrapper_dom=this;this.fuf_variables={};this.fuf_variables.form=wrapper.parents("*").andSelf().filter("form:first");this.fuf_variables.real_table=wrapper.find("table.multiupload_table").hide();this.fuf_variables.table=this.fuf_variables.real_table.find("tbody");this.fuf_variables.empty_page=$('<p class="empty_page"><span class="inner">'+App.lang("Click button to choose files which will be uploaded")+"</span><br />"+App.lang("Maximum file size you can upload is :max_size",{max_size:App.formatFileSize(options.size_limit)})+"</p>").insertBefore(this.fuf_variables.real_table);this.fuf_variables.add_file_wrapper=$('<div class="add_button_wrapper" id="'+this.fuf_variables.form.attr("id")+'_add_button_wrapper"></div>').insertAfter(this.fuf_variables.real_table);this.fuf_variables.add_file_button=$('<a class="link_button" href="#" id="'+this.fuf_variables.form.attr("id")+'_add_button"><span class="inner"><span class="icon button_add">'+App.lang("Choose Files")+"</span></span></a>").appendTo(this.fuf_variables.add_file_wrapper);this.fuf_variables.delete_icon_url=options.delete_button_url;this.fuf_variables.uploader=new plupload.Uploader({url:App.extendUrl(options.upload_url,{advanced_upload:1}),runtimes:options.uploader_runtimes,container:this.fuf_variables.add_file_wrapper.attr("id"),browse_button:this.fuf_variables.add_file_button.attr("id"),max_file_size:options.size_limit,file_data_name:options.upload_name,flash_swf_url:options.flash_uploader_url,silverlight_xap_url:options.silverlight_uploader_url,multipart:true,multipart_params:{submitted:"submitted"}});this.fuf_variables.uploader.bind("FilesAdded",function(uploader,files){files_added.apply(wrapper_dom,[files])});this.fuf_variables.uploader.bind("UploadProgress",function(uploader,file){update_file_progress.apply(wrapper_dom,[file])});this.fuf_variables.uploader.bind("FileUploaded",function(uploader,file,response){eval("var response = "+response.response);if(response&&response.type=="Error"){response.file=file;upload_error.apply(wrapper_dom,[response])}else{finalize_file_upload.apply(wrapper_dom,[file,response])}});this.fuf_variables.uploader.bind("Error",function(uploader,error){upload_error.apply(wrapper_dom,[error])});this.fuf_variables.uploader.bind("UploadComplete",function(uploader){wrapper_dom.fuf_variables.form.removeClass("uploading")});this.fuf_variables.uploader.init();wrapper.on("click","td.options a.delete_row",function(){remove_file.apply(wrapper_dom,[$(this).parents("tr:first")]);return false})})}};var files_added=function(files){var wrapper_dom=this;wrapper_dom.fuf_variables.empty_page.hide();wrapper_dom.fuf_variables.real_table.show();$.each(files,function(file_id,file){$('<tr row_id="'+file.id+'" class="pending"><td class="file_input_container"><div class="placeholder">'+file.name+'<div class="progressbar"><div class="progressbar_inner" style="width: 0%"></div></div></div></td><td class="description_input_container"><input type="text" name="descriptions['+file.id+']" /></td><td class="options"><a href="#" class="delete_row"><img src="'+wrapper_dom.fuf_variables.delete_icon_url+'" alt="x" /></a></td></tr>').appendTo(wrapper_dom.fuf_variables.table)});if(this.fuf_variables.uploader.state==plupload.STOPPED){setTimeout(function(){wrapper_dom.fuf_variables.uploader.start()},20)}this.fuf_variables.form.addClass("uploading")};var get_file_row=function(file_id){return this.fuf_variables.table.find("tr[row_id="+file_id+"]")};var update_file_progress=function(file){get_file_row.apply(this,[file.id]).find("div.progressbar div:first").css("width",file.percent+"%")};var finalize_file_upload=function(file,response){var row=get_file_row.apply(this,[file.id]);if(!row.length){return false}row.removeClass("uploading");row.find("div.placeholder").removeClass("uploading").addClass("success").after('<input type="hidden" name="attachments['+file.id+']" value="'+response.id+'" />');row.find("div.progressbar").hide()};var upload_error=function(error){var file=error.file;var row=get_file_row.apply(this,[file.id]);row.removeClass("uploading");row.find("div.placeholder").removeClass("uploading").addClass("error");row.find("div.progressbar").hide();if(error.message){App.Wireframe.Flash.error(error.message)}else{App.Wireframe.Flash.error("Upload Failed")}this.fuf_variables.uploader.refresh()};var remove_file=function(file_row){var wrapper=$(this);var file_id=file_row.attr("row_id");this.fuf_variables.uploader.removeFile(file_id);file_row.remove();if(!this.fuf_variables.table.find("tr").length){this.fuf_variables.empty_page.show();this.fuf_variables.real_table.hide()}};var plugin_name="fileUploadForm";var settings={};$.fn[plugin_name]=function(method){if(public_methods[method]){return public_methods[method].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof method==="object"||!method){return public_methods.init.apply(this,arguments)}else{$.error("Method "+method+" does not exist on jQuery."+plugin_name)}}};var _variables=function(element){var variables=element.data(plugin_name+"Variables");if(variables){return variables}element.data(plugin_name+"Variables",{});return element.data(plugin_name+"Variables")}})(jQuery);
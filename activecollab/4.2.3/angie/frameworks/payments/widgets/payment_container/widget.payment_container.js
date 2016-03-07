(function($){var plugin_name="paymentContainer";var settings={object:null,payments:null,payment_btn:null,load_more_url:"{assemble route=incoming_email_admin_mailboxes}",items_per_load:100};var public_methods={init:function(options){return this.each(function(){var _this=this;var $this=$(this);_initvar($this);_var($this).settings=$.extend({},settings,options||{});if(!_var($this).settings.payments){_var($this).settings.payments=new Array()}if(_var($this).settings.object.status_conditions["is_draft"]){}else{if(_var($this).settings.object.status_conditions["is_issued"]){initialize_payment_container.apply(_this)}else{if(_var($this).settings.object.status_conditions["is_paid"]){initialize_payment_container.apply(_this)}else{if(_var($this).settings.object.status_conditions["is_canceled"]){}}}}})},refresh:function(object){return this.each(function(){var _this=this;var $this=$(this);var old_object=_var($this).settings.object;_var($this).settings.object=object;if(old_object.status!=object.status){if(_var($this).settings.object.status_conditions["is_issued"]){if($this.find(".object_payment").length==0){initialize_payment_container.apply(_this)}}else{if(_var($this).settings.object.status_conditions["is_canceled"]){remove_payment_container.apply(_this)}}}})},add_payment:function(payment){return this.each(function(){add_payment.apply(this,[payment])})},update_payment:function(payment){return this.each(function(){update_payment.apply(this,[payment])})}};var initialize_payment_container=function(){var _this=this;var $this=$(this);var container=$('<div class="object_section object_payment"></div>').append('<div class="content_section_title"></div>').append('<div id="object_payments"></div>');$this.append(container);managePaymentsButtons.apply(_this,[_var($this).settings.object]);populate_payment_items.apply(_this);container.find("tfoot").append('<tr><td colspan="4">&nbsp;</td><td colspan="2" class="total_paid">'+App.lang("Total:")+' <span class="total_amount">'+App.moneyFormat(_var($this).settings.object.payments["paid_amount"],_var($this).settings.object.currency,null,true)+' </span> (<span class="total_amount_percent">'+_var($this).settings.object.payments["paid_amount_percentage"]+"</span>%)</td></tr>")};var remove_payment_container=function(){$(this).find("div.object_payment").remove()};var makeDeleteLinkAsync=function(link){link.asyncLink({confirmation:App.lang("Are you sure that you want to mark this payment as deleted?"),success_event:"payment_updated"})};var populate_payment_items=function(){var _this=this;var $this=$(this);$("#object_payments").pagedObjectsList({load_more_url:_var($this).settings.load_more_url,items:_var($this).settings.payments,items_per_load:100,total_items:_var($this).settings.object.payments["total_payments"],list_items_are:"tr",list_item_attributes:{"class":"payments"},columns:{paid_on:App.lang("Paid On"),gateway_type:App.lang("Gateway"),comment:App.lang("Comment"),status:App.lang("Status"),method:App.lang("Method"),amount:App.lang("Amount"),options:""},listen:"payment",listen_scope:"single",listen_constraint:function(event,item){return typeof(item)=="object"&&item&&item.id},empty_message:App.lang("There are no payments yet"),on_add_item:function(item){var payment=$(this);payment.append('<td class="paid_on"><td class="gateway_type"></td><td class="comment"></td><td class="status"></td><td class="method"></td><td class="amount"></td><td class="options"></td>');payment.find("td.paid_on").text(item.paid_on["formatted_gmt"]);var icon='<img src="'+App.Wireframe.Utils.imageUrl(item.gateway_icon,"payments")+'">';payment.find("td.gateway_type").html(icon);var comment=App.clean(item.comment)?App.clean(item.comment):App.lang("Comment not provided for this payment");payment.find("td.comment").text(comment);payment.find("td.status").text(App.clean(item.status));payment.find("td.amount").append(App.moneyFormat(item.amount,item.currency,null,true));if(item.method){payment.find("td.method").append(item.method)}if(_var($this).settings.object.payments["permissions"]["can_view"]){payment.find("td.options").append('<a href="'+item.urls["view"]+'" class="payment_details" title="'+App.lang("View Details")+'"><img src="'+App.Wireframe.Utils.imageUrl("/icons/12x12/preview.png","environment")+'" /></a>')}if(_var($this).settings.object.payments["permissions"]["can_edit"]){payment.find("td.options").append('<a href="'+item.urls["edit"]+'" class="edit_payment" title="'+App.lang("Edit Payment")+'"><img src="'+App.Wireframe.Utils.imageUrl("/icons/12x12/edit.png","environment")+'" /></a>')}if(_var($this).settings.object.payments["permissions"]["can_delete"]){if(!item.is_deleted){payment.find("td.options").append('<a href="'+item.urls["delete"]+'" class="delete_payment" title="'+App.lang("Mark As Deleted")+'"><img src="'+App.Wireframe.Utils.imageUrl("/icons/12x12/delete.png","environment")+'" /></a>')}}payment.find("td.options a.payment_details").flyout({width:550});payment.find("td.options a.edit_payment").flyoutForm({success_event:"payment_updated",width:450});var delete_link=payment.find("td.options a.delete_payment");makeDeleteLinkAsync.apply(_this,[delete_link])}})};var managePaymentsButtons=function(invoice){var _this=this;var $this=$(this);var total_percent=invoice.payments["paid_amount_percentage"];$(".object_payment .content_section_title .make_a_payment").remove();if(total_percent!=100){if(invoice.payments["show_payment_btn"]){var payment_btns='<span class="make_a_payment"><a href="'+invoice.payments["add_url"]+'" class="section_button make_a_payment_btn"><span><img src="'+App.Wireframe.Utils.imageUrl("icons/16x16/go-to-project.png","environment")+'" />'+App.lang("Make a Payment")+"</span></a></span>";$(".object_payment .content_section_title").append(payment_btns);$(".object_payment .make_a_payment_btn").flyoutForm({title:App.lang("Make a Payment"),success_event:"payment_created",width:650})}}};var recalculate_total=function(payment){var wrapper=$(this);wrapper.find(".total_paid .total_amount").text(App.moneyFormat(payment.total,_var(wrapper).settings.object.currency,null,true));wrapper.find(".total_paid .total_amount_percent").text(App.moneyFormat(payment.total_percent,_var(wrapper).settings.object.currency))};var add_payment=function(payment){var _this=this;var $this=$(this);if(payment.id){_var($this).settings.payments.push(payment);if(payment.type=="PaypalExpressCheckoutPayment"){window.location=payment.redirect_url}else{managePaymentsButtons.apply(_this,[payment.invoice]);recalculate_total.apply(_this,[payment]);App.Wireframe.Flash.success(App.lang("New payment added."))}}else{App.Wireframe.Flash.error(payment)}};var update_payment=function(payment){var _this=this;var $this=$(this);if(payment.id){recalculate_total.apply(_this,[payment])}managePaymentsButtons.apply(_this,[payment.invoice]);App.Wireframe.Flash.success(App.lang("Payment updated."))};$.fn[plugin_name]=function(method){if(public_methods[method]){return public_methods[method].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof method==="object"||!method){return public_methods.init.apply(this,arguments)}else{$.error("Method "+method+" does not exist on jQuery.paymentContainer")}}};var _var=function(element){return element.data(plugin_name+"Variables")};var _initvar=function(element){element.data(plugin_name+"Variables",{})}})(jQuery);
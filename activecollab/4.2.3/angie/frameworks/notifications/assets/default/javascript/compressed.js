/* Minified: main.js */
App.Wireframe.Notifications=function(){var displayed_notification_ids=[];return{showNotification:function(notification_id,notification_message,notification_url){if(typeof(notification_id)=="number"&&notification_id&&typeof(notification_message)=="string"&&notification_message&&displayed_notification_ids.indexOf(notification_id)==-1){displayed_notification_ids.push(notification_id);if(typeof(notification_url)=="string"&&notification_url){App.Wireframe.Flash.information(notification_message,null,true,{url:notification_url})}else{App.Wireframe.Flash.information(notification_message,null,true)}}}}}();App.Wireframe.Updates.subscribe("notifications",null,function(response){if(typeof(response)=="object"&&response&&typeof(response.unseen_notifications)!="undefined"&&jQuery.isArray(response.unseen_notifications)){App.each(response.unseen_notifications,function(k,v){App.Wireframe.Notifications.showNotification(k,v.message,v.url)})}});
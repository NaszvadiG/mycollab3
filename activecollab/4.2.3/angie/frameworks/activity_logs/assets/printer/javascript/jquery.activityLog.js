/**
 * JS builder for access log
 */
jQuery.fn.activityLog = function(s) {
  var settings = jQuery.extend({
    'entries' : null, 
    'authors' : null, 
    'subjects' : null, 
    'targets' : null, 
    'domains' : null, 
    'callbacks' : null, 
    'decorator' : null,
    'interface' : null
  }, s);
  
  if(settings['callbacks']) {
    for(var i in settings['callbacks']) {
      settings['callbacks'][i] = eval(settings['callbacks'][i]);
    } // for
  };
  
  var decorator_name = settings['decorator'];
  
  if(decorator_name && typeof(window[decorator_name]) == 'function') {
    settings['decorator'] = window[decorator_name];
  } // if
  
  return this.each(function() {
    var wrapper = $(this).addClass('activity_log');
    
    if(decorator_name && typeof(settings['decorator']) == 'function') {
      wrapper.attr('activity_log_decorator', decorator_name);
    } // if
    var empty_message = $('<p class="empty_page">' + App.lang('There are no activities in the log') + '</p>').hide().appendTo(wrapper);
    
    /**
     * Return day table
     * 
     * @param Object datetime
     */
    var day_table = function(datetime) {
      var day = datetime['formatted_date'];
      
      if(App.isToday(datetime)) {
        var caption = App.lang('Today');
        var extra_class = 'today';
      } else if(App.isYesterday(datetime)) {
        var caption = App.lang('Yesterday');
        var extra_class = 'yesterday';
      } else {
        var caption = day;
        var extra_class = 'older';
      } // if
      
      var day_table = wrapper.find('table[activity_logs_dat="' + day + '"]');
      
      if(day_table.length < 1) {
        var day_table = $('<table cellspacing="0" class="day_activity_logs common ' + extra_class + '" activity_logs_dat="' + day + '"><tbody><tr><th class="date" colspan="4">' + App.clean(caption) + '</th><th>&nbsp;</th></tr></tbody></table>').appendTo(wrapper);
      } // if
      
      return day_table;
    }; // day_table
    
    /**
     * Add entry to the list
     * 
     * @param Object entry
     */
    var add_entry = function(entry) {
    	
      if(entry['created_by_id'] > 0 && typeof(settings['authors']) == 'object' && settings['authors'] && typeof(settings['authors'][entry['created_by_id']]) == 'object') {
        var author = settings['authors'][entry['created_by_id']];
      } else {
        if(entry['created_by_name'] || entry['created_by_email']) {
          var author = {
            'id' : 0, 
            'display_name' : entry['created_by_name'] ? entry['created_by_name'] : entry['created_by_email'].substr(0, entry['created_by_email'].indexOf('@')), 
            'urls' : {
              'view' : 'mailto:' + entry['created_by_email']
            }
          };
        } else {
          var author = {
            'id' : 0, 
            'display_name' : App.lang('Deleted User'), 
            'urls' : {
              'view' : 'mailto:noreply@activecollab.com'
            }
          };
        } // if
      } // if
      
      var subject_type = entry['subject_type'];
      var subject_id = entry['subject_id'];
      
      if(typeof(settings['subjects']) == 'object' && settings['subjects']) {
        var subject = typeof(settings['subjects'][subject_type]) == 'object' && typeof(settings['subjects'][subject_type][subject_id]) == 'object' ? settings['subjects'][subject_type][subject_id] : null;
      } else {
        var subject = null;
      } // if
      
      if(subject === null) {
        return; // Nothing to add :(
      } // if
      
      var target_type = entry['target_type'];
      var target_id = entry['target_id']; 
      
      if(target_type && target_id) {
        var target = typeof(settings['targets'][target_type]) == 'object' && typeof(settings['targets'][target_type][target_id]) == 'object' ? settings['targets'][target_type][target_id] : null;
      } else {
        var target = null;
      } // if
      
      var base_type_name = entry['action'].substr(0, entry['action'].indexOf('/'));
      var row = $('<tr class="' + entry['action'].replace('/', '_') + ' ' + base_type_name + '" parent_type="' + App.clean(subject_type) + '" parent_id="' + subject_id + '" parent_permalink="' + App.clean(subject['permalink']) + '">' +
        '<td class="parent"><span class="object_type inverse object_type_' + base_type_name + '">' + App.clean(subject['verbose_type']) + '</span></td>' +
        '<td class="action"></td>' +
        '<td class="author"></td>' +
        '<td class="subject"></td>' +
        '<td class="timestamp">' + entry['created_on']['formatted_time'] + '</td>' + 
      '</tr>').appendTo(day_table(entry['created_on']));

      var action = entry['action'];
      var any_action = '*' + entry['action'].substr(entry['action'].indexOf('/'));

      if(typeof(settings['callbacks'][action]) == 'function') {
        settings['callbacks'][action].apply(row[0], [ entry, author, subject, target, settings['interface'] ]);
      } else if(typeof(settings['callbacks'][any_action]) == 'function') {
        settings['callbacks'][any_action].apply(row[0], [ entry, author, subject, target, settings['interface'] ]);
      } // if
      
      if(typeof(settings['decorator']) == 'function') {
        settings['decorator'].apply(row[0], [ entry, author, subject, target, settings['interface'] ]);
      } // if
    }; // add_entry
    
    
    // add entries
    if(jQuery.isArray(settings['entries']) && settings['entries'].length > 0 && typeof(settings['subjects']) == 'object' && settings['subjects']) {
      for(var i in settings['entries']) {
        add_entry(settings['entries'][i]);
      } // for
    } else {
      empty_message.show();
    } // if
  });
};
<div id="empty_slate_system_roles" class="empty_slate">
  <h3>{lang}About Scheduled Tasks{/lang}</h3>
  
  <ul class="icon_list">
    <li>
      <img src="{image_url name="empty-slates/modules.png" module=$smarty.const.ENVIRONMENT_FRAMEWORK}" class="icon_list_icon" alt="" />
      <span class="icon_list_title">{lang}Scheduled Tasks{/lang}</span>
      <span class="icon_list_description">{lang}Some activeCollab modules require to be called periodically in order to do something. For instance, Invoicing module requires to be called once a day in order to process recurring profiles. Tasks that are executed in this way are usually utility tasks and do not require user interaction{/lang}.</span>
    </li>
    
    <li>
      <img src="{image_url name="empty-slates/scheduled-tasks.png" module=$smarty.const.ENVIRONMENT_FRAMEWORK}" class="icon_list_icon" alt="" />
      <span class="icon_list_title">{lang}Execution Frequency{/lang}</span>
      <span class="icon_list_description">{lang}There are three type of scheduled events - events executed frequently (every 3 - 5 minutes), events executed once an hour and events executed once a day. These events need to be triggered from outside, by system utility used to periodically trigger and execute tasks{/lang}.</span>
    </li>
    
    <li>
      <img src="{image_url name="empty-slates/cli.png" module=$smarty.const.ENVIRONMENT_FRAMEWORK}" class="icon_list_icon" alt="" />
      <span class="icon_list_title">{lang}Executing Scheduled Tasks{/lang}</span>
      <span class="icon_list_description">
        {if !is_windows_server()}
            {lang}Scheduled tasks can be executed through command line by executing following commands{/lang}:
            <pre>php {scheduled_task_command task=frequently} &gt; /dev/null
php {scheduled_task_command task=hourly} &gt; /dev/null
php {scheduled_task_command task=daily} &gt; /dev/null</pre>
            {lang}or through web interface by sending HTTP request to event URL-s{/lang}:
            <pre>/usr/bin/curl -s -L {scheduled_task_url task=frequently} &gt; /dev/null
/usr/bin/curl -s -L {scheduled_task_url task=hourly} &gt; /dev/null
/usr/bin/curl -s -L {scheduled_task_url task=daily} &gt; /dev/null</pre>
          {lang}Commands listed above are just examples. Please consult your system administrator or hosting provider for exact location of PHP or curl executables and for assistance with getting these commands to execute properly on your server{/lang}.
        {else}
          <pre>&quot;C:&#92;PHP&#92;php.exe&quot; {scheduled_task_command task=frequently}
&quot;C:&#92;PHP&#92;php.exe&quot; {scheduled_task_command task=hourly}
&quot;C:&#92;PHP&#92;php.exe&quot; {scheduled_task_command task=daily}</pre>
          {lang}On Windows you can also use Scheduled Tasks to trigger scheduled tasks in activeCollab. To set-up Scheduled Tasks on Windows XP, Vista and Windows 7 (as well as Windows 2003 Server or later) you can use schtasks.exe. To do so, open the command line and type in the following commands{/lang}:
          <pre>schtasks /create /ru "System" /sc minute /mo 3 /tn "activeCollab frequently job" /tr &quot;C:&#92;PHP&#92;php.exe {scheduled_task_command task=frequently} -f&quot;
schtasks /create /ru "System" /sc hourly /st 12:00:00 /tn "activeCollab hourly job" /tr &quot;C:&#92;PHP&#92;php.exe {scheduled_task_command task=hourly} -f&quot;
schtasks /create /ru "System" /sc daily /st 12:00:00 /tn "activeCollab daily job" /tr &quot;C:&#92;PHP&#92;php.exe {scheduled_task_command task=daily} -f&quot;</pre>
          {lang}Commands listed above are just examples. Please consult your system administrator or hosting provider for exact location of PHP executables and for assistance with getting these commands to execute properly on your server{/lang}.
        {/if}
      </span>
    </li>
    
    <li>
      <img src="{image_url name="empty-slates/help.png" module=$smarty.const.ENVIRONMENT_FRAMEWORK}" class="icon_list_icon" alt="" />
      <span class="icon_list_title">{lang}More Info{/lang}</span>
      <span class="icon_list_description">{lang url='http://www.activecollab.com/docs/manuals/admin-version-3/configuration/scheduled-tasks'}You can read more about Scheduled Tasks and how they should be configured in <a href=":url" target="_blank">Administrator's Guide</a>{/lang}.</span>
    </li>
  </ul>
</div>
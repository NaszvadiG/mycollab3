{title}Scheduled Tasks{/title}
{add_bread_crumb}Scheduled Tasks{/add_bread_crumb}

<div id="scheduled_tasks_admin" class="wireframe_content_wrapper settings_panel">
  <div class="settings_panel_header">
    <table class="settings_panel_header_cell_wrapper">
      <tr>
        <td class="settings_panel_header_cell">
          <h2>{lang}Scheduled Tasks{/lang}</h2>
			    <div class="properties">
			      <div class="property">
			        <div class="label">{lang}Frequently{/lang}</div>
			        <div class="data">
			        {if $last_frequently_activity instanceof DateTimeValue}
			          {$last_frequently_activity|datetime}
			        {else}
			          {lang}Never executed{/lang}
			        {/if}
			        </div>
			      </div>
			      
			      <div class="property">
			        <div class="label">{lang}Hourly{/lang}</div>
			        <div class="data">
			        {if $last_hourly_activity instanceof DateTimeValue}
			          {$last_hourly_activity|datetime}
			        {else}
			          {lang}Never executed{/lang}
			        {/if}
			        </div>
			      </div>
			      
			      <div class="property">
			        <div class="label">{lang}Daily{/lang}</div>
			        <div class="data">
			        {if $last_daily_activity instanceof DateTimeValue}
			          {$last_daily_activity|datetime}
			        {else}
			          {lang}Never executed{/lang}
			        {/if}
			        </div>
			      </div>
			    </div>
        </td>
      </tr>
    </table>
  </div>
  
  <div class="settings_panel_body">
    {empty_slate name=scheduled_tasks module=$smarty.const.ENVIRONMENT_FRAMEWORK}  
  </div>
</div>



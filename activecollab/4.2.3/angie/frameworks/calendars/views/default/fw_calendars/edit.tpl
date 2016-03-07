{title}Update Calendar{/title}
{add_bread_crumb}Edit{/add_bread_crumb}

<div id="edit_calendar">
  {form action=$active_calendar->getEditUrl()}
    {wrap_fields}
      {include file=get_view_path('_calendar_form', 'fw_calendars', $smarty.const.CALENDARS_FRAMEWORK)}
    {/wrap_fields}

    {wrap_buttons}
      {submit}Save Changes{/submit}
    {/wrap_buttons}
  {/form}
</div>
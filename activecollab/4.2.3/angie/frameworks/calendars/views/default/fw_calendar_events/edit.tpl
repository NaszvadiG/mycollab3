{title}Edit Event{/title}
{add_bread_crumb}Edit Event{/add_bread_crumb}

<div id="edit_calendar_event">
	{form action=$active_calendar_event->getEditUrl()}
	{wrap_fields}
		{include file=get_view_path('_calendar_event_form', 'fw_calendar_events', $smarty.const.CALENDARS_FRAMEWORK)}
	{/wrap_fields}

	{wrap_buttons}
		{submit}Save Changes{/submit}
		{if $active_calendar_event->state()->canTrash($logged_user)}
			{button href=$active_calendar_event->state()->getTrashUrl() success_event="calendar_event_trashed" async=true confirm="Are you sure you want move this event to trash?"}Move To Trash{/button}
		{/if}
	{/wrap_buttons}
	{/form}
</div>
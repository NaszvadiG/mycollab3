{title}Edit Mailbox{/title}
{add_bread_crumb}Edit Mailbox{/add_bread_crumb}
{use_widget name="add_edit_incoming_mailbox_form" module="email"}

<div id="mailbox_form">
  {form action=$active_mailbox->getEditUrl() method=post}
    {include file=get_view_path('_mailbox_form', 'fw_incoming_mailboxes_admin', $smarty.const.EMAIL_FRAMEWORK)}
    
    {wrap_buttons}
  	  {submit}Save Changes{/submit}
    {/wrap_buttons}
  {/form}
</div>

<script type="text/javascript">
  App.widgets.AddEditIncomingMailboxForm.init('mailbox_form', '{assemble route=incoming_email_admin_mailbox_test_connection}');
</script>
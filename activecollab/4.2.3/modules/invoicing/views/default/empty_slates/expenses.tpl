<div id="empty_slate_invoice_time" class="empty_slate">
  <h3>{lang}About Invoice Expenses{/lang}</h3>
  
  <ul class="icon_list">
    <li>
      <img src="{image_url name="empty-slates/date-time.png" module=$smarty.const.SYSTEM_MODULE}" class="icon_list_icon" alt="" />
      <span class="icon_list_title">{lang}Related Expenses{/lang}</span>
      <span class="icon_list_description">{lang}All expenses related to this invoice will be automatically marked as "Pending Payment" when this invoice gets issued. When invoice is marked as paid, then all related expenses will be automatically marked as paid, too. When the invoice is canceled, all related records will be automatically reverted to their original, billable state and released{/lang}.</span>
    </li>
    
    <li>
      <img src="{image_url name="empty-slates/release.png" module=invoicing}" class="icon_list_icon" alt="" />
      <span class="icon_list_title">{lang}On Releasing Expenses{/lang}</span>
      <span class="icon_list_description">{lang}When records are released, relation between this invoice and them is removed, without any records being deleted. Instead, releated records will be reverted to their original, billable state, and invoice will not change their status in the future{/lang}.</span>
    </li>
  </ul>
</div>
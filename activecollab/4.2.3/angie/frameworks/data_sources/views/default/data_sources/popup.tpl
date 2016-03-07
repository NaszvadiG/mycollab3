<div class="data_sources_popup">
  {if is_foreachable($data_sources->toArray())}
    {foreach $data_sources as $item}
      <div class="data_source_item">
        {link title=$item.title  href=$item.url mode="flyout_form" flyout_width=$item.width success_event=$item.event}
          <img src="{$item.icon}">
          <span class="label">{$item.text}</span>
        {/link}
      </div>
    {/foreach}
  {else}
    <p>{lang}No Data Sources defined. Go to administration page and define one.{/lang}</p>
  {/if}
</div>
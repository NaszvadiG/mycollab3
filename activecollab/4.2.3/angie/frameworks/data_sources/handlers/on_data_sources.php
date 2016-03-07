<?php

  /**
   * on_data_sources event handler
   *
   * @package angie.frameworks.data_sources
   */

  /**
   * On Data Sources
   *
   * @param $sources
   */
  function data_sources_handle_on_data_sources(NamedList &$sources) {
    $defined_data_sources = DataSources::find();

    if(is_foreachable($defined_data_sources)) {
      foreach($defined_data_sources as $source) {
        $sources->add(HTML::uniqueId(get_class($source)), array(
          'text'	=> $source->getName(),
          'title' => lang('Import data from :name',array('name' => $source->getName())),
          'icon' => $source->getIconUrl(),
          'url'		=> $source->getImportUrl(),
          'width' => 600,
          'event' => 'data_imported'
        ));
      } //foreach
    } //if
  } // data_sources_handle_on_data_sources
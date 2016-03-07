<?php
  AngieApplication::useController('backend', SYSTEM_MODULE);

  /**
   * Class DataSourcesController
   */
  class DataSourcesController extends BackendController {

    /**
     * @var DataSource
     */
    var $active_data_source;

    /**
     * Constructor method
     *
     * @param string $request
     */
    function __construct($request) {
      parent::__construct($request);

    } // __construct

    /**
     * Prepare controller
     */
    function __before() {
      parent::__before();

      $data_source_id = $this->request->get('data_source_id');
      if($data_source_id) {
        $this->active_data_source = DataSources::findById($data_source_id);
      } //if

      $this->response->assign(array(
        'active_data_source' => $this->active_data_source
      ));
    } // __construct

    /**
     * Popup
     *
     */
    function popup() {
      try {
        $data_sources = new NamedList();
        EventsManager::trigger('on_data_sources', array(&$data_sources));

        $this->response->assign(array(
          'data_sources' => $data_sources
        ));
      } catch (Error $e) {
        $this->response->exception($e);
      } //try
    } //popup

    /**
     * Import from source
     *
     */
    function import() {
      if(!$this->request->isAsyncCall()) {
        $this->response->badRequest();
      } //if

      if($this->request->isSubmitted()) {
        try {
          $params = $this->request->post('params');

          $this->active_data_source->import($params);
        } catch (Error $e) {
          $this->response->exception($e);
        } //try
      } //if

    } //import

  } //DataSourcesController
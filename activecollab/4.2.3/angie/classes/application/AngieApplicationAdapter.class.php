<?php

  /**
   * Base angie application class
   *
   * This class implements most of the Angie application initialization routines. 
   * We figured that things were scattered to much in previous setup so we 
   * decided to move everything into one class that can be overriden if user 
   * finds need to change initialization process.
   * 
   * @package angie.library.application
   */
  abstract class AngieApplicationAdapter {
    
    /**
     * Return application name
     *
     * @return string
     */
    abstract function getName();
    
    /**
     * Return application URL
     * 
     * @return string
     */
    abstract function getUrl();
    
    /**
     * Return application version
     *
     * @return string
     */
    function getVersion() {
      return APPLICATION_VERSION;
    } // getVersion
    
    /**
     * Returns true if current application version is stable
     *
     * @return boolean
     */
    abstract function isStable();
    
    /**
     * Return vendor name
     * 
     * @return string
     */
    abstract function getVendor();
    
    /**
     * Return license agreement URL
     * 
     * @return string
     */
    abstract function getLicenseAgreementUrl();

    /**
     * Return check for updates URL
     *
     * @return string
     */
    abstract function getCheckForUpdatesUrl();

    /**
     * Return download update URL
     *
     * @return string
     */
    abstract function getDownloadUpdateUrl();
    
    /**
     * Return application API version
     *
     * @return string
     */
    abstract function getApiVersion();
    
    /**
     * Return something unique to this application setup
     */
    abstract function getUniqueKey();

    /**
     * Return module signature
     *
     * @return string
     */
    abstract function getModuleSignature();
    
    // ---------------------------------------------------
    //  On first run
    // ---------------------------------------------------
    
    /**
     * Do application specific intialization
     */
    function onFirstRun() {
      
    } // onFirstRun

    /**
     * Return module compatibility link
     *
     * @param AngieModule $module
     * @param boolean $module_declared_internal
     * @return string
     */
    function getCompatibilityLink(AngieModule $module, $module_declared_internal = false) {
      return '#';
    } // getCompatibilityLink
    
    // ---------------------------------------------------
    //  Handlers
    // ---------------------------------------------------
    
    /**
     * Get and handle HTTP request
     * 
     * @param string $path_info
     * @param $query_string
     * @throws RoutingError
     * @throws ControllerDnxError
     */
    function handleHttpRequest($path_info, $query_string) {
      $request = Router::match($path_info, $query_string);
      
      $controller_name = $request->getController(); // we'll use this a lot
      
      AngieApplication::useController($controller_name, $request->getModule());
      
      $controller_class = Inflector::camelize($controller_name) . 'Controller';
      if(!class_exists($controller_class)) {
        throw new ControllerDnxError($controller_name);
      } // if
      
      $controller = new $controller_class($request);
      if($controller instanceof Controller) {
        $controller->__execute($request->getAction());
      } else {
        throw new ControllerDnxError($controller_name);
      } // if
    } // handleHttpRequest
    
  }
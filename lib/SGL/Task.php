<?php

class SGL_Task
{
   /**
    * object of type DAL
    * @var object
    */
    var $oDal;

    function SGL_Task()
    {
        #$this->oDal = & $this->_getDal();
    }

    /**
     * @abstract
     *
     */
    function run($data = null)
    {
        return;
    }

   /**
    * Example ...
    * @access private
    */
    function &_getDal()
    {
        $oServiceLocator = &ServiceLocator::instance();
        $oDal = $oServiceLocator->get('dal');
        if (!$oDal) {
            $oDal = &DA_FooBar::singleton();
            $oServiceLocator->register('dal', $oDal);
        }
        return $oDal;
    }
}

?>
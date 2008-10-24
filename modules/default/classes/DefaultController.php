<?php

class DefaultController extends SGL2_Controller_Page
{
    public function __construct()
    {
        parent::__construct();

        $this->_pageTitle    = 'Home';
        $this->_template     = 'default.html';
        $this->_aActionsMapping =  array(
            'list'  => array('list'),
        );
    }

    public function validate(SGL2_Request $input)
    {
        $input->layout      = $this->_layout;
        $input->template    = $this->_template;
        $input->pageTitle   = $this->_pageTitle;
        $input->action      = ($input->get('action')) ? $input->get('action') : 'list';

        // filtering and validation example
        // see http://framework.zend.com/manual/en/zend.filter.input.html
        $filters = array(
            '*'     => 'StringTrim',
            'month' => 'Digits'
        );
        $validators = array(
            'month'   => array(
                'Digits',                // string
                new Zend_Validate_Int(), // object instance
                array('Between', 1, 12)  // string with constructor arguments
            )
        );
        $zfi = new Zend_Filter_Input($filters, $validators, $input->getTainted());
        if ($zfi->hasInvalid() || $zfi->hasMissing()) {
            $this->setMessages($zfi->getMessages());
            $ret = false;
        } else {
            $input->add($zfi->getUnescaped());
            $ret = true;
        }
        return $ret;
    }

    protected function _doList(SGL2_Request $input, SGL2_Response $output)
    {
        $input->foo = 'myFoo';
        $output->bar = 'myBar';
    }

    public function display(SGL2_Response $output)
    {
        $output->baz = 'myBaz';
    }
}
?>
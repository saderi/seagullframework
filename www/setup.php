<?php
/*
sgl setup
=========
- ability to upload and unzip/tar a packaged module
- file permission handling ideas from FUDforum installer
- more user-friendly error messages from Gallery2
- if no DB detected, prompt to create, otherwise offer to create tables

PROCESS
========

- create lockfile, system in 'setup' mode
- block public access
    one idea, using key in hidden file
        - put main site in standby mode
        - create randomly named dir
        - perform install in above dir
        - delete dir when finished

php interpreter
===============
- min php version, not over max
- get php sapi type
- check loaded extensions

php ini
=======
- deal with register_globals and set session.use_trans_sid = 0
- allow_url_fopen = Off
- detect and deal with safe_mode
- magic_quotes must be off
- file_uploads ideally enabled

filesystem
==========
- check pear libs exists and are loadable
- determine location in filesystem
- test if seagull/var exists & is writable
- copy config file there
- rewrite with correct values
- create seagull/var tmp dir for session
    
db setup
========
- test db connection
- test db perms
- get prefix, db params
- create tables
- insert default SQL data
- insert sample SQL data
- load constraints

config setup
============
- form
    - system paths
    - general
        - name + desc of site [metas]
        - admin email address
        - lang
        - server time offset
        
- the domain of the cookie that will be used
        
module setup
============
- choose modules and permissions must be created and set at install time
- attempt to 
    - uncompress
    - move to correct locatin
    - apply user perms
    - apply prefs
    - add module's db tables to Config
    - load module's schema + data
    - add 'section' or 'screen' navigation links
    - register module in registry

user setup
==========
- create admin, set username, password and email
- option to add user

- For security reasons, you must remove the installation script ...
- remove lockfile, system set to 'production' mode
*/



sgl_header();

require_once dirname(__FILE__) . '/../lib/SGL/TaskRunner.php';
require_once dirname(__FILE__) . '/../lib/SGL/Tasks/All.php';

$runner = new SGL_TaskRunner();
$runner->addTask(new SGL_Task_GetLoadedModules());
$runner->addTask(new SGL_Task_GetPhpEnv());
$runner->addTask(new SGL_Task_GetPhpIniValues());
$runner->addTask(new SGL_Task_GetFilesystemInfo());
$runner->addTask(new SGL_Task_GetPearInfo());
$output = $runner->main();

print $output;

sgl_footer();


exit;

require_once 'HTML/QuickForm/Controller.php';

// Load some default action handlers
require_once 'HTML/QuickForm/Action/Submit.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Direct.php';

session_start();

class PageFoo extends HTML_QuickForm_Page
{
    function buildForm()
    {
        $this->_formBuilt = true;

        $tabs[] =& $this->createElement('submit',   $this->getButtonName('foo'), 'Foo', array('class' => 'flat', 'disabled' => 'disabled'));
        $tabs[] =& $this->createElement('submit',   $this->getButtonName('bar'), 'Bar', array('class' => 'flat'));
        $tabs[] =& $this->createElement('submit',   $this->getButtonName('baz'), 'Baz', array('class' => 'flat'));
        $this->addGroup($tabs, 'tabs', null, '&nbsp;', false);
        
        $this->addElement('header',     null, 'Foo page');

        $radio[] = &$this->createElement('radio', null, null, 'Yes', 'Y');
        $radio[] = &$this->createElement('radio', null, null, 'No', 'N');
        $radio[] = &$this->createElement('radio', null, null, 'Maybe', 'M');
        $this->addGroup($radio, 'iradYesNoMaybe', 'Do you want this feature?', '<br />');

        $this->addElement('text',       'tstText', 'Why do you want it?', array('size'=>20, 'maxlength'=>50));

        $this->addElement('submit',     $this->getButtonName('submit'), 'Big Red Button', array('class' => 'bigred'));

        $this->addRule('iradYesNoMaybe', 'Check a radiobutton', 'required');

        $this->setDefaultAction('submit');
    }
}

class PageBar extends HTML_QuickForm_Page
{
    function buildForm()
    {
        $this->_formBuilt = true;

        $tabs[] =& $this->createElement('submit',   $this->getButtonName('foo'), 'Foo', array('class' => 'flat'));
        $tabs[] =& $this->createElement('submit',   $this->getButtonName('bar'), 'Bar', array('class' => 'flat', 'disabled' => 'disabled'));
        $tabs[] =& $this->createElement('submit',   $this->getButtonName('baz'), 'Baz', array('class' => 'flat'));
        $this->addGroup($tabs, 'tabs', null, '&nbsp;', false);
        
        $this->addElement('header',     null, 'Bar page');

        $this->addElement('date',       'favDate', 'Favourite date:', array('format' => 'd-M-Y', 'minYear' => 1950, 'maxYear' => date('Y')));
        $checkbox[] = &$this->createElement('checkbox', 'A', null, 'A');
        $checkbox[] = &$this->createElement('checkbox', 'B', null, 'B');
        $checkbox[] = &$this->createElement('checkbox', 'C', null, 'C');
        $checkbox[] = &$this->createElement('checkbox', 'D', null, 'D');
        $checkbox[] = &$this->createElement('checkbox', 'X', null, 'X');
        $checkbox[] = &$this->createElement('checkbox', 'Y', null, 'Y');
        $checkbox[] = &$this->createElement('checkbox', 'Z', null, 'Z');
        $this->addGroup($checkbox, 'favLetter', 'Favourite letters:', array('&nbsp;', '<br />'));

        $this->addElement('submit',     $this->getButtonName('submit'), 'Big Red Button', array('class' => 'bigred'));

        $this->setDefaultAction('submit');
    }
}

class PageBaz extends HTML_QuickForm_Page
{
    function buildForm()
    {
        $this->_formBuilt = true;

        $tabs[] =& $this->createElement('submit',   $this->getButtonName('foo'), 'Foo', array('class' => 'flat'));
        $tabs[] =& $this->createElement('submit',   $this->getButtonName('bar'), 'Bar', array('class' => 'flat'));
        $tabs[] =& $this->createElement('submit',   $this->getButtonName('baz'), 'Baz', array('class' => 'flat', 'disabled' => 'disabled'));
        $this->addGroup($tabs, 'tabs', null, '&nbsp;', false);
        
        $this->addElement('header',     null, 'Baz page');

        $this->addElement('textarea',   'textPoetry', 'Recite a poem:', array('rows' => 5, 'cols' => 40));
        $this->addElement('textarea',   'textOpinion', 'Did you like this demo?', array('rows' => 5, 'cols' => 40));

        $this->addElement('submit',     $this->getButtonName('submit'), 'Big Red Button', array('class' => 'bigred'));

        $this->addRule('textPoetry', 'Pretty please!', 'required');

        $this->setDefaultAction('submit');
    }
}

// We subclass the default 'display' handler to customize the output
class ActionDisplay extends HTML_QuickForm_Action_Display
{
    function _renderForm(&$page) 
    {
        $renderer =& $page->defaultRenderer();
        // Do some cheesy customizations
        $renderer->setElementTemplate("\n\t<tr>\n\t\t<td align=\"right\" valign=\"top\" colspan=\"2\">{element}</td>\n\t</tr>", 'tabs');
        $renderer->setFormTemplate(<<<_HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Controller example 3: tabbed form</title>
<style type="text/css">
input.bigred {font-weight: bold; background: #FF6666;}
input.flat {border-style: solid; border-width: 2px; border-color: #000000;}
</style>
</head>

<body>
<form{attributes}>
<table border="0">
{content}
</table>
</form>
</body>
</html>
_HTML
);
        $page->display();
    }
}

class ActionProcess extends HTML_QuickForm_Action
{
    function perform(&$page, $actionName)
    {
        echo "Submit successful!<br>\n<pre>\n";
        var_dump($page->controller->exportValues());
        echo "\n</pre>\n";
    }
}

$tabbed =& new HTML_QuickForm_Controller('Tabbed', false);

$tabbed->addPage(new PageFoo('foo'));
$tabbed->addPage(new PageBar('bar'));
$tabbed->addPage(new PageBaz('baz'));

// These actions manage going directly to the pages with the same name
$tabbed->addAction('foo', new HTML_QuickForm_Action_Direct());
$tabbed->addAction('bar', new HTML_QuickForm_Action_Direct());
$tabbed->addAction('baz', new HTML_QuickForm_Action_Direct());

// We actually add these handlers here for the sake of example
// They can be automatically loaded and added by the controller
$tabbed->addAction('jump', new HTML_QuickForm_Action_Jump());
$tabbed->addAction('submit', new HTML_QuickForm_Action_Submit());

// The customized actions
$tabbed->addAction('display', new ActionDisplay());
$tabbed->addAction('process', new ActionProcess());

$tabbed->setDefaults(array(
    'iradYesNoMaybe' => 'M',
    'favLetter'      => array('A' => true, 'Z' => true),
    'favDate'        => array('d' => 1, 'M' => 1, 'Y' => 2001),
    'textOpinion'    => 'Yes, it rocks!'
));

$tabbed->run();


function sgl_header()
{
    $html = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Seagull Framework :: FAQ</title>        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
    <meta http-equiv="Content-Language" content="en" />
    <meta name="ROBOTS" content="ALL" />
    <meta name="Copyright" content="Copyright (c) 2005 Seagull Framework, Demian Turner, and the respective authors" />
    <meta name="Rating" content="General" />
    <meta name="Generator" content="Seagull Framework v0.5.3" />

    <link rel="help" href="http://seagull.phpkitchen.com/docs/" title="Seagull Documentation." />
    
    <style type="text/css" media="screen">
        @import url("http://localhost/seagull/trunk/www/themes/default/css/style.php?navStylesheet=SglDefault_TwoLevel&moduleName=faq");
    </style>
    </head>
<body>

<div id="sgl">
<!-- Logo and header -->
<div id="header">
    <a id="logo" href="http://localhost/seagull/trunk/www" title="Home">
        <img src="http://localhost/seagull/trunk/www/themes/default/images/logo.gif" align="absmiddle" alt="Seagull Framework Logo" /> Seagull Framework
    </a>
</div>

HTML;
    print $html;
}

function sgl_footer()
{
    $html = <<<HTML
<div id="footer">
    Powered by <a href="http://seagull.phpkitchen.com" title="Seagull framework homepage">Seagull Framework</a> v0.5.3<br />
            Execution Time = 122 ms, 9 queries     
    </div>
</body>
</html>
HTML;
    print $html;
}
?>
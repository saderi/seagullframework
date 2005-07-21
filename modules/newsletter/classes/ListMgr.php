<?php
/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Copyright (c) 2005, Demian Turner                                         |
// | All rights reserved.                                                      |
// |                                                                           |
// | Redistribution and use in source and binary forms, with or without        |
// | modification, are permitted provided that the following conditions        |
// | are met:                                                                  |
// |                                                                           |
// | o Redistributions of source code must retain the above copyright          |
// |   notice, this list of conditions and the following disclaimer.           |
// | o Redistributions in binary form must reproduce the above copyright       |
// |   notice, this list of conditions and the following disclaimer in the     |
// |   documentation and/or other materials provided with the distribution.    |
// | o The names of the authors may not be used to endorse or promote          |
// |   products derived from this software without specific prior written      |
// |   permission.                                                             |
// |                                                                           |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS       |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT         |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR     |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT      |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,     |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT          |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,     |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY     |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT       |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE     |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.      |
// |                                                                           |
// +---------------------------------------------------------------------------+
// | Seagull 0.4                                                               |
// +---------------------------------------------------------------------------+
// | ListMgr.php                                                               |
// +---------------------------------------------------------------------------+
// | Author:   Demian Turner <demian@phpkitchen.com>                           |
// +---------------------------------------------------------------------------+
// $Id: ListMgr.php,v 1.7 2005/06/12 18:19:18 demian Exp $

require_once SGL_ENT_DIR  . '/Newsletter.php';
require_once SGL_CORE_DIR . '/Emailer.php';
require_once SGL_MOD_DIR  . '/newsletter/classes/NewsletterMgr.php';
require_once SGL_MOD_DIR  . '/user/classes/DA_User.php';
require_once 'Mail.php';
require_once 'Mail/mime.php';
require_once 'Validate.php';

/**
 * For distributing 'newsletter' type email to users.
 *
 * @package newsletter
 * @author  Demian Turner <demian@phpkitchen.com>
 * @version $Revision: 1.7 $
 * @since   PHP 4.1
 */
class ListMgr extends NewsletterMgr
{
    function ListMgr()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $this->module           = 'newsletter';
        $this->pageTitle        = 'Newsletter List Mgr';
        $this->template         = 'newsletter.html';
        $this->da               = & DA_User::singleton();

        $this->_aActionsMapping =  array(
            'list'   => array('list'),      // Compose newsletter 
            'send' => array('send', 'redirectToDefault'), 
            'addressBook'   => array('addressBook'),           
            'listSubscribers' => array('listSubscribers'),
            'editSubscriber' => array('editSubscriber'),
            'updateSubscriber' => array('updateSubscriber','listSubscribers'),
            'deleteSubscriber' => array('deleteSubscriber','listSubscribers'),            
            'listLists' => array('listLists'),
            'addList' => array('addList'),
            'editList' => array('editList'),
            'updateList' => array('updateList','listLists'),
            'deleteLists' => array('deleteLists','listLists'),
        );
    }


    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        
        $conf = & $GLOBALS['_SGL']['CONF'];
        
        $this->validated    = true;
        $input->error       = array();
        $input->pageTitle   = $this->pageTitle;
        $input->masterTemplate = 'masterLeftCol.html';
        $input->template    = $this->template;
        $input->action      = ($req->get('action')) ? $req->get('action') : 'list';
        $input->submit      = $req->get('submitted');
        $input->from        = $req->get('frmFrom') ? $req->get('frmFrom') : $conf['email']['admin'];
        $input->email       = $req->get('frmEmail');
        $input->subject     = $req->get('frmSubject');
        $input->body        = $req->get('frmBodyName', $allowTags = true);
        $input->oUser       = $req->get('oUser');
       
        $aGroups            = $req->get('frmGroups');
        $input->groups = array();
        $input->roleList    = $this->da->getRoles($excludeAdmin = true);
        
        $input->newsList      = $req->get('frmNewsList') ? $req->get('frmNewsList') : array();
        $input->validNewsList = $this->_getList();
        
        $input->newsletter_id = $req->get('frmID');
        $input->aDelete     = $req->get('frmDelete');
        $input->totalItems  = $req->get('totalItems');
        $input->sortBy      = SGL_Util::getSortBy($req->get('frmSortBy'), SGL_SORTBY_USER);
        $input->sortOrder   = SGL_Util::getSortOrder($req->get('frmSortOrder'));

        $aErrors = array();
           
        if ($input->submit) {
            $v = & new Validate();

            if (isset($input->from)) {
                    if (!$v->email($input->from)) {
                        $aErrors['from'] = 'incorrectly formatted email';
                    }
                } else {
                    $aErrors['from'] = 'Please fill in the email field';
            }

            // Validation for subscriber edit
            if (isset($input->action) and $input->action == 'updateSubscriber') {
                $input->template = 'editSubscriber.html';
                if (isset($input->oUser['email'])) {
                    if (!$v->email($input->oUser['email'])) {
                        $aErrors['email'] = 'incorrectly formatted email';
                    }
                } else {
                    $aErrors['email'] = 'Please fill in the email field';
                }
                
                if (!(isset($input->oUser['newsletter_id']) and $input->oUser['newsletter_id'] > 0)) {
                    SGL::raiseError('Incorrect parameter passed to ' . 
                    __CLASS__ . '::' . __FUNCTION__, SGL_ERROR_INVALIDARGS);
                }
            }
            
            // Validate for list edit 
            if (isset($input->action) and $input->action == 'updateList') {
                $input->template = 'editList.html';
                if (isset($input->oUser['list']) and strlen(trim($input->oUser['list'])) > 0) {
                  
                    if (strlen($input->oUser['list']) > 32) {
                        $aErrors['list'] = 'Max lenght for list field is 32 characters';
                    }
                    if (!empty($input->oUser['list']) and preg_match("([^\w\s])",$input->oUser['list'])) {
                        $aErrors['list'] = 'Invalid input supplied to list name';
                    }
                    if (!$this->_checkForDuplicateList($input->oUser['list'], $input->oUser['newsletter_id'])) {
                        $aErrors['list'] = 'This list name already exists';
                    }
                    
                } else {
                    $aErrors['list'] = 'Please fill in the list field';
                }
                
                if (isset($input->oUser['name']) and strlen($input->oUser['name']) > 128) {
                        $aErrors['name'] = 'Max lenght for description field is 128 characters';
                }                
                
                if (!(isset($input->oUser['newsletter_id']) and $input->oUser['newsletter_id'] > 0)) {
                    SGL::raiseError('Incorrect parameter passed to ' . 
                    __CLASS__ . '::' . __FUNCTION__, SGL_ERROR_INVALIDARGS);
                }
            }

            // Validation for sending messages
            if (isset($input->action) and $input->action == 'send') {

                if (empty($input->subject)) {
                    $aErrors['subject'] = 'Please fill in the subject field';
                }
                if (empty($input->body)) {
                    $aErrors['body'] = 'Please fill in the body field';
                }
                  
                $groupSelected = false;
                // Lists validation
                if (is_array($input->newsList) and count($input->newsList)) {
                    foreach($input->newsList as $listID) {
                        if (!array_key_exists($listID,$input->validNewsList)) {
                           // Would only happen if someone was hacking the form:
                           $aErrors['newslist'] = 'You managed to choose an invalid newsletter list';
                           break; 
                        } else {
                            $groupSelected = true;
                        }
                    }
                }
    
                // Groups validation
                if (count($aGroups)) {
                    foreach ($aGroups as $hash => $roleId) {
                        if (isset($input->roleList[$roleId])) {
    
                            // Remember all the valid roleIds from the form
                            array_push($input->groups, $roleId);
                            $groupSelected = true;
                        } else {
    
                            // Would only happen if someone was hacking the form:
                            $aErrors['group'] = 'You managed to choose an invalid group';
                        }
                    }
                
                }
                
                if (empty($input->email)) {
                        //  if no groups/roles have been selected, make sure there's at least one email address
                        if (!$groupSelected) {
                            $aErrors['email'] = 'Please include at least one email address';
                        }
                    } else {
                        $aRecipients = explode(';', $input->email);
                        $aRecipientsWithNulls = array_filter($aRecipients, 'strlen');
                        foreach ($aRecipientsWithNulls as $email) {
                            if (!$v->email($email)) {
                                $aErrors['email'] = 'incorrectly formatted email';
                                break;
                            }
                        }
                }
            }
            
        }
        
        //  if errors have occured
        if (is_array($aErrors) && count($aErrors)) {
            SGL::raiseMsg('Please fill in the indicated fields');
            $input->error = $aErrors;
            $this->validated = false;
        }
    }

    
    /**
    * Display the compose form 
    *
    * @access public
    *
    */
    function _list(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $output->wysiwyg     = true;        
        $output->template = 'newsletter.html';
    }

    /**
    * Display the list of subscribers
    *
    * @access public
    *
    */
    function _listSubscribers(&$input, &$output) 
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $conf = & $GLOBALS['_SGL']['CONF'];
        $output->template = 'listSubscribers.html';
        $input->pageTitle = 'Newsletter List Mgr :: Subscribers';
        
        $orderBy_query = '';
        $allowedSortFields = array('newsletter_id','list','name','email','status','action_request','last_updated','date_created');
        if (isset($input->sortBy) and strlen($input->sortBy) > 0 
           and isset($input->sortOrder) and strlen($input->sortOrder) > 0 
           and in_array($input->sortBy, $allowedSortFields)) {
                $orderBy_query = 'ORDER BY ' . $input->sortBy . ' ' . $input->sortOrder ; 
        }
        
        $dbh = & SGL_DB :: singleton();
        $query = "SELECT * FROM {$conf['table']['newsletter']} WHERE status <> 9 ".$orderBy_query;;
          
        $limit = 5 * $_SESSION['aPrefs']['resPerPage'];
        $pagerOptions = array ('mode' => 'Sliding', 'delta' => 3, 'perPage' => $limit, 'totalItems' => $input->totalItems);
        $aPagedData = SGL_DB :: getPagedData($dbh, $query, $pagerOptions);
        
        if (!DB::isError($aPagedData)) {   

            if (is_array($aPagedData['data']) && count($aPagedData['data'])) {
                $output->pager = ($aPagedData['totalItems'] <= $limit) ? false : true;
            }

            $output->totalItems = $aPagedData['totalItems'];
            $output->aPagedData = $aPagedData;
        } 
    }

    /**
    * Display the list of subscribers
    *
    * @access public
    *
    */
    function _editSubscriber(& $input, & $output) 
    {
        SGL :: logMessage(null, PEAR_LOG_DEBUG);
        $output->template = 'editSubscriber.html';
        $input->pageTitle = 'Newsletter List Mgr :: Subscriber Edit';
        
        $oUser = & new DataObjects_Newsletter();
        $oUser->get($input->newsletter_id);
                
        $output->oUser = (array) $oUser;     
    }

    /**
    * Update subscriber data
    *
    * @access public
    *
    */
    function _updateSubscriber (& $input, & $output) 
    {
        SGL :: logMessage(null, PEAR_LOG_DEBUG);
        $output->template = 'editSubscriber.html';
        $input->pageTitle = 'Newsletter List Mgr :: Subscriber Edit';
        
        $input->oUser = (object) $input->oUser;
        $input->oUser->action_request = $input->oUser->action_request == 'empty' ? '' : $input->oUser->action_request;
        
        $oUser = & new DataObjects_Newsletter();
        $oUser->get($input->oUser->newsletter_id);
        unset($input->oUser->newsletter_id);
        unset($input->oUser->list);
        $noRows = $oUser->find();
        $oUser->setFrom($input->oUser);
        if ($noRows == 0) {
            $success = $oUser->input();
        } else {
            $success = $oUser->update();
        }
        
        if ($success === false) {
            SGL::raiseError('Incorrect parameter passed to ' . 
                __CLASS__ . '::' . __FUNCTION__, SGL_ERROR_INVALIDARGS);
        } else {
            SGL::raiseMsg('Subscriber updated successfully');
        }
    }


    /**
    * Delete/unsubscribe a subscriber
    *
    * @access public
    *
    */
    function _deleteSubscriber (& $input, & $output) 
    {
        SGL :: logMessage(null, PEAR_LOG_DEBUG);
        if (is_array($input->aDelete)) {
            foreach ($input->aDelete as $index => $newsletter_id) {
                $oUser = & new DataObjects_Newsletter();
                $oUser->get($newsletter_id);
                $oUser->delete();
                unset ($oUser);
            }
        } else {
            SGL :: raiseError('Incorrect parameter passed to '.__CLASS__.'::'.__FUNCTION__, SGL_ERROR_INVALIDARGS);
        }
        
        SGL :: raiseMsg('Subscriber deleted successfully');
    }


    /**
    * Display the news lists
    *
    * @access public
    *
    */
    function _listLists(&$input, &$output) 
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $conf = & $GLOBALS['_SGL']['CONF'];
        $output->template = 'listLists.html';
        $input->pageTitle = 'Newsletter List Mgr :: Lists';
        
        $orderBy_query = '';
        $allowedSortFields = array('newsletter_id','list','name','last_updated','date_created');
        if (isset($input->sortBy) and strlen($input->sortBy) > 0 
           and isset($input->sortOrder) and strlen($input->sortOrder) > 0 
           and in_array($input->sortBy, $allowedSortFields)) {
                $orderBy_query = 'ORDER BY ' . $input->sortBy . ' ' . $input->sortOrder ; 
        }
        
        $dbh = & SGL_DB :: singleton();
        $query = "SELECT * FROM {$conf['table']['newsletter']} WHERE status = 9 ".$orderBy_query;;
          
        $limit = 5 * $_SESSION['aPrefs']['resPerPage'];
        $pagerOptions = array ('mode' => 'Sliding', 'delta' => 3, 'perPage' => $limit, 'totalItems' => $input->totalItems);
        $aPagedData = SGL_DB :: getPagedData($dbh, $query, $pagerOptions);
        
        if (!DB :: isError($aPagedData)) {   

            if (is_array($aPagedData['data']) && count($aPagedData['data'])) {
                $output->pager = ($aPagedData['totalItems'] <= $limit) ? false : true;
            }
            $output->totalItems = $aPagedData['totalItems'];
            $output->aPagedData = $aPagedData;
        } 
    }


    /**
    * Add a newslist
    *
    * @access public
    *
    */
    function _addList(& $input, & $output) 
    {
        SGL :: logMessage(null, PEAR_LOG_DEBUG);
        $output->template = 'editList.html';
        $input->pageTitle = 'Newsletter List Mgr :: List Add';
        
        $oUser = & new DataObjects_Newsletter();
        $dbh = $oUser->getDatabaseConnection();
        $oUser->newsletter_id = $dbh->nextId('newsletter');
                
        $output->oUser = (array) $oUser;  
    }


    /**
    * Retrieve list data and populate the edit form
    *
    * @access public
    *
    */
    function _editList(& $input, & $output) 
    {
        SGL :: logMessage(null, PEAR_LOG_DEBUG);
        $output->template = 'editList.html';
        $input->pageTitle = 'Newsletter List Mgr :: List Edit';
        
        $oUser = & new DataObjects_Newsletter();
        $oUser->get($input->newsletter_id);
                
        $output->oUser = (array) $oUser;  
    }


    /**
    * Update list data
    *
    * @access public
    *
    */
    function _updateList(& $input, & $output) 
    {
        SGL :: logMessage(null, PEAR_LOG_DEBUG);        
        $output->template = 'editList.html';
        $input->pageTitle = 'Newsletter List Mgr :: List Edit';
        $conf = & $GLOBALS['_SGL']['CONF'];
        
        $input->oUser = (object) $input->oUser;
        $oUser = & new DataObjects_Newsletter();
        $oUser->get($input->oUser->newsletter_id);
        $oldName = $oUser->list;
        $noRows = $oUser->find();
        $oUser->setFrom($input->oUser);
        $oUser->status = 9;
        $oUser->last_updated = SGL::getTime();  
        if ($noRows == 0) {
            $oUser->date_created = SGL::getTime();
            $oUser->newsletter_id = $input->oUser->newsletter_id;
            $success = $oUser->insert();
        } else {
            $success = true;
            if ($oldName != $oUser->list) {
                // List name has changed. Change the subscribed users too;
                $dbh = $oUser->getDatabaseConnection();
                $query = "UPDATE {$conf['table']['newsletter']} SET list='".$oUser->list."'WHERE list='".$oldName."' AND status<>9";
                $result = $dbh->query($query);
                if (is_a($result, 'PEAR_Error')) {
                    $success = false;
                }        
            }
            $success = $success && $oUser->update();
        }
        
        if ($success === false) {
            SGL::raiseError('Incorrect parameter passed to ' . 
                __CLASS__ . '::' . __FUNCTION__, SGL_ERROR_INVALIDARGS);
        } else {
            SGL :: raiseMsg('List updated successfully');
        }
    }


    /**
    * Delete a news list
    *
    * @access public
    *
    */
    function _deleteLists(& $input, & $output) 
    {
        SGL :: logMessage(null, PEAR_LOG_DEBUG);
              
        if (is_array($input->aDelete)) {
            foreach ($input->aDelete as $index => $newsletter_id) {
                $oUser = & new DataObjects_Newsletter();
                $oUser->whereAdd("list = '".$input->validNewsList[$newsletter_id]['name']."'");
                $oUser->delete(DB_DATAOBJECT_WHEREADD_ONLY);
                unset ($oUser);
            }
        } else {
            SGL::raiseError('Incorrect parameter passed to '.__CLASS__.'::'.__FUNCTION__, SGL_ERROR_INVALIDARGS);
        }
        SGL::raiseMsg('List deleted successfully');
    }


    /**
    * Send e-mail function
    *
    * @access public
    *
    */
    function _send(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $conf = & $GLOBALS['_SGL']['CONF'];
        $output->template = 'newsletter.html';

        // Add group emails to the address list
        $sGroupRecipients = '';
        foreach ($input->groups as $roleId) {
            $sAllEmailsByRole = $this->da->getEmailsByRole($roleId);
            if ($sAllEmailsByRole != null) {
                $sGroupRecipients .= $sAllEmailsByRole . ';';
            }
        }
        
        // Add newsletter emails to the address list
        $sListRecipients = '';
        if (is_array($input->newsList) and count($input->newsList)) {
            foreach($input->newsList as $listID) {
            $aListUsers = $this->_getUsersByList($input->validNewsList[$listID]['name']);
                foreach ($aListUsers as $user) {
                    $sListRecipients .= $user['email'] . ';';
                }
            }
        }
        
        $tmpRecipients = explode(';', $sGroupRecipients . $sListRecipients . $input->email);
        $aRecipients = array();

        // Remove empty and duplicate emails
        foreach($tmpRecipients as $tmpEmail) {
            if (strlen($tmpEmail) > 0 AND !in_array($tmpEmail, $aRecipients)) {
                $aRecipients[] = $tmpEmail;
            }
        }
        
        if (count($aRecipients) < 1) {
            SGL::raiseError('Problem sending email: no recipients', SGL_ERROR_EMAILFAILURE);
            $success = false;
        }
 
        //  TODO: Use BCC to send multiple emails at once?
        foreach ($aRecipients as $email) {
            $headers['From'] = $conf['email']['admin'];
            $headers['Subject'] = $input->subject;
            $crlf = SGL_String::getCrlf();
            $mime = & new Mail_mime($crlf);
            $mime->setHTMLBody($input->body);
            $body = $mime->get();
            $hdrs = $mime->headers($headers);
            $mail = & SGL_Emailer::factory();
            $success = $mail->send($email, $hdrs, $body);
        }
        if ($success) {
            //  redirect on success
            SGL::raiseMsg('Newsletter sent successfully');
        } else {
            SGL::raiseError('Problem sending email', SGL_ERROR_EMAILFAILURE);
        }
    }   


    /**
    * Display address book data
    *
    * @access public
    *
    */
    function _addressBook(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aRoles = $this->da->getRoles($excludeAdmin = true);

        $output->groups = SGL_Output::generateCheckboxList($output->roleList, $input->groups, 'frmGroups[]');
        foreach ($aRoles as $roleId => $roleName) {
            $aAllEmailsByRole[$roleName] = $this->da->getEmailsByRole($roleId);

            //  remove groups with no members
            if ($aAllEmailsByRole[$roleName] == null) {
                unset($aAllEmailsByRole[$roleName]);
            }
        }
        $output->groupList = $aAllEmailsByRole;
        $output->wysiwyg = false;
        $output->masterTemplate = 'masterBlank.html';
        $output->template='newletterAddressBook.html';
    }
    
    
    /**
    * Returns all the subscribers of one list
    *
    * @access   private
    * @author   Benea Rares <rbenea@bluestardesign.ro>
    * @param    string  $listName   List name
    * @return   array   $ret        list of subscibers or false on error
    * 
    */
    function _getUsersByList($listName)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        
        if (empty($listName)) {
            SGL :: raiseError('Incorrect parameter passed to '.__CLASS__.'::'.__FUNCTION__, SGL_ERROR_INVALIDARGS);
            return false;
        }
        
        $conf = & $GLOBALS['_SGL']['CONF'];
        $dbh = & SGL_DB :: singleton();
        
        $query = "SELECT * FROM {$conf['table']['newsletter']} WHERE list='$listName' AND status=0";
        
        $result = $dbh->query($query);
        if (is_a($result, 'PEAR_Error')) {
            return false;
        }
        
        $ret = array();
        while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            $ret[] = $row;
        }
        
        return $ret;
    }


    /**
    * Checks if exists an list with same name but different ID
    *
    * @access   private
    * @author   Benea Rares <rbenea@bluestardesign.ro>
    * @param    string      $listName   List name
    * @param    integer     $listID     List ID
    * @return   boolean                 true if NOT exists, false if exists or on error
    * 
    */
    function _checkForDuplicateList($listName, $listId) {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        
        if (strlen($listName) < 0 or $listId < 1) {
            SGL :: raiseError('Incorrect parameter passed to '.__CLASS__.'::'.__FUNCTION__, SGL_ERROR_INVALIDARGS);
            return false;
        }
        
        $conf = & $GLOBALS['_SGL']['CONF'];
        $dbh = & SGL_DB :: singleton();
        
        $query = "SELECT * FROM {$conf['table']['newsletter']} WHERE list='$listName' AND newsletter_id<>'$listId' AND status=9";
        
        $result = $dbh->query($query);
        if (is_a($result, 'PEAR_Error')) {
            return false;
        }
        
        if ($result->numRows() > 0) {
            return false;
        }
        
        return true;
    }

}
?>
/******************************************************************************/
/*                                  LAYOUT CSS                                */
/******************************************************************************/

/*
==========================General=============================*/
body, h1, h2, h3, h4, p, ul, li {
    margin: 0;
    padding: 0;
}
body {
    font-size: <?php echo $fontSize ?>;
    font-family: <?php echo $fontFamily ?>;
    line-height: 140%;
    margin: 0;
    padding: 0;
    color: <?php echo $primaryText ?>;
    background-color: <?php echo $tertiaryDark ?>;
    text-align: center;
}
ul {
    list-style: none;
}
p {
    margin-bottom: 0.5em;
}
a {
    color: <?php echo $linkColor ?>;
    text-decoration: <?php echo $linkDecoration ?>;
}
a:hover {
    color: <?php echo $linkHoverColor ?>;
    text-decoration: <?php echo $linkHoverDecoration ?>;
}
img {
    border: none;
}

/*
=========================Messages=============================*/
.message {
    text-align: center;
    font-size: 0.9em;
    z-index: 1;
}
.message div {
    width: 60%;
    margin: 0 auto 15px;
    padding: 5px 0;
    background-color: <?php echo $tertiaryLightest ?>;
    background-position: 0 50%;
    background-repeat: no-repeat;
    border-width: 1px;
    border-style: solid;
    -moz-border-radius: 0.4em;
}
.infoMessage {
    background-image: url('<?php echo $baseUrl ?>/images/22/dialog_info.gif');
    border-color: <?php echo $infoMessage ?>;
    color: <?php echo $infoMessage ?>;
}
.errorMessage {
    background-image: url('<?php echo $baseUrl ?>/images/22/dialog_error.gif');
    border-color: <?php echo $errorMessage ?>;
    color: <?php echo $errorMessage ?>;
}
.error {
    color: <?php echo $errorMessage ?>;
}
.warningMessage {
    background-image: url('<?php echo $baseUrl ?>/images/22/dialog_warning.gif');
    border-color: <?php echo $warningMessage ?>;
    color: <?php echo $warningMessage ?>;
}

/*
=======================Buttons like===========================*/
a.sgl-button, input.sgl-button {
    margin: 0;
    padding: 2px 4px;
    background: url('<?php echo $baseUrl ?>/images/backgrounds/bg_buttons_blue.gif') 0 50% repeat-x;
    border: none;
    border-style: solid;
    border-width: 2px;
    border-color: <?php echo $primaryLightest ?> <?php echo $primary ?> <?php echo $primary ?> <?php echo $primaryLightest ?>;
    color: <?php echo $tertiaryDarkest ?>;
    font-size: 1em;
    text-transform: capitalize;
}
input.sgl-button[disabled], input.sgl-button[disabled]:hover {
    background: <?php echo $tertiary ?>;
    border-width: 2px;
    border-color: <?php echo $tertiary ?> <?php echo $tertiaryDarkest ?> <?php echo $tertiaryDarkest ?> <?php echo $tertiary ?>;
    color: <?php echo $tertiaryDarkest ?>;
}
a.sgl-button:hover, input.sgl-button:hover, input.sfhover {
    border-width: 2px;
    border-color: <?php echo $primary ?> <?php echo $primaryLightest ?> <?php echo $primaryLightest ?> <?php echo $primary ?>;
    color: <?php echo $tertiaryDarkest ?>;
}

/*
======================Global layaout==========================*/
#outer-wrapper {
    min-width: 740px;
    max-width: 1280px;
    margin: 0 auto;
    text-align: left;
}
#header {
    position: relative;
}
#inner-wrapper {
    clear: both;
    padding-top: 10px;
    border-top: 1px solid <?php echo $borderLight ?>;
}
#container {

}
#left-column {
    float: left;
    width: 200px;
}
#middle-column {
    margin-left: 200px;
    margin-right: 5px;
}
#main {
    float: left;
    width: 100%;
    font-size: 1em;
    border: 1px solid <?php echo $borderDark ?>;
    -moz-border-radius: 0.4em;
}
#content {
    clear: both;
    padding: 5px 5px 0;
    background: <?php echo $tertiaryLight ?>; /* FIXME tertiaryLight? */
    -moz-border-radius: 0 0 0.4em 0.4em;
    border-top: 1px solid <?php echo $borderLight ?>;
    padding-bottom: 40px; /* TO REMOVE */
}
#footer {
    margin: 5px 0;
    text-align: center;
    background: <?php echo $tertiary ?>;
}

/*
=========================Header===============================*/
#header-left {
    height: 50px;
    background: <?php echo $primary ?> url('<?php echo $baseUrl ?>/images/backgrounds/bg_header_blue.gif') repeat-x;
    border-bottom: 2px solid <?php echo $secondary ?>;
}
#header-right {
    height: 20px;
    line-height: 20px;
    background-color: <?php echo $tertiary ?>;
    border-top: 1px solid <?php echo $borderLight ?>;
    border-bottom: 1px solid <?php echo $borderDark ?>;
    text-align: right;
}
#header .info {
    height: 20px;
    line-height: 20px;
    padding: 0 10px;
}
#header h1, #header h1 a {
    float: left;
    width: 270px;
    height: 50px;
    margin: 0;
    padding: 0;
}
#header h1 {
    background: url('<?php echo $baseUrl ?>/images/logo.png') no-repeat 10px 50%;
}
#header h1 a {
    position: relative;
    height: 50px;
    line-height: 50px;
    text-indent: 90px;
    color: <?php echo $tertiaryLightest ?>;
}
#header h1 a span {
    position: absolute;
    left: -9999px
}

/*
====================Left column block=========================*/
#block-container {
    min-height: 300px;
    margin: 0 5px;
    padding-bottom: 3em;
    background-color: <?php echo $tertiary ?>;
    background-image: url('<?php echo $baseUrl ?>/images/backgrounds/bg_leftcol.jpg');
    background-position: top left;
    background-repeat: repeat-x;
    border: 1px solid <?php echo $borderDark ?>;
    -moz-border-radius: 0.4em;
}
.block-header {
    margin: 0;
    height: 30px;
    line-height: 30px;
    background: <?php echo $tertiaryLight ?>;
    font-weight: bold;
    font-size: 1.1em;
    text-align: center;
    color: buttonshadow;
    border-bottom: 1px solid <?php echo $borderDark ?>;
    -moz-border-radius: 0.4em 0.4em 0 0;
}
.block-content {
    border-bottom: 1px solid <?php echo $borderLight ?>;
}

/*
========================Main block============================*/
#manager-infos {
    float: left;
    width: 100%;
    background: <?php echo $tertiaryLighter ?>;
    border-bottom: 1px solid <?php echo $borderDark ?>;
    -moz-border-radius: 0.4em 0.4em 0 0;
}
#manager-infos h1 {
    float: left;
    height: 30px;
    line-height: 30px;
    text-indent: 2em;
    color: <?php echo $tertiaryDarkest ?>;
    font-size: 1.1em;
}
a#module-conf {
    float: right;
    margin-right: 10px;
    padding-left: 25px;
    height: 30px;
    line-height: 30px;
    background: <?php echo $tertiary ?>;
    background: url('<?php echo $baseUrl ?>/images/22/action_config.gif') 0 50% no-repeat;
    color: <?php echo $primary ?>;
}
#breadcrumbs {
    float: left;
    width: 100%;
    background: <?php echo $tertiary ?>;
    border-top: 1px solid <?php echo $borderLight ?>;
    border-bottom: 1px solid <?php echo $borderDark ?>;
}
#breadcrumbs p {
    text-indent: 2em;
}
#breadcrumbs a  {
    padding-right: 20px;
    color: <?php echo $primary ?>;
}
#manager-actions {
    float: left;
    width: 100%;
    height: 32px;
    padding: 1px 0;
    background: <?php echo $tertiary ?>;
    border-top: 1px solid <?php echo $borderLight ?>;
    border-bottom: 1px solid <?php echo $borderDark ?>;
}
body * #manager-actions {
    padding: 1px 0 0;
}
#manager-actions span {
    float: left;
    text-indent: 2em;
    padding-right: 10px;
    height: 30px;
    line-height: 30px;
    font-weight: bold;
    color: <?php echo $tertiaryDarkest ?>;
}
#manager-actions a {
    float: left;
    display: block;
    margin-right: 0.5em;
    padding: 0 4px 0 28px;
    height: 28px;
    line-height: 28px;
    border: 1px solid <?php echo $tertiary ?>;
    color: <?php echo $tertiaryDarkest ?>;
    text-decoration: none;
    /* -- See below for each action backgroud image
    -----------------------------------------------*/
}
#manager-actions a:hover {
    border-style: solid;
    border-width: 1px;
    border-color: <?php echo $tertiaryDarkest ?>;
}
#content-header {

}
#content-header h2 {
    margin: 5px 0 10px;
    font-size: 1.5em;
    font-weight: normal;
    color: <?php echo $primary ?>;
    text-align: center;
}
/*
======================No forms layout=========================*/
div.fieldsetlike { /*
--------------------- as some pages don't use forms/fieldsets
- e.g. module/overview, we have to put data in a fieldset like
- div to have same render ------------------------------------*/
    padding: 0 5px;
}
div.fieldsetlike h3 {
    margin-bottom: 0.5em;
    font-size: 1.2em;
}
/*
===================Forms default layout=======================*/
form {
    float: left;
    width: 100%; /* FIXME
    ------2 lines-- determine if could be removed */
    margin: 0;
    padding: 0;
}
fieldset {
    margin: 0 0 1em;
    padding: 10px;
    border: 1px solid <?php echo $borderDark ?>;
}
select, input, textarea {
    font-size: 1em;
    z-index: 1;
}
html>body select, html>body input, html>body textarea {
    border: 1px solid <?php echo $primary ?>;
}
select:focus, input:focus, textarea:focus {
    background: <?php echo $primaryLightest ?>;

}
fieldset.noBorder {
    padding: 0;
    border: none;
}
fieldset.inside { /*
    ---------------------------- also for pages without form (e.g. module/overview) */
    background: <?php echo $tertiaryLightest ?>;
}
fieldset.options {
    clear: left;
    background: <?php echo $tertiaryLightest ?>;
    border-top: none;
}
form h3, fieldset h3 {
    margin-bottom: 0.5em;
    font-size: 1.2em;
}
fieldset p {
    clear: left;
    line-height: 1.8;
    margin: 0.5em 0 0.5em;
}
form p label {
    float: left;
    text-align: right;
    padding-right: 20px;
    color: <?php echo $primaryDark ?>;
}
form p.col label {
    padding-right: 1em;
}

/*
==================Tables default layout=======================*/
table {
    background: #fff;
    border-collapse: collapse;
    border: 1px solid <?php echo $tertiaryLightest ?>;
    font-family: helvetica;
}
tr {
    height: 24px;
    line-height: 24px;
    vertical-align: middle;
}
tr img, tr input {
    vertical-align: middle;
}
tr th, tr td {
    text-align: center;
    border: none;
}
tr th {
    color: #afafaf;
    font-weight: bold;
}
thead tr {
    height: 20px;
    line-height: 20px;
    background: <?php echo $primary; ?>;
    color: <?php echo $tertiaryLightest ?>;
}
thead tr.infos, tfoot tr.infos {
    height: 16px;
    line-height: 16px;
    background: <?php echo $tertiaryDark ?>;
    font-size: 10px;
    color: <?php echo $primary; ?>;
}
thead th, thead th a {
    color: <?php echo $tertiaryLightest ?>;
}
thead tr a:hover {
    color: <?php echo $primaryDark ?>;
}
tr.backLight {
    background: <?php echo $tableRowLight ?>;
    border-bottom: 1px solid <?php echo $primaryLight ?>;
}
tr.backDark {
    background: <?php echo $tableRowDark ?>;
    border-bottom: 1px solid <?php echo $primaryLight ?>;
}
tr.backHighlight {
    background: <?php echo $primaryLightest ?>;
}

/*
==========================Tip boxes===========================*/
span.tipOwner, label.tipOwner, input.tipOwner {
    position: relative;
    cursor: help;
}
label.tipOwner {
    background: url('<?php echo $baseUrl ?>/images/tooltip.gif') 98% 50% no-repeat;
}
span.tipOwner span.tipText, label.tipOwner span.tipText, input.tipOwner span.tipText {
    display: none;
    position: absolute;
    top: 2em;
    left: 100%;
    border: 1px solid <?php echo $borderDark ?>;
    background-color: <?php echo $primaryBackground ?>;
    color: <?php echo $primaryText ?>;
    text-align: center;
    line-height: normal;
    width: 20em;
    padding: 2px 5px;
    -moz-opacity: 1;
    z-index: 100;
    <?php if ($browserFamily == 'MSIE') {?>
    filter: alpha(opacity=100);
    filter: progid: DXImageTransform.Microsoft.Alpha(opacity=100);
    <?php } ?>
}
span.tipOwner:hover span.tipText, label.tipOwner:hover span.tipText, input.tipOwner:hover span.tipText {
    display: block;
}
<?php if ($browserFamily == 'MSIE') {?>
/* IE javascript workaround */
span.tipOwner, label.tipOwner, input.tipOwner {
    behavior: url(<?php echo $baseUrl ?>/css/tooltipHover.htc);
}
<?php } ?>
/*-- Special TipText boxes ----------------------------------*/
span#becareful {
    top: -35px;
    left: -3.5em;
    width: 6em;
    padding: 5px;
    background: #fff;
    border: 1px solid #ff3300;
    color: #ff3300;
    z-index: 150;
}

/*
===================Manager-actions images=====================*/
/*-- Each action link (<a> tag) has a standard "action" class name
  -- plus a specific <action-type> class name e.g. "add", "edit",...
  -- to define which image to use. This allows to change assigned
  -- images in a single location : here. -------------------------*/
a.action {
    background-position: 3px 50%;
    background-repeat: no-repeat;
}
a.action:hover {
    background-color: <?php echo $tertiaryLightest ?>;
}
a.add {
    background-image: url('<?php echo $baseUrl ?>/images/22/action_add.gif');
}
a.edit {
    background-image: url('<?php echo $baseUrl ?>/images/22/action_edit.gif');
}
a.delete {
    background-image: url('<?php echo $baseUrl ?>/images/22/action_delete.gif');
}
a.save {
    background-image: url('<?php echo $baseUrl ?>/images/22/action_save.gif');
}
a.cancel {
    background-image: url('<?php echo $baseUrl ?>/images/22/action_cancel.gif');
}
a.undo {
    background-image: url('<?php echo $baseUrl ?>/images/22/action_undo.gif');
}
a.upload {
    background-image: url('<?php echo $baseUrl ?>/images/22/action_upload.gif');
}

/*
======================CategoryNav Block=======================*/
div#categorySelect {
    float: left;
    width: 20%;
}
a.catSelect {
    margin-left: 1em;
    padding: 2px 0 2px 20px;
    background: url('<?php echo $baseUrl ?>/images/16/folder.gif') 0 80% no-repeat;
}
a.catSelect:hover {
    background: url('<?php echo $baseUrl ?>/images/16/folder_open.gif') 0 80% no-repeat;
    text-decoration: none;
    cursor: pointer;
}
div#categoryNav {
    display: none;
    position: absolute;
    width: 200px;
    background: <?php echo $tertiaryLightest ?>;
    border-style: solid;
    border-color: <?php echo $primary ?>;
    border-width: 1px 2px 2px 1px;
    z-index: 100;
    <?php if ($browserFamily == 'MSIE') {?>
    filter: alpha(opacity=90);
    <?php } else { ?>
    -moz-opacity: 0.9;
    <?php } ?>
}

div.close {
    border-bottom: 1px solid <?php echo $primary ?>;
    text-align: right;
}
div.close span {
    padding-right: 20px;
    background: url('<?php echo $baseUrl ?>/images/close.gif') 92% 50% no-repeat;
    color: <?php echo $primary ?>;
    cursor: pointer;
}

/*
========================Options Links=========================*/
#optionsLinks {
    float:left;
    width:100%;
    background: url('<?php echo $baseUrl ?>/images/backgrounds/bg_tabs.gif') repeat-x left bottom;
    font-size: 0.9em;
}
#optionsLinks ul {
    padding:10px 10px 0;
}
#optionsLinks li {
    float:left;
    width: auto;
    background: url('<?php echo $baseUrl ?>/images/backgrounds/tab_right.gif') no-repeat right top;
}
#optionsLinks li.current {
    background-image: url('<?php echo $baseUrl ?>/images/backgrounds/tab_right_on.gif');
}
#optionsLinks a {
    display:block;
    background: url('<?php echo $baseUrl ?>/images/backgrounds/tab_left.gif') no-repeat left top;
    padding:5px 15px 4px;
}
#optionsLinks li.current a {
    background-image: url('<?php echo $baseUrl ?>/images/backgrounds/tab_left_on.gif');
    padding-bottom:5px;
}

/*
========================Miscellaneous=========================*/
.floatLeft {
    float: left;
}
.floatRight {
    float: right;
}
.clear {
    clear: both;
}
.spacer {
    clear: both;
    visibility: hidden;
    line-height: 1px;
}
.left {
    text-align: left;
}
.right {
    text-align: right;
}
.center {
    text-align: center;
}
.hide {
    display: none;
}
.full {
    width: 100%;
}
.noBg {
    background: none;
}
.pager {
    white-space: nowrap;
    font-size: 1.1em;
}
.pager .currentPage {
    font-weight: bold;
    padding: 0 1em;
    color: <?php echo $primaryDark ?>;
    font-weight: bold;
}
.pager a {
    padding: 0 1em;
    color: <?php echo $primary ?>;
    font-weight: bold;
}
.pager a:hover {
    background: <?php echo $primaryDark ?>;
    color: <?php echo $tertiaryLightest ?>;
}
<?php
/**
 * /assets/add/registrar-account.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2020 Greg Chetcuti <greg@chetcuti.com>
 *
 * Project: http://domainmod.org   Author: http://chetcuti.com
 *
 * DomainMOD is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DomainMOD. If not, see
 * http://www.gnu.org/licenses/.
 *
 */
?>
<?php
require_once __DIR__ . '/../../_includes/start-session.inc.php';
require_once __DIR__ . '/../../_includes/init.inc.php';
require_once DIR_INC . '/config.inc.php';
require_once DIR_INC . '/software.inc.php';
require_once DIR_ROOT . '/vendor/autoload.php';

$deeb = DomainMOD\Database::getInstance();
$system = new DomainMOD\System();
$layout = new DomainMOD\Layout();
$time = new DomainMOD\Time();
$form = new DomainMOD\Form();
$sanitize = new DomainMOD\Sanitize();
$unsanitize = new DomainMOD\Unsanitize();
$validate = new DomainMOD\Validate();

require_once DIR_INC . '/head.inc.php';
require_once DIR_INC . '/debug.inc.php';
require_once DIR_INC . '/settings/assets-add-registrar-account.inc.php';

$system->authCheck();
$system->readOnlyCheck($_SERVER['HTTP_REFERER']);
$pdo = $deeb->cnxx;

$new_owner_id = (int) $_POST['new_owner_id'];
$new_registrar_id = (int) $_POST['new_registrar_id'];
$new_email_address = $sanitize->text($_POST['new_email_address']);
$new_username = $sanitize->text($_POST['new_username']);
$new_password = $sanitize->text($_POST['new_password']);
$new_api_app_name = $sanitize->text($_POST['new_api_app_name']);
$new_api_key = $sanitize->text($_POST['new_api_key']);
$new_api_secret = $sanitize->text($_POST['new_api_secret']);
$new_api_ip_id = (int) $_POST['new_api_ip_id'];
$new_reseller = (int) $_POST['new_reseller'];
$new_reseller_id = $sanitize->text($_POST['new_reseller_id']);
$new_notes = $sanitize->text($_POST['new_notes']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($validate->text($new_username) && $new_owner_id !== 0 && $new_registrar_id !== 0) {

        $stmt = $pdo->prepare("
            INSERT INTO registrar_accounts
            (owner_id, registrar_id, email_address, username, `password`, reseller, reseller_id, api_app_name,
             api_key, api_secret, api_ip_id, notes, created_by, insert_time)
            VALUES
            (:new_owner_id, :new_registrar_id, :new_email_address, :new_username, :new_password, :new_reseller,
             :new_reseller_id, :new_api_app_name, :new_api_key, :new_api_secret, :new_api_ip_id, :new_notes,
             :created_by, :timestamp)");
        $stmt->bindValue('new_owner_id', $new_owner_id, PDO::PARAM_INT);
        $stmt->bindValue('new_registrar_id', $new_registrar_id, PDO::PARAM_INT);
        $stmt->bindValue('new_email_address', $new_email_address, PDO::PARAM_STR);
        $stmt->bindValue('new_username', $new_username, PDO::PARAM_STR);
        $stmt->bindValue('new_password', $new_password, PDO::PARAM_STR);
        $stmt->bindValue('new_reseller', $new_reseller, PDO::PARAM_INT);
        $stmt->bindValue('new_reseller_id', $new_reseller_id, PDO::PARAM_STR);
        $stmt->bindValue('new_api_app_name', $new_api_app_name, PDO::PARAM_STR);
        $stmt->bindValue('new_api_key', $new_api_key, PDO::PARAM_STR);
        $stmt->bindValue('new_api_secret', $new_api_secret, PDO::PARAM_STR);
        $stmt->bindValue('new_api_ip_id', $new_api_ip_id, PDO::PARAM_INT);
        $stmt->bindValue('new_notes', $new_notes, PDO::PARAM_LOB);
        $stmt->bindValue('created_by', $_SESSION['s_user_id'], PDO::PARAM_INT);
        $timestamp = $time->stamp();
        $stmt->bindValue('timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->execute();

        $assets = new DomainMOD\Assets();
        $_SESSION['s_message_success'] .= sprintf(_('Registrar Account %s (%s, %s) added'),
                $new_username, $assets->getRegistrar($new_registrar_id), $assets->getOwner($new_owner_id)) . '<BR>';

        if ($_SESSION['s_has_registrar_account'] != '1') {

            $system->checkExistingAssets();

            header("Location: ../../domains/index.php");

        } else {

            header("Location: ../registrar-accounts.php");

        }
        exit;

    } else {

        if ($new_owner_id === 0) {

            $_SESSION['s_message_danger'] .= _('Choose the owner') . '<BR>';

        }

        if ($new_registrar_id === 0) {

            $_SESSION['s_message_danger'] .= _('Choose the registrar') . '<BR>';

        }

        if (!$validate->text($new_username)) { $_SESSION['s_message_danger'] .= _('Enter a username') . '<BR>'; }

    }

}
?>
<?php require_once DIR_INC . '/doctype.inc.php'; ?>
<html>
<head>
    <title><?php echo $layout->pageTitle($page_title); ?></title>
    <?php require_once DIR_INC . '/layout/head-tags.inc.php'; ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php require_once DIR_INC . '/layout/header.inc.php'; ?>
<?php
echo $form->showFormTop('');

echo $form->showDropdownTop('new_registrar_id', _('Registrar'), '', '1', '');
if ($new_registrar_id === 0) {
    $to_compare = $_SESSION['s_default_registrar'];
} else {
    $to_compare = $new_registrar_id;
}
$result = $pdo->query("
    SELECT id, `name`
    FROM registrars
    ORDER BY `name` ASC")->fetchAll();
foreach ($result as $row) {
    echo $form->showDropdownOption($row->id, $row->name, $to_compare);
}
echo $form->showDropdownBottom('');

echo $form->showDropdownTop('new_owner_id', _('Account Owner'), '', '1', '');
if ($new_owner_id === 0) {
    $to_compare = $_SESSION['s_default_owner_domains'];
} else {
    $to_compare = $new_owner_id;
}
$result = $pdo->query("
    SELECT id, `name`
    FROM owners
    ORDER BY `name` ASC")->fetchAll();
foreach ($result as $row) {
    echo $form->showDropdownOption($row->id, $row->name, $to_compare);
}
echo $form->showDropdownBottom('');

echo $form->showInputText('new_email_address', _('Email Address') . ' (100)', '', $unsanitize->text($new_email_address), '100', '', '', '', '');
echo $form->showInputText('new_username', _('Username') . ' (100)', '', $unsanitize->text($new_username), '100', '', '1', '', '');
echo $form->showInputText('new_password', _('Password') . ' (255)', '', $unsanitize->text($new_password), '255', '', '', '', '');

echo $form->showRadioTop(_('Reseller Account') . '?', '', '');
echo $form->showRadioOption('new_reseller', '1', _('Yes'), $new_reseller, '<BR>', '&nbsp;&nbsp;&nbsp;&nbsp;');
echo $form->showRadioOption('new_reseller', '0', _('No'), $new_reseller, '', '');
echo $form->showRadioBottom('');

echo $form->showInputText('new_reseller_id', _('Reseller ID') . ' (100)', '', $new_reseller_id, '100', '', '', '', '');
?>

<div class="box box-default collapsed-box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title" style="padding-top: 3px;">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>&nbsp;API Credentials
        </h3>
    </div>
    <div class="box-body"><?php

        echo $form->showInputText('new_api_app_name', _('API App Name'), '', $unsanitize->text($new_api_app_name), '255', '', '', '', '');
        echo $form->showInputText('new_api_key', _('API Key'), '', $unsanitize->text($new_api_key), '255', '', '', '', '');
        echo $form->showInputText('new_api_secret', _('API Secret'), '', $unsanitize->text($new_api_secret), '255', '', '', '', '');

        echo $form->showDropdownTop('new_api_ip_id', _('API IP Address'), _('The IP Address that you whitelisted with the domain registrar for API access.'), '', '');
        echo $form->showDropdownOption('0', 'n/a', '0');
        $result = $pdo->query("
            SELECT id, `name`, ip
            FROM ip_addresses
            ORDER BY `name` ASC")->fetchAll();
        foreach ($result as $row) {
            echo $form->showDropdownOption($row->id, $row->name . ' (' . $row->ip . ')', $new_api_ip_id);
        }
        echo $form->showDropdownBottom(''); ?>

    </div>
</div><BR><?php

echo $form->showInputTextarea('new_notes', _('Notes'), '', $unsanitize->text($new_notes), '', '', '');
echo $form->showSubmitButton(_('Add Registrar Account'), '', '');
echo $form->showFormBottom('');
?>
<?php require_once DIR_INC . '/layout/footer.inc.php'; ?>
</body>
</html>

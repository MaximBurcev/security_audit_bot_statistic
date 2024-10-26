<?php

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

// пространство имен для подключений ланговых файлов
use Bitrix\Main\Localization\Loc;

// подключение ланговых файлов
Loc::loadMessages(__FILE__);

// сформируем верхний пункт меню
$aMenu = [
    // пункт меню в разделе Контент
    'parent_menu' => 'global_menu_content',
    // сортировка
    'sort'        => 1,
    // название пункта меню
    'text'        => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_ADMIN_MENU_TITLE'),
    // идентификатор ветви
    "items_id"    => "menu_webforms",
    // иконка
    "icon"        => "form_menu_icon",
];
// дочерния ветка меню
$aMenu["items"][] = [
    // название подпункта меню
    'text' => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_ADMIN_MENU_PAGE_TITLE'),
    // ссылка для перехода
    'url'  => 'security_audit_bot_statistic.php?lang=' . LANGUAGE_ID
];
// дочерния ветка меню
$aMenu["items"][] = [
    // название подпункта меню
    'text' => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_ADMIN_MENU_ADMIN_TITLE'),
    // ссылка для перехода
    'url'  => 'settings.php?lang=ru&mid=intensa.security_audit_bot_statistic'
];
// возвращаем основной массив $aMenu
return $aMenu;
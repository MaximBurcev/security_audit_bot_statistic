<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

// пространство имен для автозагрузки модулей
use \Bitrix\Main\Loader;

// получим права доступа текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight("intensa.security_audit_bot_statistic");
// если нет прав - отправим к форме авторизации с сообщением об ошибке
if ($POST_RIGHT == "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
// вывод заголовка
$APPLICATION->SetTitle("Статистика");
// подключаем языковые файлы
IncludeModuleLangFile(__FILE__);

// подключаем модуль для того что бы был видем класс ORM
Loader::includeModule("intensa.security_audit_bot_statistic");

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
?>

sdfsdf


<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
?>
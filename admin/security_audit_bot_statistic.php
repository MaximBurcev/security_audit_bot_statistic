<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

// пространство имен для автозагрузки модулей
use \Bitrix\Main\Loader;
use SecurityAuditBotStatistic\StatisticTable;

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
$arData = StatisticTable::getList()->fetchAll();
?>

<?php
foreach ($arData as $arDataItem) {
    ?>
    <h3><?=$arDataItem['date']?></h3>
    <ul>
        <li>users: <?=$arDataItem['users']?></li>
        <li>reports: <?=$arDataItem['reports']?></li>
        <li>audits: <?=$arDataItem['audits']?></li>
        <li>projects: <?=$arDataItem['projects']?></li>
        <li>tasks: <?=$arDataItem['tasks']?></li>
        <li>utilities: <?=$arDataItem['utilities']?></li>
    </ul>
    <?php
}
?>


<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
?>
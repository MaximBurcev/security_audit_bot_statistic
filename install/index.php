<?php


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class intensa_security_audit_bot_statistic extends CModule
{

    public function __construct()
    {
        if (is_file(__DIR__ . '/version.php')) {
            include_once(__DIR__ . '/version.php');
            $this->MODULE_ID = 'intensa.security_audit_bot_statistic';
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME = Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_MODULE_NAME');
            $this->MODULE_DESCRIPTION = Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_MODULE_DESCRIPTION');
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_VERSION_FILE_NOT_FOUND') . ' version.php'
            );
        }
    }

    public function doInstall()
    {
        global $APPLICATION;

        // мы используем функционал нового ядра D7 — поддерживает ли его система?
        if (version_compare(ModuleManager::getVersion('main'), '14.00.00') >= 0) {
            // копируем файлы, необходимые для работы модуля
            $this->installFiles();

            // регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);

            // создаем таблицы БД, необходимые для работы модуля
            $this->installDB();

            $this->installAgents();
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_INSTALL_ERROR')
            );
            return;
        }

        $APPLICATION->includeAdminFile(
            Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_INSTALL_TITLE') . ' «' . Loc::getMessage(
                'SECURITY_AUDIT_BOT_STATISTIC_MODULE_NAME'
            ) . '»',
            __DIR__ . '/step.php'
        );
    }

    public function installFiles(): void
    {
        CopyDirFiles(
            __DIR__ . "/../admin/security_audit_bot_statistic.php",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/security_audit_bot_statistic.php",
        );
    }


    public function doUninstall()
    {
        global $APPLICATION;

        $this->uninstallFiles();
        $this->uninstallDB();
        $this->unInstallAgents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->includeAdminFile(
            Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_UNINSTALL_TITLE') . ' «' . Loc::getMessage(
                'SECURITY_AUDIT_BOT_STATISTIC_MODULE_NAME'
            ) . '»',
            __DIR__ . '/unstep.php'
        );
    }

    public function uninstallFiles()
    {
        DeleteDirFiles(
            __DIR__ . "/../admin/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/",
        );
        // удаляем настройки нашего модуля
        Option::delete($this->MODULE_ID);
    }

    public function installDB()
    {
        // подключаем модуль для того что бы был видем класс ORM
        if (Loader::includeModule($this->MODULE_ID)) {
            // через класс Application получаем соединение по переданному параметру, параметр берем из ORM-сущности (он указывается, если необходим другой тип подключения, отличный от default), если тип подключения по умолчанию, то параметр можно не передавать. Далее по подключению вызываем метод isTableExists, в который передаем название таблицы полученное с помощью метода getDBTableName() класса Base
            if (!Application::getConnection(
                \SecurityAuditBotStatistic\StatisticTable::getConnectionName()
            )->isTableExists(Base::getInstance("SecurityAuditBotStatistic\StatisticTable")->getDBTableName())) {
                // eсли таблицы не существует, то создаем её по ORM сущности
                Base::getInstance("SecurityAuditBotStatistic\StatisticTable")->createDbTable();
            }
        }
    }

    public function uninstallDB()
    {
// подключаем модуль для того что бы был видем класс ORM
        Loader::includeModule($this->MODULE_ID);
        // делаем запрос к бд на удаление таблицы, если она существует, по подключению к бд класса Application с параметром подключения ORM сущности
        Application::getConnection(\SecurityAuditBotStatistic\StatisticTable::getConnectionName())->queryExecute(
            'DROP TABLE IF EXISTS ' . Base::getInstance("SecurityAuditBotStatistic\StatisticTable")->getDBTableName()
        );
        Application::getConnection(\SecurityAuditBotStatistic\StatisticTable::getConnectionName())->queryExecute(
            'DROP TABLE IF EXISTS ' . Base::getInstance("SecurityAuditBotStatistic\StatisticTable")->getDBTableName()
        );
        // удаляем параметры модуля из базы данных битрикс
        Option::delete($this->MODULE_ID);
    }

    private function installAgents()
    {
        \CAgent::AddAgent(
        // строка PHP для запуска агента-функции
            "\SecurityAuditBotStatistic\Agent::superAgent();",
            // идентификатор модуля, необходим для подключения файлов модуля (необязательный)
            $this->MODULE_ID,
            // период, нужен для агентов, которые должны выполняться точно в срок. Если агент пропустил запуск, то он сделает его столько раз, сколько он пропустил. Если значение N, то агент после первого запуска будет запускаться с заданным интервалам (необязательный, по умолчанию N)
            "N",
            // интервал в секундах (необязательный, по умолчанию 86400 (сутки))
            86400,
            // дата первой проверки (необязательный, по умолчанию текущее время)
            date('d.m.Y H:i:s', time() + 60 * 1),
            // активность агента (необязательный, по умолчанию Y)
            "Y",
            // дата первого запуска (необязательный, по умолчанию текущее время)
            date('d.m.Y H:i:s', time() + 60 * 1),

        );
    }

    private function unInstallAgents()
    {
        \CAgent::RemoveModuleAgents($this->MODULE_ID);
    }


}
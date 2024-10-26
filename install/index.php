<?php


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

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
            // создаем таблицы БД, необходимые для работы модуля
            $this->installDB();
            // регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);
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
            __DIR__ . "/admin/security_audit_bot_statistic/step.php",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/security_audit_bot_statistic.php",
            true, // перезаписывает файлы
            true  // копирует рекурсивно
        );
    }

    public function installDB()
    {
        return;
    }


    public function doUninstall()
    {
        global $APPLICATION;

        $this->uninstallFiles();
        $this->uninstallDB();

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
            __DIR__ . "/admin",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"
        );
        // удаляем настройки нашего модуля
        Option::delete($this->MODULE_ID);
    }

    public function uninstallDB()
    {
        return;
    }


}
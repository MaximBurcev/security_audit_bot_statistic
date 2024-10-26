<?php

namespace SecurityAuditBotStatistic;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class StatisticTable extends DataManager
{
    // название таблицы
    public static function getTableName()
    {
        return 'security_audit_bot_statistic';
    }

    // создаем поля таблицы
    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'autocomplete' => true,
                'primary'      => true
            ]),
            new DateField('date', [
                'required' => true,
            ]),
            new IntegerField('users', [
                'required' => true,
            ]),
            new IntegerField('reports', [
                'required' => true,
            ]),
            new IntegerField('audits', [
                'required' => true,
            ]),
            new IntegerField('projects', [
                'required' => true,
            ]),
            new IntegerField('tasks', [
                'required' => true,
            ]),
            new IntegerField('utilities', [
                'required' => true,
            ]),
        ];
    }
}
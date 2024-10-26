<?php

Bitrix\Main\Loader::registerAutoloadClasses(
    'intensa.security_audit_bot_statistic',
    [
        'SecurityAuditBotStatistic\\StatisticTable' => 'lib/StatisticTable.php',
        'SecurityAuditBotStatistic\\Agent'          => 'lib/Agent.php',
    ]
);
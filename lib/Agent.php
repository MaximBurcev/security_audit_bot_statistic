<?php

namespace SecurityAuditBotStatistic;

use Bitrix\Main\Type\Date;
use SecurityAuditBotStatistic\StatisticTable;

use \Bitrix\Main\Web\HttpClient;
use \Bitrix\Main\Web\Uri;

class Agent
{

    const HOST = 'security-audit-bot.ru';

    const URL = '/api/statistic';

    static public function superAgent()
    {
        $options = [
            "accept" => 'application/json',
        ];
        $httpClient = new HttpClient($options);

        $uri = implode('?', ['https://' . self::HOST . self::URL, http_build_query(['api_token' => \COption::GetOptionString('intensa.security_audit_bot_statistic', 'api_token')])]);

        $result = $httpClient->get($uri);

        $arData = json_decode($result, true);
        $arData['date'] = new Date();

        AddMessage2Log($arData);

        try {
            (new \SecurityAuditBotStatistic\StatisticTable())->add($arData);
        } catch (\Exception $e) {
            AddMessage2Log($e->getMessage());
        }


        return "\SecurityAuditBotStatistic\Agent::superAgent();";
    }
}
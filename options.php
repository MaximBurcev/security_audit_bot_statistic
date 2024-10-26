<?php


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

// получаем идентификатор модуля
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] != '' ? $request['mid'] : $request['id']);
// подключаем наш модуль
Loader::includeModule($module_id);

/*
 * Параметры модуля со значениями по умолчанию
 */
$aTabs = [
    [
        /*
         * Первая вкладка «Основные настройки»
         */
        'DIV'     => 'edit1',
        'TAB'     => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_TAB_GENERAL'),
        'TITLE'   => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_TAB_GENERAL'),
        'OPTIONS' => [
            [
                'switch_on',                                   // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SWITCH_ON'), // поясняющий текст — «Включить прокрутку»
                'Y',                                           // значение по умолчанию «да»
                ['checkbox']                              // тип элемента формы — checkbox
            ],
            [
                'jquery_on',                                   // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_JQUERY_ON'), // поясняющий текст — «Подключить jQuery»
                'N',                                           // значение по умолчанию «нет»
                ['checkbox']                              // тип элемента формы — checkbox
            ],
        ]
    ],
    [
        /*
         * Вторая вкладка «Дополнительные настройки»
         */
        'DIV'     => 'edit2',
        'TAB'     => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_TAB_ADDITIONAL'),
        'TITLE'   => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_TAB_ADDITIONAL'),
        'OPTIONS' => [
            /*
             * секция «Внешний вид»
             */
            Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SECTION_VIEW'),
            [
                'width',                                    // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_WIDTH'),  // поясняющий текст — «Ширина (пикселей)»
                '50',                                       // значение по умолчанию 50px
                ['text', 5]                            // тип элемента формы — input type="text", ширина 5 симв.
            ],
            [
                'height',                                   // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_HEIGHT'), // поясняющий текст — «Высота (пикселей)»
                '50',                                       // значение по умолчанию 50px
                ['text', 5]                            // тип элемента формы — input type="text", ширина 5 симв.
            ],
            [
                'radius',                                   // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_RADIUS'), // поясняющий текст — «Радиус (пикселей)»
                '50',                                       // значение по умолчанию 50px
                ['text', 5]                            // тип элемента формы — input type="text", ширина 5 симв.
            ],
            [
                'color',                                    // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_COLOR'),  // поясняющий текст — «Цвет фона»
                '#bf3030',                                  // значение по умолчанию #bf3030
                ['text', 5]                            // тип элемента формы — input type="text", ширина 5 симв.
            ],
            /*
             * секция «Положение на странице»
             */
            Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SECTION_LAYOUT'),
            [
                'side',                                       // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_POSITION'), // поясняющий текст — «Положение кнопки»
                'left',                                       // значение по умолчанию «left»
                [
                    'selectbox',                              // тип элемента формы — <select>
                    [
                        'left'  => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SIDE_LEFT'),
                        'right' => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SIDE_RIGHT')
                    ]
                ]
            ],
            [
                'indent_bottom',                                   // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_INDENT_BOTTOM'), // поясняющий текст — «Отступ снизу (пикселей)»
                '10',                                              // значение по умолчанию 10px
                ['text', 5]                                   // тип элемента формы — input type="text"
            ],
            [
                'indent_side',                                     // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_INDENT_SIDE'),   // поясняющий текст — «Отступ сбоку (пикселей)»
                '10',                                              // значение по умолчанию 10px
                ['text', 5]                                   // тип элемента формы — input type="text"
            ],
            /*
             * секция «Поведение»
             */
            Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SECTION_ACTION'),
            [
                'speed',                                   // имя элемента формы
                Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SPEED'), // поясняющий текст — «Скорость прокрутки»
                'normal',                                  // значение по умолчанию «normal»
                [
                    'selectbox',                           // тип элемента формы — <select>
                    [
                        'slow'   => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SPEED_SLOW'),
                        'normal' => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SPEED_NORMAL'),
                        'fast'   => Loc::getMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_SPEED_FAST')
                    ]
                ]
            ]
        ]
    ]
];

/*
 * Создаем форму для редактирвания параметров модуля
 */
$tabControl = new CAdminTabControl(
    'tabControl',
    $aTabs
);

$tabControl->begin();
?>
    <form action="<?= $APPLICATION->getCurPage(); ?>?mid=<?= $module_id; ?>&lang=<?= LANGUAGE_ID; ?>" method="post">
        <?= bitrix_sessid_post(); ?>
        <?php
        foreach ($aTabs as $aTab) { // цикл по вкладкам
            if ($aTab['OPTIONS']) {
                $tabControl->beginNextTab();
                __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
            }
        }
        $tabControl->buttons();
        ?>
        <input type="submit" name="apply"
               value="<?= Loc::GetMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_INPUT_APPLY'); ?>" class="adm-btn-save"/>
        <input type="submit" name="default"
               value="<?= Loc::GetMessage('SECURITY_AUDIT_BOT_STATISTIC_OPTIONS_INPUT_DEFAULT'); ?>"/>
    </form>

<?php
$tabControl->end();

/*
 * Обрабатываем данные после отправки формы
 */
if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) { // цикл по вкладкам
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) { // если это название секции
                continue;
            }
            if ($arOption['note']) { // если это примечание
                continue;
            }
            if ($request['apply']) { // сохраняем введенные настройки
                $optionValue = $request->getPost($arOption[0]);
                
                Option::set(
                    $module_id,
                    $arOption[0],
                    is_array($optionValue) ? implode(',', $optionValue) : $optionValue
                );
            } elseif ($request['default']) { // устанавливаем по умолчанию
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->getCurPage() . '?mid=' . $module_id . '&lang=' . LANGUAGE_ID);
}
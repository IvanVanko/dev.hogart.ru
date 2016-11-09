<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 28/07/16
 * Time: 13:58
 */

namespace Sprint\Migration;


/*
    Требуется в настройках сайта dbconn.php заменить строки

    define("BX_CRONTAB_SUPPORT", true);
    define("BX_CRONTAB", true);

    на

    if(!(defined("CHK_EVENT") && CHK_EVENT===true))
        define("BX_CRONTAB_SUPPORT", true);
*/
class Version201607280001 extends Version
{
    protected $description = "Отключение работы агентов по хиту, перенос переодических задач на CRON";

    public function up()
    {
        \COption::SetOptionString("main", "agents_use_crontab", "N");
        \COption::SetOptionString("main", "check_agents", "N");
        \COption::SetOptionString("main", "mail_event_bulk", "20");
    }
}

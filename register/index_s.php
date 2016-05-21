<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
global $APPLICATION;
?>
    <div class="inner">
        <?$APPLICATION->ShowViewContent('SEMINAR_H1');?>
        <h1>Вы успешно зарегистрированы на семинар
            «Компьютерные программы Oventrop
            для проектирования»</h1>
        <small>Перед проведением семинара вы будете дополнительно оповещены по электронной почте.</small>
        <small>Чтобы пройти на семинар, распечатайте эту страницу и приложите штрих-код к считывающему устройству при входе
            в конференц зал.
        </small>

        <div class="green-line-registration">
            <a href="#" class="icon-print black nohover"><span>Распечатать приглашение</span></a>

            <div class="right">
                <a href="#" class="icon-phone black nohover"><span>Отправить по смс</span></a>
                <a href="#" class="icon-email black nohover"><span><?= GetMessage("Отправить на e-mail") ?></span></a>
            </div>
        </div>
        <div class="reg-kupon">
            <div class="col1">
                <img src="<?=HogartHelpers::getS?>" alt=""/>

                <h3>Организатор</h3>
                <span>Иванов Сергей Николаевич</span>
                <span>Тел.: +7 (932) 444 - 33 - 22</span>
                <span>E-mail: <a href="#">sergey@seminars.ru</a></span>
            </div>
            <div class="col2">
                <h2>Приглашение на семинар<br>Компьютерные программы Oventrop для проектирования</h2>

                <h3>Семен Слепаков</h3>

                <div class="big-text">ООО Русские Сантехники</div>
                <div class="row">
                    <div class="col2">
                        <h3>Дата и время</h3>
                        <span>27 сентября 2015 г. / Время начала 10:00</span>
                    </div>
                    <div class="col2">
                        <h3>Адрес</h3>
                        <span>Артплей, г. Москва, ул. Амурская, д. 7, стр. 1</span>
                    </div>
                </div>
                <h3>Лекторы семинара</h3>

                <p>
                    Иванов Сергей Николаевич / <span class="company-reg">ООО Симбирцит</span>, 
                    Иваненко Анна Александровна / <span class="company-reg">ЗАО СантехКредит</span>, 
                    Заиченко
                    Феофанна Игоревна / <span class="company-reg">ООО Русские Сантехники</span>, 
                    Иванов Сергей Николаевич / <span class="company-reg">ООО Симбирцит</span>, 
                    Иваненко Анна
                    Александровна / <span class="company-reg">ЗАО СантехКредит</span>, Заиченко Феофанна Игоревна / <span class="company-reg">ООО Русские Сантехники</span>, Заиченко
                    Феофанна Игоревна / <span class="company-reg">ООО Русские Сантехники</span>
                </p>
            </div>

            <i>* Приглашение дейстивительно только при предъявлении лицом, на которое оно выписано.</i>
        </div>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
</section>
<!-- Второе меню -->
<section class="slide hide" id="second_menu_slide">
    <ul class="second_menu big-menu">
        <li><a href="#">компания</a></li>
        <li><a href="#" >Новости</a></li>
        <li><a href="#">акции</a></li>
        <li><a href="#">каталог</a></li>
        <li><a href="#">обучение</a></li>
        <li><a href="#">документация</a></li>
        <li><a href="#">комплексные решения</a></li>
        <li class="small"><a href="#">Перезвоните мне!</a></li>
        <li class="small"><a href="#">Заявка на подбор оборудования</a></li>
        <li class="small"><a href="#">Склады и офисы</a></li>
    </ul>
</section>
<!-- окно логина -->
<!-- <section class="slide hide" id="profile_slide">
    <div class="top_block">
        <form action="" class="main-form">
            <div class="field">
                <label>Логин</label>
                <input type="text" value="">
            </div>
            <div class="field">
                <label>Пароль</label>
                <input type="password" value="">
            </div>
            <input type="submit" value="Войти">
            <a href="#" class="forget_link">Забыли пароль?</a>
            <a href="#" class="input-btn">регистрация</a>

        </form>
    </div>
</section> -->

<!-- Профиль -->
<section class="slide hide" id="profile_slide">
    <ul class="top-item height-auto big-menu">
        <li class="active"><a href="#" class="active">hello@yandex.ru</a></li>
    </ul>
    <ul class="second_menu profile_menu height-auto big-menu">
        <li><a href="#">Заказы <span class="green">(5)</span></a></li>
        <li><a href="#" >счета</a></li>
        <li><a href="#">документы</a></li>
        <li><a href="#">Сообщения <span class="green">(5)</span></a></li>
        <li><a href="#">Настройки</a></li>
        <li><a href="#">Корзина</a></li>
        <li class="logout"><a href="#">выйти</a></li>
    </ul>


</section>

<!-- Поиск -->
<section class="slide hide" id="search">
    <div class="search-form">
        <form action="" class="main-form ">
            <div class="field">
                <label>Введите название или артикул</label>
                <input type="text" value="">
            </div>
            <input type="submit" value="найти">

        </form>
    </div>
</section>
<!-- Послать сообщение -->
<section class="slide hide" id="message_slide">
    <div class="top_block">
        <form action="" class="message-form">
            <div class="field">
                <label>Сообщение</label>
                <div class="input-wrap">
                    <input type="text" value="" name="subject" class="transparent-input border_input" placeholder="Тема">
                    <textarea name="text" class="transparent-input"></textarea>
                </div>

            </div>
            <div class="field">
                <label>Имя</label>
                <input type="text" value="" name="name">
            </div>
            <div class="field">
                <label>Телефон</label>
                <input type="text" value="" class="masked" name="tel" placeholder="+7 _ _ _   _ _ _   _ _   _ _">
            </div>
            <div class="field">
                <label>E-mail</label>
                <input type="email" value="" name="email">
            </div>
            <input type="submit" value="отправить">

        </form>
    </div>
</section>

<!-- Контакты -->

<section class="slide hide" id="map_slide">
    <ul class="main_menu big-menu map-menu menu_animation_2 height-auto">

        <li class="active" data-effect="contactMenuAnimation"><a href="#map_slide" class="slide-trigger active map_active" data-effect="contactMenuAnimation" data-val="55.550745, 37.546469">Центральный офис Москва</a>


            <div class="inner_block menu_inner">
                <small>склад и сервис</small>
                <div class="contacts-office">

                    <div class="address-block">
                        <a href="tel:+74957881112">117041, г. Москва, ул. Поляны, д. 52</a>
                    </div>

                    <div class="time-block">
                        <a href="tel:+74957807866">00:00 - 00:00</a>
                    </div>

                    <div class="tel-block">
                        <a href="tel:+74957881112">+7 (495) 788-11-12</a>
                        <a href="tel:+74957807866">+7 (495) 780-78-66</a>
                    </div>

                    <div class="email-block">
                        <a href="mailto:info@hogart.ru">info@hogart.ru</a>
                        <a href="mailto:service@hogart.ru">service@hogart.ru</a>
                    </div>

                </div>

                <div class="map-contacts-wrap">
                    <a href="http://maps.google.com/maps?daddr=55.550745, 37.546469" class="icon_1"></a>
                    <a href="http://maps.google.com/maps?daddr=55.550745, 37.546469" class="icon_2"></a>
                    <div class="contacts-map" id="map_canvas"></div>
                </div>


            </div>



        </li>
        <li class="" data-effect="contactMenuAnimation"><a href="#map_slide" class="slide-trigger " data-effect="contactMenuAnimation" data-val="55.550745, 37.546469">Санкт-Петербург</a>
            <div class="inner_block menu_inner">
                <small>склад и сервис</small>
                <div class="contacts-office">
                    <div class="tel-block">
                        <a href="tel:+74957881112">+7 (495) 788-11-12</a>
                        <a href="tel:+74957807866">+7 (495) 780-78-66</a>
                    </div>

                    <div class="email-block">
                        <a href="mailto:info@hogart.ru">info@hogart.ru</a>
                        <a href="mailto:service@hogart.ru">service@hogart.ru</a>
                    </div>

                </div>


            </div>

        </li>
        <li class="" data-effect="contactMenuAnimation"><a href="#map_slide" class="slide-trigger " data-effect="contactMenuAnimation" data-val="55.550745, 37.546469">Artplay</a>
            <div class="inner_block menu_inner">
                <small>склад и сервис</small>
                <div class="contacts-office">
                    <div class="tel-block">
                        <a href="tel:+74957881112">+7 (495) 788-11-12</a>
                        <a href="tel:+74957807866">+7 (495) 780-78-66</a>
                    </div>

                    <div class="email-block">
                        <a href="mailto:info@hogart.ru">info@hogart.ru</a>
                        <a href="mailto:service@hogart.ru">service@hogart.ru</a>
                    </div>

                </div>



            </div>


        </li>
        <li class="" data-effect="contactMenuAnimation"><a href="#map_slide" class="slide-trigger " data-effect="contactMenuAnimation" data-val="55.550745, 37.546469">Хамовнический Вал</a>
            <div class="inner_block menu_inner">
                <small>склад и сервис</small>
                <div class="contacts-office">
                    <div class="tel-block">
                        <a href="tel:+74957881112">+7 (495) 788-11-12</a>
                        <a href="tel:+74957807866">+7 (495) 780-78-66</a>
                    </div>

                    <div class="email-block">
                        <a href="mailto:info@hogart.ru">info@hogart.ru</a>
                        <a href="mailto:service@hogart.ru">service@hogart.ru</a>
                    </div>

                </div>




            </div>


        </li>
    </ul>
</section>

</div>
<footer class="footer main_footer">
    <small>© 2014, ООО «Хогарт»</small>
    <div class="credits"></div>
</footer>
</body>
</html>
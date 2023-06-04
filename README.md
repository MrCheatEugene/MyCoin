# MyCoin
Платежный сервис с поддержкой платежей через внешние NFC-метки.

# Описание

- MyCoin - проект с открытым исходным кодом, использующий различные веб-технологии чтобы создать идеальную централизованную платежную систему.

- MyCoin **не хранит данные карт пользователей в открытом виде**, вся информация о них **шифруется по алгоритму AES-256-CTR**.

- MyCoin **имеет простое и понятное API** для интеграции с другими системами

- MyCoin был создан **для обычных пользователей, как продавцов, так и покупателей**

- MyCoin **не имеет какого-то своего способа получения внутренней валюты**, его вы дописываете **сами**, в зависимости от ваших надобностей

- MyCoin имеет интеграцию с **Web NFC**, что позволяет **почти любому пользователю** даже с бюджетным смартфоном, **самостоятельно создавать свои физические карты, без задержек на выпуск.**

# Установка

1. Разверните базу данных (см. файл mycoin.sql), и создайте пользователя который будет иметь к ней доступ
2. Загрузите сайт на хостинг -- требуются PHP модули OpenSSL, mysqli, json, а также PHP от 7 версии(но лучше установить 8).
3. В файле sql.php настройте подключение к базе данных (аргументы по порядку - хост, юзер, пароль, база данных)
4. В файле index.php измените значение переменной baseurl на ссылку на ваш сайт(без слэша, можно подпапку, SSL обязателен только если вам нужен NFC)
5. Создайте почтовый ящик где угодно, получите от него доступ к SMTP, и введите их в mailer.php.
6. Дайте рекурсивные права на все чтение и исполнение всех файлов в папке с сайтом.
7. Готово.

# Лицензии

В коде содержатся сторонние библиотеки.
- PHPMailer, под лицензией GNU Lesser General Public License v2.1
- Ityped, под лицензией MIT License
- Некая библиотека для MD5 хэширования, предположительно [эта](https://gist.github.com/josedaniel/951664), минифицированна, лицензия BSD License
- Bootstrap / Bootstrap Icons, под лицензией MIT License

**Остальной код MyCoin, за исключением встроенных в браузер API, написан лично мной, и лицензируется под лицензией The Unlicense. Все вышеперечисленные лицензии, включая The Unlicense допускают компоновку кода, лицензированного под другими лицензиями.**

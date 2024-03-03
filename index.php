<?php
include_once 'example_persons_array.php';

/* Разбиение и объединение ФИО
Обработка строк
В вашей информационной системе ФИО пользователей хранятся в виде строк,
содержащих сразу и фамилию, и имя, и отчество (именно в этом порядке).
В некоторых случаях такие данные требуется разъединять.

Разработайте две функции: getPartsFromFullname и getFullnameFromParts.

getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество.
Возвращает как результат их же, но склеенные через пробел.
Пример: как аргументы принимаются три строки «Иванов», «Иван» и «Иванович»,
а возвращается одна строка — «Иванов Иван Иванович».

getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО.
Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’.
Пример: как аргумент принимается строка «Иванов Иван Иванович»,
а возвращается массив [‘surname’ => ‘Иванов’ ,‘name’ => ‘Иван’, ‘patronomyc’ => ‘Иванович’].

Обратите внимание на порядок «Фамилия Имя Отчество», его требуется соблюсти.

Разделение Строк explode(string $separator, string $string, int $limit = PHP_INT_MAX): array
Функция возвращает массив строк, каждая из которых — подстрока,
которая образовалась за счёт разделения строки string по границам,
которые образовала строка-разделитель separator.
Снова Объединение строк array_combine(array $keys, array $values): array
Создаёт массив (array), используя значения массива keys в качестве ключей
и значения массива values в качестве соответствующих значений.
Начиная с PHP 8.0.0, выдаётся ошибка ValueError, если количество элементов в keys и values не совпадает.
До PHP 8.0.0 вместо этого выдавалась ошибка уровня E_WARNING.
https://www.php.net/manual/ru/function.array-combine.php
*/

$surname = 'Иванов';
$name = 'Иван';
$patronomyc = 'Иванович';

function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}
echo (getFullnameFromParts($surname, $name, $patronomyc)) . PHP_EOL;
echo PHP_EOL;

function getPartsFromFullname($name) {
    $a = ['surname', 'name', 'patronomyc'];
    $b = explode(' ', $name);
    return array_combine($a, $b);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    print_r(getPartsFromFullname($name));
}

/* Сокращение ФИО
Обработка строк
При разработке информационной системы вы всячески стараетесь избежать распространения персональных данных.
При отображении информации пользователю о других пользователях требуется сокращать фамилию и откидывать отчество.

Разработайте функцию getShortName, принимающую как аргумент строку,
содержащую ФИО вида «Иванов Иван Иванович» и возвращающую строку вида «Иван И.»,
где сокращается фамилия и отбрасывается отчество.
Для разбиения строки на составляющие используйте функцию getPartsFromFullname.
https://www.php.net/manual/ru/function.mb-substr.php
mbstring не входит в список модулей, устанавливаемых по умолчанию. https://www.php.net/manual/ru/mbstring.installation.php
    Linux/debian: sudo apt install php-mbstring команда работает без проблем на linux/debian
*/

function getShortName($name) {
    $arr = getPartsFromFullname($name);
    $firstName = $arr['name'];
    $surname = $arr['surname'];
    return $firstName . ' ' . mb_substr($surname, 0, 1) . '.';
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getShortName($name) . PHP_EOL;
}
echo PHP_EOL;

/*
Функция определения пола по ФИО
Обработка строк, космический корабль, арифметика
При разработке информационной системы вы всячески стараетесь избежать сбора персональных данных.
Однако, видимо, дизайнера забыли об этом предупредить,
и он разработал два вида интерфейса — “мужской” и “женский”.
Получилось очень здорово, и вы решили определять пол автоматически по ФИО.

Разработайте функцию getGenderFromName, принимающую как аргумент строку, содержащую ФИО (вида «Иванов Иван Иванович»).

Будем производить определение следующим образом:

внутри функции делим ФИО на составляющие с помощью функции getPartsFromFullname;
изначально «суммарный признак пола» считаем равным 0;
если присутствует признак мужского пола — прибавляем единицу;
если присутствует признак женского пола — отнимаем единицу.
после проверок всех признаков, если «суммарный признак пола» больше нуля — возвращаем 1 (мужской пол);
после проверок всех признаков, если «суммарный признак пола» меньше нуля — возвращаем -1 (женский пол);
после проверок всех признаков, если «суммарный признак пола» равен 0 — возвращаем 0 (неопределенный пол).

Признаки женского пола:
отчество заканчивается на «вна»;
имя заканчивается на «а»;
фамилия заканчивается на «ва»;

Признаки мужского пола:
отчество заканчивается на «ич»;
имя заканчивается на «й» или «н»;
фамилия заканчивается на «в».
 */

function getGenderFromName($name) {
    $arr = getPartsFromFullname($name);
    $surname = $arr['surname'];
    $firstName = $arr['name'];
    $patronomyc = $arr['patronomyc'];
    $sumGender = 0;

    if (mb_substr($surname, -1, 1) === 'в') {
        $sumGender++;
    } elseif (mb_substr($surname, -2, 2) === 'ва') {
        $sumGender--;
    }

    if ((mb_substr($firstName, -1, 1) == 'й') || (mb_substr($firstName, -1, 1) == 'н')) {
        $sumGender++;
    } elseif (mb_substr($firstName, -1, 1) === 'а') {
        $sumGender--;
    }

    if (mb_substr($patronomyc, -2, 2) === 'ич') {
        $sumGender++;
    } elseif (mb_substr($patronomyc, -3, 3) === 'вна') {
        $sumGender--;
    }

    return ($sumGender <=> 0);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    //echo getGenderFromName($name) . PHP_EOL;
    if (getGenderFromName($name) === 1) {
        echo 'мужской пол ' . ($name) . PHP_EOL;
    } elseif (getGenderFromName($name) === -1) {
        echo 'женский пол ' . ($name) . PHP_EOL;
    } else {
        echo 'неопределённый пол ' . ($name) . PHP_EOL;
    }
}
echo PHP_EOL;

/*
Определение возрастно-полового состава
Обработка массивов, арифметика, обработка строк
В админском интерфейсе требуется выводить половой состав аудитории.

Напишите функцию getGenderDescription для определения полового состава аудитории.
Как аргумент в функцию передается массив, схожий по структуре с массивом $example_persons_array.
Как результат функции возвращается информация в следующем виде:

Гендерный состав аудитории:
---------------------------
Мужчины - 55.5%
Женщины - 35.5%
Не удалось определить - 10.0%
Используйте для решения функцию фильтрации элементов массива,
функцию подсчета элементов массива, функцию getGenderFromName, округление.
*/

function getGenderDescription($persons) {

    $men = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderMen = getGenderFromName($fullname);
        if ($genderMen > 0) {
            return $genderMen;
        }
    });

    $women = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderWomen = getGenderFromName($fullname);
        if ($genderWomen < 0) {
            return $genderWomen;
        }
    });

    $failedGender = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderFailed = getGenderFromName($fullname);
        if ($genderFailed == 0) {
            return $genderFailed + 1; /* при этом если "неопределенного пола" не будет,
            то значение не будет выведено в функции getGenderFromName($fullname),
            то есть ноль (нуль) воспринимается как false
            нужно вывести любое цифровое значение со знаком минус
            или плюс просто чтобы получить значение true */
        }
    });

    // в результате выполнения функции по определению пола - getGenderFromName($name)
    $allMen = count($men);                       // подсчитываются все мужчины
    $allWomen = count($women);                   // подсчитываются все женщины
    $allFailedGender = count($failedGender);     // подсчитываются все чей пол не определился
    $allPeople = count($persons);                // посчитываются все люди

    $percentMen = round((100 / $allPeople * $allMen), 1); // round(int|float $num, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): float
    $percentWomen = round((100 / $allPeople * $allWomen), 1); // https://www.php.net/manual/ru/function.round.php
    $percenFailedGender = round((100 / $allPeople * $allFailedGender), 1);

    return <<< HEREDOC
    Гендерный состав аудитории:
    ---------------------------
    Мужчины - $percentMen%
    Женщины - $percentWomen%
    Неудалось определить - $percenFailedGender%
    HEREDOC;
}
echo getGenderDescription($example_persons_array) . PHP_EOL;
echo PHP_EOL;

/* Идеальный подбор пары
Обработка массивов, арифметика, обработка строк
Совсем недавно вы решили добавить в информационную систему «идеальный подбор пары».
Рекламщики уже привлекли внимание, руководство ждёт большие доходы, но вот функции для подбора пары еще нет.

Напишите функцию getPerfectPartner для определения «идеальной» пары.

Как первые три аргумента в функцию передаются строки с фамилией, именем и отчеством (именно в этом порядке).
При этом регистр может быть любым: ИВАНОВ ИВАН ИВАНОВИЧ, ИваНов Иван иванович.

Как четвертый аргумент в функцию передается массив, схожий по структуре с массивом $example_persons_array.

Алгоритм поиска идеальной пары:

приводим фамилию, имя, отчество (переданных первыми тремя аргументами) к привычному регистру;
склеиваем ФИО, используя функцию getFullnameFromParts;
определяем пол для ФИО с помощью функции getGenderFromName;
случайным образом выбираем любого человека в массиве;
проверяем с помощью getGenderFromName, что выбранное из Массива ФИО - противоположного пола, если нет,
то возвращаемся к шагу 4, если да - возвращаем информацию.
Как результат функции возвращается информация в следующем виде:

Иван И. + Наталья С. =
♡ Идеально на 64.43% ♡
Процент совместимости «Идеально на ...» — случайное число от 50% до 100% с точностью два знака после запятой.
*/

$surname = 'ИВАНОВ';
$name = 'Иван';
$patronomyc = 'Иванович';

/*
https://www.php.net/manual/ru/function.mb-convert-case.php

mb_convert_case(string $string, int $mode, ?string $encoding = null): string
Меняет регистр символов в строке, преобразовывая её заданным в параметре mode способом.

Список параметров
string
Строка (string) для преобразования.

mode
Режим смены регистра. Он может принимать значение одной из констант:
MB_CASE_UPPER, MB_CASE_LOWER, MB_CASE_TITLE, MB_CASE_FOLD, MB_CASE_UPPER_SIMPLE,
MB_CASE_LOWER_SIMPLE, MB_CASE_TITLE_SIMPLE или MB_CASE_FOLD_SIMPLE.

encoding
Параметр encoding — это кодировка символов. Если он опущен или равен null,
для него будет установлена внутренняя кодировка символов.

Возвращаемые значения
Возвращает версию переданной в параметр string строки с изменённым регистром,
преобразованной заданным в параметре mode способом.
 */

function getPerfectPartner($surname, $name, $patronomyc, $persons) {

    $surnameNorm = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $patronomycNorm = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);
    $nameNorm = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);

    $fullNameNorm = getFullnameFromParts($surnameNorm, $nameNorm, $patronomycNorm);  // полное имя главного имени
    $shortNameNorm = getShortName($fullNameNorm);                                    // сокращенное имя главного имени
    $genderFullNameNorm = getGenderFromName($fullNameNorm);                          // пол главного имени в виде: -1 0 1

    $allPersons = count($persons);

    // https://www.php.net/manual/ru/function.rand.php
    // проверка противоположности пола
    do {
        $personsNumRand = rand(0, $allPersons - 1); // номер случайного имени отсчет в массиве от 0 до 10 = 11, значит от 11-1 будут все значения от 0 до 10
        $personFullNameRand = $persons[$personsNumRand]['fullname'];         // полное имя случайного имени
        $personFullNameRandGender = getGenderFromName($personFullNameRand);  // пол случайного имени в виде: -1 0 1
    } while (($genderFullNameNorm == $personFullNameRandGender) || ($personFullNameRandGender == 0));

    $personShortNameRand = getShortName($personFullNameRand);   // сокращенное имя случайного имени
    $percentPerfect = rand(5000, 10000) / 100;                  // от 50% до 100% количество знаков после запятой
                                            // определяется подбором нужного значения и делением на определённое число

    return <<< HEREDOC
    $shortNameNorm + $personShortNameRand =
    ♡ Идеально на $percentPerfect% ♡
    HEREDOC;
}
echo getPerfectPartner($surname, $name, $patronomyc, $example_persons_array) . PHP_EOL;

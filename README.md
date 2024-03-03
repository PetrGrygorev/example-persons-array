# WEBSITE

 My website was created
 for educational purposes

 ---

## The MIT License (MIT)

Copyright © 2023 `PeterGrygorev`

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Разработан в качестве практического задания на курсе
веб-программирование с нуля backend PHP skillfactory. <br>

### Практическая работа PHP

#### Объединение ФИО

`getFullnameFromParts` принимает как аргумент три строки — фамилию, имя и отчество.
Возвращает как результат их же, но склеенные через пробел.
Пример: как аргументы принимаются три строки `'Иванов'`, `'Иван'` и `'Иванович'`, а возвращается одна строка — `'Иванов Иван Иванович'`.

#### Разбиение ФИО

`getPartsFromFullname` принимает как аргумент одну строку — склеенное ФИО. Возвращает как результат массив из трёх элементов с ключами `'surname', 'name', 'patronomyc'`.
Пример: как аргумент принимается строка `'Иванов Иван Иванович'`, а возвращается массив `['surname' => 'Иванов', 'name' => 'Иван', 'patronomyc' => 'Иванович']`.

#### Сокращение ФИО

Функция `getShortName`, принимает как аргумент строку, содержащую ФИО вида `'Иванов Иван Иванович'` и возвращающую строку вида `'Иван И.'`, где сокращается фамилия и отбрасывается отчество.

Для разбиения строки на составляющие используется функция `getPartsFromFullname`.

#### Функция определения пола по ФИО

Функция `getGenderFromName`, принимает как аргумент строку, содержащую ФИО (вида "Иванов Иван Иванович").
Определение производится следующим образом:

* внутри функции делим ФИО на составляющие с помощью функции `getPartsFromFullname`;
изначально "суммарный признак пола" считаем равным 0;
* если присутствует признак мужского пола — прибавляем единицу, если женского — отнимаем единицу.
* после проверок всех признаков, если "суммарный признак пола":
  * больше нуля — возвращаем 1 (мужской пол);
  * меньше нуля — возвращаем -1 (женский пол);
  * равен 0 — возвращаем 0 (неопределенный пол).

Признаки мужского пола:

* отчество заканчивается на "ич";
* имя заканчивается на "й" или "н";
* фамилия заканчивается на «в».

Признаки женского пола:

* отчество заканчивается на "вна";
* имя заканчивается на "а";
* фамилия заканчивается на "ва";

#### Определение возрастно-полового состава

Функция `getGenderDescription` принимает как аргумент массив `$example_persons_array`.
Как результат функции возвращается информация в следующем виде:

>Гендерный состав аудитории:
><br> ------------------------------------------
><br> Мужчины - 55.5%
><br> Женщины - 35.5%
><br> Не удалось определить - 10.0%

Используется функция фильтрации элементов массива, функция подсчета элементов массива, функция `getGenderFromName`, округление.

#### Идеальный подбор пары

Функция `getPerfectPartner` принимает:

* первые три аргумента - строки с фамилией, именем и отчеством (регистр может быть любым).
* Четвертый аргумент - массив `$example_persons_array`.

Алгоритм поиска идеальной пары:

1. фамилию, имя, отчество приводятся к нужному регистру;
2. функция `getFullnameFromParts` объединяет ФИО;
3. функция `getGenderFromName` определяет пол для ФИО;
4. случайным образом выбирается любой человек в массиве;
5. `getGenderFromName`, проверяет, что выбранное из массива ФИО - противоположного пола, если нет, то возвращаемся к шагу 4, если да - возвращаем информацию.

>Как результат функции возвращается информация
><br> в следующем виде:
><br> Иван И. + Наталья С. =
><br> ♡ Идеально на 64.43% ♡

Процент совместимости "Идеально на ..." — случайное число от 50% до 100% с точностью два знака после запятой.

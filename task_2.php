<?php

function generateDigitalCode() {
    // Генерируем число
    $digits = generateDigits();
    // Получаем контрольную цифру
    $control = calculateControl($digits);
    
    return $digits . $control;
}

function generateDigits() {
    $start_line = '988988';
    $random_line = '';
    for ($i = 0; $i < 7; $i++) {
        $random_line .= rand(0, 9);
    }
    
    return $start_line . $random_line;
}

function calculateControl($digits) {
    $digits_array = str_split($digits);
    $sum_odd = 0;
    $sum_even = 0;

    // Суммируем четные и нечетные справо налево
    $digits_array = array_reverse($digits_array);
    $length_digits = count($digits_array);
    for ($i = 0; $i < $length_digits; $i++) {
        if (($i + 1) % 2 == 1) { // так как порядковый номер == индекс + 1
            $sum_odd += $digits_array[$i];
        } else {
            $sum_even += $digits_array[$i];
        }
    }
    // Вычисляем контрольную цифру
    $sum_odd_even = $sum_odd * 3 + $sum_even;
    $last_digit = $sum_odd_even % 10;
    
    return ($last_digit == 0) ? 0 : 10 - $last_digit;
}

// Проверка
echo generateDigitalCode();

?>
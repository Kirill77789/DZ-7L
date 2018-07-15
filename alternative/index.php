<?php
// Добавлять в отчет все ошибки PHP (см. список изменений)
error_reporting(E_ALL);
// То же, что и error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
function echo_var($name){
    if(!empty($_POST[$name])){
        return $_POST[$name];
    };
    return '';
};
function year_word($age){  // Первая функция для написания лет
    $words = [
        'лет' => [11, 12, 13, 14, 0, 5, 6, 7, 8, 9,],
        'год' => [1,],
        'года' => [2, 3, 4,],
    ];
    for ($i=2; $i > 0; $i--){
        $age = substr($age, -$i);
        foreach ($words as $word => $years) {
            foreach ($years as $year) {
                if ($age == $year) {
                    return $word;
                };
            };
        };
    };
};
function year_word2($age, $sequence){   //вторая функция для напсиания лет, если значение перем. $sequence написано
    //'до', то слова "лет и пр." пишутся до числового значения
    $ar_1 = [11, 12, 13, 14];
    $ar_2 = [2, 3, 4];
    if ($age > 10 && $age < 15){
        $znach = $age;
    }else{
        $znach = $age % 10;
    };
    switch($znach){
        case ($ar_1[(array_search($znach, $ar_1))]):
            $word = 'лет';
            break;
        default:
            switch ($znach){
                case 1:
                    $word = 'год';
                    break;
                case ($ar_2[(array_search($znach, $ar_2))]):
                    $word = 'года';
                    break;
                default:
                    $word = 'лет';
            };
    };
    if($sequence == 'do'){
        $out = $word .' '.$_POST['age'];
    }else{
        $out = $_POST['age'].' '.$word;
    };
    return $out;
};
function output_txt($redirect = false){
    $out_1 =[];
    $out_2 =[];
    $word ='';
    if(empty($_POST['pay'])){
        $_POST['pay'] = 'off';}
    if(empty($_POST['DO'])){
        $_POST['DO'] = 'off';}
    if(empty($_POST['delimeter'])){
        $_POST['delimeter'] = ' ';}
    if(!empty($_POST)){
        foreach($_POST as $key=>$value){
            if(!empty($_POST[$key])){
                switch($key){
                    case 'FIO':
                        $out_1 []= 'Здравствуйте '.$_POST['FIO'].'.';
                        $out_2 []= $_POST['FIO'].'.';
                        break;
                    case 'age':
                        $word_1 = year_word($_POST['age']);
                        $word_2 = year_word2($_POST['age'], 'do');
                        if($_POST['DO'] == 'on'){
                            $out_1 []= 'Вам '.$word_1.$_POST['delimeter'].$_POST['age'].' (по функции №2- '.$word_2.')';
                            $out_2 []= '('.$word_2.').';
                        }else{
                            $out_1 []= 'Вам '.$_POST['age'].$_POST['delimeter'].$word_1.' (по функции №2- '.$word_2.')';
                            $out_2 []= '('.$word_2.').';
                        };
                        break;
                    case 'pay':
                        if($_POST['pay'] == 'on'){
                            $pay = 'за';
                            $pay_2 = 1;
                        }else{
                            $pay = 'не ';
                            $pay_2 = 0;
                        };
                        $out_1 []='Ваше решение '.$pay.'платить учтено.';
                        $out_2 []=$pay_2;
                        break;
                };
            };
        };
    };
    $out_1 = implode(' ',$out_1);
    $out_2 = implode(' ',$out_2);
    $answer = file_get_contents('answer.txt');
    file_put_contents('answer.txt', $out_2."\n".$answer);
    if($redirect == true){
        header('location: ?');
    }else{
        echo $out_1;
    };
    $per = fopen('answer.txt', 'r');
    $matr = [];
    while (!feof($per)){
        $line = fgets($per, 1024);
        $matr []= $line;
    };
    $arr=[];
    if (sizeof($matr)<6){
        foreach ($matr as $value){
            $arr[] = $value;
        };
    }else{
        for($i = sizeof($matr)-1; $i > (sizeof($matr)-6) ;$i--){
            $arr[] = $matr[$i];
        };
    };
    $matr = implode('<br>', $arr);
    echo '<br>'.$matr;
};

if (empty($_POST)){?>
    <form action="" method="post">
        <div class="form form_group">
            <label for="" class="form_label">Имя</label>
            <input type="text" name="FIO" class="form_input" value="<?php echo echo_var('FIO'); ?>">
        </div>
        <div class="form form_group">
            <label for="" class="form_label">Возраст</label>
            <input type="number" name="age" class="form_input" value="<?php echo echo_var('age'); ?>">
        </div>
        <div class="form form_group">
            <label for="" class="form_label">
                <?php if(!empty($_POST['pay'])&& $_POST['pay']=='on'){
                    $checked = 'checked';
                }else{
                    $checked = '';
                }; ?>
                <input type="checkbox" name="pay" class="form_input" <?php echo $checked; ?>>
                Согласен внести плату за ремонт
            </label>
        </div>
        <div class="form form_group">
            <label for="" class="form_label">
                <?php if(!empty($_POST['DO'])&& $_POST['DO']=='on'){
                    $checked = 'checked';
                }else{
                    $checked = '';
                }; ?>
                <input type="checkbox" name="DO" class="form_input" <?php echo $checked; ?>>
                Слово "год(года/лет)" поставить перед числом
            </label>
        </div>
        <div class="form form_group">
            <label for="" class="form_label">Разделитель между годами и значением</label>
            <input type="text" name="delimeter" class="form_input" value="<?php echo echo_var('FIO'); ?>">
        </div>
        <button class="form_button">OK</button>
    </form>
<?php }else{
    output_txt(false);};
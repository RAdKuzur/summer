<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
?>

<?php

$script = <<< JS

var isDragonDead = false;

// Глобальные функции
function updateDragonHealth() {
    const scoreData = $('#score-data');
    if (!scoreData.length) return;
   
    // Получаем значения и преобразуем в числа
    const team1Score = parseInt(scoreData.data('team1Score')) || 0;
    const team2Score = parseInt(scoreData.data('team2Score')) || 0;
    
    // Рассчитываем суммарный урон и здоровье с проверкой
    const totalDamage = Math.min(team1Score + team2Score, 100);
    const dragonHealth = Math.max(0, 100 - totalDamage);
    const healthPercent = Math.max(0, Math.min(100, dragonHealth));

     // Если дракон мертв и здоровье все еще 0, ничего не делаем
    if (isDragonDead && healthPercent <= 0) {
        return;
    }
    
    // Обновляем полосу здоровья
    const healthBar = $('#dragon-health-bar');
    healthBar.css('width', healthPercent + '%');
    
    // Обновляем текстовое отображение
    $('#dragon-hp-text').text(healthPercent);

    // Получаем элементы для управления изображением и звуком
    const dragonHead = $('#DragonHead img');

    // Обработка состояний здоровья
    if (healthPercent <= 0) {
        // Состояние при нулевом здоровье
        healthBar.css('background', 'linear-gradient(135deg, #FF0000, #990000)');
        healthBar.parent().addClass('zero-health');
        healthBar.parent().removeClass('low-health');
        healthBar.css('opacity', '0.7');
        healthBar.parent().css('box-shadow', '0 0 15px rgba(255, 0, 0, 0.8)');
        
        // Меняем изображение на яйцо
        if (dragonHead.attr('src') !== 'dragon-egg.jpg') {
            dragonHead.attr('src', 'dragon-egg.jpg');
        }
        console.log("BEFORE")
       
        healthBar.addClass('was-zero');
         
        // Останавливаем автоматическое обновление
        isDragonDead = true;
        clearInterval(refreshInterval);
        
        showVictoryMessage()
        console.log("AFTER")
    } 
    else if (healthPercent <= 30) {
        // Состояние при низком здоровье
        healthBar.css('background', 'linear-gradient(135deg, #FF5722, #F44336)');
        healthBar.parent().addClass('low-health');
        healthBar.parent().removeClass('zero-health');
        healthBar.css('opacity', '1');
        
        // Возвращаем изображение дракона (если было яйцо)
        if (dragonHead.attr('src') !== 'dragon3.png') {
            dragonHead.attr('src', 'dragon3.png');
        }
        
        healthBar.removeClass('was-zero');
    } 
    else {
        // Нормальное состояние
        healthBar.css('background', 'linear-gradient(135deg, #D0A2E8, #A467EA)');
        healthBar.parent().removeClass('low-health zero-health');
        healthBar.css('opacity', '1');
        
        // Возвращаем изображение дракона (если было яйцо)
        if (dragonHead.attr('src') !== 'dragon3.png') {
            dragonHead.attr('src', 'dragon3.png');
        }
        
        healthBar.removeClass('was-zero');
    }
}

function showVictoryMessage() {
    // Создаем элемент сообщения
    const message = $('<div>', {
        id: 'victory-message',
        css: {
            position: 'fixed',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            color: 'gold',
            padding: '20px',
            borderRadius: '10px',
            fontSize: '32px',
            fontFamily: 'Minecraft, sans-serif',
            textAlign: 'center',
            zIndex: '9999',
            border: '3px solid gold'
        },
        text: 'ДРАКОН ПОБЕЖДЁН!'
    });
    
    $('body').append(message);
}



function applyViewMode(mode) {
    if (mode === 'dragon') {
        if (isDragonDead)
        {
            $('#victory-message').show();
        }
        // Основные элементы
        $('#score-table').hide();
        $('#timerblock').hide();
        $('#damage').show();
        $('#dragon-health-container').show();
        $('#toggle-view-mode').text('Обычный режим');
         $('#DragonHead').show();
        // Применяем стили режима дракона
        $('body').addClass('dragon-mode');
        $('footer').addClass('dragon-mode-footer');
        $('nav.navbar').addClass('dragon-mode-navbar');
        
        // Если нужно изменить цвет текста в других элементах
        $('.some-text-element').addClass('dragon-mode-text');
        
        updateDragonHealth();
    } else {
        // Возвращаем обычный режим
        $('#victory-message').hide();
        $('#score-table').show();
        $('#dragon-health-container').hide();
        $('#damage').hide();
        $('#toggle-view-mode').text('Режим дракона');
        $('#timerblock').show();
        $('#DragonHead').hide();
        
        // Удаляем классы режима дракона
        
        $('body').removeClass('dragon-mode');
        $('footer').removeClass('dragon-mode-footer');
        $('nav.navbar').removeClass('dragon-mode-navbar');
        $('.some-text-element').removeClass('dragon-mode-text');
    }
    localStorage.setItem('viewMode', mode);
}

var refreshInterval;

$(document).ready(function() {
    // Устанавливаем интервал обновления только если дракон жив
    refreshInterval = setInterval(function(){ 
        if (!isDragonDead) {
            $('#refreshButton').click(); 
        }
    }, 1000);
    
    // Обновляем здоровье дракона сразу при загрузке
    updateDragonHealth();
    
    // Применяем сохраненный режим
    const savedMode = localStorage.getItem('viewMode') || 'table';
    applyViewMode(savedMode);

    // Обработчик переключения режимов
    $('#toggle-view-mode').click(function() {
        const currentMode = localStorage.getItem('viewMode') || 'table';
        const newMode = currentMode === 'table' ? 'dragon' : 'table';
        applyViewMode(newMode);
    });

    // Инициализация таймера
    const timer = document.getElementById('timer');
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const resetBtn = document.getElementById('resetBtn');
    
    let seconds = 0;
    let minutes = 0;
    let hours = 0;
    let interval;

    function updateTime() {
        seconds++;
        if (seconds === 60) {
            minutes++;
            seconds = 0;
        }
        if (minutes === 60) {
            hours++;
            minutes = 0;
        }
        timer.textContent = 'Время ' + 
            (hours < 10 ? '0' + hours : hours) + ':' + 
            (minutes < 10 ? '0' + minutes : minutes) + ':' + 
            (seconds < 10 ? '0' + seconds : seconds);
    }

    startBtn.addEventListener('click', () => {
        interval = setInterval(updateTime, 1000);
        startBtn.disabled = true;
        pauseBtn.disabled = false;
        resetBtn.disabled = false;
    });

    pauseBtn.addEventListener('click', () => {
        clearInterval(interval);
        startBtn.disabled = false;
        pauseBtn.disabled = true;
    });

    resetBtn.addEventListener('click', () => {
        clearInterval(interval);
        seconds = 0;
        minutes = 0;
        hours = 0;
        timer.textContent = 'Время 00:00:00';
        startBtn.disabled = false;
        pauseBtn.disabled = true;
        resetBtn.disabled = true;
    });
});
// Обработчик PJAX
$(document).on('pjax:success', function() {
     // Сначала обновляем здоровье дракона
    updateDragonHealth();
    // Применяем текущий режим
    const currentMode = localStorage.getItem('viewMode') || 'table';
    applyViewMode(currentMode);
});
JS;
$this->registerJs($script);
?>
<!-- Кнопка переключения режимов -->
<button id="toggle-view-mode" style="
    top: 100px;
    right: 10px;
    background: rgba(139, 0, 139, 0.7);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-family: 'Minecraft', sans-serif;
    z-index: 1000;
">
    Переключить вид
</button>
<style>
    table {
        width: 90%;
        height: 90%;
        margin: auto;
    }
    td {
        width: 33.33%;
        height: 33.33%;
        position: relative;
        color: black;
        text-align: center;
        font-family: digital;
        font-size: 30px;
    }
    td:after {
        content: '';
        display: block;
    }
    td .content {
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: gold;
    }

    #DragonHead img {
        height: 500px;
        width: 500px;
        object-fit: contain; /* Это обеспечит правильное масштабирование */
        transition: all 0.5s ease; /* Плавное изменение изображения */
    }

    .dragon-mode {
        background: linear-gradient(135deg, #000000, #2E0854, #5D3FD3) !important;
        color: #fff !important;
    }

    .dragon-mode-footer {
        background-color: #1a0033 !important;
        border-top: 1px solid #5D3FD3 !important;
        color: #fff !important;
    }

    .dragon-mode-navbar {
        background-color: #0d001a !important;
        border-bottom: 1px solid #5D3FD3 !important;
    }

    .dragon-mode-text {
        color: #fff !important;
    }


    /* Стили для полосы здоровья Эндердракона */
    #dragon-health-container {
        display: none;
        width: 90%;
        margin: 20px auto;
        text-align: center;
    }
    .dragon-health-bar {
        height: 60px;
        background: #222;
        border: 4px solid #000;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(139, 0, 139, 0.7);
    }

    .dragon-health-inner {
        height: 100%;
        width: 100%;
        transition: width 0.5s ease-in-out;
        position: relative; /* Добавляем для z-index */
        z-index: 1; /* Меньше чем у сегментов */
    }

    .health-segment {
        position: absolute;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(to bottom,
        rgba(255, 255, 255, 0.8) 0%,
        rgba(200, 200, 255, 0.6) 50%,
        rgba(255, 255, 255, 0.8) 100%);
        z-index: 2; /* Больше чем у health-inner */
        box-shadow:
                0 0 3px rgba(0, 0, 0, 0.8),
                inset 0 0 5px rgba(255, 255, 255, 0.5);
        pointer-events: none; /* Чтобы не мешали взаимодействию */
    }

    /* Контейнер для сегментов */
    .segments-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 3;
        pointer-events: none;
    }

    .dragon-health-text {
        font-family: 'Minecraft', sans-serif;
        font-size: 32px; /* Немного больше */
        color: #FFF;
        text-shadow:
                2px 2px 0 #000,
                -1px -1px 0 #000,
                1px -1px 0 #000,
                -1px 1px 0 #000; /* Контур */
        margin-top: 15px;
        background: rgba(0, 0, 0, 0.5);
        padding: 5px 15px;
        border-radius: 10px;
        display: inline-block;
    }

    /* Стили для состояний */
    .low-health {
        animation: pulse 1s infinite alternate;
    }

    .zero-health {
        animation: pulse-fast 0.5s infinite alternate;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 10px rgba(208, 162, 232, 0.7); }
        100% { box-shadow: 0 0 25px rgba(244, 67, 54, 0.9); }
    }

    @keyframes pulse-fast {
        0% { box-shadow: 0 0 5px rgba(255, 0, 0, 0.7); }
        100% { box-shadow: 0 0 20px rgba(255, 0, 0, 0.9); }
    }
</style>

<div id="DragonHead" class="text-center my-3">
    <img src="dragon3.png" style="height: 500px; width: 500px">
</div>

<?php Pjax::begin(['id' => 'score-pjax', 'enablePushState' => false]); ?>
<?= Html::a("Обновить", ['team/team-view', 'id' => $scores[0]->team_id], ['class' => 'hidden', 'id' => 'refreshButton']) ?>
<br>


<div id="score-data"
     data-team1-score="<?= $scores[0]->total_score ?? 0 ?>"
     data-team2-score="<?= $scores[1]->total_score ?? 0 ?>"
     style="display: none">
</div>

<!-- Контейнер для полосы здоровья Эндердракона -->
<div id="dragon-health-container">
    <div class="dragon-health-bar">
        <div class="dragon-health-inner" id="dragon-health-bar"></div>
        <div class="segments-container">
            <?php for ($i = 1; $i < 10; $i++): ?>
                <div class="health-segment" style="left: <?= $i * 10 ?>%"></div>
            <?php endfor; ?>
        </div>
    </div>
    <div class="dragon-health-text">
        Здоровье дракона: <span id="dragon-hp-text">100</span> / 100
    </div>
</div>


<!-- Основная таблица со счетами команд -->
<div id="score-table">
    <table>
        <tr>
            <?php if (count($scores) > 0) echo '<td style="background: '.$scores[0]->color->code.'">'.$scores[0]->team_name.'<p style="font-size: 100px">'.$scores[0]->total_score.'</p></td>' ?>
            <?php if (count($scores) > 1) echo '<td style="background: '.$scores[1]->color->code.'">'.$scores[1]->team_name.'<p style="font-size: 100px">'.$scores[1]->total_score.'</p></td>' ?>
            <?php if (count($scores) > 2) echo '<td style="background: '.$scores[2]->color->code.'">'.$scores[2]->team_name.'<p style="font-size: 100px">'.$scores[2]->total_score.'</p></td>' ?>
        </tr>
        <tr>
            <?php if (count($scores) > 3) echo '<td style="background: '.$scores[3]->color->code.'">'.$scores[3]->team_name.'<p style="font-size: 100px">'.$scores[3]->total_score.'</p></td>' ?>
            <?php if (count($scores) > 4) echo '<td style="background: '.$scores[4]->color->code.'">'.$scores[4]->team_name.'<p style="font-size: 100px">'.$scores[4]->total_score.'</p></td>' ?>
            <?php if (count($scores) > 5) echo '<td style="background: '.$scores[5]->color->code.'">'.$scores[5]->team_name.'<p style="font-size: 100px">'.$scores[5]->total_score.'</p></td>' ?>
        </tr>
        <tr style="height: 50px"></tr>
    </table>
</div>



<?php Pjax::end(); ?>

<!--<div id="damage" class="d-none text-center my-3" style="display: none !important">-->
<!--    --><?php
//    $buttonStyle = '
//        background: rgba(139, 0, 139, 0.7);
//        color: white;
//        border: none;
//        padding: 8px 15px;
//        border-radius: 5px;
//        cursor: pointer;
//        font-family: Minecraft, sans-serif;
//        margin: 10px;
//    ';
//
//    echo Html::a('Произвести выстрел', \yii\helpers\Url::to(['site/plus-score', 'numb' => 5, 'id' => $scores[0]->id, 'branch' => 1]), [
//        'style' => $buttonStyle,
//        'class' => 'm-3'
//    ]);
//
//    echo Html::a('Произвести выстрел №2', \yii\helpers\Url::to(['site/plus-score', 'numb' => 5, 'id' => $scores[1]->id, 'branch' => 1]), [
//        'style' => $buttonStyle,
//        'class' => 'm-3'
//    ]);
//    ?>
<!--</div>-->

<div id="timerblock" style="width: 100%">
    <table>
        <tr>
            <td>
                <b><h1 id="timer" class="number" align="center">Время 00:00:00</h1></b>
            </td>
            <td>
                <button id="startBtn" style="background-color: white; border-radius: 10px"><img src="play.png" style="height: 50px; width: 50px"></button>
                <button id="pauseBtn" style="background-color: white; border-radius: 10px" disabled><img src="pause.png" style="height: 50px; width: 50px"></button>
                <button id="resetBtn" style="background-color: white; border-radius: 10px" disabled><img src="refresh.png" style="height: 50px; width: 50px"></button>
            </td>
        </tr>
    </table>

</div>

<style>
    .number {
        width: 500px;
        font-family: digital, Consolas;
        font-size: 50px;
        background-color: #8dead7;
        padding: 5px 15px 10px 15px;
        border-radius: 10px;
        border-left: 4px solid #1f8c73;
        border-right: 4px solid #1f8c73;
        border-bottom: 4px solid #1f8c73;
    }
    @font-face {
        font-family: 'digital';
        src: url('DS-DIGI.TTF');
    }
</style>
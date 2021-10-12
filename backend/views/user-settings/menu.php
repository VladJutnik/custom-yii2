<?php


use yii\bootstrap4\Html;

echo Html::a('<i class="bx bx-user"></i><span> Профиль</span>', ['/user-settings/profile'],  ['class' => 'btn btn-primary btn-block']);
echo Html::a('<i class="bx bx-cog"></i><span> Настройки аккаунта</span>', ['/user-settings/settings'], ['class' => 'btn btn-primary btn-block']);
echo Html::a('<i class="lni lni-reload"></i><span> Смена пароля</span>', ['/user-settings/password-change'], ['class' => 'btn btn-primary btn-block']);

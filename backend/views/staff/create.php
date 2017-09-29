<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-create">

    <?= $this->render('_form', [
        'model' => $model,
		'mod_users' => $mod_users,
		'mod_address' => $mod_address,
		'country' => $country,
		'state' => $state,
		'role' => $role,
		'route' => $route,
		
    ])?>

</div>

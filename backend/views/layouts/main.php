<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">													 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
	
	<link rel="apple-touch-icon" sizes="57x57" href="<?=Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo Yii::$app->request->baseUrl ?>/admin-lte/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	
    <title><?= Html::encode($this->title) ?></title>
	<?php //$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => './favicon.png']); ?>
	<!-- Custom styles for this template -->
	<!--link href="<?php //Yii::$app->request->baseUrl ?>/admin-lte/build/less/header.less" rel="stylesheet" type="text/css" /-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<body class="skin-blue" >
<?php $this->beginBody() ?>
<div class="wrapper">
<?php if (!Yii::$app->user->isGuest) { ?>
	<header class="main-header">
         <a href="<?php echo Yii::$app->homeUrl ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
				
					<img src= "<?= Yii::$app->request->baseUrl ?>/admin-lte/dist/img/doodhvale.png" style="height: 50px; margin-top: -6px;" alt="Logo" />
				
            </a> 
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <?php 
	               	if (!Yii::$app->user->isGuest) {
	           	?>
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <?php } ?>
                <div class="logoname"> 
                <?php 
                    echo ('<h3 class="margin-top:10px;">Doodhvale Dairy Farms</h3>');
                ?>
                </div>
                <div class="navbar-custom-menu">
				 <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <?php
								//NavBar::begin();
								if (Yii::$app->user->isGuest) {
									$menuItems = [['label' => '<i class="glyphicon glyphicon-home"></i>    '.'Home', 'url' => ['/site/index'],]];
									$menuItems[] = ['label' => '<i class="glyphicon glyphicon-lock"></i>    '.'Login', 'url' => ['/site/login']];
								} 
								else {
									$menuItems[] = ['label'=> '<i class="glyphicon glyphicon-user"></i>    '.Yii::$app->user->identity->username,
													'items'=>[
														   ['label' => 'Profile', 'url' => Yii::$app->urlManager->createUrl(['/users/update'])],
														   ['label' => 'Change Password', 'url' => Yii::$app->urlManager->createUrl(['/users/password'])],
														   ['label' => 'Logout', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
														]		
													];
								}
								echo Nav::widget([
									'options' => ['class' => 'navbar-nav navbar-right'], 
									'items' => $menuItems,
									'encodeLabels' => false
								]);
								//NavBar::end();
							?>
                        </li>
                    </ul>
		
		</div>
            </nav>
        </header>
		
		<?php
	}		
		if (!Yii::$app->user->isGuest) {
		?>
		  
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src= "<?= Yii::$app->request->baseUrl ?>/admin-lte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>Hello, <?= Yii::$app->user->identity->username; ?> </p>
							<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li <?= ( Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
                            <a href= <?php echo Yii::$app->getUrlManager()->createUrl(['site/index']);?> >
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
						
						<li <?= (in_array(Yii::$app->controller->id,['delivery'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-bar-chart-o"></i>
                                <span>Delivery Reports</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								<li <?= ( Yii::$app->controller->id == 'delivery' && Yii::$app->controller->action->id == 'summary-report')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['delivery/summary-report']);?> ><i class="fa fa-angle-double-right"></i>Summary Daily Milk Delivery </a>
								</li>
								
							   <li <?= ( Yii::$app->controller->id == 'delivery' && Yii::$app->controller->action->id == 'daily-report')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['delivery/daily-report']);?> ><i class="fa fa-angle-double-right"></i> Daily Milk Delivery </a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'delivery' && Yii::$app->controller->action->id == 'monthly-report')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['delivery/monthly-report']);?> ><i class="fa fa-angle-double-right"></i> Monthly Milk Delivery</a>
								</li>
								
								
								<li <?= ( Yii::$app->controller->id == 'delivery' && Yii::$app->controller->action->id == 'customer-delivery')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['delivery/customer-delivery']);?> ><i class="fa fa-angle-double-right"></i> View Customer Delivery</a>
								</li>
								
								
							</ul>
                        </li>
						
						
						<li <?= (in_array(Yii::$app->controller->id,['delivery'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-fighter-jet"></i>
                                <span>Manage Delivery</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								
								<li <?= ( Yii::$app->controller->id == 'delivery' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['delivery/index']);?>">
									 <i class="fa fa-angle-double-right"></i>Delivery
								  </a>
								</li>
								
							</ul>
                        </li>   
                       
                        <li <?= (in_array(Yii::$app->controller->id,['category','products','product-price','product-brand-name','delivery-slot-name','product-filter'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-tree"></i>
                                <span>Manage Products</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								<li <?= ( Yii::$app->controller->id == 'category' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['category/index']);?> ><i class="fa fa-angle-double-right"></i> Category</a>
								</li>
								
								
							   <li <?= ( Yii::$app->controller->id == 'products' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['products/index']);?> ><i class="fa fa-angle-double-right"></i> Products</a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'product-price' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['product-price/index']);?> ><i class="fa fa-angle-double-right"></i> Product Price</a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'product-brand-name' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['product-brand-name/index']);?>">
									<i class="fa fa-angle-double-right"></i>Product Brand Name
								  </a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'delivery-slot-name' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['delivery-slot-name/index']);?>">
									<i class="fa fa-angle-double-right"></i>Product Delivery Slot
								  </a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'product-filter' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['product-filter/index']);?>">
									<i class="fa fa-angle-double-right"></i>Product Filter/Tag
								  </a>
								</li>
							</ul>
                        </li>
						
						<li <?= (in_array(Yii::$app->controller->id,['staff','subscription','xref-pause-subscription'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-truck"></i>
                                <span>Manage Orders</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
									<li <?= ( Yii::$app->controller->id == 'staff' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
									  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['staff/index']);?>">
										<i class="fa fa-angle-double-right"></i> Users
									  </a>
									</li>
									<li <?= ( Yii::$app->controller->id == 'subscription' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
									  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['subscription/index']);?>">
										<i class="fa fa-angle-double-right"></i>Subscription
									  </a>
									</li>
									
									<li <?= ( Yii::$app->controller->id == 'xref-pause-subscription' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
									  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['xref-pause-subscription/index']);?>">
										<i class="fa fa-angle-double-right"></i>Pause/Unpause Calendar
									  </a>
									</li>
									
									 
			
							</ul>
                        </li>
						
						<li <?= (in_array(Yii::$app->controller->id,['cow-milk-billing','account-statement'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-fighter-jet"></i>
                                <span>Manage Milk Billings</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								
								<li <?= ( Yii::$app->controller->id == 'cow-milk-billing' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['cow-milk-billing/index']);?> ><i class="fa fa-angle-double-right"></i> View All Generated Bill</a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'cow-milk-billing' && Yii::$app->controller->action->id == 'create')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['cow-milk-billing/create']);?> ><i class="fa fa-angle-double-right"></i> Generate Milk Bill</a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'account-statement' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
									  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['account-statement/index']);?>">
										<i class="fa fa-angle-double-right"></i>Account Statement
									  </a>
								</li>
								<li <?= ( Yii::$app->controller->id == 'account-statement' && Yii::$app->controller->action->id == 'pending-amount-report')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['account-statement/pending-amount-report']);?> ><i class="fa fa-angle-double-right"></i> Pending Milk Amounts</a>
								</li>
								
							</ul>
                        </li>   
						
						<li <?= (in_array(Yii::$app->controller->id,['route','route-map'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-road"></i>
                                <span>Manage Route</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								<li <?= ( Yii::$app->controller->id == 'route' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['route/index']);?> ><i class="fa fa-angle-double-right"></i> Routes</a>
								</li>
								
							   <li <?= ( Yii::$app->controller->id == 'route-map' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['route-map/index']);?> ><i class="fa fa-angle-double-right"></i> Route Map</a>
								</li>
								
							</ul>
                        </li>
						
						<li <?= (in_array(Yii::$app->controller->id,['track-delivery-boy','delivery-analytics','track-history'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-user"></i>
                                <span>Manage Delivery Boy</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								<li <?= ( Yii::$app->controller->id == 'track-delivery-boy' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['track-delivery-boy/index']);?>">
									<i class="fa fa-angle-double-right"></i>Track Delivery Boy
								  </a>
								</li>
								<li <?= ( Yii::$app->controller->id == 'delivery-analytics' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['delivery-analytics/index']);?>">
									<i class="fa fa-angle-double-right"></i>Delivery Analytics
								  </a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'track-history' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['track-history/index']);?>">
									<i class="fa fa-angle-double-right"></i>Track History of Delivery Boy
								  </a>
								</li>
								
							</ul>
                        </li>





			<li <?= (in_array(Yii::$app->controller->id,['sms-template','route-map'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-edit"></i>
                                <span>Manage SMS</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								<li <?= ( Yii::$app->controller->id == 'sms-template' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['sms-template/index']);?> ><i class="fa fa-angle-double-right"></i> SMS Template</a>
								</li>
								
							   <li <?= ( Yii::$app->controller->id == 'scheduler' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['scheduler/index']);?> ><i class="fa fa-angle-double-right"></i> SMS Scheduler</a>
								</li>
							<li <?= ( Yii::$app->controller->id == 'scheduler' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?> >
								<a href= <?php echo Yii::$app->getUrlManager()->createUrl(['scheduler/customer-sms']);?> ><i class="fa fa-angle-double-right"></i> SMS to Customers</a>
								</li>
							</ul>
                        </li>



						
							<li <?= (in_array(Yii::$app->controller->id,['faq','city','area-discount'])) ? 'class="treeview active" ' : 'class="treeview" '?> >
                            <a href="#">
                                <i class="fa fa-cogs"></i>
                                <span>Setting</span>
                                <i class="fa fa-angle-left pull-right "></i>
                            </a>
                            <ul class="treeview-menu">
								<li <?= ( Yii::$app->controller->id == 'area-discount' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['area-discount/index']);?>">
									<i class="fa fa-angle-double-right"></i>Area Discount
								  </a>
								</li>
								 <li <?= ( Yii::$app->controller->id == 'faq' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['faq/index']);?>">
									<i class="fa fa-angle-double-right"></i>Faq
								  </a>
								</li>
								
								<li <?= ( Yii::$app->controller->id == 'city' && Yii::$app->controller->action->id == 'index')?'class="active"':'class=""' ?>>
								  <a href="<?php echo Yii::$app->getUrlManager()->createUrl(['city/index']);?>">
									<i class="fa fa-angle-double-right"></i>City 
								  </a>
								</li>
							
							</ul>
                        </li>
						
                        
						<li <?= ( Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'logout')?'class="active"':'class=""' ?>>
						  <?= Html::a('<i class="fa fa-power-off"></i>  Log Out', ['site/logout'], ['data-method' => 'post'])?>
							
						</li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
	 
        <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

           <?php  echo Breadcrumbs::widget([
                'homeLink' => ['label' => Html::a('<span class="glyphicon glyphicon-home"></span> Home', \Yii::$app->getHomeUrl(),[
    															'title' => Yii::t('yii', 'Home'),
    													])],
                 'encodeLabels' => false,
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
			
			<h1>
            <?php //echo  $this->title ?>
            <small></small>
          </h1>
        </section>

		
        <!-- Main content -->
		<aside class="content">
        <?= Alert::widget() ?>
		 <?= $content ?>

		</aside>
        <!-- /.content -->
      </div><!-- /.content-wrapper -->
	   <footer class="main-footer">
        <div class="pull-right hidden-xs">
          
        </div>






        <strong> &copy; Doodhvale Dairy Farms <?= date('Y') ?> . </strong> All rights reserved.

      </footer>
		<?php 
 	      } else { ?>
		  
	  <aside class="content content-login">
        <!-- Main content -->
		
		 <?= $content ?>
        <!-- /.content -->
      </aside><!-- /.content-wrapper -->
	  
      <footer class="main-footer1">
 
        <div class="pull-right hidden-xs">
          
        </div>
        <strong> &copy; Doodhvale Dairy Farms <?= date('Y') ?> . </strong> All rights reserved.
      </footer>
	<?php 
 	      }  ?>
    </div><!-- ./wrapper -->
	<?php /*if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}*/ ?>
 <?php $this->endBody() ?> 
 </body>
</html>
<?php $this->endPage() ?>

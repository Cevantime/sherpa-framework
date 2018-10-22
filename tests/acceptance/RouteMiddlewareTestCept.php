<?php 
$I = new AcceptanceTester($scenario);

$I->wantTo('test route middleware');

$I->amOnPage('/middleware');
$I->see('Hello Sherpa !!');

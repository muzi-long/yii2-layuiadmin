<?php
namespace backend\rules;

use yii\rbac\Rule;

class AAA extends Rule
{
    public $name = 'AAA';

    public function execute($user, $item, $params)
    {
        // TODO: Implement execute() method.
        return $user==1;
    }
}
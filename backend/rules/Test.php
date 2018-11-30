<?php
namespace backend\rules;

use yii\rbac\Rule;

class Test extends Rule
{
    public $name = 'Test';

    public function execute($user, $item, $params)
    {
        // TODO: Implement execute() method.
        return $user==1;
    }
}
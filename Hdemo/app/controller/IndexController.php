<?php

use models\User;
use models\test2\Test;

class IndexController extends Controller{

    public function actionIndex(){
        $this->render();
    }

    public function actionTest(){
        echo '当前访问控制器：'.$this->controller.' 当前方法：'.$this->action;
    }

    public function actionModel(){
        $res = User::model()->join('left join {{pwd}} as pw on pw.user_id = t.id')->query();
        p($res);
    }

    public function actionTest2(){
        $test = new Test();
        $test->say();
    }

}
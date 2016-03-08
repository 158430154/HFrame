<?php

namespace models\test2;
use HModel;

class Test extends HModel{

    public function say(){
        echo '我是被自动加载的<br>';
    }

}
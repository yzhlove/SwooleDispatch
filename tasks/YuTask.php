<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/15
 * Time: 下午7:25
 */

class YuTask extends \Phalcon\Cli\Task {

    public function indexAction() {

        $loop_queue = new \games\room\LoopQueue("1234");

        for ($i = 1;$i <= 5;$i++)
            $loop_queue->push($i);

        var_dump($loop_queue->show());

        $arr = $loop_queue->next();
        echo "next:" . var_dump($arr);

        var_dump($loop_queue->show());

        $loop_queue->pop($arr[0]);

        var_dump($loop_queue->show());

        $loop_queue->delete();


    }

}
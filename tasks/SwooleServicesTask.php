<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/2/3
 * Time: 下午1:33
 */

class SwooleServicesTask extends \Phalcon\CLI\Task
{

    //开启服务
    public function startAction()
    {
        $this->service = new \services\SwooleServices();
        $this->service->startService();
    }

    //重启服务
    public function stopAction()
    {
        $url = "127.0.0.1:9601?action=stop";
        $resp = httpGet($url);
        info("StopAction:", $resp);
    }

    //关闭服务
    public function reloadAction()
    {
        $url = "127.0.0.1:9601?action=reload";
        $resp = httpGet($url);
        info("ReloadAction:", $resp);
    }

    // 杀掉进程
    public function killAction()
    {
        $log_dir = $this->config->application->log;
        checkDirExists("{$log_dir}/pids/");
        if (file_exists("{$log_dir}/pids/websocket_server.pid")) {
            $pid = file_get_contents("{$log_dir}/pids/websocket_server.pid");
            $pid = intval(trim($pid));
            if (!$pid || @pcntl_getpriority($pid) === false) {
                info('websocket process not exited!');
                file_put_contents("{$log_dir}/pids/websocket_server.pid", '');
                return true;
            }
            $result = posix_kill($pid, SIGTERM);
            if ($result) {
                file_put_contents("{$log_dir}/pids/websocket_server.pid", '');
                info('websocket process exited!');
                info("###stop websocket###");
                return true;
            } else {
                info('can not kill websocket process!');
                return false;
            }
        }
        return true;
    }
}
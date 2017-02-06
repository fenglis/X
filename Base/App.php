<?php
namespace X\Base;

class App {
    public function initSystemHandlers() {
        set_error_handler([$this, 'ErrorHandler']);
        set_exception_handler([$this, 'ExceptionHandler']);
    }

    /**
    * 接管错误处理
    * 系统出错时将调用并传递4个参数,错误号,代码,行,及文件
    * 为统一处理,把错误包装成异常抛出.
    */


    /**
     *  接管系统异常处理
     */
    public function ExceptionHandler($exception) {
        //禁止在处理错误或异常，防止递归
        restore_error_handler();
        restore_exception_handler();

        $this->handler($exception);
    }

    /**
     * 输出异常函数
     */
    public function handler($e) {
        $fileName = $e->getFile();  //错误文件名
        $errorLine = $e->getLine(); //错误行号
        $trace = $e->getTrace();    //错误栈

        $title = $fileName . "Line[$errorLine]" . 'reason: ' . $e->getMessage();
        echo '<h2>' . $title . '</h2>';

        if($e instanceof \ErrorException) {     //判断$e是否为 ErrorException的实例
            array_shift($trace);
        }

        foreach($trace as $i =>$t) {
            if(!isset($t['file'])) {
                $trace[$i]['file'] = 'unknown';
            }
            if(!isset($t['line']))
                $trace[$i]['line']=0;

            if(!isset($t['function']))
                $trace[$i]['function']='unknown';

            unset($trace[$i]['object']);
        }

        echo "<pre>";
        print_r($trace);
    }

}

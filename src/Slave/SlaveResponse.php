<?php
namespace Jorker\Slave;

use Jorker\Slave\Error\SlaveError;
use Jorker\Slave\Error\SlaveErrorInterface;
use Jorker\Slave\Error\SlaveException;

/**
 * 子进程响应回传
 * Class SlaveResponse
 * @package Jorker\Slave
 */
class SlaveResponse
{
    /**
     * @var bool
     */
    public $ok;

    /**
     * @var mixed
     */
    public $body;

    /**
     * @var SlaveInfo
     */
    public $slaveInfo;

    /**
     * @var SlaveErrorInterface 程序抛出的异常/错误
     */
    public $error;

    /**
     * 用于通知主进程，子进程是否正在退出，正在退出时不再分发任务给该进程
     * @var bool
     */
    public $exiting = false;

    /**
     * 执行成功回传
     * @return SlaveResponse
     */
    public static function complete()
    {
        $ins = new SlaveResponse();
        $ins->ok = true;
        return $ins;
    }

    /**
     * 执行失败回传
     * @param \Exception|array $error
     * @return SlaveResponse
     */
    public static function fail($error)
    {
        $ins = new SlaveResponse();
        $ins->ok = false;
        $ins->error = ($error instanceof \Exception) ? new SlaveException($error) : new SlaveError($error);
        return $ins;
    }

    public function setExiting($exiting)
    {
        $this->exiting = $exiting;
    }

    public function __toString()
    {
        return json_encode(
            ['ok' => $this->ok]
            + ($this->error ? ['error' => (string)$this->error] : [])
        );
    }
}
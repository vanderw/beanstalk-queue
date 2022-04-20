<?php

/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    vanderwaal<grant.vanderwaal@gmail.com>
 * @copyright vanderwaal<grant.vanderwaal@gmail.com>
 * @link      https://github.com/vanderw/beanstalk-queue
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Vanderw\BeanstalkQueue;

use Pheanstalk\Pheanstalk;

/**
 * Class BeanstalkQueue
 * @package support
 *
 * Strings methods
 * @method static void send($queue, $data, $delay=0)
 */
class Client
{
    /**
     * @var Client[]
     */
    protected static $_connections = null;
    

    /**
     * @param string $name
     * @return RedisClient
     */
    public static function connection($name = 'default') {
        if (!isset(static::$_connections[$name])) {
            $config = config('beanstalk_queue', config('plugin.vanderw.beanstalk-queue.beanstalk', []));
            if (!isset($config[$name])) {
                throw new \RuntimeException("BeanstalkQueue connection $name not found");
            }
            $ip = $config[$name]['ip'];
            $port = $config[$name]['port'];
            $timeout = $config[$name]['timeout'];
            // $options = $config[$name]['options'];
            $client = Pheanstalk::create($ip, $port, $timeout);
            static::$_connections[$name] = $client;
        }
        return static::$_connections[$name];
    }

    /**
     * 
     * @param string $tube tube 名稱
     * @param string|array $data 進入 queue 參數，array 自動執行 json_encode
     * @param int 優先級
     * @param int $delay 延遲秒數
     * @param int $retry_after 幾秒後重試
     * @param string $instance beanstalk 實例，對應 beanstalk.php 配置 key 
     */
    public static function send($tube, $data, $priority=Pheanstalk::DEFAULT_PRIORITY, $delay=Pheanstalk::DEFAULT_DELAY, $retry_after=Pheanstalk::DEFAULT_TTR, $instance='default')
    {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }
        return static::connection($instance)
            ->useTube($tube)
            ->put($data, $priority, $delay, $retry_after);
    }

    public static function watch($tube, $instance='default')
    {
        return static::connection($instance)->watch($tube);
    }

    /**
     * 阻塞直到從隊列取到 job
     * 
     * job 對象有 getData() 函數來取得任務 payload
     * 
     * @param string $instance
     * @return Job
     * 
     */
    public static function reserve($instance='default')
    {
        return static::connection($instance)->reserve();
    }

    /**
     * 帶超時的 reserve
     * 
     * @param int $timeout 超時秒數
     * @return Job|null
     */
    public static function reserve_with_timeout($timeout, $instance='default')
    {
        return static::connection($instance)->reserveWithTimeout($timeout);
    }

    /**
     * 在 beanstalk 服務預估的時間內客戶端未完成，客戶端請求 touch 來續期
     * 
     * @param object $job reserve 的返回值
     */
    public static function touch($job, $instance='default')
    {
        return static::connection($instance)->touch($job);
    }

    /**
     * 執行 job 完成後刪除
     * 
     */
    public static function delete($job, $instance='default')
    {
        return static::connection($instance)->delete($job);
    }

    /**
     * 執行 job 條件未滿足情況下重新入 queue，其他 consumer 可能會執行它
     * 
     */
    public static function release($job, $instance='default')
    {
        return static::connection($instance)->release($job);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::connection('default')->{$name}(... $arguments);
    }
}

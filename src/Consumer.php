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


/**
 * Interface Consumer
 * @package Vanderw\BeanstalkQueue
 */
interface Consumer
{
    public function consume($job);
}
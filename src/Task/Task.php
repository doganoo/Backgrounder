<?php
declare(strict_types=1);
declare(ticks=1);
/**
 * MIT License
 *
 * Copyright (c) 2018 Dogan Ucar, <dogan@dogan-ucar.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace doganoo\Backgrounder\Task;

use doganoo\Backgrounder\Service\Log\ILoggerService;
use doganoo\Backgrounder\Util\Util;

/**
 * Class Task
 *
 * @package doganoo\Backgrounder\Task
 */
abstract class Task {

    /** @var array SIGNALS */
    private const SIGNALS = [
        SIGTERM
        , SIGHUP
    ];

    /** @var ILoggerService */
    private $logger;

    /** @var bool */
    private $debug;

    /**
     * runs the job
     */
    public function run(): bool {
        $this->registerSignalHandler();
        $this->onAction();
        $ran = $this->action();
        $this->onClose();
        return $ran;
    }

    /**
     * registers the signal handler.
     * Currently, there are only SIGTERM and SIGHUP supported.
     */
    private function registerSignalHandler(): void {

        foreach (Task::SIGNALS as $signal) {
            if (false === Util::extensionExists("pcntl") &&
                false === Util::functionExists("pcntl_signal")) {
                echo "\nthe pcntl extension is missing. Can not handle signals\n";
                continue;
            }
            pcntl_signal($signal, [$this, "handleSignal"]);
        }

    }

    /**
     * runs before action is performed
     */
    protected abstract function onAction(): void;

    /**
     * performs the action
     *
     * @return bool
     */
    protected abstract function action(): bool;

    /**
     * runs after action is performed
     */
    protected abstract function onClose(): void;

    /**
     * @return ILoggerService
     */
    public function getLogger(): ILoggerService {
        return $this->logger;
    }

    /**
     * @param ILoggerService $logger
     */
    public function setLogger(ILoggerService $logger): void {
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug): void {
        $this->debug = $debug;
    }

    /**
     * currently nothing. Override when needed!
     *
     * @param int $number
     */
    protected function handleSignal(int $number): void {
        return;
    }

}

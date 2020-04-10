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

namespace doganoo\Backgrounder\BackgroundJob;

use DateTime;
use doganoo\Backgrounder\Util\Util;
use function pcntl_signal;

/**
 * Class Job
 * @package doganoo\Backgrounder\BackgroundJob
 */
abstract class Job {

    /** @var string JOB_TYPE_ONE_TIME */
    public const JOB_TYPE_ONE_TIME = "time.one.type.job";
    /** @var string JOB_TYPE_REGULAR */
    public const JOB_TYPE_REGULAR = "regular.type.job";

    /** @var array SIGNALS */
    private const SIGNALS = [
        SIGTERM
        , SIGHUP
    ];

    /** @var int $id */
    private $id = 0;
    /** @var int $interval */
    private $interval = null;
    /** @var string $type */
    private $type = null;
    /** @var null|int $lastRun */
    private $lastRun = null;
    /** @var array $info */
    private $info = null;
    /** @var DateTime $createTs */
    private $createTs;

    /**
     * Job constructor.
     * @param int    $interval
     * @param string $type
     */
    public function __construct(
        int $interval
        , string $type = Job::JOB_TYPE_REGULAR
    ) {
        $this->setInterval($interval);
        $this->setType($type);
        $this->info = [];
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @param string $type
     */
    protected function setType(string $type): void {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param int $interval
     */
    protected function setInterval(int $interval): void {
        $this->interval = $interval;
    }

    /**
     * @return int
     */
    public function getInterval(): int {
        return $this->interval;
    }

    /**
     * @param int $lastRun
     */
    public function setLastRun(int $lastRun): void {
        $this->lastRun = $lastRun;
    }

    /**
     * @return int|null
     */
    public function getLastRun(): ?int {
        return $this->lastRun;
    }

    /**
     * @param array $info
     */
    public function setInfo(array $info): void {
        $this->info = $info;
    }

    /**
     * @return array
     */
    public function getInfo(): array {
        return $this->info;
    }

    /**
     * @param $key
     * @param $info
     */
    public function addInfo($key, $info): void {
        $this->info[$key] = $info;
    }

    /**
     * @return DateTime
     */
    public function getCreateTs(): DateTime {
        return $this->createTs;
    }

    public function setCreateTs(DateTime $createTs): void {
        $this->createTs = $createTs;
    }

    /**
     * runs the job
     */
    public function run() {
        $this->registerSignalHandler();
        $this->onAction();
        $this->action();
        $this->afterAction();
    }

    /**
     * registers the signal handler.
     * Currently, there are only SIGTERM and SIGHUP supported.
     */
    private function registerSignalHandler(): void {

        foreach (Job::SIGNALS as $signal) {
            if (false === Util::extensionExists("pcntl") &&
                false === Util::functionExists("pcntl_signal")) {
                echo "\nthe pcntl extension is missing. Can not handle signals\n";
                continue;
            }
            pcntl_signal($signal, [$this, "handleSignal"]);
        }

    }

    /**
     * currently nothing. Override when needed!
     *
     * @param int $number
     */
    protected function handleSignal(int $number): void {
        return;
    }

    protected abstract function onAction(): void;

    protected abstract function action(): void;

    protected abstract function afterAction(): void;

}

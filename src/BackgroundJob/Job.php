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
use DateTimeInterface;

/**
 * Class Job
 * @package doganoo\Backgrounder\BackgroundJob
 */
class Job {

    /** @var string JOB_TYPE_ONE_TIME */
    public const JOB_TYPE_ONE_TIME = "time.one.type.job";
    /** @var string JOB_TYPE_REGULAR */
    public const JOB_TYPE_REGULAR = "regular.type.job";

    private int                $id       = 0;
    private string             $name;
    private int                $interval = 0;
    private string             $type     = '';
    private ?DateTimeInterface $lastRun  = null;
    private ?array             $info     = null;
    private DateTimeInterface  $createTs;

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
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void {
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
    public function setInterval(int $interval): void {
        $this->interval = $interval;
    }

    /**
     * @return int
     */
    public function getInterval(): int {
        return $this->interval;
    }

    /**
     * @param DateTimeInterface|null $lastRun
     * @return void
     */
    public function setLastRun(?DateTimeInterface $lastRun): void {
        $this->lastRun = $lastRun;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastRun(): ?DateTimeInterface {
        return $this->lastRun;
    }

    /**
     * @param null|array $info
     */
    public function setInfo(?array $info): void {
        $this->info = $info;
    }

    /**
     * @return array|null
     */
    public function getInfo(): ?array {
        return $this->info;
    }

    /**
     * @param string $key
     * @param mixed  $info
     */
    public function addInfo(string $key, $info): void {
        $this->info[$key] = $info;
    }

    /**
     * @return bool
     */
    public function isOneTime(): bool {
        return Job::JOB_TYPE_ONE_TIME === $this->getType();
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreateTs(): DateTimeInterface {
        return $this->createTs;
    }

    public function setCreateTs(DateTimeInterface $createTs): void {
        $this->createTs = $createTs;
    }

}

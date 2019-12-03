<?php
declare(strict_types=1);
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

namespace doganoo\Backgrounder;

use DateTime;
use doganoo\Backgrounder\BackgroundJob\Job;
use doganoo\Backgrounder\BackgroundJob\JobList;
use doganoo\Backgrounder\BackgroundJob\OneTimeJob;
use doganoo\PHPUtil\FileSystem\DirHandler;
use Exception;

/**
 * Class Backgrounder
 * @package doganoo\Backgrounder
 */
class Backgrounder {

    /** @var string LOCK_FILE_NAME */
    public const LOCK_FILE_NAME = 'name.file.lock.lock';
    /** @var string ONE_TIME_JOB_ALREADY_RAN */
    public const ONE_TIME_JOB_ALREADY_RAN = "ran.already.job.time.one";
    /** @var string REGULAR_JOB_INTERVAL_NOT_REACHED */
    public const REGULAR_JOB_INTERVAL_NOT_REACHED = "reached.not.interval.job.regular";
    /** @var string JOB_RUN_REGULARLY */
    public const JOB_RUN_REGULARLY = "regularly.run.job";

    /** @var JobList $jobList */
    private $jobList = null;
    /** @var bool $locked */
    private $locked = false;
    /** @var bool $debug */
    private $debug = false;

    /**
     * Backgrounder constructor.
     * @param JobList|null $jobList
     */
    public function __construct(?JobList $jobList) {
        $this->setJobList($jobList);
    }

    /**
     * @param JobList|null $jobList
     */
    public function setJobList(?JobList $jobList): void {
        $this->jobList = $jobList;
    }

    /**
     * @return JobList|null
     */
    public function getJobList(): ?JobList {
        return $this->jobList;
    }

    /**
     * @return bool
     */
    private function lock(): bool {
        if ($this->isLocked()) return false;

        $tempDir    = sys_get_temp_dir() . "/";
        $dirHandler = new DirHandler($tempDir);

        $created = $dirHandler->createFile(
            Backgrounder::LOCK_FILE_NAME
            , false
            , (string) getmygid()
        );

        $this->setLocked($created);
        return $created;
    }

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug): void {
        $this->debug = $debug;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool {
        return $this->debug;
    }

    /**
     * @return bool
     */
    private function unlock(): bool {
        if (false === $this->isLocked()) return true;
        $tempDir    = sys_get_temp_dir() . "/";
        $dirHandler = new DirHandler($tempDir);
        $hasFile    = $dirHandler->hasFile(Backgrounder::LOCK_FILE_NAME);
        if (false === $hasFile) {
            $this->setLocked($hasFile);
            return true;
        }
        $deleted = $dirHandler->deleteFile(Backgrounder::LOCK_FILE_NAME);
        $this->setLocked($deleted);
        return $deleted;
    }

    /**
     * @return JobList|null
     * @throws Exception
     */
    public function run(): ?JobList {

        if (null === $this->getJobList()) return null;
        if ($this->isLocked()) return $this->jobList;

        $this->lock();

        $now = (new DateTime())->getTimestamp();

        /**
         * @var int $key
         * @var Job $job
         */
        foreach ($this->getJobList() as $key => &$job) {

            if ($job instanceof OneTimeJob && null !== $job->getLastRun()) {
                $job->addInfo("status", Backgrounder::ONE_TIME_JOB_ALREADY_RAN);
                continue;
            }


            $skippable = $this->isSkippable(
                $job->getLastRun()
                , $job->getInterval()
                , $now
            );

            if (true === $skippable) {
                $job->addInfo("status", Backgrounder::REGULAR_JOB_INTERVAL_NOT_REACHED);
                continue;
            }

            $job->run();
            $lastRun = (new DateTime())->getTimestamp();
            $job->setLastRun($lastRun);
            $job->addInfo("status", Backgrounder::JOB_RUN_REGULARLY);

        }

        $this->unlock();
        return $this->getJobList();
    }

    private function isSkippable(?int $lastRun, int $interval, int $now): bool {
        $lastRun   = null === $lastRun ? 0 : $lastRun;
        $skippable = ($lastRun + $interval) > $now;
        return true === $skippable && false === $this->isDebug();
    }

    /**
     * @return bool
     */
    public function isLocked(): bool {
        return $this->locked;
    }

    /**
     * @param bool $locked
     */
    public function setLocked(bool $locked): void {
        $this->locked = $locked;
    }

}
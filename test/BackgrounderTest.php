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

namespace doganoo\Backgrounder\Test;

use DateTime;
use doganoo\Backgrounder\Backgrounder;
use doganoo\Backgrounder\BackgroundJob\Job;
use doganoo\Backgrounder\BackgroundJob\OneTimeJob;
use doganoo\Backgrounder\BackgroundJob\RegularJob;
use doganoo\PHPUtil\Util\DateTimeUtil;
use PHPUnit\Framework\TestCase;


/**
 * Class BackgrounderTest
 */
class BackgrounderTest extends TestCase {

    /**
     * test backgrounder
     */
    public function testBackgrounder() {
        $jobList = Util::getJobList();

        $backgrounder = new Backgrounder($jobList);
        $result       = $backgrounder->run();

        /** @var Job $value */
        foreach ($result as $value) {
            $this->assertTrue($value->getInfo()["status"] === Backgrounder::JOB_RUN_REGULARLY);
        }

        $backgrounder = new Backgrounder($result);
        $result       = $backgrounder->run();

        /** @var Job $value */
        foreach ($result as $value) {
            if ($value instanceof OneTimeJob) {
                $this->assertTrue($value->getInfo()["status"] === Backgrounder::ONE_TIME_JOB_ALREADY_RAN);
            } else {
                if ($value instanceof RegularJob) {
                    $this->assertTrue($value->getInfo()["status"] === Backgrounder::REGULAR_JOB_INTERVAL_NOT_REACHED);
                } else {
                    $this->assertTrue(false);
                }
            }

        }

    }

    /**
     * test interval
     */
    public function testInterval() {
        $jobList = Util::getRegularJob(1);

        $backgrounder = new Backgrounder($jobList);
        $result       = $backgrounder->run();

        /** @var Job $job */
        $job = $result->get(0);

        $this->assertTrue($job->getInfo()["status"] === Backgrounder::JOB_RUN_REGULARLY);

        $backgrounder = new Backgrounder($result);
        $result       = $backgrounder->run();

        /** @var Job $job */
        $job = $result->get(0);

        $this->assertTrue($job->getInfo()["status"] === Backgrounder::REGULAR_JOB_INTERVAL_NOT_REACHED);

        $lastRun = new DateTime();
        $lastRun->setTimestamp(1);
        $job->setLastRun($lastRun);
        $backgrounder = new Backgrounder($result);
        $result       = $backgrounder->run();

        /** @var Job $job */
        $job = $result->get(0);

        $this->assertTrue($job->getInfo()["status"] === Backgrounder::JOB_RUN_REGULARLY);

    }

}

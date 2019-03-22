<?php
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

use doganoo\Backgrounder\BackgroundJob\JobList;
use Object\OneTimeJob;
use Object\RegularJob;

/**
 * Class Util
 */
class Util {

    /**
     * Util constructor.
     */
    private function __construct() {
    }

    /**
     * @return JobList
     */
    public static function getJobList(): JobList{

        $jobList = new JobList();
        $jobList->add(new OneTimeJob());
        $jobList->add(new RegularJob(5 * 60 * 60));

        return $jobList;
    }

    /**
     * @param int $amount
     * @return JobList
     */
    public static function getRegularJob(int $amount = 1): JobList{

        $jobList = new JobList();
        for ($i = 0;$i<$amount;$i++){
            $jobList->add(new RegularJob(5 * 60 * 60));
        }

        return $jobList;
    }

}
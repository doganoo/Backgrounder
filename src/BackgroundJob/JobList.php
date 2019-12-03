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

namespace doganoo\Backgrounder\BackgroundJob;

use doganoo\Backgrounder\Exception\InvalidJobException;
use doganoo\PHPAlgorithms\Datastructure\Lists\ArrayLists\ArrayList;

/**
 * Class JobList
 * @package doganoo\Backgrounder\BackgroundJob
 */
class JobList extends ArrayList {

    /**
     * @param $item
     * @return bool
     * @throws InvalidJobException
     */
    public function add($item): bool {

        if ($item instanceof Job) {
            return parent::add($item);
        }
        throw new InvalidJobException();
    }

    /**
     * @param ArrayList $arrayList
     * @return bool
     * @throws InvalidJobException
     */
    public function addAll(ArrayList $arrayList): bool {

        if ($arrayList instanceof JobList) {
            return parent::addAll($arrayList);
        }
        throw new InvalidJobException();
    }

    /**
     * @param int $index
     * @param     $item
     * @return bool
     * @throws InvalidJobException
     */
    public function addToIndex(int $index, $item): bool {

        if ($item instanceof Job) {
            return parent::addToIndex($index, $item);
        }
        throw new InvalidJobException();

    }

}
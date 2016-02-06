<?php
/*
 * Copyright 2015-2016
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace alxmsl\Connection\Tests\Postgresql;

use alxmsl\Connection\Postgresql\QueryTemplate;
use PHPUnit_Framework_Error_Warning;
use PHPUnit_Framework_TestCase;

/**
 * QueryTemplate class test.
 * Require postgres host with
 *  host=postgres
 *  user=postgres
 * @author mkrasilnikov
 */
class QueryTemplateTest extends PHPUnit_Framework_TestCase {

    /**
     * @inheritdoc
     */
    public function setUp() {
        parent::setUp();
        try {
            pg_connect('host=postgres user=postgres');
        } catch (PHPUnit_Framework_Error_Warning $Exception) {
            $this->markTestSkipped('Missing postgres connection: host=postgres user=postgres');
        }
    }

    /**
     * @dataProvider jsonDataProvider
     * @param $source
     * @param $expected
     */
    public function testJson($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->json($source));
    }

    /**
     * @dataProvider stringDataProvider
     * @param $source
     * @param $expected
     */
    public function testStr($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->str($source));
    }

    /**
     * @dataProvider intDataProvider
     * @param $source
     * @param $expected
     */
    public function testInt($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->int($source));
    }

    /**
     * @dataProvider floatDataProvider
     * @param $source
     * @param $expected
     */
    public function testFloat($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->float($source));
    }

    /**
     * @dataProvider boolDataProvider
     * @param $source
     * @param $expected
     */
    public function testBool($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->bool($source));
    }

    /**
     * @dataProvider rowDataProvider
     * @param $source
     * @param $expected
     */
    public function testRow($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->row($source));
    }

    /**
     * @dataProvider tblDataProvider
     * @param $source
     * @param $expected
     */
    public function testTbl($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->tbl($source));
    }

    /**
     * @dataProvider arrIntDataProvider
     * @param $source
     * @param $expected
     */
    public function testArrInt($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->arrint($source));
    }

    /**
     * @dataProvider arrStrDataProvider
     * @param $source
     * @param $expected
     */
    public function testArrStr($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->arrstr($source));
    }

    /**
     * @dataProvider inintDataProvider
     * @param $source
     * @param $expected
     */
    public function testInint($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->inint($source));
    }

    /**
     * @dataProvider instrDataProvider
     * @param $source
     * @param $expected
     */
    public function testInstr($source, $expected) {
        $this->assertEquals($expected, $this->getQueryTemplate()->instr($source));
    }

    /**
     * @return QueryTemplate
     */
    private function getQueryTemplate() {
        return new QueryTemplate();
    }

    /**
     * @return array
     */
    public function arrIntDataProvider() {
        return array(
            array(
                array(1, 2, 3),
                '\'{1,2,3}\'',
            ),
            array(
                array(),
                '\'{}\'',
            ),
            array(
                array(null, 'aaa', 1),
                '\'{0,0,1}\'',
            ),
        );
    }

    /**
     * @return array
     */
    public function arrStrDataProvider() {
        return array(
            array(
                array('foo', 'bar'),
                '\'{"foo","bar"}\'',
            ),
            array(
                array(1, 2, 3),
                '\'{"1","2","3"}\'',
            ),
            array(
                array(null, 2, 'foo'),
                '\'{"","2","foo"}\'',
            ),
        );
    }

    /**
     * @return array
     */
    public function inintDataProvider() {
        return array(
            array(
                array('foo', 'bar'),
                '(0,0)',
            ),
            array(
                array(1, 2, 3),
                '(1,2,3)',
            ),
            array(
                array(null, 2, 'foo'),
                '(0,2,0)',
            ),
        );
    }

    /**
     * @return array
     */
    public function instrDataProvider() {
        return array(
            array(
                array('foo', 'bar'),
                '(\'foo\',\'bar\')',
            ),
            array(
                array(1, 2, 3),
                '(\'1\',\'2\',\'3\')',
            ),
            array(
                array(null, 2, 'foo'),
                '(\'\',\'2\',\'foo\')',
            ),
        );
    }

    /**
     * @return array
     */
    public function rowDataProvider() {
        return array(
            array(
                123,
                123,
            ),
            array(
                'foo',
                'foo',
            ),
            array(
                null,
                null,
            ),
            array(
                array('foo', 'bar'),
                array('foo', 'bar'),
            ),
        );
    }

    /**
     * @return array
     */
    public function tblDataProvider() {
        return array(
            array(
                123,
                '"123"',
            ),
            array(
                'foo',
                '"foo"',
            ),
            array(
                null,
                '""',
            ),
        );
    }

    /**
     * @return array
     */
    public function boolDataProvider() {
        return array(
            array(
                true,
                'true',
            ),
            array(
                false,
                'false',
            ),
            array(
                0,
                'false',
            ),
            array(
                'foo',
                'true',
            ),
            array(
                array(),
                'false',
            ),
            array(
                array('foo'),
                'true',
            ),
            array(
                null,
                'false',
            ),
        );
    }

    /**
     * @return array
     */
    public function floatDataProvider() {
        return array(
            array(
                1.1,
                1.1,
            ),
            array(
                0,
                0,
            ),
            array(
                null,
                0,
            ),
            array(
                array(),
                0,
            ),
            array(
                'fooo',
                0,
            ),
        );
    }

    /**
     * @return array
     */
    public function intDataProvider() {
        return array(
            array(
                123,
                123,
            ),
            array(
                null,
                0,
            ),
            array(
                'foo',
                0,
            ),
            array(
                array(),
                0,
            ),
            array(
                array('foo'),
                1,
            ),
            array(
                array('foo', 'bar'),
                1,
            ),
        );
    }

    /**
     * @return array
     */
    public function stringDataProvider() {
        return array(
            array(
                'foo',
                '\'foo\'',
            ),
            array(
                '123',
                '\'123\'',
            ),
            array(
                null,
                '\'\'',
            ),
        );
    }

    /**
     * @return array
     */
    public function jsonDataProvider() {
        return array(
            array(
                array(1, 2, 3, 4),
                '\'[1,2,3,4]\'',
            ),
            array(
                array('foo', 'bar'),
                '\'["foo","bar"]\'',
            ),
            array(
                array('foo' => 'bar', 'bee' => 'baap'),
                '\'{"foo":"bar","bee":"baap"}\''
            ),
            array(
                array(
                    array(
                        'foo' => 'bar',
                        'bee' => 'baap'
                    ),
                    array(1, 2, 3),
                ),
                '\'[{"foo":"bar","bee":"baap"},[1,2,3]]\''
            ),
            array(
                array(null, '123', 'foo'),
                '\'[null,"123","foo"]\''
            )
        );
    }
}

<?php
/**
 * Copyright 2011 Bas de Nooijer. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this listof conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are
 * those of the authors and should not be interpreted as representing official
 * policies, either expressed or implied, of the copyright holder.
 */

class Solarium_Query_SelectTest extends PHPUnit_Framework_TestCase
{

    protected $_query;

    public function setUp()
    {
        $this->_query = new Solarium_Query_Select;
    }

    public function testSetAndGetStart()
    {
        $this->_query->setStart(234);
        $this->assertEquals(234, $this->_query->getQuery());
    }

    public function testSetAndGetQueryWithTrim()
    {
        $this->_query->setQuery(' *:* ');
        $this->assertEquals('*:*', $this->_query->getStart());
    }

    public function testSetAndGetResultClass()
    {
        $this->_query->setResultClass('MyResult');
        $this->assertEquals('MyResult', $this->_query->getResultClass());
    }

    public function testSetAndGetDocumentClass()
    {
        $this->_query->setDocumentClass('MyDocument');
        $this->assertEquals('MyDocument', $this->_query->getDocumentClass());
    }

    public function testSetAndGetRows()
    {
        $this->_query->setRows(100);
        $this->assertEquals(100, $this->_query->getRows());
    }

    public function testAddField()
    {
        $expectedFields = $this->_query->getFields();
        $expectedFields[] = 'newfield';
        $this->assertEquals($expectedFields, $this->_query->addField('newfield'));
    }

    public function testClearFields()
    {
        $this->_query->addField('newfield');
        $this->_query->clearFields();
        $this->assertEquals(array(), $this->_query->getFields());
    }

    public function testAddFields()
    {
        $fields = array('field1','field2');

        $this->_query->clearFields();
        $this->_query->addFields($fields);
        $this->assertEquals($fields, $this->_query->getFields());
    }

    public function testAddFieldsAsStringWithTrim()
    {
        $this->_query->clearFields();
        $this->_query->addFields('field1, field2');
        $this->assertEquals(array('field1','field2'), $this->_query->getFields());
    }

    public function testRemoveField()
    {
        $this->_query->clearFields();
        $this->_query->addFields(array('field1','field2'));
        $this->_query->removeField('field1');
        $this->assertEquals(array('field2'), $this->_query->getFields());
    }

    public function testSetFields()
    {
        $this->_query->clearFields();
        $this->_query->addFields(array('field1','field2'));
        $this->_query->setFields(array('field3','field4'));
        $this->assertEquals(array('field3','field4'), $this->_query->getFields());
    }

    public function testAddSortField()
    {
        $this->_query->addSortField('field1', Solarium_Query_Select::SORT_DESC);
        $this->assertEquals(
            array('field1' => Solarium_Query_Select::SORT_DESC),
            $this->_query->getSortFields()
        );
    }

    public function testAddSortFields()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->assertEquals(
            $sortFields,
            $this->_query->getSortFields()
        );
    }

    public function testRemoveSortField()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->removeSortField('field1');
        $this->assertEquals(
            array('field2' => Solarium_Query_Select::SORT_ASC),
            $this->_query->getSortFields()
        );
    }

    public function testRemoveInvalidSortField()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->removeSortField('invalidfield'); //continue silently
        $this->assertEquals(
            $sortFields,
            $this->_query->getSortFields()
        );
    }

    public function testClearSortFields()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->clearSortFields();
        $this->assertEquals(
            array(),
            $this->_query->getSortFields()
        );
    }

    public function testSetSortFields()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->setSortFields(array('field3' => Solarium_Query_Select::SORT_ASC));
        $this->assertEquals(
            array('field3' => Solarium_Query_Select::SORT_ASC),
            $this->_query->getSortFields()
        );
    }

    /**

    TODO testAddFilterQuery

    TODO testAddFilterQueries

    TODO testGetFilterQueryInvalidTag

    TODO testGetFilterQuery

    TODO testRemoveFilterQueryInvalidTag

    TODO testRemoveFilterQuery

    TODO testClearFilterQueries

    TODO testSetFilterQueries

    */
}
<?php
/**
 * Copyright (c) 2009 - 2011, RealDolmen
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of RealDolmen nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY RealDolmen ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL RealDolmen BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Microsoft
 * @package    Microsoft_WindowsAzure
 * @subpackage UnitTests
 * @version    $Id: BlobStorageTest.php 14561 2009-05-07 08:05:12Z unknown $
 * @copyright  Copyright (c) 2009 - 2011, RealDolmen (http://www.realdolmen.com)
 * @license    http://phpazure.codeplex.com/license
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Microsoft_WindowsAzure_TableEntityQueryTest::main');
}

/**
 * Test helpers
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';
require_once dirname(__FILE__) . '/../../TestConfiguration.php';
require_once 'PHPUnit/Framework/TestCase.php';

/** Microsoft_WindowsAzure_Storage_TableEntityQuery */
require_once 'Microsoft/WindowsAzure/Storage/TableEntityQuery.php';

/**
 * @category   Microsoft
 * @package    Microsoft_WindowsAzure
 * @subpackage UnitTests
 * @version    $Id: BlobStorageTest.php 14561 2009-05-07 08:05:12Z unknown $
 * @copyright  Copyright (c) 2009 - 2011, RealDolmen (http://www.realdolmen.com)
 * @license    http://phpazure.codeplex.com/license
 */
class Microsoft_WindowsAzure_TableEntityQueryTest extends PHPUnit_Framework_TestCase
{
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Microsoft_WindowsAzure_TableEntityQueryTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }
    
    /**
     * Test all records query
     */
    public function testAllRecordsQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable');
               
        $this->assertEquals('MyTable()', $target->__toString());
    }
    
    /**
     * Test partition key query
     */
    public function testPartitionKeyQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->wherePartitionKey('test');
               
        $this->assertEquals('MyTable(PartitionKey=\'test\')', $target->__toString());
    }
    
    /**
     * Test row key query
     */
    public function testRowKeyQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->whereRowKey('test');
               
        $this->assertEquals('MyTable(RowKey=\'test\')', $target->__toString());
    }
    
    /**
     * Test identifier query
     */
    public function testIdentifierQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->wherePartitionKey('test')
               ->whereRowKey('123');
               
        $this->assertEquals('MyTable(PartitionKey=\'test\', RowKey=\'123\')', $target->__toString());
    }
    
    /**
     * Test top records query
     */
    public function testTopQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->top(10);
               
        $this->assertEquals('MyTable()?$top=10', $target->__toString());
    }
    
    /**
     * Test order by query
     */
    public function testOrderByQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->orderBy('Name', 'asc');
               
        $this->assertEquals('MyTable()?$orderby=Name asc', $target->__toString());
    }
    
    /**
     * Test order by multiple query
     */
    public function testOrderByMultipleQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->orderBy('Name', 'asc')
               ->orderBy('Visible', 'desc');
               
        $this->assertEquals('MyTable()?$orderby=Name asc,Visible desc', $target->__toString());
    }
    
    /**
     * Test where query
     */
    public function testWhereQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->where('Name eq ?', 'Maarten');
               
        $this->assertEquals('MyTable()?$filter=Name eq \'Maarten\'', $target->__toString());
    }
    
    /**
     * Test where array query
     */
    public function testWhereArrayQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->where('Name eq ? or Name eq ?', array('Maarten', 'Vijay'));
               
        $this->assertEquals('MyTable()?$filter=Name eq \'Maarten\' or Name eq \'Vijay\'', $target->__toString());
    }
    
    /**
     * Test where multiple query
     */
    public function testWhereMultipleQuery()
    {
        $target = new Microsoft_WindowsAzure_Storage_TableEntityQuery();
        $target->select()
               ->from('MyTable')
               ->where('Name eq ?', 'Maarten')
               ->andWhere('Visible eq true');
               
        $this->assertEquals('MyTable()?$filter=Name eq \'Maarten\' and Visible eq true', $target->__toString());
    }
}

// Call Microsoft_WindowsAzure_TableEntityQueryTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Microsoft_WindowsAzure_TableEntityQueryTest::main") {
    Microsoft_WindowsAzure_TableEntityQueryTest::main();
}

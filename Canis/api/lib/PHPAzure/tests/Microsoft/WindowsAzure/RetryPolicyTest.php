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
 * @version    $Id: RetryPolicyTest.php 55733 2011-01-03 09:17:16Z unknown $
 * @copyright  Copyright (c) 2009 - 2011, RealDolmen (http://www.realdolmen.com)
 * @license    http://phpazure.codeplex.com/license
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Microsoft_WindowsAzure_RetryPolicyTest::main');
}

/**
 * Test helpers
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';
require_once dirname(__FILE__) . '/../../TestConfiguration.php';
require_once 'PHPUnit/Framework/TestCase.php';

/** Microsoft_WindowsAzure_RetryPolicy_RetryPolicyAbstract */
require_once 'Microsoft/WindowsAzure/RetryPolicy/RetryPolicyAbstract.php';

/**
 * @category   Microsoft
 * @package    Microsoft_WindowsAzure
 * @subpackage UnitTests
 * @version    $Id: RetryPolicyTest.php 55733 2011-01-03 09:17:16Z unknown $
 * @copyright  Copyright (c) 2009 - 2011, RealDolmen (http://www.realdolmen.com)
 * @license    http://phpazure.codeplex.com/license
 */
class Microsoft_WindowsAzure_RetryPolicyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Helper variable for counting retries
     * 
     * @var int
     */
    protected $_executedRetries = 0;
    
    /**
     * Helper variable for setting Exception count 
     * 
     * @var int
     */
    protected $_exceptionCount = 0;
    
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Microsoft_WindowsAzure_RetryPolicyTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Test retry policy - noRetry
     */
    public function testNoRetry()
    {
        $this->_executedRetries = 0;
        $policy = Microsoft_WindowsAzure_RetryPolicy_RetryPolicyAbstract::noRetry();
        $retries = $policy->execute(
            array($this, '_countRetries')
        );
        $this->assertEquals(1, $retries);
    }
    
    /**
     * Test retry policy - retryN
     */
    public function testRetryN()
    {
        $this->_executedRetries = 0;
        $this->_exceptionCount = 9;
        
        $policy = Microsoft_WindowsAzure_RetryPolicy_RetryPolicyAbstract::retryN(10, 100);
        $retries = $policy->execute(
            array($this, '_countRetriesAndThrowExceptions')
        );
        $this->assertEquals(10, $retries);
    }
    
    /**
     * Helper function, counting retries
     */
    public function _countRetries()
    {
        return ++$this->_executedRetries;
    }
    
    /**
     * Helper function, counting retries and generating number of exceptions
     */
    public function _countRetriesAndThrowExceptions()
    {
        ++$this->_executedRetries;
        if ($this->_exceptionCount-- > 0) {
            throw new Exception("Exception thrown.");
        }
        return $this->_executedRetries;
    }
}

// Call Microsoft_WindowsAzure_RetryPolicyTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Microsoft_WindowsAzure_RetryPolicyTest::main") {
    Microsoft_WindowsAzure_RetryPolicyTest::main();
}

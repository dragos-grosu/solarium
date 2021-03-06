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
 *
 * @copyright Copyright 2011 Bas de Nooijer <solarium@raspberry.nl>
 * @license http://github.com/basdenooijer/solarium/raw/master/COPYING
 *
 * @package Solarium
 * @subpackage Client
 */

/**
 * Base class for handling Solr HTTP responses
 *
 * Most {@link Solarium_Client_Adapter} implementations will use HTTP for
 * communicating with Solr. While the HTTP part is adapter-specific, the parsing
 * of the response into Solarium_Result classes is not. This abstract class is
 * the base for several response handlers that do just that for the various
 * querytypes.
 *
 * @package Solarium
 * @subpackage Client
 */
abstract class Solarium_Client_Response
{

    /**
     * Query instance
     *
     * The query that was used for executing a request that led to this
     * response. The query holds important settings for generating the right
     * result, like the resultclass and documentclass settings.
     *
     * @var Solarium_Query
     */
    protected $_query;

    /**
     * Response data
     *
     * A (json)decoded HTTP response body data array.
     *
     * @var array
     */
    protected $_data;

    /**
     * Constructor
     *
     * @param Solarium_Query $query Query instance that was used for the request
     * @param array $data Decoded data array of the HTTP response
     */
    public function __construct($query, $data = null)
    {
        $this->_query = $query;
        $this->_data = $data;
    }

    /**
     * Get a Solarium_Result instance for the response
     *
     * When this method is called the actual response parsing is started.
     *
     * @internal Must be implemented in descendents because this parsing is
     *  query specific.
     *
     * @abstract
     * @return mixed
     */
    abstract function getResult();

}
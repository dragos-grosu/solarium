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
 * Build an update request
 *
 * @package Solarium
 * @subpackage Client
 */
class Solarium_Client_Request_Update extends Solarium_Client_Request
{

    /**
     * Get HTTP request method
     *
     * Update uses raw POST data so a POST method has to be used.
     *
     * @return string
     */
    public function getMethod()
    {
        return self::POST;
    }

    /**
     * Get uri
     *
     * Return the default url with the addition of the wt param.
     * This enables a JSON response, that is the easiest and most efficient
     * format to decode in the response handler.
     *
     * @return string
     */
    public function getUri()
    {
        $this->_params = array('wt' => 'json');
        return $this->buildUri();
    }

    /**
     * Generates raw POST data
     *
     * Each commandtype is delegated to a separate builder method.
     *
     * @throws Solarium_Exception
     * @return string
     */
    public function getRawData()
    {
        $xml = '<update>';
        foreach ($this->_query->getCommands() AS $command) {
            switch ($command->getType()) {
                case Solarium_Query_Update_Command::ADD:
                    $xml .= $this->buildAddXml($command);
                    break;
                case Solarium_Query_Update_Command::DELETE:
                    $xml .= $this->buildDeleteXml($command);
                    break;
                case Solarium_Query_Update_Command::OPTIMIZE:
                    $xml .= $this->buildOptimizeXml($command);
                    break;
                case Solarium_Query_Update_Command::COMMIT:
                    $xml .= $this->buildCommitXml($command);
                    break;
                case Solarium_Query_Update_Command::ROLLBACK:
                    $xml .= $this->buildRollbackXml();
                    break;
                default:
                    throw new Solarium_Exception('Unsupported command type');
                    break;
            }
        }
        $xml .= '</update>';

        return $xml;
    }

    /**
     * Build XML for an add command
     *
     * @param Solarium_Query_Update_Command_Add $command
     * @return string
     */
    public function buildAddXml($command)
    {
        $xml = '<add';
        $xml .= $this->boolAttrib('overwrite', $command->getOverwrite());
        $xml .= $this->attrib('commitWithin', $command->getCommitWithin());
        $xml .= '>';

        foreach ($command->getDocuments() AS $doc) {
            $xml .= '<doc';
            $xml .= $this->attrib('boost', $doc->getBoost());
            $xml .= '>';

            foreach ($doc->getFields() AS $name => $value) {
                $xml .= '<field name="' . $name . '"';
                $xml .= $this->attrib('boost', $doc->getFieldBoost($name));
                $xml .= '>' . htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8')
                        . '</field>';
            }

            $xml .= '</doc>';
        }

        $xml .= '</add>';

        return $xml;
    }

    /**
     * Build XML for a delete command
     *
     * @param Solarium_Query_Update_Command_Delete $command
     * @return string
     */
    public function buildDeleteXml($command)
    {
        $xml = '<delete>';
        foreach ($command->getIds() AS $id) {
            $xml .= '<id>' . htmlspecialchars($id, ENT_NOQUOTES, 'UTF-8')
                    . '</id>';
        }
        foreach ($command->getQueries() AS $query) {
            $xml .= '<query>' . htmlspecialchars($query, ENT_NOQUOTES, 'UTF-8')
                    . '</query>';
        }
        $xml .= '</delete>';

        return $xml;
    }

    /**
     * Build XML for an update command
     *
     * @param Solarium_Query_Update_Command_Optimize $command
     * @return string
     */
    public function buildOptimizeXml($command)
    {
        $xml = '<optimize';
        $xml .= $this->boolAttrib('waitFlush', $command->getWaitFlush());
        $xml .= $this->boolAttrib('waitSearcher', $command->getWaitSearcher());
        $xml .= $this->attrib('maxSegments', $command->getMaxSegments());
        $xml .= '/>';

        return $xml;
    }

    /**
     * Build XML for a commit command
     *
     * @param Solarium_Query_Update_Command_Commit $command
     * @return string
     */
    public function buildCommitXml($command)
    {
        $xml = '<commit';
        $xml .= $this->boolAttrib('waitFlush', $command->getWaitFlush());
        $xml .= $this->boolAttrib('waitSearcher', $command->getWaitSearcher());
        $xml .= $this->boolAttrib(
            'expungeDeletes',
            $command->getExpungeDeletes()
        );
        $xml .= '/>';
        
        return $xml;
    }

    /**
     * Build XMl for a rollback command
     * 
     * @return string
     */
    public function buildRollbackXml()
    {
        return '<rollback/>';
    }

}
<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2013, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace {

from('Hoa')

/**
 * \Hoa\Eventsource\Exception
 */
-> import('Eventsource.Exception')

/**
 * \Hoa\Http\Runtime
 */
-> import('Http.Runtime')

/**
 * \Hoa\Http\Response
 */
-> import('Http.Response.~');

}

namespace Hoa\Eventsource {

/**
 * Class \Hoa\Eventsource\Server.
 *
 * A cross-protocol EventSource server.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2013 Ivan Enderlin.
 * @license    New BSD License
 */

class Eventsource {

    /**
     * Mime type.
     *
     * @const string
     */
    const MIME_TYPE = 'text/event-stream';

    /**
     * Output-buffer level.
     *
     * @var \Hoa\Eventsource int
     */
    protected $_obLevel = 0;

    /**
     * Current event.
     *
     * @var \Hoa\Eventsource string
     */
    protected $_event   = null;



    /**
     * Start an event source.
     *
     * @access  public
     * @param   bool  $verifyHeaders    Verify headers or not.
     * @return  void
     * @throw   \Hoa\Eventsource\Exception
     */
    public function __construct ( $verifyHeaders = true ) {

        if(true === $verifyHeaders && true === headers_sent($file, $line))
            throw new Exception(
                'Headers already sent in %s at line %d, cannot send data ' .
                'to client correctly.',
                0, array($file, $line));

        $mimes  = preg_split('#\s*,\s*#', \Hoa\Http\Runtime::getHeader('accept'));
        $gotcha = false;

        foreach($mimes as $mime)
            if(0 !== preg_match('#^' . self::MIME_TYPE . ';?#', $mime)) {

                $gotcha = true;
                break;
            }

        $response = new \Hoa\Http\Response();

        if(false === $gotcha) {

            $response->sendHeader(
                'Status',
                \Hoa\Http\Response::STATUS_NOT_ACCEPTABLE
            );

            throw new Exception(
                'Client does not accept text/event-stream.', 0);
        }

        $response->sendHeader('Content-Type',      self::MIME_TYPE);
        $response->sendHeader('Transfer-Encoding', 'identity');
        $response->sendHeader('Cache-Control',     'no-cache');

        ob_start();
        $this->_obLevel = ob_get_level();

        return;
    }

    /**
     * Send an event.
     *
     * @access  public
     * @param   string  $data     Data.
     * @param   string  $id       ID (empty string to reset).
     * @return  void
     */
    public function send ( $data, $id = null ) {

        if(null !== $this->_event) {

            echo 'event: ', $this->_event, "\n";
            $this->_event = null;
        }

        $data = str_replace(CRLF, "\n", trim($data));

        echo 'data: ', preg_replace("#(\n|\r)#", "\n" . 'data: >', $data);

        if(null !== $id) {

            echo "\n", 'id';

            if(!empty($id))
                echo ': ', $id;
        }

        echo "\n\n";
        ob_flush();
        flush();

        return;
    }

    /**
     * Set the reconnection time for the client.
     *
     * @access  public
     * @param   int    $ms    Time in milliseconds.
     * @return  void
     */
    public function setReconnectionTime ( $ms ) {

        echo 'retry: ', $ms, "\n\n";
        ob_flush();
        flush();

        return;
    }

    /**
     * Select an event where to send data.
     *
     * @access  public
     * @param   string  $event    Event.
     * @return  \Hoa\Eventsource
     * @throw   \Hoa\Eventsource\Exception
     */
    public function __get ( $event ) {

        if(false === (bool) preg_match('##u', $event))
            throw new Exception(
                'Event name %s must be in UTF-8.', 1, $event);

        if(0 !== preg_match('#[:' . CRLF . ']#u', $event))
            throw new Exception(
                'Event name %s contains illegal characters.', 2, $event);

        $this->_event = $event;

        return $this;
    }

    /**
     * Get last ID.
     *
     * @access  public
     * @return  string
     */
    public function getLastId ( ) {

        return \Hoa\Http\Runtime::getHeader('Last-Event-ID') ?: '';
    }

    /**
     * Close the event source.
     *
     * @access  public
     * @return  void
     */
    public function __destruct ( ) {

        while($this->_obLevel <= ob_get_level())
            ob_end_clean();

        return;
    }
}

}

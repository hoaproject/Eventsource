<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2011, Ivan Enderlin. All rights reserved.
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
 * \Hoa\EventSource\Exception
 */
-> import('EventSource.Exception');

}

namespace Hoa\EventSource {

/**
 * Class \Hoa\EventSource\Server.
 *
 * A cross-protocol EventSource server.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2011 Ivan Enderlin.
 * @license    New BSD License
 */

class EventSource {

    /**
     * Output-buffer level.
     *
     * @var \Hoa\EventSource int
     */
    protected $ob_level = 0;



    /**
     * Start an event source.
     *
     * @access  public
     * @param   bool  $verifyHeaders    Verify headers or not.
     * @return  void
     * @throw   \Hoa\EventSource\Exception
     */
    public function __construct ( $verifyHeaders = true ) {

        if(true === $verifyHeaders) {

            if(true === headers_sent($file, $line))
                throw new Exception(
                    'Headers already sent in %s at line %d, cannot send data ' .
                    'to client correctly.',
                    0, array($file, $line));

            header('Content-type: text/event-stream');
        }

        ob_start();
        $this->_ob_level = ob_get_level();

        return;
    }

    /**
     * Send an event.
     *
     * @access  public
     * @return  void
     */
    public function writeAll ( $data, $event = null ) {

        if(null !== $event)
            echo 'event: ' . $event . "\n";

        echo 'data: ' . str_replace("\n", "\n" . 'data: ', $data) . "\n\n";
        ob_flush();
        flush();

        return;
    }

    /**
     * Close the event source.
     *
     * @access  public
     * @return  void
     */
    public function __destruct ( ) {

        while($this->_ob_level <= ob_get_level())
            ob_end_clean();

        return;
    }
}

}

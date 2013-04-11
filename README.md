![Hoa](http://static.hoa-project.net/Image/Hoa_small.png)

Hoa is a **modular**, **extensible** and **structured** set of PHP libraries.
Moreover, Hoa aims at being a bridge between industrial and research worlds.

# Hoa\Eventsource

This library allows to manipulate the
[EventSource](http://w3.org/TR/eventsource/) (aka Server-Sent Events) technology
by creating a server.

## Quick usage

We propose as a quick overview to send an unlimited number of events from the
server to the client. The client will display all received events. Thus, in
`Server.php`:

    $server = new Hoa\Eventsource\Server();

    while(true) {

        // “tick” is the event name.
        $server->tick->send(time());
        sleep(1);
    }

And in `index.html`, our client:

    <pre id="output"></pre>
    <script>
    var output = document.getElementById('output');

    try {

        var source       = new EventSource('Server.php');
        source.onopen    = function ( ) {

            output.appendChild(document.createElement('hr'));

            return;
        };
        source.addEventListener('tick', function ( evt ) {

            var samp       = document.createElement('samp');
            samp.innerHTML = evt.data + '\n';
            output.appendChild(samp);

            return;
        });
    }
    catch ( e ) {

        console.log(e);
    }
    </script>

Start your HTTP server and then open `index.html`.

The `Hoa\Eventsource\Server::setReconnectionTime` method allows to redefine the
time before the client will reconnect after a disconnection. The
`Hoa\Eventsource\Server::getLastId` method allows to retrieve the last ID sent
to the client.

## Documentation

Different documentations can be found on the website:
[http://hoa-project.net/](http://hoa-project.net/).

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](http://hoa-project.net/LICENSE).

<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/eventsource"><img src="https://img.shields.io/travis/hoaproject/eventsource/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/eventsource?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/eventsource/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/eventsource"><img src="https://img.shields.io/packagist/dt/hoa/eventsource.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/eventsource.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Eventsource

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Eventsource)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/eventsource)

This library allows to manipulate the
[EventSource](http://w3.org/TR/eventsource/) (aka Server-Sent Events) technology
by creating a server.

[Learn more](https://central.hoa-project.net/Documentation/Library/Eventsource).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/eventsource`](https://packagist.org/packages/hoa/eventsource):

```sh
$ composer require hoa/eventsource '~3.0'
```

For more installation procedures, please read [the Source
page](https://hoa-project.net/Source.html).

## Testing

Before running the test suites, the development dependencies must be installed:

```sh
$ composer install
```

Then, to run all the test suites:

```sh
$ vendor/bin/hoa test:run
```

For more information, please read the [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html).

## Quick usage

We propose as a quick overview to send an unlimited number of events from the
server to the client. The client will display all received events. Thus, in
`Server.php`:

```php
$server = new Hoa\Eventsource\Server();

while (true) {
    // “tick” is the event name.
    $server->tick->send(time());
    sleep(1);
}
```

And in `index.html`, our client:

```html
<pre id="output"></pre>
<script>
var output = document.getElementById('output');

try {
    var source    = new EventSource('Server.php');
    source.onopen = function () {
        output.appendChild(document.createElement('hr'));

        return;
    };
    source.addEventListener('tick', function (evt) {
        var samp       = document.createElement('samp');
        samp.innerHTML = evt.data + '\n';
        output.appendChild(samp);

        return;
    });
} catch (e) {
    console.log(e);
}
</script>
```

Start your HTTP server and then open `index.html`.

The `Hoa\Eventsource\Server::setReconnectionTime` method allows to redefine the
time before the client will reconnect after a disconnection. The
`Hoa\Eventsource\Server::getLastId` method allows to retrieve the last ID sent
to the client.

## Awecode

The following awecodes show this library in action:

  * [`Hoa\Eventsource`](http://hoa-project.net/Awecode/Eventsource.html):
    *why and how to use `Hoa\Eventsource\Server`? A simple and daily useful
    example will illustrate the EventSource technology (or Server-Send Events)*.

## Documentation

The
[hack book of `Hoa\Eventsource`](https://central.hoa-project.net/Documentation/Library/Eventsource) contains
detailed information about how to use this library and how it works.

To generate the documentation locally, execute the following commands:

```sh
$ composer require --dev hoa/devtools
$ vendor/bin/hoa devtools:documentation --open
```

More documentation can be found on the project's website:
[hoa-project.net](https://hoa-project.net/).

## Getting help

There are mainly two ways to get help:

  * On the [`#hoaproject`](https://webchat.freenode.net/?channels=#hoaproject)
    IRC channel,
  * On the forum at [users.hoa-project.net](https://users.hoa-project.net).

## Contribution

Do you want to contribute? Thanks! A detailed [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html) explains
everything you need to know.

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](https://hoa-project.net/LICENSE) for details.

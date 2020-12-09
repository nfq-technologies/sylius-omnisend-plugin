### Using asynchronous transport (optional, but very recommended)

All commands in this plugin will extend the [CommandInterface](src/Message/Command/CommandInterface.php).
Therefore you can route all commands easily by adding this to your [Messenger config](https://symfony.com/doc/current/messenger.html#routing-messages-to-a-transport):

```yaml
# config/packages/messenger.yaml
framework:
    messenger:
        routing:
            # Route all command messages to the async transport
            # This presumes that you have already set up an 'async' transport
            # See docs on how to setup a transport like that: https://symfony.com/doc/current/messenger.html#transports-async-queued-messages
            NFQ\SyliusOmnisendPlugin\Message\Command\CommandInterface': async
```

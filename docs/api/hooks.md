##HOOKS

###Messages
Plugin uses Symfony messenger plugin. So, every action like new created customer, or new created product is implemented by dispatching appropriate command.
If there is a need to push data to Omnisend appropriate command can be dispatched to message bus. 

For example:

```php
$this->messageBus->dispatch(
    new Envelope(
        new CreateContact(
            $customer->getId(),
            $this->channelContext->getChannel()->getCode()
        )
    )
);
```

###Builder and Decorators

If resource, which is sent to omnisend does not contain all required data, concreate builder like ``ProductBuilder`` can be decorated.
Builder pattern lets us only decorated appropriate part of product data.

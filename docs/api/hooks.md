##HOOKS

###Messages
Plugin uses Symfony messenger plugin. So, every action like new created customer, or new created product is implemented by dispatching appropriate command.
If there is a need to push a resource to Omnisend, appropriate command should be dispatched to message bus. 

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

If resource, which is sent to Omnisend, does not contain all required data, concrete builder like ``ProductBuilder`` can be decorated.
Builder pattern lets us decorate only appropriate part of a product.

###Fixtures

Omnisend api key and tracking script account id can be applied by sylius fixture:

```yaml
   sylius_fixtures:
     suites:
       default:
         fixtures:
          
          ...

           omnisend_channel: #required
             options:
               custom:
                 - channel_code: "CHANNEL CODE"   #required, channel has to be created before
                   api_key: "API KEY"             #required
                   tracking_key: "ACCOUNT ID"     #required
```

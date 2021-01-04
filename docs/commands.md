###Commands

Current shop data can be synced with omnisend by executing import commands.

```shell
    bin/console nfq:sylius-omnisend:create-batch <type> <channelCode> <localeCode> --batchSize=1000
```

```
Description:
  Pushes all resources by provided type to Omnisend

Usage:
  nfq:sylius-omnisend:create-batch [options] [--] [<type> [<channelCode> [<localeCode>]]]

Arguments:
  type                                 Import type. Available types are products, categories
  channelCode                          Channel Code
  localeCode                           Locale Code

Options:
  -batchSize, --batchSize[=BATCHSIZE]  Batch size for Omnisend. Default is 1000

```


##### Categories
Omnisend do not allow updating taxons by batch command. So we strongly recommend not to execute this command periodically.
After this command execution, taxon name changes will be synced automatically.

```shell
    bin/console nfq:sylius-omnisend:create-batch categories <channelCode> <localeCode> --batchSize=1000
```

##### Products
Omnisend allows to sync created and not created products. So, during import command products will be created and updated.

```shell
    bin/console nfq:sylius-omnisend:create-batch products <channelCode> <localeCode> --batchSize=1000
```


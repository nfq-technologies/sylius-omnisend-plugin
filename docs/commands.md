###Commands

Current shop data can be synced with omnisend by executing import commands.

##### Categories
Omnisend do not allow updating taxons by batch command. So we strongly recommend not to execute this command periodically.
After this command execution, taxon name changes will be synced automaticlly.

```bash
    bin/console nfq:sylius-omnisend:push-categories --channelCode=FASHION_WEB
```

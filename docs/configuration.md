##CONFIGURATION

Plugin has this default configuration values:

```yaml
nfq_sylius_omnisend:
    driver: doctrine/orm
    client_logger: null
    product_image_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_image
    order_coupon_resolver: nfq_sylius_omnisend_plugin.resolver.default_order_coupon
    courier_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_courier_data_resolver
    product_additional_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_additional_data
    product_url_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_url
    product_variant_stock_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_variant_stock
    customer_additional_data: nfq_sylius_omnisend_plugin.resolver.default_customer_additional_data
    order_states: {  }
    payment_states: {  }
    product_image:
        type: main
        filter: sylius_shop_product_large_thumbnail
        default_image: 'https://placehold.it/400x30'
    product_attributes:
        vendor: omnisend_vendor
        type: omnisend_type
        tags: omnisend_tags
        custom_fields:
            - omnisend_custom_field_1
            - omnisend_custom_field_2
    send_welcome_message:
        email: true
        phone: false
    resources:
        order_details:
            classes:
                model: NFQ\SyliusOmnisendPlugin\Model\OrderDetails
        event:
            classes:
                model: NFQ\SyliusOmnisendPlugin\Model\Event
                form: NFQ\SyliusOmnisendPlugin\Form\Type\EventType
                controller: Sylius\Bundle\ResourceBundle\Controller\ResourceController
                factory: Sylius\Component\Resource\Factory\Factory
        event_field:
            classes:
                model: NFQ\SyliusOmnisendPlugin\Model\EventField
                form: NFQ\SyliusOmnisendPlugin\Form\Type\EventFieldType
                factory: Sylius\Component\Resource\Factory\Factory
```

###Explanation:

- ####[client_logger](configuration/logger.md)

- ####[resolvers](configuration/resolvers.md)

- ####[states_mapper](configuration/resolvers.md)

- ####send_welcome_message
    This config value defines if welcome message will be sent on contact create action.
If email is set to true - email will be received from omnisend.
If phone is set to true - sms will be received form Omnisend, 
    ```yaml
    nfq_sylius_omnisend_plugin:
      send_welcome_message:
        email: true
        phone: false
    ```
- ####[custom_product_attributes](configuration/custom_product_data.md)

###Admin configuration
Omnisend account tracking key and API key is configurated in channel form:
![Alt text](img/admin_configuration.png)

Futhermore, these values can be added by [Sylius fixtures](fixtures.md).

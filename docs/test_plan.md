#Test Plan.

##Tracking.

- Add account id in channel config

Omnisend tracking script should appear with accountID

    <script type="text/javascript">
        //OMNISEND-SNIPPET-SOURCE-CODE-V1
        window.omnisend = window.omnisend || [];
        omnisend.push(["accountID", "accountID"]);
        omnisend.push(["track", "$pageViewed"]);
        !function () {
            var e = document.createElement("script");
            e.type = "text/javascript", e.async = !0, e.src = "https://omnisrc.com/inshop/launcher-v2.js";
            var t = document.getElementsByTagName("script")[0];
            t.parentNode.insertBefore(e, t)
        }();
    </script>


Remove account id form channel config
Omnisend tracking script should not be included in page



##Product picker:

- Go to product page inspect content. 

Product tracking script should be displayed.

    <script type="text/javascript">
        window.omnisend = window.omnisend || [];
        omnisend.push(
            [
                "track",
                "$productViewed",
                {"$productID":"111F_patched_jeans_with_fancy_badges","$variantID":"111F_patched_jeans_with_fancy_badges-variant-0","$currency":"USD","$price":9980,"$title":"111F patched jeans with fancy badges","$description":"Quis dolorum quia dolore ut. Aliquam ullam exercitationem vel autem et facere rerum. Similique nemo corporis eum eius quia ut.\n\nConsequuntur tenetur blanditiis amet et itaque. Eos qui rerum unde hic. Voluptatem aut non vel nobis aut at sed.\n\nVelit natus occaecati sunt laboriosam dolores eius. Hic qui deleniti adipisci ipsam nihil voluptas. Qui corporis placeat asperiores sit qui harum culpa. Sed vel rerum asperiores ea.","$imageUrl":"http:\/\/sylius-omnisend-plugin-nfq-s1.demo.nfq.lt\/media\/cache\/resolve\/sylius_shop_product_large_thumbnail\/5e\/1f\/5ae68a0ba54d9ebfc9f6e940a1b5.jpeg","$productUrl":"http:\/\/sylius-omnisend-plugin-nfq-s1.demo.nfq.lt\/en_US\/products\/111f-patched-jeans-with-fancy-badges"}
            ]
        );
    </script>

product data:

```json
{
  "$productID": "111F_patched_jeans_with_fancy_badges",
  "$variantID": "111F_patched_jeans_with_fancy_badges-variant-0",
  "$currency": "USD",
  "$price": 9980,
  "$title": "111F patched jeans with fancy badges",
  "$description": "Quis dolorum quia dolore ut. Aliquam ullam exercitationem vel autem et facere rerum. Similique nemo corporis eum eius quia ut.\n\nConsequuntur tenetur blanditiis amet et itaque. Eos qui rerum unde hic. Voluptatem aut non vel nobis aut at sed.\n\nVelit natus occaecati sunt laboriosam dolores eius. Hic qui deleniti adipisci ipsam nihil voluptas. Qui corporis placeat asperiores sit qui harum culpa. Sed vel rerum asperiores ea.",
  "$imageUrl": "http:\/\/sylius-omnisend-plugin-nfq-s1.demo.nfq.lt\/media\/cache\/resolve\/sylius_shop_product_large_thumbnail\/5e\/1f\/5ae68a0ba54d9ebfc9f6e940a1b5.jpeg",
  "$productUrl": "http:\/\/sylius-omnisend-plugin-nfq-s1.demo.nfq.lt\/en_US\/products\/111f-patched-jeans-with-fancy-badges"
}

```

- add `omnisend_vendor` attribute

Value should be visible in script:

```json
{"$vendor":"VENDRO OMNISEND TEST"}
```

- custom tags attributes with code `omnisend_tag_1` and `omnisend_tag_2` should be added in admin: 

Values should be visible in script :

```json
"$tags":{"omnisend_tag_1":"444","omnisend_tag_2":"f2326d02-2f04-11eb-b3f1-02420a180367, f232d9d6-2f04-11eb-b5b5-02420a180367"}

```

- remove omnisend tracking account id from admin area

script should be not included in product view.


##Taxons:

- On taxon create in admin area, data should be sent to OMNISEND

categories	postCategories	200	/v3/categories	

Request body:
```json
{
  "categoryID": "Taxon",
  "title": "NAME",
  "createdAt": "2020-11-25T10:44:26+00:00",
  "updatedAt": "2020-11-25T10:44:26+00:00"
}
```

Response body:

```json
  "categoryID": "Taxon"
}
```
- On taxon update in amdin area, data should be send to Omnisend

/v3/categories/Taxon

Request body:

```json
{
  "categoryID": "Taxon",
  "title": "NAMEEEE",
  "createdAt": "2020-11-25T10:44:26+00:00",
  "updatedAt": "2020-11-25T10:44:27+00:00"
}
```
Response body:

```json
{
  "categoryID": "Taxon"
}
```

- On taxon delete in amdin area, data should be send to Omnisend

`DELETE	/v3/categories/Taxon`

- On taxon sync

command should be executed:

```
php tests/Application/bin/console nfq:sylius-omnisend:create-batch categories FASHION_WEB en_US --batchSize=4
```

Several batch request should be sent to Omnisend:

```json
{
  "method": "POST",
  "endpoint": "categories",
  "items": [
    {
      "categoryID": "jeans",
      "title": "Jeans",
      "createdAt": "2020-11-25T09:55:19+00:00",
      "updatedAt": "2020-11-25T09:55:19+00:00"
    },
    {
      "categoryID": "mens_jeans",
      "title": "Men",
      "createdAt": "2020-11-25T09:55:19+00:00",
      "updatedAt": "2020-11-25T09:55:19+00:00"
    },
    {
      "categoryID": "womens_jeans",
      "title": "Women",
      "createdAt": "2020-11-25T09:55:19+00:00",
      "updatedAt": "2020-11-25T09:55:19+00:00"
    },
    {
      "categoryID": "naujas",
      "title": "Test",
      "createdAt": "2020-11-25T11:22:43+00:00",
      "updatedAt": "2020-11-25T11:22:43+00:00"
    }
  ]
}
```

And endpoint `/v3/categories` should return all categories:

```json
{
  "categories": [
    {
      "categoryID": "mens_t_shirts",
      "title": "Men",
      "createdAt": "2020-11-25T09:55:18Z",
      "updatedAt": "2020-11-25T09:55:18Z"
    },
    {
      "categoryID": "t_shirts",
      "title": "T-shirts",
      "createdAt": "2020-11-25T09:55:18Z",
      "updatedAt": "2020-11-25T09:55:18Z"
    },
    {
      "categoryID": "MENU_CATEGORY",
      "title": "Category",
      "createdAt": "2020-11-25T09:55:12Z",
      "updatedAt": "2020-11-25T09:55:12Z"
    },
    {
      "categoryID": "simple_caps",
      "title": "Simple",
      "createdAt": "2020-11-25T09:55:18Z",
      "updatedAt": "2020-11-25T09:55:18Z"
    }
    ...
}
```


##Products:

- On product create in admin area, data should be sent to OMNISEND

`/v3/products	`

Request body:
```json
{
  "productID": "000F_office_grey_jeans",
  "title": "000F office grey jeans",
  "status": "inStock",
  "description": "Facilis veritatis quo sunt asperiores ut. Et occaecati reiciendis rerum nemo nostrum et molestiae. Nobis beatae ad ipsa laboriosam sed sed nemo mollitia.\r\n\r\nTotam suscipit voluptatibus repudiandae velit. Neque saepe libero et.\r\n\r\nVeniam fugit hic et consequatur iure dolores. Quis ut cumque possimus omnis. Laborum a dolorem dolores qui quia sit ipsam. Error aut eligendi neque et est.",
  "currency": "USD",
  "productUrl": "http://10.24.3.97/en_US/products/000f-office-grey-jeans",
  "vendor": "VENDRO OMNISEND TEST",
  "createdAt": "2020-11-20T02:36:32+00:00",
  "updatedAt": "2020-11-25T09:55:19+00:00",
  "tags": [
    "e94de110-2f1f-11eb-ae68-02420a180367",
    "e94da27c-2f1f-11eb-a54b-02420a180367"
  ],
  "categoryIDs": [
    "jeans",
    "womens_jeans"
  ],
  "images": [
    {
      "imageID": "41",
      "url": "http://10.24.3.97/media/cache/resolve/sylius_shop_product_large_thumbnail/58/3d/08baa406494be70b7aac10259236.jpeg",
      "default": true,
      "variantIDs": [
        "000F_office_grey_jeans-variant-0",
        "000F_office_grey_jeans-variant-1",
        "000F_office_grey_jeans-variant-2",
        "000F_office_grey_jeans-variant-3",
        "000F_office_grey_jeans-variant-4"
      ]
    }
  ],
  "variants": [
    {
      "variantID": "000F_office_grey_jeans-variant-0",
      "title": "S",
      "sku": "000F_office_grey_jeans-variant-0",
      "status": "inStock",
      "price": 8545,
      "productUrl": "http://10.24.3.97/en_US/products/000f-office-grey-jeans"
    },
    {
      "variantID": "000F_office_grey_jeans-variant-1",
      "title": "M",
      "sku": "000F_office_grey_jeans-variant-1",
      "status": "inStock",
      "price": 5922,
      "productUrl": "http://10.24.3.97/en_US/products/000f-office-grey-jeans"
    },
    {
      "variantID": "000F_office_grey_jeans-variant-2",
      "title": "L",
      "sku": "000F_office_grey_jeans-variant-2",
      "status": "inStock",
      "price": 7787,
      "productUrl": "http://10.24.3.97/en_US/products/000f-office-grey-jeans"
    },
    {
      "variantID": "000F_office_grey_jeans-variant-3",
      "title": "XL",
      "sku": "000F_office_grey_jeans-variant-3",
      "status": "inStock",
      "price": 9765,
      "productUrl": "http://10.24.3.97/en_US/products/000f-office-grey-jeans"
    },
    {
      "variantID": "000F_office_grey_jeans-variant-4",
      "title": "XXL",
      "sku": "000F_office_grey_jeans-variant-4",
      "status": "inStock",
      "price": 4116,
      "productUrl": "http://10.24.3.97/en_US/products/000f-office-grey-jeans"
    }
  ]
}
```

Response body:

```json
{
  "productID": "000F_office_grey_jeans"
}
```
- On product update in amdin area, data should be send to Omnisend

`/v3/products/000F_office_grey_jeans`

Request body:

```json
{
  "productID": "000F_office_grey_jeans",
  "title": "000F office grey jeans",
  "status": "inStock",
.....
}
```
Response body:

```json
{
  "productID": "000F_office_grey_jeans"
}
```

- On product delete in amdin area, data should be send to Omnisend

`DELETE	/v3/products/000F_office_grey_jeans`

- On product sync

command should be executed:

```
php tests/Application/bin/console nfq:sylius-omnisend:create-batch products FASHION_WEB en_US --batchSize=4
```

Several batch request should be sent to Omnisend:

```json
{
  "method": "POST",
  "endpoint": "products",
  "items": [
    {
      "productID": "007M_black_elegance_jeans",
      "title": "007M black elegance jeans",
      "status": "inStock",
      "description": "Magni et suscipit corporis id quaerat vel quibusdam. Aperiam minus aut cumque ut adipisci. Sint ea quis rerum et odio aut ducimus. Pariatur voluptates ratione nihil qui eveniet ipsum cum.\r\n\r\nQuam illum odio voluptas eveniet est provident est molestiae. Autem magni nobis numquam. Ea enim voluptatem ad consectetur consequatur placeat totam. Voluptas sit voluptates recusandae aut.\r\n\r\nIllum praesentium ut dolor in possimus. Ut consectetur maxime eligendi. Fugit aliquam iusto dolores quis error reiciendis. Molestias aut est sit quam hic.",
      "currency": "USD",
      "productUrl": "http://localhost/en_US/products/007m-black-elegance-jeans",
      "createdAt": "2020-11-24T16:08:58+00:00",
      "updatedAt": "2020-11-25T12:56:41+00:00",
      "tags": [],
      "categoryIDs": [
        "jeans",
        "mens_jeans"
      ],
      "images": [
        {
          "imageID": "38",
          "url": "http://localhost/media/cache/resolve/sylius_shop_product_large_thumbnail/58/ef/08d44a883930fe8a283381087c41.jpeg",
          "default": true,
          "variantIDs": [
...
}
```

And endpoint `/v3/products` should return all products:

```json
{
  "products": [
    {
      "productID": "007M_black_elegance_jeans",
      "title": "007M black elegance jeans",
      "status": "inStock",
      "description": "Magni et suscipit corporis id quaerat vel quibusdam. Aperiam minus aut cumque ut adipisci. Sint ea quis rerum et odio aut ducimus. Pariatur voluptates ratione nihil qui eveniet ipsum cum....",
      "currency": "USD",
      "images": [
        {
    ...
}
```


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


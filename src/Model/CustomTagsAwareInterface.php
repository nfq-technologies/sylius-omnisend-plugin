<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Model;

use stdClass;

interface CustomTagsAwareInterface
{
    /**
     * You need to first create custom fields in the https://app.omnisend.com Omnisend app by defining their types.
     * Example: customFields {"color":"red", "sime":"M", "inStore": true}.
     * customFields data types can be:
     *  - integer
     *  - float
     *  - boolean
     *  - string
     *  - email
     *  - url
     *  - date - format: YYYY-MM-DD
     *  - datetime -  format. Example: 2017-05-30T14:11:12Z
     */
    public function getOmnisendCustomTags(): ?stdClass;
}

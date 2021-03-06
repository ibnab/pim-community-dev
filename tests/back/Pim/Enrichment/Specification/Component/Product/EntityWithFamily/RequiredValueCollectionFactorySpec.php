<?php

namespace Specification\Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily;

use PhpSpec\ObjectBehavior;
use Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\RequiredValue;
use Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\RequiredValueCollectionFactory;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Akeneo\Pim\Structure\Component\Model\AttributeRequirementInterface;
use Akeneo\Channel\Component\Model\ChannelInterface;
use Akeneo\Pim\Structure\Component\Model\FamilyInterface;
use Akeneo\Channel\Component\Model\LocaleInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\ValueInterface;

class RequiredValueCollectionFactorySpec extends ObjectBehavior
{
    function let(
        AttributeInterface $sku,
        AttributeInterface $price,
        AttributeInterface $description,
        AttributeInterface $image,
        ChannelInterface $ecommerce,
        ChannelInterface $print,
        LocaleInterface $en_US,
        LocaleInterface $fr_FR
    ) {
        $sku->getCode()->willReturn('sku');
        $price->getCode()->willReturn('price');
        $description->getCode()->willReturn('description');
        $image->getCode()->willReturn('image');
        $ecommerce->getCode()->willReturn('ecommerce');
        $print->getCode()->willReturn('print');
        $en_US->getCode()->willReturn('en_US');
        $fr_FR->getCode()->willReturn('fr_FR');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(\Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\RequiredValueCollectionFactory::class);
    }

    function it_creates_a_collection_from_family_requirements_for_a_channel(
        $en_US,
        $fr_FR,
        $sku,
        $description,
        $price,
        $image,
        $ecommerce,
        $print,
        FamilyInterface $family,
        AttributeRequirementInterface $requirement1,
        AttributeRequirementInterface $requirement2,
        AttributeRequirementInterface $requirement3,
        AttributeRequirementInterface $requirement4,
        AttributeRequirementInterface $requirement5,
        AttributeRequirementInterface $requirement6,
        RequiredValue $expectedRequiredValue1,
        RequiredValue $expectedRequiredValue2,
        RequiredValue $expectedRequiredValue3,
        RequiredValue $expectedRequiredValue4,
        RequiredValue $expectedRequiredValue5
    ) {
        $expectedRequiredValue1->forAttribute()->willReturn($sku);
        $expectedRequiredValue1->forChannel()->willReturn($ecommerce);
        $expectedRequiredValue1->forLocale()->willReturn($en_US);
        $expectedRequiredValue1->attribute()->willReturn('sku');
        $expectedRequiredValue1->channel()->willReturn(null);
        $expectedRequiredValue1->locale()->willReturn(null);

        $expectedRequiredValue2->forAttribute()->willReturn($description);
        $expectedRequiredValue2->forChannel()->willReturn($ecommerce);
        $expectedRequiredValue2->forLocale()->willReturn($en_US);
        $expectedRequiredValue2->attribute()->willReturn('description');
        $expectedRequiredValue2->channel()->willReturn('ecommerce');
        $expectedRequiredValue2->locale()->willReturn('en_US');

        $expectedRequiredValue3->forAttribute()->willReturn($description);
        $expectedRequiredValue3->forChannel()->willReturn($ecommerce);
        $expectedRequiredValue3->forLocale()->willReturn($fr_FR);
        $expectedRequiredValue3->attribute()->willReturn('description');
        $expectedRequiredValue3->channel()->willReturn('ecommerce');
        $expectedRequiredValue3->locale()->willReturn('fr_FR');

        $expectedRequiredValue4->forAttribute()->willReturn($price);
        $expectedRequiredValue4->forChannel()->willReturn($ecommerce);
        $expectedRequiredValue4->forLocale()->willReturn($en_US);
        $expectedRequiredValue4->attribute()->willReturn('price');
        $expectedRequiredValue4->channel()->willReturn(null);
        $expectedRequiredValue4->locale()->willReturn(null);

        $expectedRequiredValue5->forAttribute()->willReturn($image);
        $expectedRequiredValue5->forChannel()->willReturn($ecommerce);
        $expectedRequiredValue5->forLocale()->willReturn($fr_FR);
        $expectedRequiredValue5->attribute()->willReturn('image');
        $expectedRequiredValue5->channel()->willReturn(null);
        $expectedRequiredValue5->locale()->willReturn(null);

        $family->getAttributeRequirements()->willReturn([$requirement1, $requirement2, $requirement3, $requirement4, $requirement5, $requirement6]);

        $ecommerce->getLocales()->willReturn([$en_US, $fr_FR]);
        $print->getLocales()->willReturn([$en_US]);

        $sku->isScopable()->willReturn(false);
        $sku->isLocalizable()->willReturn(false);
        $sku->isLocaleSpecific()->willReturn(false);
        $description->isScopable()->willReturn(true);
        $description->isLocalizable()->willReturn(true);
        $description->isLocaleSpecific()->willReturn(false);
        $price->isScopable()->willReturn(false);
        $price->isLocalizable()->willReturn(false);
        $price->isLocaleSpecific()->willReturn(false);
        $image->isScopable()->willReturn(false);
        $image->isLocalizable()->willReturn(false);
        $image->isLocaleSpecific()->willReturn(true);
        $image->hasLocaleSpecific($fr_FR)->willReturn(true);
        $image->hasLocaleSpecific($en_US)->willReturn(false);

        $requirement1->getAttribute()->willReturn($sku);
        $requirement1->getChannel()->willReturn($ecommerce);
        $requirement1->isRequired()->willReturn(true);

        $requirement2->getAttribute()->willReturn($description);
        $requirement2->getChannel()->willReturn($ecommerce);
        $requirement2->isRequired()->willReturn(true);

        $requirement3->getAttribute()->willReturn($sku);
        $requirement3->getChannel()->willReturn($print);
        $requirement3->isRequired()->willReturn(false);

        $requirement4->getAttribute()->willReturn($description);
        $requirement4->getChannel()->willReturn($print);
        $requirement4->isRequired()->willReturn(false);

        $requirement5->getAttribute()->willReturn($price);
        $requirement5->getChannel()->willReturn($print);
        $requirement5->isRequired()->willReturn(true);

        $requirement6->getAttribute()->willReturn($image);
        $requirement6->getChannel()->willReturn($ecommerce);
        $requirement6->isRequired()->willReturn(true);

        $expectedRequiredValuesEcommerce = $this->forChannel($family, $ecommerce);
        $expectedRequiredValuesEcommerce->count()->shouldReturn(4);
        $expectedRequiredValuesEcommerce->hasSame($expectedRequiredValue1)->shouldReturn(true);
        $expectedRequiredValuesEcommerce->hasSame($expectedRequiredValue2)->shouldReturn(true);
        $expectedRequiredValuesEcommerce->hasSame($expectedRequiredValue3)->shouldReturn(true);
        $expectedRequiredValuesEcommerce->hasSame($expectedRequiredValue5)->shouldReturn(true);

        $expectedRequiredValuesPrint = $this->forChannel($family, $print);
        $expectedRequiredValuesPrint->count()->shouldReturn(1);
        $expectedRequiredValuesPrint->hasSame($expectedRequiredValue4)->shouldReturn(true);
    }
}

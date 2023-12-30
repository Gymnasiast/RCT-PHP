<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\Research\List;

use RCTPHP\RCT12\Research\Entry\ResearchEntry;
use RCTPHP\RCT12\Research\ResearchItem;
use RCTPHP\RCT12\Research\Separator\EndOfListMarker;
use RCTPHP\RCT12\Research\Separator\StartRandomSeparator;
use RCTPHP\RCT12\Research\Separator\StartUninventedSeparator;
use function count;
use function assert;

class ResearchLists
{
    public function __construct(
        /** @var ResearchEntry[] */
        public readonly array $inventedItems,
        /** @var ResearchEntry[] */
        public readonly array $uninventedItems,
        /** @var RandomEntryPair[] */
        public readonly array $randomItems,
    ) {
    }

    /**
     * @param ResearchItem[] $list
     */
    public static function createFromResearchItemList(array $list): self
    {
        /** @var ResearchEntry[] $inventedItems */
        $inventedItems = [];
        /** @var ResearchEntry[] $uninventedItems */
        $uninventedItems = [];
        /** @var RandomEntryPair[] $randomItems */
        $randomItems = [];

        $invented = true;
        $random = false;

        $length = count($list);
        for ($i = 0; $i < $length; $i++)
        {
            $item = $list[$i];
            if ($item instanceof StartUninventedSeparator)
            {
                $invented = false;
            }
            elseif ($item instanceof StartRandomSeparator)
            {
                $random = true;
            }
            elseif ($item instanceof EndOfListMarker)
            {
                break;
            }
            else
            {
                assert($item instanceof ResearchEntry);
                if ($random)
                {
                    /** @var ResearchEntry $nextEntry */
                    $nextEntry = $list[$i + 1];
                    $randomItems[] = new RandomEntryPair($item, $nextEntry);
                    $i++;
                }
                elseif ($invented)
                {
                    $inventedItems[] = $item;
                }
                else
                {
                    $uninventedItems[] = $item;
                }
            }
        }

        return new self($inventedItems, $uninventedItems, $randomItems);
    }
}

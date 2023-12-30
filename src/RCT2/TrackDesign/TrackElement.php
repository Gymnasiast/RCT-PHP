<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

use RCTPHP\OpenRCT2\RideType;
use RCTPHP\OpenRCT2\Track\TrackElementType;
use RCTPHP\RCT2\SeatRotation;

final class TrackElement
{
    private const CHAIN_LIFT = 0b1000_0000;
    private const INVERTED = 0b0100_0000;
    private const COLOUR_SCHEME = 0b0011_0000;
    private const SPEED_OR_ROTATION = 0b0000_1111;

    public function __construct(
        public readonly TrackElementType $trackPiece,
        public readonly bool $hasChain,
        public readonly bool $isInverted,
        public int $colorScheme,
        public int $speed,
        public SeatRotation $seatRotation,
    ) {
    }

    public static function fromTD6Bytes(RideType $rideType, int $firstByte, int $secondByte): self
    {
        $trackPiece = TrackElementType::fromTD6($rideType, $firstByte);
        $hasChain = (bool)($secondByte & self::CHAIN_LIFT);
        $inverted = (bool)($secondByte & self::INVERTED);
        $colorScheme = ($secondByte & self::COLOUR_SCHEME) >> 4;
        $speedOrRotation = $secondByte & self::SPEED_OR_ROTATION;
        $speed = 0;
        $seatRotation = SeatRotation::NONE;

        if ($trackPiece === TrackElementType::Brakes || $trackPiece === TrackElementType::Booster)
        {
            $speed = $speedOrRotation << 1;
        }
        else
        {
            $seatRotation = SeatRotation::from($speedOrRotation);
        }

        return new self($trackPiece, $hasChain, $inverted, $colorScheme, $speed, $seatRotation);
    }
}

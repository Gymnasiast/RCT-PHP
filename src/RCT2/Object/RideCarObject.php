<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

class RideCarObject
{
    public const CAR_ENTRY_FLAG_LOADING_WAYPOINTS = (1 << 26);

    public int $tabRotationMask = 0;
    public int $spacing = 0;
    public int $carMass = 0;
    public int $tabHeight = 0;
    public int $numSeats = 0;
    public int $spriteGroups = 0;
    public int $spriteWidth = 0;
    public int $spriteHeightPegative = 0;
    public int $spriteHeightPositive = 0;
    public int $animation = 0;
    public int $flags = 0;
    public int $baseNumFrames = 0;
    public int $numSeatingRows = 0;
    public int $spinningInertia = 0;
    public int $spinningFriction = 0;
    public int $frictionSoundId = 0;
    public int $reversedCarIndex = 0;
    public int $soundRange = 0;
    public int $doubleSoundFrequency = 0;
    public int $poweredAcceleration = 0;
    public int $poweredMaxSpeed = 0;
    public int $paintStyle = 0;
    public int $effectVisual = 0;
    public int $drawOrder = 0;
    public int $numVerticalFramesOverride = 0;

    public int $peepLoadingWaypointSegments = 0;
}
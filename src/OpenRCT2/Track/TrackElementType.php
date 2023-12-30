<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Track;

use RCTPHP\OpenRCT2\RideType;
use RCTPHP\RCT1\RideType as RCT1RideType;
use RCTPHP\RCT2\TrackDesign\TrackTypeID;
use RuntimeException;

enum TrackElementType : int
{
    case Flat = 0;
    case EndStation = 1;
    case BeginStation = 2;
    case MiddleStation = 3;
    case Up25 = 4;
    case Up60 = 5;
    case FlatToUp25 = 6;
    case Up25ToUp60 = 7;
    case Up60ToUp25 = 8;
    case Up25ToFlat = 9;
    case Down25 = 10;
    case Down60 = 11;
    case FlatToDown25 = 12;
    case Down25ToDown60 = 13;
    case Down60ToDown25 = 14;
    case Down25ToFlat = 15;
    case LeftQuarterTurn5Tiles = 16;
    case RightQuarterTurn5Tiles = 17;
    case FlatToLeftBank = 18;
    case FlatToRightBank = 19;
    case LeftBankToFlat = 20;
    case RightBankToFlat = 21;
    case BankedLeftQuarterTurn5Tiles = 22;
    case BankedRightQuarterTurn5Tiles = 23;
    case LeftBankToUp25 = 24;
    case RightBankToUp25 = 25;
    case Up25ToLeftBank = 26;
    case Up25ToRightBank = 27;
    case LeftBankToDown25 = 28;
    case RightBankToDown25 = 29;
    case Down25ToLeftBank = 30;
    case Down25ToRightBank = 31;
    case LeftBank = 32;
    case RightBank = 33;
    case LeftQuarterTurn5TilesUp25 = 34;
    case RightQuarterTurn5TilesUp25 = 35;
    case LeftQuarterTurn5TilesDown25 = 36;
    case RightQuarterTurn5TilesDown25 = 37;
    case SBendLeft = 38;
    case SBendRight = 39;
    case LeftVerticalLoop = 40;
    case RightVerticalLoop = 41;
    case LeftQuarterTurn3Tiles = 42;
    case RightQuarterTurn3Tiles = 43;
    case LeftBankedQuarterTurn3Tiles = 44;
    case RightBankedQuarterTurn3Tiles = 45;
    case LeftQuarterTurn3TilesUp25 = 46;
    case RightQuarterTurn3TilesUp25 = 47;
    case LeftQuarterTurn3TilesDown25 = 48;
    case RightQuarterTurn3TilesDown25 = 49;
    case LeftQuarterTurn1Tile = 50;
    case RightQuarterTurn1Tile = 51;
    case LeftTwistDownToUp = 52;
    case RightTwistDownToUp = 53;
    case LeftTwistUpToDown = 54;
    case RightTwistUpToDown = 55;
    case HalfLoopUp = 56;
    case HalfLoopDown = 57;
    case LeftCorkscrewUp = 58;
    case RightCorkscrewUp = 59;
    case LeftCorkscrewDown = 60;
    case RightCorkscrewDown = 61;
    case FlatToUp60 = 62;
    case Up60ToFlat = 63;
    case FlatToDown60 = 64;
    case Down60ToFlat = 65;
    case TowerBase = 66;
    case TowerSection = 67;
    case FlatCovered = 68;
    case Up25Covered = 69;
    case Up60Covered = 70;
    case FlatToUp25Covered = 71;
    case Up25ToUp60Covered = 72;
    case Up60ToUp25Covered = 73;
    case Up25ToFlatCovered = 74;
    case Down25Covered = 75;
    case Down60Covered = 76;
    case FlatToDown25Covered = 77;
    case Down25ToDown60Covered = 78;
    case Down60ToDown25Covered = 79;
    case Down25ToFlatCovered = 80;
    case LeftQuarterTurn5TilesCovered = 81;
    case RightQuarterTurn5TilesCovered = 82;
    case SBendLeftCovered = 83;
    case SBendRightCovered = 84;
    case LeftQuarterTurn3TilesCovered = 85;
    case RightQuarterTurn3TilesCovered = 86;
    case LeftHalfBankedHelixUpSmall = 87;
    case RightHalfBankedHelixUpSmall = 88;
    case LeftHalfBankedHelixDownSmall = 89;
    case RightHalfBankedHelixDownSmall = 90;
    case LeftHalfBankedHelixUpLarge = 91;
    case RightHalfBankedHelixUpLarge = 92;
    case LeftHalfBankedHelixDownLarge = 93;
    case RightHalfBankedHelixDownLarge = 94;
    case LeftQuarterTurn1TileUp60 = 95;
    case RightQuarterTurn1TileUp60 = 96;
    case LeftQuarterTurn1TileDown60 = 97;
    case RightQuarterTurn1TileDown60 = 98;
    case Brakes = 99;
    case Booster = 100;
    case Maze = 101;
    case LeftQuarterBankedHelixLargeUp = 102;
    case RightQuarterBankedHelixLargeUp = 103;
    case LeftQuarterBankedHelixLargeDown = 104;
    case RightQuarterBankedHelixLargeDown = 105;
    case LeftQuarterHelixLargeUp = 106;
    case RightQuarterHelixLargeUp = 107;
    case LeftQuarterHelixLargeDown = 108;
    case RightQuarterHelixLargeDown = 109;
    case Up25LeftBanked = 110;
    case Up25RightBanked = 111;
    case Waterfall = 112;
    case Rapids = 113;
    case OnRidePhoto = 114;
    case Down25LeftBanked = 115;
    case Down25RightBanked = 116;
    case Watersplash = 117;
    case FlatToUp60LongBase = 118;
    case Up60ToFlatLongBase = 119;
    case Whirlpool = 120;
    case Down60ToFlatLongBase = 121;
    case FlatToDown60LongBase = 122;
    case CableLiftHill = 123;
    case ReverseFreefallSlope = 124;
    case ReverseFreefallVertical = 125;
    case Up90 = 126;
    case Down90 = 127;
    case Up60ToUp90 = 128;
    case Down90ToDown60 = 129;
    case Up90ToUp60 = 130;
    case Down60ToDown90 = 131;
    case BrakeForDrop = 132;
    case LeftEighthToDiag = 133;
    case RightEighthToDiag = 134;
    case LeftEighthToOrthogonal = 135;
    case RightEighthToOrthogonal = 136;
    case LeftEighthBankToDiag = 137;
    case RightEighthBankToDiag = 138;
    case LeftEighthBankToOrthogonal = 139;
    case RightEighthBankToOrthogonal = 140;
    case DiagFlat = 141;
    case DiagUp25 = 142;
    case DiagUp60 = 143;
    case DiagFlatToUp25 = 144;
    case DiagUp25ToUp60 = 145;
    case DiagUp60ToUp25 = 146;
    case DiagUp25ToFlat = 147;
    case DiagDown25 = 148;
    case DiagDown60 = 149;
    case DiagFlatToDown25 = 150;
    case DiagDown25ToDown60 = 151;
    case DiagDown60ToDown25 = 152;
    case DiagDown25ToFlat = 153;
    case DiagFlatToUp60 = 154;
    case DiagUp60ToFlat = 155;
    case DiagFlatToDown60 = 156;
    case DiagDown60ToFlat = 157;
    case DiagFlatToLeftBank = 158;
    case DiagFlatToRightBank = 159;
    case DiagLeftBankToFlat = 160;
    case DiagRightBankToFlat = 161;
    case DiagLeftBankToUp25 = 162;
    case DiagRightBankToUp25 = 163;
    case DiagUp25ToLeftBank = 164;
    case DiagUp25ToRightBank = 165;
    case DiagLeftBankToDown25 = 166;
    case DiagRightBankToDown25 = 167;
    case DiagDown25ToLeftBank = 168;
    case DiagDown25ToRightBank = 169;
    case DiagLeftBank = 170;
    case DiagRightBank = 171;
    case LogFlumeReverser = 172;
    case SpinningTunnel = 173;
    case LeftBarrelRollUpToDown = 174;
    case RightBarrelRollUpToDown = 175;
    case LeftBarrelRollDownToUp = 176;
    case RightBarrelRollDownToUp = 177;
    case LeftBankToLeftQuarterTurn3TilesUp25 = 178;
    case RightBankToRightQuarterTurn3TilesUp25 = 179;
    case LeftQuarterTurn3TilesDown25ToLeftBank = 180;
    case RightQuarterTurn3TilesDown25ToRightBank = 181;
    case PoweredLift = 182;
    case LeftLargeHalfLoopUp = 183;
    case RightLargeHalfLoopUp = 184;
    case LeftLargeHalfLoopDown = 185;
    case RightLargeHalfLoopDown = 186;
    case LeftFlyerTwistUp = 187;
    case RightFlyerTwistUp = 188;
    case LeftFlyerTwistDown = 189;
    case RightFlyerTwistDown = 190;
    case FlyerHalfLoopUninvertedUp = 191;
    case FlyerHalfLoopInvertedDown = 192;
    case LeftFlyerCorkscrewUp = 193;
    case RightFlyerCorkscrewUp = 194;
    case LeftFlyerCorkscrewDown = 195;
    case RightFlyerCorkscrewDown = 196;
    case HeartLineTransferUp = 197;
    case HeartLineTransferDown = 198;
    case LeftHeartLineRoll = 199;
    case RightHeartLineRoll = 200;
    case MinigolfHoleA = 201;
    case MinigolfHoleB = 202;
    case MinigolfHoleC = 203;
    case MinigolfHoleD = 204;
    case MinigolfHoleE = 205;
    case MultiDimInvertedFlatToDown90QuarterLoop = 206;
    case Up90ToInvertedFlatQuarterLoop = 207;
    case InvertedFlatToDown90QuarterLoop = 208;
    case LeftCurvedLiftHill = 209;
    case RightCurvedLiftHill = 210;
    case LeftReverser = 211;
    case RightReverser = 212;
    case AirThrustTopCap = 213;
    case AirThrustVerticalDown = 214;
    case AirThrustVerticalDownToLevel = 215;
    case BlockBrakes = 216;
    case LeftBankedQuarterTurn3TileUp25 = 217;
    case RightBankedQuarterTurn3TileUp25 = 218;
    case LeftBankedQuarterTurn3TileDown25 = 219;
    case RightBankedQuarterTurn3TileDown25 = 220;
    case LeftBankedQuarterTurn5TileUp25 = 221;
    case RightBankedQuarterTurn5TileUp25 = 222;
    case LeftBankedQuarterTurn5TileDown25 = 223;
    case RightBankedQuarterTurn5TileDown25 = 224;
    case Up25ToLeftBankedUp25 = 225;
    case Up25ToRightBankedUp25 = 226;
    case LeftBankedUp25ToUp25 = 227;
    case RightBankedUp25ToUp25 = 228;
    case Down25ToLeftBankedDown25 = 229;
    case Down25ToRightBankedDown25 = 230;
    case LeftBankedDown25ToDown25 = 231;
    case RightBankedDown25ToDown25 = 232;
    case LeftBankedFlatToLeftBankedUp25 = 233;
    case RightBankedFlatToRightBankedUp25 = 234;
    case LeftBankedUp25ToLeftBankedFlat = 235;
    case RightBankedUp25ToRightBankedFlat = 236;
    case LeftBankedFlatToLeftBankedDown25 = 237;
    case RightBankedFlatToRightBankedDown25 = 238;
    case LeftBankedDown25ToLeftBankedFlat = 239;
    case RightBankedDown25ToRightBankedFlat = 240;
    case FlatToLeftBankedUp25 = 241;
    case FlatToRightBankedUp25 = 242;
    case LeftBankedUp25ToFlat = 243;
    case RightBankedUp25ToFlat = 244;
    case FlatToLeftBankedDown25 = 245;
    case FlatToRightBankedDown25 = 246;
    case LeftBankedDown25ToFlat = 247;
    case RightBankedDown25ToFlat = 248;
    case LeftQuarterTurn1TileUp90 = 249;
    case RightQuarterTurn1TileUp90 = 250;
    case LeftQuarterTurn1TileDown90 = 251;
    case RightQuarterTurn1TileDown90 = 252;
    case MultiDimUp90ToInvertedFlatQuarterLoop = 253;
    case MultiDimFlatToDown90QuarterLoop = 254;
    case MultiDimInvertedUp90ToFlatQuarterLoop = 255;
    case RotationControlToggle = 256;

    case FlatTrack1x4A = 257;
    case FlatTrack2x2 = 258;
    case FlatTrack4x4 = 259;
    case FlatTrack2x4 = 260;
    case FlatTrack1x5 = 261;
    case FlatTrack1x1A = 262;
    case FlatTrack1x4B = 263;
    case FlatTrack1x1B = 264;
    case FlatTrack1x4C = 265;
    case FlatTrack3x3 = 266;

    // Track Elements specific to OpenRCT2
    case LeftLargeCorkscrewUp = 267;
    case RightLargeCorkscrewUp = 268;
    case LeftLargeCorkscrewDown = 269;
    case RightLargeCorkscrewDown = 270;
    case LeftMediumHalfLoopUp = 271;
    case RightMediumHalfLoopUp = 272;
    case LeftMediumHalfLoopDown = 273;
    case RightMediumHalfLoopDown = 274;
    case LeftZeroGRollUp = 275;
    case RightZeroGRollUp = 276;
    case LeftZeroGRollDown = 277;
    case RightZeroGRollDown = 278;
    case LeftLargeZeroGRollUp = 279;
    case RightLargeZeroGRollUp = 280;
    case LeftLargeZeroGRollDown = 281;
    case RightLargeZeroGRollDown = 282;

    case LeftFlyerLargeHalfLoopUninvertedUp = 283;
    case RightFlyerLargeHalfLoopUninvertedUp = 284;
    case LeftFlyerLargeHalfLoopInvertedDown = 285;
    case RightFlyerLargeHalfLoopInvertedDown = 286;
    case LeftFlyerLargeHalfLoopInvertedUp = 287;
    case RightFlyerLargeHalfLoopInvertedUp = 288;
    case LeftFlyerLargeHalfLoopUninvertedDown = 289;
    case RightFlyerLargeHalfLoopUninvertedDown = 290;

    case FlyerHalfLoopInvertedUp = 291;
    case FlyerHalfLoopUninvertedDown = 292;

    case LeftEighthToDiagUp25 = 293;
    case RightEighthToDiagUp25 = 294;
    case LeftEighthToDiagDown25 = 295;
    case RightEighthToDiagDown25 = 296;
    case LeftEighthToOrthogonalUp25 = 297;
    case RightEighthToOrthogonalUp25 = 298;
    case LeftEighthToOrthogonalDown25 = 299;
    case RightEighthToOrthogonalDown25 = 300;

    case DiagUp25ToLeftBankedUp25 = 301;
    case DiagUp25ToRightBankedUp25 = 302;
    case DiagLeftBankedUp25ToUp25 = 303;
    case DiagRightBankedUp25ToUp25 = 304;
    case DiagDown25ToLeftBankedDown25 = 305;
    case DiagDown25ToRightBankedDown25 = 306;
    case DiagLeftBankedDown25ToDown25 = 307;
    case DiagRightBankedDown25ToDown25 = 308;
    case DiagLeftBankedFlatToLeftBankedUp25 = 309;
    case DiagRightBankedFlatToRightBankedUp25 = 310;
    case DiagLeftBankedUp25ToLeftBankedFlat = 311;
    case DiagRightBankedUp25ToRightBankedFlat = 312;
    case DiagLeftBankedFlatToLeftBankedDown25 = 313;
    case DiagRightBankedFlatToRightBankedDown25 = 314;
    case DiagLeftBankedDown25ToLeftBankedFlat = 315;
    case DiagRightBankedDown25ToRightBankedFlat = 316;
    case DiagFlatToLeftBankedUp25 = 317;
    case DiagFlatToRightBankedUp25 = 318;
    case DiagLeftBankedUp25ToFlat = 319;
    case DiagRightBankedUp25ToFlat = 320;
    case DiagFlatToLeftBankedDown25 = 321;
    case DiagFlatToRightBankedDown25 = 322;
    case DiagLeftBankedDown25ToFlat = 323;
    case DiagRightBankedDown25ToFlat = 324;
    case DiagUp25LeftBanked = 325;
    case DiagUp25RightBanked = 326;
    case DiagDown25LeftBanked = 327;
    case DiagDown25RightBanked = 328;

    case LeftEighthBankToDiagUp25 = 329;
    case RightEighthBankToDiagUp25 = 330;
    case LeftEighthBankToDiagDown25 = 331;
    case RightEighthBankToDiagDown25 = 332;
    case LeftEighthBankToOrthogonalUp25 = 333;
    case RightEighthBankToOrthogonalUp25 = 334;
    case LeftEighthBankToOrthogonalDown25 = 335;
    case RightEighthBankToOrthogonalDown25 = 336;

    case DiagBrakes = 337;
    case DiagBlockBrakes = 338;

    public static function fromTD4(RCT1RideType $rideType, int $input): self
    {
        if ($rideType === RCT1RideType::HEDGE_MAZE)
        {
            throw new RuntimeException('Mazes do not save the track element type in this way!');
        }

        if ($member = TrackTypeID::tryFrom($input))
        {
            if (self::isFlatRide($rideType))
            {
                return self::normalizeFlatTrackAlias($member);
            }
        }

        return self::from($input);
    }

    public static function fromTD6(RideType $rideType, int $input): self
    {
        if ($rideType === RideType::MAZE)
        {
            throw new RuntimeException('Mazes do not save the track element type in this way!');
        }

        if ($member = TrackTypeID::tryFrom($input))
        {
            if ($member === TrackTypeID::InvertedUp90ToFlatQuarterLoopAlias)
            {
                return self::MultiDimInvertedUp90ToFlatQuarterLoop;
            }
            if (($rideType === RideType::STEEL_WILD_MOUSE || $rideType === RideType::SPINNING_WILD_MOUSE)
                && $member === TrackTypeID::RotationControlToggleAlias)
            {
                return self::RotationControlToggle;
            }

            if (self::isFlatRide($rideType))
            {
                return self::normalizeFlatTrackAlias($member);
            }
        }

        return self::from($input);
    }

    private static function normalizeFlatTrackAlias(TrackTypeID $member): self
    {
        return match ($member)
        {
            TrackTypeID::FlatTrack1x4A_Alias => self::FlatTrack1x4A,
            TrackTypeID::FlatTrack2x2_Alias => self::FlatTrack2x2,
            TrackTypeID::FlatTrack4x4_Alias => self::FlatTrack4x4,
            TrackTypeID::FlatTrack2x4_Alias => self::FlatTrack2x4,
            TrackTypeID::FlatTrack1x5_Alias => self::FlatTrack1x5,
            TrackTypeID::FlatTrack1x1A_Alias => self::FlatTrack1x1A,
            TrackTypeID::FlatTrack1x4B_Alias => self::FlatTrack1x4B,
            TrackTypeID::FlatTrack1x1B_Alias => self::FlatTrack1x1B,
            TrackTypeID::FlatTrack1x4C_Alias => self::FlatTrack1x4C,
            TrackTypeID::FlatTrack3x3_Alias => self::FlatTrack3x3,
            default => throw new RuntimeException('Invalid piece for a flat ride!'),
        };
    }

    private static function isFlatRide(RideType|RCT1RideType $rideType): bool
    {
        if ($rideType instanceof RCT1RideType)
        {
            return match ($rideType)
            {
                RCT1RideType::SPIRAL_SLIDE => true,
                RCT1RideType::DODGEMS => true,
                RCT1RideType::SWINGING_SHIP => true,
                RCT1RideType::SWINGING_INVERTER_SHIP => true,
                RCT1RideType::MERRY_GO_ROUND => true,
                RCT1RideType::FERRIS_WHEEL => true,
                RCT1RideType::MOTION_SIMULATOR => true,
                RCT1RideType::_3D_CINEMA => true,
                RCT1RideType::TOP_SPIN => true,
                RCT1RideType::SPACE_RINGS => true,
                RCT1RideType::TWIST => true,
                RCT1RideType::HAUNTED_HOUSE => true,
                RCT1RideType::CIRCUS => true,
                RCT1RideType::FLYING_SAUCERS => true,
                RCT1RideType::CROOKED_HOUSE => true,
                RCT1RideType::ENTERPRISE => true,
                default => false,
            };
        }

        return match ($rideType)
        {
            RideType::SPIRAL_SLIDE => true,
            RideType::DODGEMS => true,
            RideType::SWINGING_SHIP => true,
            RideType::SWINGING_INVERTER_SHIP => true,
            RideType::MERRY_GO_ROUND => true,
            RideType::FERRIS_WHEEL => true,
            RideType::MOTION_SIMULATOR => true,
            RideType::_3D_CINEMA => true,
            RideType::TOP_SPIN => true,
            RideType::SPACE_RINGS => true,
            RideType::TWIST => true,
            RideType::HAUNTED_HOUSE => true,
            RideType::CIRCUS => true,
            RideType::FLYING_SAUCERS => true,
            RideType::CROOKED_HOUSE => true,
            RideType::MAGIC_CARPET => true,
            RideType::ENTERPRISE => true,
            default => false,
        };
    }
}

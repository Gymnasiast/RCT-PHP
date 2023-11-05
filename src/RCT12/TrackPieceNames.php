<?php
declare(strict_types=1);

namespace RCTPHP\RCT12;

final class TrackPieceNames
{
    // As used by Greg Wolking’s RCT Track Decoder,
    // of which the results are used by the TD6Sherlock program that RCTSpace’s Ride Exchange used.
    public const NAMES = [
        0 => "Level",
        1 => "End station",
        2 => "Begin station",
        3 => "Middle station",
        4 => "Up",
        5 => "Up steep",
        6 => "Level to up",
        7 => "Up to up steep",
        8 => "Up steep to up",
        9 => "Up to level",
        10 => "Down",
        11 => "Down steep",
        12 => "Level to down",
        13 => "Down to down steep",
        14 => "Down steep to down",
        15 => "Down to level",
        16 => "Left curve [R3]",
        17 => "Right curve [R3]",
        18 => "Level to roll left",
        19 => "Level to roll right",
        20 => "Roll left to level",
        21 => "Roll right to level",
        22 => "Banked left curve [R3]",
        23 => "Banked right curve [R3]",
        24 => "Roll left to slope up",
        25 => "Roll right to slope up",
        26 => "Up to level, roll left",
        27 => "Up to level, roll right",
        28 => "Roll left to slope down",
        29 => "Roll right to slope down",
        30 => "Down to level, roll left",
        31 => "Down to level, roll right",
        32 => "Roll left",
        33 => "Roll right",
        34 => "Left curve up [R3]",
        35 => "Right curve up [R3]",
        36 => "Left curve down [R3]",
        37 => "Right curve down [R3]",
        38 => "Ess bend left",
        39 => "Ess bend right",
        40 => "Vertical loop left",
        41 => "Vertical loop right",
        42 => "Left curve [R2]",
        43 => "Right curve [R2]",
        44 => "Banked left curve [R2]",
        45 => "Banked right curve [R2]",
        46 => "Left curve up [R2]",
        47 => "Right curve up [R2]",
        48 => "Left curve down [R2]",
        49 => "Right curve down [R2]",
        50 => "Left curve [R1]",
        51 => "Right curve [R1]",
        52 => "Inline twist left, normal to inverted",
        53 => "Inline twist right, normal to inverted",
        54 => "Inline twist left, inverted to normal",
        55 => "Inline twist right, inverted to normal",
        56 => "Half loop, normal to inverted",
        57 => "Half loop, inverted to normal",
        58 => "Half-corkscrew left, normal to inverted",
        59 => "Half-corkscrew right, normal to inverted",
        60 => "Half-corkscrew left, inverted to normal",
        61 => "Half-corkscrew right, inverted to normal",
        62 => "Level to up steep",
        63 => "Up steep to level",
        64 => "Level to down steep",
        65 => "Down steep to level",
        66 => "Tower base",
        67 => "Tower vertical section",
        68 => "Level, covered",
        69 => "Up, covered",
        70 => "Up steep, covered",
        71 => "Level to up, covered",
        72 => "Up to up steep, covered",
        73 => "Up steep to up, covered",
        74 => "Up to level, covered",
        75 => "Down, covered",
        76 => "Down steep, covered",
        77 => "Level to down, covered",
        78 => "Down to down steep, covered",
        79 => "Down steep to down, covered",
        80 => "Down to level, covered",
        81 => "Left curve [R3], covered",
        82 => "Right curve [R3], covered",
        83 => "Ess bend left, covered",
        84 => "Ess bend right, covered",
        85 => "Left curve [R2], covered",
        86 => "Right curve [R2], covered",
        87 => "Helix up left [R2]",
        88 => "Helix up right [R2]",
        89 => "Helix down left [R2]",
        90 => "Helix down right [R2]",
        91 => "Helix up left [R3]",
        92 => "Helix up right [R3]",
        93 => "Helix down left [R3]",
        94 => "Helix down right [R3]",
        95 => "Steep upward twist left [R1]",
        96 => "Steep upward twist right [R1]",
        97 => "Steep downward twist left [R1]",
        98 => "Steep downward twist right [R1]",
        99 => "Brakes",
        100 => "Booster",
        101 => "Quarter loop up",
        102 => "Helix up left [R3]",
        103 => "Helix up right [R3]",
        104 => "Helix down left [R3]",
        105 => "Helix down right [R3]",
        106 => "Helix up left [R3]",
        107 => "Helix up right [R3]",
        108 => "Helix down left [R3]",
        109 => "Helix down right [R3]",
        110 => "Slope up banked left",
        111 => "Slope up banked right",
        112 => "Waterfall",
        113 => "Rapids",
        114 => "On-ride photo section",
        115 => "Slope down banked left",
        116 => "Slope up banked right",
        117 => "Water splash",
        118 => "Parabolic level to up steep",
        119 => "Parabolic up steep to level",
        120 => "Whirlpool",
        121 => "Parabolic down steep to level",
        122 => "Parabolic level to down steep",
        123 => "Cable lift hill",
        124 => "Slope up to vertical",
        125 => "Vertical slope up",
        126 => "Vertical slope up",
        127 => "Vertical slope down",
        128 => "Up steep to vertical",
        129 => "Vertical to down steep",
        130 => "Vertical to up steep",
        131 => "Down steep to vertical",
        132 => "Holding brake for vertical drop",
        133 => "Left curve [R4]",
        134 => "Right curve [R4]",
        135 => "Left curve [R4]",
        136 => "Right curve [R4]",
        137 => "Banked left curve [R4]",
        138 => "Banked right curve [R4]",
        139 => "Banked left curve [R4]",
        140 => "Banked right curve [R4]",
        141 => "Level [diag]",
        142 => "Up [diag]",
        143 => "Up steep [diag]",
        144 => "Level to up [diag]",
        145 => "Up to up steep [diag]",
        146 => "Up steep to up [diag]",
        147 => "Up to level [diag]",
        148 => "Down [diag]",
        149 => "Down steep [diag]",
        150 => "Level to down [diag]",
        151 => "Down to down steep [diag]",
        152 => "Down steep to down [diag]",
        153 => "Down to level [diag]",
        154 => "Level to up steep [diag]",
        155 => "Up steep to level [diag]",
        156 => "Level to down steep [diag]",
        157 => "Down steep to level [diag]",
        158 => "Level to roll left [diag]",
        159 => "Level to roll right [diag]",
        160 => "Roll left to level [diag]",
        161 => "Roll right to level [diag]",
        162 => "Roll left to up [diag]",
        163 => "Roll right to up [diag]",
        164 => "Up to roll left [diag]",
        165 => "Up to roll right [diag]",
        166 => "Roll left to down [diag]",
        167 => "Roll right to down [diag]",
        168 => "Down to roll left [diag]",
        169 => "Down to roll right [diag]",
        170 => "Roll left [diag]",
        171 => "Roll right [diag]",
        172 => "Reverser turntable",
        173 => "Spinning tunnel",
        174 => "Barrel roll left, normal to inverted",
        175 => "Barrel roll right, normal to inverted",
        176 => "Barrel roll left, inverted to normal",
        177 => "Barrel roll right, inverted to normal",
        178 => "Roll left to left turn up [R2]",
        179 => "Roll right to right turn up [R2]",
        180 => "Left curve down [R2] to roll left",
        181 => "Right curve down [R2] to roll right",
        182 => "Launched lift hill",
        183 => "Large half loop left, normal to inverted",
        184 => "Large half loop right, normal to inverted",
        185 => "Large half loop left, inverted to normal",
        186 => "Large half loop right, inverted to normal",
        187 => "Inline twist left, normal to inverted",
        188 => "Inline twist right, normal to inverted",
        189 => "Inline twist left, inverted to normal",
        190 => "Inline twist right, inverted to normal",
        191 => "Half loop, normal to inverted",
        192 => "Half loop, inverted to normal",
        193 => "Half-corkscrew left, normal to inverted",
        194 => "Half-corkscrew right, normal to inverted",
        195 => "Half-corkscrew left, inverted to normal",
        196 => "Half-corkscrew right, inverted to normal",
        197 => "Heartline transfer up",
        198 => "Heartline transfer down",
        199 => "Heartline roll left",
        200 => "Heartline roll right",
        201 => "Mini-golf hole A",
        202 => "Mini-golf hole B",
        203 => "Mini-golf hole C",
        204 => "Mini-golf hole D",
        205 => "Mini-golf hole E",
        206 => "Quarter loop down",
        207 => "Quarter loop up",
        208 => "Quarter loop down",
        209 => "Spiral lift hill left",
        210 => "Spiral lift hill right",
        211 => "Reversing section left",
        212 => "Reversing section right",
        213 => "Top section",
        214 => "Vertical slope down",
        215 => "Slope to level",
        216 => "Block brake",
        217 => "Banked left curve up [R2]",
        218 => "Banked right curve up [R2]",
        219 => "Banked left curve down [R2]",
        220 => "Banked right curve down [R2]",
        221 => "Banked left curve up [R3]",
        222 => "Banked right curve up [R3]",
        223 => "Banked left curve down [R3]",
        224 => "Banked right curve down [R3]",
        225 => "Slope up to sloped left bank",
        226 => "Slope up to sloped right bank",
        227 => "Left bank slope up to slope up",
        228 => "Right bank slope up to slope up",
        229 => "Slope down to sloped left bank",
        230 => "Slope down to sloped right bank",
        231 => "Left bank slope down to slope down",
        232 => "Right bank slope down to slope down",
        233 => "Left bank to left bank slope up",
        234 => "Right bank to right bank slope up",
        235 => "Left bank slope up to left bank",
        236 => "Right bank slope up to right bank",
        237 => "Left bank to left bank slope down",
        238 => "Right bank to right bank slope down",
        239 => "Left bank slope down to left bank",
        240 => "Right bank slope down to right bank",
        241 => "Level to left bank slope up",
        242 => "Level to right bank slope up",
        243 => "Left bank slope up to level",
        244 => "Right bank slope up to level",
        245 => "Level to left bank slope down",
        246 => "Level to right bank slope down",
        247 => "Left bank slope down to level",
        248 => "Right bank slope down to level",
        249 => "Vertical twist up left",
        250 => "Vertical twist up right",
        251 => "Vertical twist down left",
        252 => "Vertical twist down right",
        253 => "Quarter loop up",
        254 => "Quarter loop down",
    ];
}

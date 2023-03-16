# RCT-PHP

RCT-PHP is a library to read and manipulate object files from RollerCoaster Tycoon 1, RollerCoaster Tycoon 2, and OpenRCT2. The aim is to be as complete as possible in this, avoiding the need to chain with external tools or having to fix stuff manually. As a bonus, there is also some support for .DAT files from Locomotion, as these are very similar to the ones in RCT2.

## Technical

RCT-PHP needs at least PHP 8.1, with the gd extension enabled. Composer is used to handle autoloading. The library is mostly intended as an external dependency for other projects like [GOES](https://goes.rctspace.com/), although scripts are provided to output data via the command line.

RCT-PHP is still very much in early development, and as such is subject to breaking changes at any point. For the same reason, backwards compatibility with older PHP versions is not a priority either. Both points should eventually be resolved once the library matures.

## Features

Note that many of these are still a work-in-progress.

- Reading of .DAT files from RCT2 and displaying their data
- Decoding of .DAT string tables, including conversion to UTF-8
- Conversion of RCT2 .DAT files to OpenRCT2 .parkobj
- Reading and extracting image tables (from both g1.dat and .DAT objects)
- Reading, extracting and creating CSS1.DAT sound effects files
- Converting .TP4 images to .PNG and vice versa
- Reading .DAT files from Locomotion and displaying their data.
- Reading and converting palettes from .DAT files and RCT2 Palette Maker files to .parkobj
- Creating animated PNGs with moving water from a still image

## Future plans

- Adding support for _all_ .DAT object types
- Adding support for _all_ OpenRCT2 .parkobj types
- Splitting off the command line functions into their own project and extending these
- Re-rendering an existing screenshot with a different palette

<?php

namespace WhichBrowser\Analyser\Header\Useragent;

trait Device
{
    use Device\Cars, Device\Gaming, Device\Ereader, Device\Mobile,
        Device\Media, Device\Television, Device\Signage, Device\Pda;

    private function &detectDevice($ua)
    {
        $this->detectCars($ua);
        $this->detectEreader($ua);
        $this->detectGaming($ua);
        $this->detectTelevision($ua);
        $this->detectSignage($ua);
        $this->detectMedia($ua);
        $this->detectPda($ua);
        $this->detectMobile($ua);

        return $this;
    }
}

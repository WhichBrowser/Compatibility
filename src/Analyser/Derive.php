<?php

namespace WhichBrowser\Analyser;

use WhichBrowser\Constants;
use WhichBrowser\Model\Family;
use WhichBrowser\Model\Using;
use WhichBrowser\Model\Version;

trait Derive
{
    private function &deriveInformation()
    {
        if (isset($this->data->device->flag)) {
            $this->deriveBasedOnDeviceFlag();
        }

        if (isset($this->data->os->name)) {
            $this->deriveBasedOnOperatingSystem();
        }

        if (isset($this->data->browser->name)) {
            $this->deriveOperaDevices();
        }

        if (isset($this->data->browser->name)) {
            $this->deriveFirefoxOS();
        }

        return $this;
    }




    private function &deriveDeviceSubType()
    {
        if ($this->data->device->type == 'mobile') {
            $this->data->device->subtype = 'feature';

            if (isset($this->data->os->family) && in_array($this->data->os->family->getName(), [ 'Android' ])) {
                $this->data->device->subtype = 'smart';
            }

            if (in_array($this->data->os->getName(), [ 'Android', 'Bada', 'BlackBerry', 'BlackBerry OS', 'Firefox OS', 'iOS', 'iPhone OS', 'Kin OS', 'Maemo', 'MeeGo', 'Palm OS', 'Sailfish', 'Series60', 'Tizen', 'Ubuntu', 'Windows Mobile', 'Windows Phone', 'webOS' ])) {
                $this->data->device->subtype = 'smart';
            }
        }

        return $this;
    }


    private function deriveFirefoxOS()
    {
        if ($this->data->browser->name == 'Firefox Mobile' && !isset($this->data->os->name)) {
            $this->data->os->name = 'Firefox OS';
        }

        if (isset($this->data->os->name) && $this->data->os->name == 'Firefox OS') {
            switch ($this->data->engine->getVersion()) {
                case '18.0':
                    $this->data->os->version = new Version([ 'value' => '1.0.1' ]);
                    break;
                case '18.1':
                    $this->data->os->version = new Version([ 'value' => '1.1' ]);
                    break;
                case '26.0':
                    $this->data->os->version = new Version([ 'value' => '1.2' ]);
                    break;
                case '28.0':
                    $this->data->os->version = new Version([ 'value' => '1.3' ]);
                    break;
                case '30.0':
                    $this->data->os->version = new Version([ 'value' => '1.4' ]);
                    break;
                case '32.0':
                    $this->data->os->version = new Version([ 'value' => '2.0' ]);
                    break;
                case '34.0':
                    $this->data->os->version = new Version([ 'value' => '2.1' ]);
                    break;
            }
        }
    }


    private function deriveOperaDevices()
    {
        if ($this->data->browser->name == 'Opera' && $this->data->device->type == Constants\DeviceType::TELEVISION) {
            $this->data->browser->name = 'Opera Devices';

            if ($this->data->engine->getName() == 'Presto') {
                switch (implode('.', array_slice(explode('.', $this->data->engine->getVersion()), 0, 2))) {
                    case '2.12':
                        $this->data->browser->version = new Version([ 'value' => '3.4' ]);
                        break;
                    case '2.11':
                        $this->data->browser->version = new Version([ 'value' => '3.3' ]);
                        break;
                    case '2.10':
                        $this->data->browser->version = new Version([ 'value' => '3.2' ]);
                        break;
                    case '2.9':
                        $this->data->browser->version = new Version([ 'value' => '3.1' ]);
                        break;
                    case '2.8':
                        $this->data->browser->version = new Version([ 'value' => '3.0' ]);
                        break;
                    case '2.7':
                        $this->data->browser->version = new Version([ 'value' => '2.9' ]);
                        break;
                    case '2.6':
                        $this->data->browser->version = new Version([ 'value' => '2.8' ]);
                        break;
                    case '2.4':
                        $this->data->browser->version = new Version([ 'value' => '10.3' ]);
                        break;
                    case '2.3':
                        $this->data->browser->version = new Version([ 'value' => '10' ]);
                        break;
                    case '2.2':
                        $this->data->browser->version = new Version([ 'value' => '9.7' ]);
                        break;
                    case '2.1':
                        $this->data->browser->version = new Version([ 'value' => '9.6' ]);
                        break;
                    default:
                        unset($this->data->browser->version);
                }
            } else {
                switch (explode('.', $this->data->browser->getVersion())[0]) {
                    case '17':
                        $this->data->browser->version = new Version([ 'value' => '4.0' ]);
                        break;
                    case '19':
                        $this->data->browser->version = new Version([ 'value' => '4.1' ]);
                        break;
                    case '22':
                        $this->data->browser->version = new Version([ 'value' => '4.2' ]);
                        break;
                    default:
                        unset($this->data->browser->version);
                }
            }

            unset($this->data->os->name);
            unset($this->data->os->version);
        }
    }



    private function deriveBasedOnDeviceFlag()
    {
        if ($this->data->device->flag == Constants\Flag::NOKIAX) {
            $this->data->os->name = 'Nokia X Platform';
            $this->data->os->family = new Family([ 'name' => 'Android' ]);

            unset($this->data->os->version);
            unset($this->data->device->flag);
            return;
        }

        if ($this->data->device->flag == Constants\Flag::FIREOS) {
            $this->data->os->name = 'FireOS';
            $this->data->os->family = new Family([ 'name' => 'Android' ]);

            if (isset($this->data->os->version) && isset($this->data->os->version->value)) {
                switch ($this->data->os->version->value) {
                    case '2.3.3':
                        $this->data->os->version = new Version([ 'value' => '1' ]);
                        break;
                    case '4.0.3':
                        $this->data->os->version = new Version([ 'value' => '2' ]);
                        break;
                    case '4.2.2':
                        $this->data->os->version = new Version([ 'value' => '3' ]);
                        break;
                    case '4.4.2':
                        $this->data->os->version = new Version([ 'value' => '4' ]);
                        break;
                    case '4.4.3':
                        $this->data->os->version = new Version([ 'value' => '4.5' ]);
                        break;
                    case '5.1.1':
                        $this->data->os->version = new Version([ 'value' => '5' ]);
                        break;
                    default:
                        unset($this->data->os->version);
                        break;
                }
            }

            if ($this->data->isBrowser('Chrome')) {
                $this->data->browser->reset();
                $this->data->browser->using = new Using([ 'name' => 'Amazon WebView' ]);
            }

            if ($this->data->browser->isUsing('Chromium WebView')) {
                $this->data->browser->using = new Using([ 'name' => 'Amazon WebView' ]);
            }

            unset($this->data->device->flag);
            return;
        }

        if ($this->data->device->flag == Constants\Flag::GOOGLETV) {
            $this->data->os->name = 'Google TV';
            $this->data->os->family = new Family([ 'name' => 'Android' ]);

            unset($this->data->os->version);
            unset($this->data->device->flag);
            return;
        }

        if ($this->data->device->flag == Constants\Flag::ANDROIDTV) {
            $this->data->os->name = 'Android TV';
            $this->data->os->family = new Family([ 'name' => 'Android' ]);

            unset($this->data->device->flag);
            return;
        }

        if ($this->data->device->flag == Constants\Flag::ANDROIDWEAR) {
            $this->data->os->name = 'Android Wear';
            $this->data->os->family = new Family([ 'name' => 'Android' ]);
            unset($this->data->os->version);
            unset($this->data->device->flag);

            if ($this->data->browser->isUsing('Chrome Content Shell')) {
                $this->data->browser->name = 'Wear Internet Browser';
                $this->data->browser->using = null;
            }

            return;
        }

        if ($this->data->device->flag == Constants\Flag::GOOGLEGLASS) {
            $this->data->os->family = new Family([ 'name' => 'Android' ]);
            unset($this->data->os->name);
            unset($this->data->os->version);
            unset($this->data->device->flag);
            return;
        }
    }

    private function deriveBasedOnOperatingSystem()
    {
        /* Derive the default browser on Android */

        if ($this->data->os->name == 'Android' && !isset($this->data->browser->using) && !isset($this->data->browser->name) && $this->data->browser->stock) {
            $this->data->browser->name = 'Android Browser';
        }

        /* Derive the default browser on Google TV */

        if ($this->data->os->name == 'Google TV' && !isset($this->data->browser->name) && $this->data->browser->stock) {
            $this->data->browser->name = 'Chrome';
        }

        /* Derive the default browser on BlackBerry */

        if ($this->data->os->name == 'BlackBerry' && !isset($this->data->browser->name) && $this->data->browser->stock) {
            $this->data->browser->name = 'BlackBerry Browser';
            $this->data->browser->hidden = true;
        }

        if ($this->data->os->name == 'BlackBerry OS' && !isset($this->data->browser->name) && $this->data->browser->stock) {
            $this->data->browser->name = 'BlackBerry Browser';
            $this->data->browser->hidden = true;
        }

        if ($this->data->os->name == 'BlackBerry Tablet OS' && !isset($this->data->browser->name) && $this->data->browser->stock) {
            $this->data->browser->name = 'BlackBerry Browser';
            $this->data->browser->hidden = true;
        }

        /* Derive the default browser on Tizen */

        if ($this->data->os->name == 'Tizen' && !isset($this->data->browser->name) && $this->data->browser->stock && $this->data->device->type == Constants\DeviceType::MOBILE) {
            $this->data->browser->name = 'Samsung Browser';
        }

        /* Derive the default browser on Aliyun OS */

        if ($this->data->os->name == 'Aliyun OS' && !isset($this->data->browser->using) && !isset($this->data->browser->name) && $this->data->browser->stock) {
            $this->data->browser->name = 'Aliyun Browser';
        }

        if ($this->data->os->name == 'Aliyun OS' && $this->data->browser->isUsing('Chrome Content Shell')) {
            $this->data->browser->name = 'Aliyun Browser';
            $this->data->browser->using = null;
            $this->data->browser->stock = true;
        }

        if ($this->data->os->name == 'Aliyun OS' && $this->data->browser->stock) {
            $this->data->browser->hidden = true;
        }

        /* Derive iOS and OS X versions from Darwin */

        if ($this->data->os->name == 'Darwin' && $this->data->device->type == Constants\DeviceType::MOBILE) {
            $this->data->os->name = 'iOS';

            switch (strstr($this->data->os->getVersion(), '.', true)) {
                case '9':
                    $this->data->os->version = new Version([ 'value' =>'1' ]);
                    $this->data->os->alias = 'iPhone OS';
                    break;
                case '10':
                    $this->data->os->version = new Version([ 'value' =>'4' ]);
                    break;
                case '11':
                    $this->data->os->version = new Version([ 'value' =>'5' ]);
                    break;
                case '13':
                    $this->data->os->version = new Version([ 'value' =>'6' ]);
                    break;
                case '14':
                    $this->data->os->version = new Version([ 'value' =>'7' ]);
                    break;
                case '15':
                    $this->data->os->version = new Version([ 'value' =>'9' ]);
                    break;
                default:
                    $this->data->os->version = null;
            }
        }

        if ($this->data->os->name == 'Darwin' && $this->data->device->type == Constants\DeviceType::DESKTOP) {
            $this->data->os->name = 'OS X';

            switch (strstr($this->data->os->getVersion(), '.', true)) {
                case '1':
                    $this->data->os->version = new Version([ 'value' =>'10.0' ]);
                    break;
                case '5':
                    $this->data->os->version = new Version([ 'value' =>'10.1' ]);
                    break;
                case '6':
                    $this->data->os->version = new Version([ 'value' =>'10.2' ]);
                    break;
                case '7':
                    $this->data->os->version = new Version([ 'value' =>'10.3' ]);
                    break;
                case '8':
                    $this->data->os->version = new Version([ 'value' =>'10.4' ]);
                    break;
                case '9':
                    $this->data->os->version = new Version([ 'value' =>'10.5' ]);
                    break;
                case '10':
                    $this->data->os->version = new Version([ 'value' =>'10.6' ]);
                    break;
                case '11':
                    $this->data->os->version = new Version([ 'value' =>'10.7' ]);
                    break;
                case '12':
                    $this->data->os->version = new Version([ 'value' =>'10.8' ]);
                    break;
                case '13':
                    $this->data->os->version = new Version([ 'value' =>'10.9' ]);
                    break;
                case '14':
                    $this->data->os->version = new Version([ 'value' =>'10.10' ]);
                    break;
                case '15':
                    $this->data->os->version = new Version([ 'value' =>'10.11' ]);
                    break;
                default:
                    $this->data->os->version = null;
            }

            if (!empty($this->data->os->version)) {
                if ($this->data->os->version->is('<', '10.7')) {
                    $this->data->os->alias = 'Mac OS X';
                }

                if ($this->data->os->version->is('10.7')) {
                    $this->data->os->version->nickname = 'Lion';
                }

                if ($this->data->os->version->is('10.8')) {
                    $this->data->os->version->nickname = 'Mountain Lion';
                }

                if ($this->data->os->version->is('10.9')) {
                    $this->data->os->version->nickname = 'Mavericks';
                }

                if ($this->data->os->version->is('10.10')) {
                    $this->data->os->version->nickname = 'Yosemite';
                }
                
                if ($this->data->os->version->is('10.11')) {
                    $this->data->os->version->nickname = 'El Capitan';
                }
            }
        }
    }
}

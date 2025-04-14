<?php

/**
 * Project: EU Cookie Law Add-On
 *
 * @copyright 2017 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version 1.1.2
 */

namespace Concrete\Package\EuCookieLaw\Src;

use \Concrete\Core\Support\Facade\Application;
use \Concrete\Core\Http\Request;
use \GeoIp2\Database\Reader;
use \Concrete\Core\Page\Page;
use \Concrete\Core\Localization\Localization;
use \Concrete\Core\Multilingual\Page\Section\Section;

class Helpers
{
    private static $cachedCountryCode = null;

    /**
     * @param array $arr
     *
     * @return array
     */
    public static function getKeys($arr)
    {
        $keys = array();

        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                array_push($keys, $k);

                unset($v);
            }
        }

        return $keys;
    }


    /**
     * @param string $color
     *
     * @return boolean
     */
    public static function isValidColor($color)
    {
        $allColors = array('aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');

        if (in_array(strtolower($color), $allColors)) {
            return true;
        } elseif (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return true;
        } elseif (preg_match('/^[a-f0-9]{6}$/i', $color)) {
            return true;
        }

        return false;
    }

    /**
     * @param integer $pageId
     *
     * @return boolean
     */
    public static function isValidPageId($pageId)
    {
        return is_numeric($pageId) && is_object(Page::getById($pageId)) && $pageId > 0;
    }

    /**
     *
     * @param string $url
     *
     * @return string
     */
    public static function fetchUrl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public static function getPublicIpAddress()
    {
        $url = "https://freegeoip.net/json/";

        $data = self::fetchUrl($url);

        $json = json_decode($data, true);

        if (isset($json["ip"])) {
            return $json["ip"];
        } else {
            return "";
        }
    }

    public static function isLocalIpAddress($ipAddress)
    {
        return $ipAddress === "127.0.0.1" || $ipAddress === "::1";
    }

    public static function getCurrentIpAddress()
    {
        $app = Application::getFacadeApplication();

        /** @var $request Request */
        $request = $app->make(Request::class);

        $ipAddress = $request->server->get("REMOTE_ADDR");

        if (self::isLocalIpAddress($ipAddress)) {
            // is local
            $ipAddress = self::getPublicIpAddress();
        }

        return $ipAddress;
    }

    /**
     * @param string $ipAddress
     *
     * @return string
     */
    public static function getCountryCodeFromIpAddress($ipAddress = null)
    {
        $defaultCountryCode = "DE";

        if (is_null(self::$cachedCountryCode)) {
            $reader = new Reader(DIR_PACKAGES . '/eu_cookie_law/files/GeoLite2-Country.mmdb');

            if (is_null($ipAddress)) {
                $ipAddress = self::getCurrentIpAddress();
            }

            if ($ipAddress === "") {
                return;
            }

            try {
                $record = $reader->country($ipAddress);

                self::$cachedCountryCode = $record->country->isoCode;
            } catch (\GeoIp2\Exception\AddressNotFoundException $ex) {
                self::$cachedCountryCode = $defaultCountryCode;
            } catch (\GeoIp2\Exception\InvalidDatabaseException $ex) {
                self::$cachedCountryCode = $defaultCountryCode;
            } catch (Exception $ex) {
                self::$cachedCountryCode = $defaultCountryCode;
            }
        }

        return self::$cachedCountryCode;
    }


    /**
     * @param string $path
     * @return string
     */
    public static function translatePath($path)
    {
        $app = Application::getFacadeApplication();

        /**
         * @var $db \Doctrine\DBAL\Connection
         */
        $db = $app->make('database');

        $prefix = $db->fetchColumn("SELECT cPath FROM `PageSearchIndex` WHERE cID = 1");

        if ($prefix === "/") {
            $prefix = "";
        }

        $page = Page::getByPath($prefix . $path);

        if (is_object($page)) {
            $originalSection = Section::getBySectionOfSite($page);

            $currentLanguage = Localization::activeLocale();

            if (is_object($originalSection)) {
                if (!in_array($page->getCollectionID(), $originalSection::getIDList())) {
                    $mpRelationID = $originalSection::getMultilingualPageRelationID($page->getCollectionID());

                    if ($mpRelationID) {
                        $translatedPageId = $originalSection::getCollectionIDForLocale($mpRelationID, $currentLanguage);

                        if ($translatedPageId) {
                            $translatedPage = Page::getByID($translatedPageId);

                            if (is_object($translatedPage) && !$translatedPage->isError()) {
                                $path = $translatedPage->getCollectionPath();
                            }
                        }
                    }
                }
            }
        }

        return $path;
    }
}

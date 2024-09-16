<?php

namespace App;

class FlashSystem
{
    public function setMessage(string $message)
    {
        $_SESSION['flash_message'] = $message;
    }

    public function tryGetFlashMessage()
    {
        $session = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);
        return $session;
    }

    public function massCorrectCurrentLink(array $data): string
    {
        $link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        foreach ($data as $key => $value) {
            $link = $this->correctCurrentLink($key, $value, $link);
        }

        return $link;
    }

    public function correctCurrentLink($parameter, $newValue, $url = null): string
    {
        if ($url === null) {
            $currentUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $currentUrl = $url;
        }

        $urlComponents = parse_url($currentUrl);

        parse_str($urlComponents['query'] ?? '', $queryParams);

        $queryParams[$parameter] = $newValue;
        $newQueryString = http_build_query($queryParams);

        return $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'] . '?' . $newQueryString;
    }

    public function back(): void
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: /');
        }
    }

    public function getTitleFromUrl($url): string {
        return 'Перейти на сайт';
        $url = trim($url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "Перейти на сайт";
        }

        $content = @file_get_contents($url);
        if ($content === FALSE) {
            return "Перейти на сайт";
        }

        preg_match("/<title>(.*?)<\/title>/si", $content, $matches);
        return !empty($matches[1]) ? $matches[1] : "Перейти на сайт";
    }

    public function groupByKey(array $items, $key = 'id'): array
    {
        $key    = $this->_convertFloatValueToInt($key);
        $result = [];
        foreach ($items as $item) {
            $keyIndex = $this->_getObjectFieldValue($item, $key);
            if ($keyIndex !== null) {
                if (!in_array(gettype($keyIndex), ['string', 'integer'])) {
                    $keyIndex = (string)$keyIndex;
                }
                if (!array_key_exists($keyIndex, $result)) {
                    $result[$keyIndex] = [];
                }
                $result[$keyIndex][] = $item;
            }
        }
        return $result;
    }

    private function _convertFloatValueToInt($value)
    {
        return is_float($value) ? intval($value) : $value;
    }

    private function _getObjectFieldValue($object, $fieldName)
    {
        if ($fieldName === null) {
            return null;
        }
        $fieldName                 = $this->_convertFloatValueToInt($fieldName);
        $isArrayKeyExistAndScalar = is_array($object) && array_key_exists($fieldName, $object) && is_scalar($object[$fieldName]);
        if ($isArrayKeyExistAndScalar) {
            return $object[$fieldName];
        }
        if (!is_object($object)) {
            return null;
        }
        $objectValue = $object->$fieldName ?? null;
        if (is_scalar($objectValue)) {
            return $objectValue;
        }
        $isObjectMethodExistAndScalar = method_exists($object, $fieldName) && is_scalar($object->$fieldName());
        if ($isObjectMethodExistAndScalar) {
            return $object->$fieldName();
        }
        $getMethodName = 'get' . ucfirst($fieldName);
        $isObjectGetMethodExistAndScalar = method_exists($object, $getMethodName) && is_scalar($object->$getMethodName());
        if ($isObjectGetMethodExistAndScalar) {
            return $object->$getMethodName();
        }
        return null;
    }
}
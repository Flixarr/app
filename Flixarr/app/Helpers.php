<?php

function xml2array($xml): array
{
    normalizeSimpleXML(simplexml_load_string($xml), $result);
    return $result;
}

function normalizeSimpleXML($obj, &$result): void
{
    $data = $obj;
    if (is_object($data)) {
        $data = get_object_vars($data);
    }
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $res = null;
            normalizeSimpleXML($value, $res);
            if (($key == '@attributes') && ($key)) {
                $result = $res;
            } else {
                $result[$key] = $res;
            }
        }
    } else {
        $result = $data;
    }
}

<?php

header('Access-Control-Allow-Origin: *');

require_once 'db_connect.php';

function getUrlQuery($url, $key = null)
{
    $parts = parse_url($url);
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
        if (is_null($key)) {
            return $query;
        } elseif (isset($query[$key])) {
            return $query[$key];
        }
    }
    return false;
}

function make_query_string($ps)
{
    $i = 0;
    $query_string = 'SELECT * FROM `cdr` WHERE ';
    foreach ($ps as $key => $p) {
        if ($p != '') {
            if ($i == 0) {
                if ($key == 'date_from') {
                    $query_string .= 'DATE(calldate)' . ' >= "' . $p . '"';
                    $i++;
                } elseif ($key == 'date_to') {
                    $query_string .= 'DATE(calldate)' . ' <= "' . $p . '"';
                    $i++;
                } else {
                    if (intval($p) === $p) {
                        $query_string .= '`' . $key . '` = ' . $p;
                        $i++;
                    } else {
                        if (strpos($p, '_')) {
                            $query_string .= '`' . $key . '` LIKE "' . $p . '"';
                            $i++;
                        } else {
                            $query_string .= '`' . $key . '` = "' . $p . '"';
                            $i++;
                        }
                    }
                }
            } else {
                if ($key == 'date_from') {
                    $query_string .= ' and DATE(calldate)' . ' >= "' . $p . '"';
                    $i++;
                } elseif ($key == 'date_to') {
                    $query_string .= ' and DATE(calldate)' . ' <= "' . $p . '"';
                    $i++;
                } else {
                    if (intval($p) === $p) {
                        $query_string .= ' and `' . $key . '` = ' . $p;
                        $i++;
                    } else {
                        if (strpos($p, '_')) {
                            $query_string .= ' and `' . $key . '` LIKE "' . $p . '"';
                            $i++;
                        } else {
                            $query_string .= ' and `' . $key . '` = "' . $p . '"';
                            $i++;
                        }
                    }
                }
            }
        }
    }
    return $query_string;
}

function make_query($qs)
{
    $sth = $GLOBALS['dbh']->query($qs);
    $array = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

$params = getUrlQuery($_SERVER["REQUEST_URI"]);
$query_string = make_query_string($params);
$result = make_query($query_string);
echo(json_encode($result));

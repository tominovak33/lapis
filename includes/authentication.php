<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 12/02/16
 * Time: 19:09
 */

function login() {
    $username = $_SERVER['HTTP_X_AUTH_USERNAME'];

    if (isset($_SERVER['HTTP_X_AUTH_PASSWORD'])) {
        $password = $_SERVER['HTTP_X_AUTH_PASSWORD'];

        $userID = checkLoginDetails($username, $password);

        if ($userID != false) {
            $token = insertToken($userID, generateAccessToken());
            return array('token' => $token, 'userID' => $userID);
        }

    }
    return false;
}

function checkToken() {
    $username = $_SERVER['HTTP_X_AUTH_USERNAME'];
    $token = $_SERVER['HTTP_X_AUTH_TOKEN'];
    return validateToken($username, $token);
}

function insertToken($username, $token) {
    $expiration = date("Y-m-d H:i:s", time() + 1800);
    // insert token into DB
    // id, username, IP, token, expiration
    $sql = sprintf("INSERT INTO " .DB_API_TOKEN_TABLE. " (username, token, expiration) VALUES ('%s', '%s', '%s')",
        db_escape_string($username),
        db_escape_string($token),
        db_escape_string($expiration)
    );

    database_query($sql);
    return $token;
}

function checkLoginDetails ($username, $password) {
    $password = generateHashedPassword($username, $password);

    $sql = sprintf("SELECT * FROM " .DB_API_USER_TABLE. " WHERE username = '%s' AND password = '%s'",
        db_escape_string($username),
        db_escape_string($password)
    );

    $result = database_query($sql);
    $row = db_fetch_assoc($result);

    if (db_number_of_rows($result) == 1) {
        return $row['id'];
    }
    return false;
}


function generateAccessToken() {
    // https://github.com/bshaffer/oauth2-server-php/blob/master/src/OAuth2/ResponseType/AccessToken.php
    if (function_exists('mcrypt_create_iv')) {
        $randomData = mcrypt_create_iv(20, MCRYPT_DEV_URANDOM);
        if ($randomData !== false && strlen($randomData) === 20) {
            return bin2hex($randomData);
        }
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        $randomData = openssl_random_pseudo_bytes(20);
        if ($randomData !== false && strlen($randomData) === 20) {
            return bin2hex($randomData);
        }
    }
    if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
        $randomData = file_get_contents('/dev/urandom', false, null, 0, 20);
        if ($randomData !== false && strlen($randomData) === 20) {
            return bin2hex($randomData);
        }
    }
    // Last resort which is not very secure
    $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);
    return substr(hash('sha512', $randomData), 0, 40);
}

function validateToken ($username, $token) {
    // check database for a username / token combination with an expiration that is before the current date

    $currentTime = date("Y-m-d H:i:s", time());

    $sql = sprintf("SELECT * FROM " .DB_API_TOKEN_TABLE. " WHERE username = '%s' AND token = '%s' AND expiration > '%s' ",
        db_escape_string($username),
        db_escape_string($token),
        db_escape_string($currentTime)
    );

    $result = database_query($sql);

    if (db_number_of_rows($result) > 0) {
        return $username;
    }

    return false;
}

function getRequestUser () {
    $username = $_SERVER['HTTP_X_AUTH_USERNAME'];

    if (isset($_SERVER['HTTP_X_AUTH_PASSWORD'])) {
        $password = $_SERVER['HTTP_X_AUTH_PASSWORD'];
        if (checkLoginDetails($username, $password)) {
            return $username;
        }
    } elseif (isset($_SERVER['HTTP_X_AUTH_TOKEN'])) {
        $token = $_SERVER['HTTP_X_AUTH_TOKEN'];
        if (validateToken($username, $token)) {
            return $username;
        }
    }

    return false;
}

function generateHashedPassword ($username, $password) {
    $pwdToHash = hash('md5', $username) . $password. PASSWORD_EXTRA_SALT;
    $hashedPassword = hash('sha256', $pwdToHash);

    return $hashedPassword;
}
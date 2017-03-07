<?php
$em = [];


define('ERROR_UNKNOWN', -40000);                        $em[ERROR_UNKNOWN] = 'unknown-error';

define( 'ERROR_ROUTE_NOT_PROVIDED', -40010);            $em[ERROR_ROUTE_NOT_PROVIDED] = 'route-is-not-provided';
define( 'ERROR_ROUTE_NOT_EXIST', -40011);               $em[ERROR_ROUTE_NOT_EXIST] = 'route-does-not-exists';
define('ERROR_MODEL_CLASS_NOT_FOUND', -40040);          $em[ERROR_MODEL_CLASS_NOT_FOUND] = "model-class-not-found";
define('ERROR_NO_RESPONSE', -40041);                    $em[ERROR_NO_RESPONSE] = "no-success-error-response";
define('ERROR_MODEL_CLASS_EMPTY', -40042);              $em[ERROR_MODEL_CLASS_EMPTY] = 'model-class-empty';
define('ERROR_MODEL_CLASS_METHOD_NOT_EXIST', -40043);   $em[ERROR_MODEL_CLASS_METHOD_NOT_EXIST] = 'method-not-exist';



define('ERROR_KEY_EXISTS', -40080);                     $em[ERROR_KEY_EXISTS] = 'key-exists';
define('ERROR_DATABASE_INSERT_FAILED', -40081);         $em[ERROR_DATABASE_INSERT_FAILED] = 'insert-failed';
define('ERROR_DATABASE_QUERY', -40083);                 $em[ERROR_DATABASE_QUERY] = 'database-query-failed';
define('ERROR_EMPTY_SQL_CONDITION', -40084);            $em[ERROR_EMPTY_SQL_CONDITION] = 'error-empty-sql-condition';
define('ERROR_INSCURE_SQL_CONDITION', -40085);          $em[ERROR_INSCURE_SQL_CONDITION] = 'sql-condition-not-secure';
define('ERROR_NO_DATA', -40085);                        $em[ERROR_NO_DATA] = 'no-data-found';


define('ERROR_MALFORMED_RESPONSE', -40090);             $em[ERROR_MALFORMED_RESPONSE] = 'malformed-response.return-data-is-not-array';

define('ERROR_ENTITY_NOT_SET', -40095);                   $em[ERROR_ENTITY_NOT_SET] = 'entity-record-not-set';




define('ERROR_USER_EXIST', -40101);                     $em[ERROR_USER_EXIST] = 'user-exist';
define('ERROR_USER_NOT_EXIST', -40102);                 $em[ERROR_USER_NOT_EXIST] = 'user-not-exist';
define('ERROR_CANNOT_CHANGE_USER_ID', -40103);          $em[ERROR_CANNOT_CHANGE_USER_ID] = 'cannot-change-user-id';
define('ERROR_SESSION_ID_EMPTY', -40104);               $em[ERROR_SESSION_ID_EMPTY] = 'session-id-is-empty';
define('ERROR_WRONG_SESSION_ID', -40105);               $em[ERROR_WRONG_SESSION_ID] = 'wrong-session-id';
define('ERROR_USER_ID_EMPTY', -40106 );                 $em[ERROR_USER_ID_EMPTY] = 'user-id-empty';
define('ERROR_PASSWORD_EMPTY', -40107 );                $em[ERROR_PASSWORD_EMPTY] = 'password-empty';
define('ERROR_USER_NOT_FOUND',-40108 );                 $em[ERROR_USER_NOT_FOUND] = 'user-not-found';
define('ERROR_WRONG_PASSWORD', -40109 );                $em[ERROR_WRONG_PASSWORD] = 'wrong-password';
define('ERROR_USER_NOT_SET', -40100);                   $em[ERROR_USER_NOT_SET] = 'user-not-set-in-user-class-call-reset-method';
define('ERROR_RECORD_NOT_SET', -40122);                 $em[ERROR_RECORD_NOT_SET] = 'record-not-set';
define('ERROR_IDX_EMPTY', -40123);                      $em[ERROR_IDX_EMPTY] = 'idx-empty';
define('ERROR_ID_IS_TOO_LONG', -40124);                 $em[ERROR_ID_IS_TOO_LONG] = 'id-is-too-long';
define('ERROR_MOBILE_NOT_NUMERIC', -40125);             $em[ERROR_MOBILE_NOT_NUMERIC] = 'mobile-not-numeric';
define('ERROR_CANNOT_CHANGE_PASSWORD_IN_UPDATE', -40126);       $em[ERROR_CANNOT_CHANGE_PASSWORD_IN_UPDATE] = 'password-cannot-change-here';
define('ERROR_PASSWORD_TOO_LONG', -40127);                      $em[ERROR_PASSWORD_TOO_LONG] = 'password-too-log';
define('ERROR_MOBILE_TOO_LONG', -40128);                        $em[ERROR_MOBILE_TOO_LONG] = 'mobile-is-too-long';
define('ERROR_USER_IDX_NOT_MATCHED' , -40129);                  $em[ERROR_USER_IDX_NOT_MATCHED] = 'idx-user-not-matched';
define('ERROR_USER_NOT_LOGIN', -4010001);                       $em[ERROR_USER_NOT_LOGIN] = 'user-not-login';




// forum errors. between from -40200 to -40299

define('ERROR_FORUM_CONFIG_EXIST', -40200);                 $em[ERROR_FORUM_CONFIG_EXIST] = 'forum-config-exist';
define('ERROR_FORUM_CONFIG_NOT_EXIST', -40201);             $em[ERROR_FORUM_CONFIG_NOT_EXIST] = 'forum-config-not-exist';
define('ERROR_FORUM_DATA_NOT_EXIST', -40202);               $em[ERROR_FORUM_DATA_NOT_EXIST] = 'forum-data-not-exist';
define('ERROR_FORUM_ID_EMPTY', -40203 );                    $em[ERROR_FORUM_ID_EMPTY] = 'forum-id-is-empty';

define('ERROR_FORUM_DATA_TITLE_EMPTY', -40204);             $em[ERROR_FORUM_DATA_TITLE_EMPTY] = 'forum-data-title-is-empty';
define('ERROR_FORUM_DATA_CONTENT_EMPTY', -40205);           $em[ERROR_FORUM_DATA_CONTENT_EMPTY] = 'forum-data-content-is-empty';
define('ERROR_FORUM_IDX_CONFIG_EMPTY', -40206);				$em[ERROR_FORUM_IDX_CONFIG_EMPTY] = 'ERROR_FORUM_IDX_CONFIG_EMPTY';
define('ERROR_TITLE_TOO_LONG', -40207);                     $em[ERROR_TITLE_TOO_LONG] = 'title-is-too-long';
define('ERROR_FORUM_CONFIG_ID_IS_TOO_LONG', -40208);        $em[ERROR_FORUM_CONFIG_ID_IS_TOO_LONG] = 'forum-config-id-is-too-long';
define('ERROR_FORUM_CONFIG_NAME_IS_TOO_LONG', -40209);      $em[ERROR_FORUM_CONFIG_NAME_IS_TOO_LONG] = 'forum-config-name-is-too-long';
define('ERROR_USER_IDX_EMPTY', -40210);                     $em[ERROR_USER_IDX_EMPTY] = 'user-idx-empty';
define('ERROR_IDX_CONFIG_NOT_NUMBER', -40211);              $em[ERROR_IDX_CONFIG_NOT_NUMBER] = 'config-idx-not-number';
define('ERROR_USER_IDX_NOT_NUMBER', -40212);                $em[ERROR_USER_IDX_NOT_NUMBER] = 'user-idx-not-number';

define('ERROR_FORUM_IDX_EMPTY', -40230);                    $em[ERROR_FORUM_IDX_EMPTY] = 'forum-config/data-idx-empty';

// permis error
define('ERROR_PERMISSION_ADMIN', -40800 );                  $em[ERROR_PERMISSION_ADMIN] = 'admin-permission-required';

define('ERROR_FAKE_ERROR', -50999);                         $em[ERROR_FAKE_ERROR] = 'fake-error';
define('OK', 0);



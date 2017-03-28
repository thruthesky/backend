<?php
$em = [];

define('OK', 0);                                        // success return. It is success and Okay.
define('ERROR', FALSE);                                   // failure return. It was an error and Bad.

define('BACKEND_PRIMARY_PHOTO', 'primary_photo');



define('ERROR_UNKNOWN', -40000);                        $em[ERROR_UNKNOWN] = 'unknown-error';
define('ERROR_TOO_MANY_VARIABLES_IN_ROUTE', -40001);    $em[ERROR_TOO_MANY_VARIABLES_IN_ROUTE] = 'too-many-variables-declared-in-route-variables';
define('ERROR_ROUTE_NOT_PROVIDED', -40010);            $em[ERROR_ROUTE_NOT_PROVIDED] = 'route-is-not-provided';
define('ERROR_ROUTE_NOT_EXIST', -40011);               $em[ERROR_ROUTE_NOT_EXIST] = 'route-does-not-exists';
define('ERROR_MODEL_CLASS_NOT_FOUND', -40040);          $em[ERROR_MODEL_CLASS_NOT_FOUND] = "model-class-not-found";
define('ERROR_NO_RESPONSE', -40041);                    $em[ERROR_NO_RESPONSE] = "no-success-error-response";
define('ERROR_EMPTY_RESPONSE', ERROR_NO_RESPONSE);


define('ERROR_MODEL_CLASS_EMPTY', -40042);              $em[ERROR_MODEL_CLASS_EMPTY] = 'model-class-empty';
define('ERROR_MODEL_CLASS_METHOD_NOT_EXIST', -40043);   $em[ERROR_MODEL_CLASS_METHOD_NOT_EXIST] = 'model-class-method-not-exist';
define('ERROR_REQUIRED_INPUT_IS_MISSING', -40044);      $em[ERROR_REQUIRED_INPUT_IS_MISSING] = "required-input-is-missing";
define('ERROR_INVALID_INPUT_VARIABLE', -40045);         $em[ERROR_INVALID_INPUT_VARIABLE] = "invalid-input-variable";
define('ERROR_MALFORMED_VARIABLE_NUMBER', -40045);      $em[ERROR_MALFORMED_VARIABLE_NUMBER] = "malformed-variable-number";
define('ERROR_VARIABLE_ARRAY', -40046);                         $em[ERROR_VARIABLE_ARRAY] = "variable-is-array";
define('ERROR_VARIABLE_NUMERIC', -40047);                       $em[ERROR_VARIABLE_NUMERIC] = 'variable-is-numeric';
define('ERROR_VARIABLE_STRING', -40048);                       $em[ERROR_VARIABLE_STRING] = 'variable-is-string';
define('ERROR_VARIABLE_EMPTY', -40049);                       $em[ERROR_VARIABLE_EMPTY] = 'variable-is-empty';

define('ERROR_JSON_PARSE', -40050);                    $em[ERROR_JSON_PARSE] = "json-parse-error-maybe-PHP-script-error-warning";

define('ERROR_ALREADY_DELETED', -40051);                $em[ERROR_ALREADY_DELETED] = 'already-deleted';
define('ERROR_ALREADY_INSTALLED', -40052);              $em[ERROR_ALREADY_INSTALLED] = 'already-installed';
define('ERROR_KEY_EXISTS', -40080);                     $em[ERROR_KEY_EXISTS] = 'key-exists';
define('ERROR_DATABASE_INSERT_FAILED', -40081);         $em[ERROR_DATABASE_INSERT_FAILED] = 'database-insert-failed';
define('ERROR_DATABASE_UPDATE_FAILED', -40082);         $em[ERROR_DATABASE_UPDATE_FAILED] = 'database-update-failed';
define('ERROR_DATABASE_QUERY', -40083);                 $em[ERROR_DATABASE_QUERY] = 'database-query-failed';
define('ERROR_EMPTY_SQL_CONDITION', -40084);            $em[ERROR_EMPTY_SQL_CONDITION] = 'error-empty-sql-condition';
define('ERROR_INSCURE_SQL_CONDITION', -40085);          $em[ERROR_INSCURE_SQL_CONDITION] = 'sql-condition-not-secure';
define('ERROR_NO_DATA', -40086);                        $em[ERROR_NO_DATA] = 'no-data-found';
define('ERROR_DATABASE_DELETE_FAILED', -40088);         $em[ERROR_DATABASE_DELETE_FAILED] = 'database-delete-failed';

define('ERROR_UNSECURE_STATEMENT_CONDITION', -40089);    $em[ERROR_UNSECURE_STATEMENT_CONDITION] = 'insecure-statement-condition';
define('ERROR_SEARCH_BIND_LACK', -40092);        $em[ERROR_SEARCH_BIND_LACK] = 'bind-lack-on-taxonomy-search';

define('ERROR_MALFORMED_RESPONSE', -40093);             $em[ERROR_MALFORMED_RESPONSE] = 'malformed-response.return-data-is-not-array';

define('ERROR_ENTITY_NOT_SET', -40094);                   $em[ERROR_ENTITY_NOT_SET] = 'entity-record-not-set';
define('ERROR_FROM_IS_NOT_NUMERIC', -40095);            $em[ERROR_FROM_IS_NOT_NUMERIC] = 'from-is-not-number';
define('ERROR_LIMIT_IS_NOT_NUMERIC', -40096);           $em[ERROR_LIMIT_IS_NOT_NUMERIC] = 'limit-is-not-number';

define('ERROR_DATABASE_ROWS_QUERY_ERROR', -40097);      $em[ERROR_DATABASE_ROWS_QUERY_ERROR] = 'database-rows-query-failed';
define('ERROR_TABLE_NOT_SET', -40098);                  $em[ERROR_TABLE_NOT_SET] = 'database-table-is-not-set-to-query'; // entity table is empty.
define('ERROR_MISSING_BINDING_MARK', -40099);           $em[ERROR_MISSING_BINDING_MARK] = 'database-where-has-no-question-mark'; // to query to database, developer needs to bind where clause with question mark. But there is no question marks on the query.
define('ERROR_SQL_BIND_NOT_SET', -46001);               $em[ERROR_SQL_BIND_NOT_SET] = 'bind-not-set-in-taxonomy-search';

define('ERROR_SEARCH_MARK_LACK', -46002);       $em[ERROR_SEARCH_MARK_LACK] = 'search-mark-lack-on-taxonomy-search';
define('ERROR_DATABASE_UNIQUE_KEY', -46003);            $em[ERROR_DATABASE_UNIQUE_KEY] = 'unique-key-exists';
define('ERROR_USER_EXIST', -40101);                     $em[ERROR_USER_EXIST] = 'user-exist';
define('ERROR_USER_NOT_EXIST', -40102);                 $em[ERROR_USER_NOT_EXIST] = 'user-not-exist';
define('ERROR_CANNOT_CHANGE_USER_ID', -40103);          $em[ERROR_CANNOT_CHANGE_USER_ID] = 'cannot-change-user-id';
define('ERROR_SESSION_ID_EMPTY', -40104);               $em[ERROR_SESSION_ID_EMPTY] = 'session-id-is-empty';
define('ERROR_WRONG_SESSION_ID', -40105);               $em[ERROR_WRONG_SESSION_ID] = 'wrong-session-id--session-id-has-been-invalidated--login-again';
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
define('ERROR_PASSWORD_TOO_LONG', -40127);                      $em[ERROR_PASSWORD_TOO_LONG] = 'password-too-long';
define('ERROR_MOBILE_TOO_LONG', -40128);                        $em[ERROR_MOBILE_TOO_LONG] = 'mobile-is-too-long';
define('ERROR_USER_IDX_NOT_MATCHED' , -40129);                  $em[ERROR_USER_IDX_NOT_MATCHED] = 'idx-user-not-matched';
define('ERROR_USER_NOT_LOGIN', -4010001);                       $em[ERROR_USER_NOT_LOGIN] = 'user-not-login';
define('ERROR_USER_LOGGED_IN', -4010002);               $em[ERROR_USER_LOGGED_IN] = 'user-already-logged-in';
define('ERROR_MALFORMED_SESSION_ID', -400130);          $em[ERROR_MALFORMED_SESSION_ID] = 'session-id-is-malformed';
define('ERROR_MALFORMED_ID', -400131);          $em[ERROR_MALFORMED_ID] = 'id-is-malformed';

define('ERROR_USER_RESIGN_FAILED', -40150);             $em[ERROR_USER_RESIGN_FAILED] = 'user-resign-failed';
define('ERROR_NOT_YOUR_POST_DATA', -40152);             $em[ERROR_NOT_YOUR_POST_DATA] = 'this-is-not-your-post-data';
define('ERROR_YOU_ARE_NOT_ANONYMOUS', -40160);          $em[ERROR_YOU_ARE_NOT_ANONYMOUS] = 'you-are-not-anonymous';
define('ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS', -40164);   $em[ERROR_POST_OWNED_BY_USER_NOT_ANONYMOUS] = 'post-owned-by-user-but-you-are-anonymous';
define('ERROR_ANONYMOUS_CAN_NOT_EDIT_PROFILE', -40168); $em[ERROR_ANONYMOUS_CAN_NOT_EDIT_PROFILE] = 'anonymous-can-not-edit-profile';
define('ERROR_ID_EMPTY', -40170);                           $em[ERROR_ID_EMPTY] = 'id-is-empty';
// post errors. between from -40200 to -40299

define('ERROR_POST_CONFIG_EXIST', -40200);                 $em[ERROR_POST_CONFIG_EXIST] = 'post-config-exist';
define('ERROR_POST_CONFIG_NOT_EXIST', -40201);             $em[ERROR_POST_CONFIG_NOT_EXIST] = 'post-config-not-exist';
define('ERROR_POST_NOT_EXIST', -40202);               $em[ERROR_POST_NOT_EXIST] = 'post-not-exist';
define('ERROR_POST_ID_EMPTY', -40203 );                    $em[ERROR_POST_ID_EMPTY] = 'post-id-is-empty';
define('ERROR_POST_DATA_NOT_EXIST', -40244);                $em[ERROR_POST_DATA_NOT_EXIST] = 'post-not-exist';

define('ERROR_POST_DATA_TITLE_EMPTY', -40204);             $em[ERROR_POST_DATA_TITLE_EMPTY] = 'post-data-title-is-empty';
define('ERROR_POST_DATA_CONTENT_EMPTY', -40205);           $em[ERROR_POST_DATA_CONTENT_EMPTY] = 'post-data-content-is-empty';
define('ERROR_POST_CONFIG_IDX_EMPTY', -40206);				$em[ERROR_POST_CONFIG_IDX_EMPTY] = 'post-config-idx-is-empty';
define('ERROR_TITLE_TOO_LONG', -40207);                     $em[ERROR_TITLE_TOO_LONG] = 'title-is-too-long';
define('ERROR_POST_CONFIG_ID_IS_TOO_LONG', -40208);        $em[ERROR_POST_CONFIG_ID_IS_TOO_LONG] = 'post-config-id-is-too-long';
define('ERROR_POST_CONFIG_NAME_IS_TOO_LONG', -40209);      $em[ERROR_POST_CONFIG_NAME_IS_TOO_LONG] = 'post-config-name-is-too-long';
define('ERROR_USER_IDX_EMPTY', -40210);                     $em[ERROR_USER_IDX_EMPTY] = 'user-idx-empty';
define('ERROR_POST_CONFIG_IDX_NOT_NUMBER', -40211);              $em[ERROR_POST_CONFIG_IDX_NOT_NUMBER] = 'post-config-idx-not-number';
define('ERROR_USER_IDX_NOT_NUMBER', -40212);                $em[ERROR_USER_IDX_NOT_NUMBER] = 'user-idx-not-number';

define('ERROR_POST_IDX_EMPTY', -40230);                    $em[ERROR_POST_IDX_EMPTY] = 'post-config/data-idx-empty';
define('ERROR_POST_CONFIG_EDIT_FAILED', -40231);                    $em[ERROR_POST_CONFIG_EDIT_FAILED] = 'post-config-edit-failed';
define('ERROR_FORUM_NOT_EXIST', -40232);                    $em[ERROR_FORUM_NOT_EXIST] = "forum-not-exist";


// permis error
define('ERROR_PERMISSION_ADMIN', -40800 );                  $em[ERROR_PERMISSION_ADMIN] = 'admin-permission-required';


// meta errors

define("ERROR_META_MULTI_CREATE_FAILED", -4091);            $em[ERROR_META_MULTI_CREATE_FAILED] = "meta-multi-creation-failed";
define("ERROR_MODEL_IS_EMPTY", -4090);                     $em[ERROR_MODEL_IS_EMPTY] = "model-is-empty";
define("ERROR_MODEL_IDX_IS_EMPTY", -4090);                     $em[ERROR_MODEL_IDX_IS_EMPTY] = "model-is-empty";
define("ERROR_CODE_IS_EMPTY", -4090);                     $em[ERROR_CODE_IS_EMPTY] = "model-is-empty";


define('ERROR_FAKE_ERROR', -50999);                         $em[ERROR_FAKE_ERROR] = 'fake-error';


// comment
define('ERROR_COMMENT_NOT_EXIST', -4850);                   $em[ERROR_COMMENT_NOT_EXIST] = 'comment-not-exist';

// category
define('ERROR_CATEGORY_NOT_EXIST', -4900);                    $em[ERROR_CATEGORY_NOT_EXIST] = "category-not-exist";
define('ERROR_CATEGORY_CHILDREN_EXIST', -4901);                 $em[ERROR_CATEGORY_CHILDREN_EXIST] = 'category-children-exists';


// file upload error

define("ERROR_MOVE_UPLOADED_FILE", -4220 );                 $em[ERROR_MOVE_UPLOADED_FILE] = "file-upload-error-move-uploaded-file";
define("ERROR_USERFILE_EMPTY",-4222 );                      $em[ERROR_USERFILE_EMPTY] = "file-upload-error-userfile-is-empty";

define('ERROR_UPLOAD_FILE_NOT_EXIST', -43101);               $em[ERROR_UPLOAD_FILE_NOT_EXIST] = 'error-upload-file-not-exist';
define('ERROR_UPLOAD_FILE_EXIST', -43102);               $em[ERROR_UPLOAD_FILE_EXIST] = 'error-upload-file-already-exist';
define('ERROR_UPLOAD_ERROR_NOT_SET', -43103);               $em[ERROR_UPLOAD_ERROR_NOT_SET] = 'file-upload-error-must-be-set';
define('ERROR_FILE_UPLOAD_CREATE_IDX_FAILED', -43104);      $em[ERROR_FILE_UPLOAD_CREATE_IDX_FAILED] = 'file-upload-create-file-record-failed';
define('ERROR_FILE_NOT_EXIST', -43105);                     $em[ERROR_FILE_NOT_EXIST] = 'file-not-exist';


define('ERROR_HOOK_FILE_IDX_IS_NOT_IN_ARRAY', -43106);      $em[ERROR_HOOK_FILE_IDX_IS_NOT_IN_ARRAY] = 'file-idx-must-be-in-array';
define('ERROR_HOOK_FILE_IDX_IS_WRONG', -43108);             $em[ERROR_HOOK_FILE_IDX_IS_WRONG] = 'wrong-file-idx--maybe-empty--maybe-not-exist--may-be-wrong-input-value-like-undefined';
define('ERROR_HOOK_NOT_YOUR_FILE', -43110);                 $em[ERROR_HOOK_NOT_YOUR_FILE] = 'uploaded-file-is-not-yours';

define('ERROR_UPLOAD_ERR_INI_SIZE', -43201);
define('ERROR_UPLOAD_ERR_FORM_SIZE', -43202);
define('ERROR_UPLOAD_ERR_PARTIAL', -43203);
define('ERROR_UPLOAD_ERR_NO_FILE', -43204);
define('ERROR_UPLOAD_ERR_NO_TMP_DIR', -43206);
define('ERROR_UPLOAD_ERR_CANT_WRITE', -43207);
define('ERROR_UPLOAD_ERR_EXTENSION', -43208);

# Backend-0.8

Backend Server for Restful APIs


# Changes

* post_data.create/edit interface response with the full post data.
* post_comment create/edit interface response with the full comment data.
* post_data.data interface accepts `extra` property.
* when user logs in, if the user is admin, "admin=1" will be set in response.
* when user related information is responded, at least it has empty array if it has no photos.
* When user has no primary photo, the responded empty value is Array.
* When user has primary photo, the responded value is Object. NOT Array.
* 'created' URL parameter is added on File URL for cache. Since a user deletes a file and upload a new file, then, it has same file.idx so, the browser cached image will be showed again to user..
* default 'no. of items' of a page(list/search page) is '$DEFAULT_NO_OF_PAGE_ITEMS' which is in etc/config.
* search/list api call now accepts 'page' and works as exptected.
* when admin edits user information, session_id will not be returned.
    * so, the session_id will be invalidated after 'admin edit'
    * but, user edits user info, then session_id will be returned.
    * so, admin edits admin information(himself) and no session_id will be returned, meaning, admin has to login again.
    
* you cannot update post_config_id. ( consider to make it updatable ).

* @fix: returns-post_config-of-requested-post-config-id-if-no-posts. Apr 11, 2017. When there is no post ( no posts are created. newly created forum ), then the no post-config information is responded. so, if there is no posts, the requested post config id's 'post config' will be responded by default.

* 'first_image_idx' is added on 'post_data' table. it holds the `file.idx' of first image.

* If there is an error while DATABASE query, it prints out error response and exits the script immediately. ( since it is not is to deliver DB error message that is in the bottom part way back up to the top part callers ).

* 'user_idx' is added on meta table.
    so, a meta record is now able to refer to whom it belongs to.
    It is only used for "meta.list" ( search ) and "meta.delete".

* 'link' is added on post_data table to hold link information.
* no 'meta.data' interface? omitted by purposely? because there is no 'meta.update', no 'meta.data'? since meta is not for individual manipulation?

* button options : allow like/dislike,


* for 'file.delete', if the file was uploaded by anonymous, then the anonymous must input password of the post.
* for 'file.upload', anonymous can upload without a password (whether the anonymous is on edit page or not), but the anonymous cannot attach the file to the post since he does not know the password( he cannot submit the edit form because he does not know the password ).
* for, post/comment edit form, the anonymous can upload but cannot delete until he submit the edit form first because the uploaded file is not yet attached to the post/comment.
    * Anyway, After all, it's safe and and no one will do those thing.
    

* file can be downloaded with `file.user_idx` and `file.code`

    ex) http://backend.org/?route=download&user_idx=1&code=primary_photo

 * admin can edit a post of anonymous even he input wrong password.
 
 * Backend can now be run on CLI.
 
    ex) $ php index.php "route=version"

  
* You can add admin IDs.

in config.php file, $ADMIN_ID is the primary admin id. and you can have many admin ID(s) on $ADMIN_IDS. They will have same privileges.

````
  $ADMIN_ID               = 'usera1';          // This is an admin id.
  $ADMIN_IDS              = ['usera2', 'usera3', '', ''];             // Array. Put other admin ids.
````


* Admin now can change user password with 'admin_change_user_password' route.


 
# Bugs

* On macOS Sierra 10.12.3 with SQLite,
    when a user upload primary photo and delete immediately, the file is deleted but after 0.3 seconds it becomes alive with Zero Bytes.
    And it produces an server error since SQLite will use the same file.idx for next photo upload.
    ( delete immediately means, right after, ... like 0.1 second after upload )
    If you give some time delay like 3 seconds, there is no error.



# Resources

* @see tests to understand Backend.


# Work Environment

## Work environment of thruthesky

* http://localhost/www/backend/ for backend restful api access.
* http://backend.org/ is same folder of http://localhost/www/backend
* http://backend-seo.org/ for seo test. ( in this cone, Backend has many of client app files, so it looks a bit dirty )




#Errors
* -in lms class id required
    [{"code":-106,"message":"Please, input Class ID."}]
* -in lms domain should not be empty
    [But in to do list for witheng and onfis the domain is empty]
* -in lms Nickname is concatinated with domain 
    [1062 : Duplicate entry 'Test Boy@witheng.onlineenglish.kr' for key 'mb_nick']
* -error Register/login password must not be numeric

# TODO

* in reservation handle also if there is many reservations on a single day


* make error on class reservation by month and show error message nicely.
* do all error handling.



# Domain and ID matching



if domain in address bar is


igoodtalk.com       then, the domain should be "igoodtalk.onlineenglish.kr"
iamtalkative.com    then, domain is '' "talkative.onlineenglish.kr"

witheng.com         then, domain is ''
onfis.com           then, domain is ''


If domain is not one of above, then, translate

"ID@" plus "first part of the domain" plus ".onlineenglish.kr"

if the first part is 'www', then apply the second part.

for instance, if domain is "www.abc.co.kr", then the translated would be "ID@abc.onlineenglish.kr"


Sample ID: mgonkim of "www.iamtalkative.com"
Sample ID: italk2 of "www.iamtalkative.com"

test id: testboy pw:0000
testboy@talkative.onlineenglish.kr




# Installation


git submodule update --imit
npm install 
npm install @ng-bootstrap/ng-bootstrap





# Design

* We will not consider the design(look) between 420px and 700px.






# 기능

소셜 로그인: 네이버, 카카오, 페이스북 로그인 기능

접속자 마케팅 : 실시간 채팅. 방문자가 있을 때, 알림.

회원 관리, 게시판, 수업 관리



# Chat functionality.

* There are many chats on chat box.
* When users chat, chat messages are display on global space.
* User may have name if they have logged in.
* If admin wants to talk to user, he clicks on user name ( or chat message of the user. )
    * 1:1 chat box will be opened. only the chat messages of the user will be displayed.
    * admin and user begin to chat.
    * if they don't chat for 1 minutes, then the 1:1 chat box will be closed and show global chat box.
    * while they are chatting, if another user chat to admin, an alert message will be dispaly on the current 1:1 chat box.

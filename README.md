# English



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
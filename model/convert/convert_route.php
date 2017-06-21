<?php


route()->add('convert.talkative', [
    'path' => "\\model\\convert\\convert",
    'method' => 'run'
]);


route()->add('convert.talkative.member', [
    'path' => "\\model\\convert\\convert",
    'method' => 'convertTalktativeMember'
]);

route()->add('convert.talkative.qna', [
    'path' => "\\model\\convert\\convert",
    'method' => 'convertTalkativeQna'
]);

route()->add('convert.talkative.review', [
    'path' => "\\model\\convert\\convert",
    'method' => 'convertTalkativeReview'
]);
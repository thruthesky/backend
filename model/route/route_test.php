<?php
namespace model\route;
class Route_Test extends \model\test\Test {

    public function __construct()
    {
        parent::__construct();
    }

    public function run() {

        $this->testId();
    }

    public function testId() {


        $params = [
            'id' => '123',
            'password' => 'abc'
        ];
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_VARIABLE_NUMERIC, "ID cannot be a number. " . get_error_string($re));



        $params['id'] = 'ab+c'; // special char
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = '^^my-id'; // special char
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = ' thruthesky@gmail.com'; // space front
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = 'abc def'; // space in middle
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = 'okay '; // space at end.
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = ',,,,'; // special char
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = 'ab'; // two letter
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = 'abcD-012345678901234567890123456789012345678901234567890123456789'; // more than 64 letters ( it's 65 letters )
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));


        $params['id'] = '한글'; // special chars
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));

        $params['id'] = 'name~abc'; // special chars
        $re = $this->route('register', $params);
        test( is_error($re) == ERROR_MALFORMED_ID, "ID can be: not number. between 3 and 64 letters, No space. Can contain a~z A~Z 0~9 - @ _ . " . get_error_string($re));


        // ok IDs
        $params['id'] = 'abc---';
        $re = $this->route('register', $params);
        test( is_success($re) || is_error($re) != ERROR_MALFORMED_ID, "ID: $params[id] " . get_error_string($re));

        $params['id'] = 'abc._-@def';
        $re = $this->route('register', $params);
        test( is_success($re) || is_error($re) != ERROR_MALFORMED_ID, "ID: $params[id] " . get_error_string($re));


        $params['id'] = '._-@azAZ09';
        $re = $this->route('register', $params);
        test( is_success($re) || is_error($re) != ERROR_MALFORMED_ID, "ID: $params[id] " . get_error_string($re));


    }
}




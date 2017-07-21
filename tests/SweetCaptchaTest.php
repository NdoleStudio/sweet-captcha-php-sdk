<?php 

class SweetCaptchaTest extends PHPUnit_Framework_TestCase{

    /**
     * @var string
     */
    private $appId = 'appId';

    /**
     * @var string
     */
    private $key = 'key';

    /**
     * @var string
     */
    private $secret = 'secret';

    /**
     * @var string
     */
    private $path = 'path';

    /**
    * Just check if the SweetCaptcha has no syntax error
    *
    * This is just a simple check to make sure SweetCaptcha has no syntax error. This helps to troubleshoot
    * any typo before this library is used in a real project.
    *
    */
    public function testIsThereAnySyntaxError(){
        $SUT = new SweetCaptcha\SweetCaptcha(
            $this->appId,
            $this->key,
            $this->secret,
            $this->path
        );

        $this->assertTrue(is_object($SUT));
    }
  
}
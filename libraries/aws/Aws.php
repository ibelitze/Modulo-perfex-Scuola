<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require FCPATH.'modules/api_aws/third_party/AWS/aws-autoloader.php';
class Aws  {
    function getAwsCred(){
        $cred = new Aws\Credentials\Credentials('', '');

        $credentials = [
			'version'     => 'latest',
			'region'      => 'us-east-2',
			'credentials' => $cred
    	];
        return $credentials;
    }
}
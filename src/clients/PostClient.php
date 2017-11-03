<?php

namespace Platron\Connectum\clients;

use Platron\Connectum\SdkException;
use Platron\Connectum\services\BasePostRequest;
use Platron\Connectum\services\BaseRequest;

class PostClient extends BaseClient {
    
    /**
     * @param BasePostRequest $service
     */
    public function sendRequest(BaseRequest $service) {
        if(!$service instanceof BasePostRequest){
            throw new SdkException('Service wait post service');
        }
        
        $requestParameters = $service->getParameters();
        $requestUrl = $this->baseUrl.$service->getRequestUrl();
        
        $curl = curl_init($requestUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($curl, CURLOPT_SSLCERT, $this->certificatePath);
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->certificatePassword);
        
        if(!empty($requestParameters)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestParameters));
        }
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
        
        if($this->logger){
            $this->logger->log(self::LOG_LEVEL, 'Requested url '.$requestUrl.' params '. json_encode($requestParameters));
            $this->logger->log(self::LOG_LEVEL, 'Response '.$response);
        }
		
		if(curl_errno($curl)){
			throw new SdkException(curl_error($curl), curl_errno($curl));
		}

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode !== self::OK_HTTP_CODE && $httpCode !== self::REDIRECT_HTTP_CODE) {
            throw new SdkException('Service error. Wrong http code '.$httpCode);
        }
        
        $decodedResponse = json_decode($response);
        if(empty($decodedResponse)){
            throw new SdkException('Service error. Empty response or not json response');
        }
		
		return $decodedResponse;
    }
}

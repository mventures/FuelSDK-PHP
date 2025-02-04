<?php
namespace FuelSdk;
/**
 * This class represents the contructor for all web service (SOAP/REST) operation and holds HTTP status code, response, result, etc.
 */
class ET_Constructor
{
    /**
     * @var 	bool         	holds the status of the web service operation: true=success, false=failure
    */
	public $status;
    /**
     * @var 	int 			holds the HTTP status code e.g. 200, 404, etc
    */	
	public $code;
    /**
     * @var 	string 			holds error message for SOAP call, else holds raw response if json_decode fails
    */	
	public $message;
    /**
     * @var stdClass Object		contains the complete result of the web service operation
    */	
	public $results;
    /**
     * @var string        		the request identifier
    */	
	public $request_id;
    /**
     * @var bool 			    whether more results are available or not   
    */	
	public $moreResults;	
	
	/** 
	* Initializes a new instance of the class.
	* @param 	string 		$requestresponse 	The response from the request
	* @param 	int 		$httpcode 			The HTTP status code e.g. 200, 404. etc
	* @param 	bool 		$restcall 			Whether to make REST or SOAP call, default is false i.e. SOAP calls
	*/
	function __construct($requestresponse, $httpcode, $restcall = false)
	{
		
		$this->code = $httpcode;
		
		if (!$restcall) {
			if(is_soap_fault($requestresponse)) {
				$this->status = false;
				$this->message = "SOAP Fault: (faultcode: {$requestresponse->faultcode}, faultstring: {$requestresponse->faultstring})";
				$this->message = "{$requestresponse->faultcode} {$requestresponse->faultstring})";
			} else {
				$this->status = true;
			}
		} else {
			if ($this->code != 200 && $this->code != 201 && $this->code != 202) {
				$this->status = false;
			} else {
				$this->status = true;
			}

			if (json_decode($requestresponse) != null){
				$this->results = json_decode($requestresponse);
			} else  {
				$this->message = $requestresponse;
			}
		}
	}
}
?>
<?php

class SanitizeTransaction {
	
	public function __construct(){
			session_start();
	}

	/**
	 * @method	: Unset variables processament [$_?] 
	 * @param 	: Booblean
	 * @throws 	: Call empty - Exception
	 * @return 	: String
	 */	
	public function UnsetVariablesPredefined($action = null){
		try {
			if($action == true){
				unset($_SESSION	);
				unset($_REQUEST	);
				unset($_SERVER	);
				unset($_COOKIE	);
				unset($_FILES	);
				unset($_POST	);
				unset($_GET		);
				unset($_ENV		);
				return $return = "sanitized";
			}
			else{
				throw new Exception("Fail to disable the variables");
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	
	/**
	 * @method	: Clear Session and destroy
	 * @param 	: Booblean
	 * @throws 	: Call empty - Exception
	 * @return 	: String
	 */
	public function CleanSession($action){
		try {
			if ($action == true) {
				session_destroy();				
				return "sanitized";
			}else{
				throw new Exception("Fail to disable the session");
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * @method	: Clear Memory
	 * @param 	: Booblean
	 * @throws 	: Call empty - Exception
	 * @return 	: String
	 */	
	public function CleanMemory($activate){
		try{ 
 			if($this->GC_Disable($activate) == 'off'){
				$return = 'sanitized';
			}
			else{
				throw new Exception("Fail to clear the memory");
			}
		} catch (Exception $e) {
			$return = $e->getMessage();
		}
		return $return;
	}
	
	/**
	 * @method	: Enabled GC
	 * @param 	: Boolean
	 * @throws 	: Call empty - Exception
	 * @return 	: Boolean
	 */		
	public function GC_Enable($activate){
		if($activate == true){
			# Enable Garbage Collector
			gc_enable();
			return true;  
		}
		else{
			return false;
		}
	}

	/**
	 * @method	: Disabled GC
	 * @param 	: Boolean
	 * @throws 	: Call empty - Exception
	 * @return 	: Boolean
	 */	
	public function GC_Disable($deactivate){
		if($deactivate == true){
			# Case is true
			if(gc_enabled()){
				# Of elements cleaned up
				gc_collect_cycles();
				# Disable Garbage Collector
				gc_disable();
			}
			return true;  
		}
		else{
			return false;  
		}
	}
}

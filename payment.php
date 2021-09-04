<?php
require_once("bin/conekta-php-master/lib/Conekta.php");
class Payment{
	
	private $ApiKey="key_tr9Ky52r5D6AqZhaTWZ9XQ";
	private $ApiVersion="2.0.0";


	public function__construct($token,$card,$name,$description,$total,$email){
    	
    	this->token=$token;
    	this->card=$card;
    	this->name=$name;
    	this->description=$description;
    	this->total=$total;
    	this->email=$email;



	}

	public function Pay(){

		\Conekta\Conekta::setApiKey($this->ApiKey);
		\Conekta\Conekta::setApiVersion($this->ApiVersion);

		if(!this->validate())
			return false;

		if(!this->CreateCustomer())
			return false;

    if(!this->CreateOrder())
      return false;

		return true;


	}
    
    
      
      public function CreateOrder(){
      try {
         $this->order = \Conekta\Order::Create(
          $array(
            "amount"=>$this->total
            "line_items" => array(
             array(
             "name" => $this->description
             "unit_price" => $this->total
             "quantity" => 1
           )
          ),
          "currency" => "MXN",
          "customer_info" => array(
          "customer_id" => $this->customer->id
          "charges" -> array(
            array(
              "payment_method" => array(
                  "type" => "default"
              )
            )
          )
        )
      );
    } catch (\Conekta\ProcessingError $error){ 
      $this->error=$error->getMessage();
      return false;
    } catch (\Conekta\ParameterValidationError $error){
      $this->error=$error->getMessage();
      return false;
    } catch (\Conekta\HandlerError $error){
      $this->error=$error->getMessage();
      return false;
    }

    return true;
  }
  
   public function CreateCustomer(){
      try {
          $this->customer = \Conekta\Customer::Create(
            $array(
              "name" -> $this->name,
              "email" -> $this->email,
              "payment sources" -> array(
                array(
                  "type" -> "card",
                  "token_id" -> $this->token

              )
            )
          )
         );

         } catch (\Conekta\ProcessingError $error){
          $this->error=$error->getMessage();
          return false;


         } catch (\Conekta\ParameterValidationError $error){
          $this->error=$error->getMessage();
          return false;

         } catch (\Conekta\Handler $error){
          $this->error=$error->getMessage();
          return false;
          
         }  

         return true;

      }
    


    

    
    public function Validate(){
    	if($this->card=="" || $this->name=="" || $this->description=="" || $this->total=="" || $this->email==""
    		$this->error="El número de tarjeta, nombre, concepto, y correo electrónico son obligatorios";
    		return false;

        }

        if(strlen($this->card)<=14){
        	$this->error="El número de tarjeta, debe tener al menos 15 caracteres";
    		return false;
        }
         
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
        	$this->error="El correo electrónico, no tiene un formato válido";
    		return false;
    	}

    	if($this->total<=20){
    		$this->error="El monto debe ser mayor a 20 pesos";
    		return false;

    	}

    	return false;


    }

}

?>

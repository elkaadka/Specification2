![build](https://travis-ci.org/elkaadka/Specification2.svg?branch=master)

# Specification design pattern

This is my implementation of  the "Specification" design pattern.
It differs from the original one since i find it simpler and more straight forward.

It also allows the composition of multiple Specifications that takes in different types of parameters 

The Goal of this pattern is to have separate classes of logic in order to do
condition composition without rewriting and maintaining the conditions at different places.

Here is a detailed "how to" for thi package :
 
## Creating a Specification 

Create a class that implements the SpecificationInterface and that holds your logic :
     
 Here is basic example of a list of specifications that checks differents things about products and customers
     
 A class the checks if a product is available :
     
```php
    class IsProductAvaialble implement SpecificationInterface
    {
        protected $product;
        protected $neededQuantity;
        
        public function __construc(Product $product, $neededQuantity = 1)
        {
            $this->product = $product;
            $this->neededQuantity = $neededQuantity;
        }
        
        public function isValid(): bool
        {
            //here your code logic that uses the data set in the construct and returns a boolean
        }
    }
```
     
A class the checks if a product can be shipped to the customer :
     
```php
    class IsProductShipableForCustomer implement SpecificationInterface
    {
        protected $product;
        protected $customer;
        
        public function __construc(Product $product, Customer $customer)
        {
            $this->product = $product;
            $this->customer = $customer;
        }
        
        public function isValid(): bool
        {
            //doing the logic to check if the product is avaialble in the customer's country 
        }
    }
```
        
A class the checks if a product is special (a special product is supposed to be (magically?) always in stock)  

```php
    class IsASpecialProduct implement SpecificationInterface
    {
        protected $product;
        
        public function __construc(Product $product)
        {
            $this->product = $product;
        }
        
        public function isValid(): bool
        {
            //doing the logic to check if the product is special 
        }
    }
```
    
A class that checks if a customer is not blacklisted  
    
```php
    class IsCustomerBlackListed implement SpecificationInterface
    {
        protected $customer;
        
        public function __construc(Customer $customer)
        {
            $this->customer = $customer;
        }
        
        public function isValid(): bool
        {
            //doing the logic to checks if the customer is banned
        }
    }
```
     
## All the available operators :

### 2.1 - isSatisfiedBy</b>
   
One of the tree entry points of the specification pattern.<br>
It Has to be used before any operator.<br />
<b>If used after an operator it will throw a WrongUsageException</b>
        
Example : 
        
```php
    $specification = (new Specification())->isSatisfiedBy(new IsProductAvaialble($product));
     
     if ($specification->isValid()) {
        ...
     }
```
    
### 2.2 - isSatisfiedByAll
        
the second possible entry point of the specification pattern.<br>
Checks if all the Specifications sent are Valid ones (equivalent to chaining and operators)<br />
It Has to be used before any operator.<br/>
<b>If used after an operator it will throw a WrongUsageException</b>   
          
Example : 
```php
    $specification = (new Specification())->isSatisfiedByAll(
        new IsProductAvaialble($product), 
        new IsProductShipableForCustome($product, $customer)
    );
    
    if ($specification->isValid()) {
        ...
    }
```

### 2.3 - isSatisfiedByAny
              
the third and last possible starting point of the specification pattern.<br>
Checks if one of the Specifications sent is a Valid one (equivalent to chaining or operators)<br />
It Has to be used before any operator.<br/>
<b>If used after an operator it will throw a WrongUsageException</b>   
                
Example : 
```php       
    $specification = (new Specification())->isSatisfiedByAny(
        new IsProductAvaialble($product), 
        new IsASpecialProduct($product)
    );
    
    if ($specification->isValid()) {
        ...
    }
```
                        
### 2.4 - and Operator
                  
adds an '&&' condition to the previously set specifications
<br/><b>It can not be used first or else it will throw a WrongUsageException</b>   
                    
Example : 
```php       
    $specification = (new Specification())
        ->isSatisfiedBy(new IsProductAvaialble($product))
        ->and(new IsProductShipableForCustomer($product, $customer))
        ...
    ;
    
    if ($specification->isValid()) {
       ...
    }
```  
        
### 2.5 - andNot Operator
                  
adds an '&& !' condition to the previously set specifications
<br/><b>It can not be used first or else it will throw a WrongUsageException</b>   
                    
Example : 
```php       
    $specification = (new Specification())
        ->isSatisfiedBy(new IsProductAvaialble($product))
        ->and(new IsProductShipableForCustomer($product, $customer))
        ->andNot(new IsCustomerBlackListed($customer)
        ...
    ;
    
    if ($specification->isValid()) {
       ...
    }
```
                    
### 2.6 - or Operator
                  
adds an '||' condition to the previously set specifications
<br/><b>It can not be used first or else it will throw a WrongUsageException</b>   
                    
Example :
```php 
    $specification = (new Specification())
       ->isSatisfiedBy(new IsProductAvaialble($product))
       ->or(new IsASpecialProduc($product))
       ...
    ;
                   
    
    if ($specification->isValid()) {
       ...
    }
```
                   
### 2.7 - orNot Operator
                  
adds an '|| !' condition to the previously set specifications
<br/><b>It can not be used first or else it will throw a WrongUsageException</b>   
                    
Example :
```php
    $specification = (new Specification())
       ->isSatisfiedBy(new IsProductAvaialble($product))
       ->orNot(new IsASpecialProduc($product))
    ;
                   
    
    if ($specification->isValid()) {
       ...
    }
```
                   
### 2.7 - xor Operator
                      
adds a 'xor' condition to the previously set specifications
<br/><b>It can not be used first or else it will throw a WrongUsageException</b>   
                        
Example : 
```php       
    $specification = (new Specification())
       ->isSatisfiedBy(new IsProductAvaialble($product))
       ->xor(new IsASpecialProduc($product))
    ;
                   
    
    if ($specification->isValid()) {
       ...
    }
```
                       
### 2.7 - xorNot Operator
                      
adds a 'xor !' condition to the previously set specifications
<br/><b>It can not be used first or else it will throw a WrongUsageException</b>   
                        
Example : 
```php       
    $specification = (new Specification())
       ->isSatisfiedBy(new IsProductAvaialble($product))
       ->xorNot(new IsASpecialProduc($product))
    ;
                   
    
    if ($specification->isValid()) {
       ...
    }
```
                       
## 3- Composition
    
The specifications can be composed  :
        
Example:
```php             
    $specification = (new Specification())
        ->isSatisfiedBy(new IsProductShipableForCustomer($product, $customer))
        ->and(
            (new Specification())
                ->isSatisfiedBy(new IsProductAvaialble($product))
                ->or(new IsASpecialProduct($product))
        )
    ;
    
    if ($specification->isValid()) {
       ...
    }
```
                
                
                    
       
        
    